<?php

namespace App\Services\Parsers\Http\Zakupki;


use App\Jobs\Parsers\Http\Zakupki\Checker as CheckerJob;
use App\Jobs\Parsers\Http\Zakupki\NewProcurement;
use App\Services\Parsers\Http\_Core\Checker as CoreChecker;
use App\Services\Parsers\Http\_Core\ExistingProcurement as CoreExistingProcurement;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class Checker extends CoreChecker
{
    protected $proxyHost;
    protected $searchParams;

    /**
     * Checker constructor.
     * @param string $proxyHost
     * @param array $searchParams
     * @param CoreExistingProcurement|null $existingService
     */
    public function __construct(string $proxyHost, array $searchParams, CoreExistingProcurement $existingService)
    {
        $this->proxyHost = $proxyHost;
        $this->searchParams = $searchParams;
        $this->existingService = $existingService;
    }

    public static function make(string $proxyHost, array $searchParams, CoreExistingProcurement $existingService): self
    {
        return new static($proxyHost, $searchParams, $existingService);
    }

    /**
     * Переход на следующую страницу.
     */
    protected function nextPage(): void
    {
        if ($this->existingService->allowedLimit()) {
            $this->searchParams['pageNumber'] = isset($this->searchParams['pageNumber']) ? $this->searchParams['pageNumber'] + 1 : 2;
            CheckerJob::dispatch($this->proxyHost, $this->searchParams, $this->existingService)->onQueue('HttpHigh');
        }
    }

    /**
     * Получение номера из блока закупки.
     *
     * @param Crawler $box
     * @return string
     */
    protected function getNumber(Crawler $box): string
    {
        $regNumber = $box->filterXPath('//dt/a')->text();

        return preg_replace('/[^\d]+/', '', $regNumber);
    }

    protected function boxesXPath(): string
    {
        return '//div[contains(@class, \'registerBox\')]';
    }

    protected function getParserData(Crawler $box): array
    {
        $href = $box->filterXPath('//dt/a/@href')->first()->text();
        $data['url'] = isset(parse_url($href)['host']) ? $href : config('parse-http.platforms.zakupki.host') . $href;
        $data['price'] = $this->getProcurementPrice($box);

        return $data;
    }

    protected function dispatchParser($data)
    {
        NewProcurement::dispatch($data)->onQueue('HttpDefault');
    }

    /**
     * @param array $baseParams
     * @param int $day
     * @return array
     */
    public static function getPortionParams(array $baseParams, int $day = 0): array
    {
        $baseParams['publishDateFrom'] = Carbon::now()->addDays($day)->format('d.m.Y');
        $baseParams['publishDateTo'] = Carbon::now()->addDays($day)->format('d.m.Y');

        return $baseParams;
    }

    private function getProcurementPrice(Crawler $box): array
    {
        $baseXPath = '//span[contains(text(), \'Начальная цена\')]';
        $cost = $box->filterXPath("{$baseXPath}/following-sibling::strong")->first();
        $currency = $box->filterXPath("{$baseXPath}/following-sibling::span")->first();

        return [
            'cost' => $cost->count() ? $cost->text() : null,
            'currency' => $currency->count() ? $currency->text() : null
        ];
    }
}

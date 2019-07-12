<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law44\Contracts;


use App\Services\Connectors\Http\Connector;
use App\Services\Parsers\Http\_Core\BaseModel;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class ContractStages extends BaseModel
{
    /**
     * @return array
     * @throws \Throwable
     */
    public function handle(): array
    {
        $xPath = '//td[contains(@tab, \'CARD_PAYMENT_INFO_AND_TARGET_OF_ORDER\')]/@url';
        $href = $this->crawler->filterXPath($xPath)->text();
        $url = isset(parse_url($href)['host']) ? $href : config('parse-http.platforms.zakupki.host') . $href;
        $html = Connector::make($url)->connect();

        $crawler = new Crawler($html);

        return $crawler->filterXPath('//td[contains(text(), \'Этап\')]')
            ->each(function (Crawler $node) {
                preg_match('/\d{2}.\d{2}.\d{4}/u', $node->text(), $matches);

                return Carbon::parse($matches[0])->format('Y-m-d') ?? null;
            });
    }
}

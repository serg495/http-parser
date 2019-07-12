<?php

namespace App\Services\Parsers\Http\Zakupki;


use App\Repositories\DBRepositories\Dictionaries\Currency;
use App\Services\Connectors\Http\Connector;
use App\Services\Parsers\Http\_Core\NewProcurement as CoreNewProcurement;
use App\Services\Parsers\Http\_Core\Saver223;
use App\Services\Parsers\Http\_Core\Saver44;
use App\Services\Parsers\Http\Procurements\_Helpers\Log;
use App\Services\Parsers\Http\Zakupki\Models\Law223\Documents\Documents as Documents223;
use App\Services\Parsers\Http\Zakupki\Models\Law223\Journal\Journal as Journal223;
use App\Services\Parsers\Http\Zakupki\Models\Law223\Lots\LotList as LotList223;
use App\Services\Parsers\Http\Zakupki\Models\Law223\Organization as Organization223;
use App\Services\Parsers\Http\Zakupki\Models\Law223\Procurement as Procurement223;
use App\Services\Parsers\Http\Zakupki\Models\Law44\Contracts\Contracts as Contracts44;
use App\Services\Parsers\Http\Zakupki\Models\Law44\Documents\Documents as Documents44;
use App\Services\Parsers\Http\Zakupki\Models\Law44\Journal\Journal as Journal44;
use App\Services\Parsers\Http\Zakupki\Models\Law44\Lots\LotList as LotList44;
use App\Services\Parsers\Http\Zakupki\Models\Law44\Organization as Organization44;
use App\Services\Parsers\Http\Zakupki\Models\Law44\Procurement as Procurement44;
use Symfony\Component\DomCrawler\Crawler;

class NewProcurement extends CoreNewProcurement
{
    /**
     * @var string
     */
    protected $platform = 'zakupki';

    /**
     * @var
     */
    protected $regNumber;

    public function __construct(string $procurementUrl, array $additionalInfo = [])
    {
        parent::__construct($procurementUrl, $additionalInfo);
        $this->adaptAdditionalInfo();
    }

    /**
     * @param string $html
     * @throws \Throwable
     */
    public function run(string $html): void
    {
        $this->regNumber = $this->getRegNumber();
        $mainCrawler = new Crawler($html);

        strlen($this->regNumber) === 11 ?
            $this->runLaw223($mainCrawler) :
            $this->runLaw44($mainCrawler);
    }

    private function getRegNumber(): string
    {
        preg_match('/(?<==)\d+/', $this->procurementUrl, $match);

        return $match[0];
    }

    /**
     * @param Crawler $mainCrawler
     * @throws \Throwable
     */
    protected function runLaw223(Crawler $mainCrawler)
    {
        try {
            $this
                ->addModel(self::GENERAL_INFO, new Procurement223($mainCrawler))
                ->addModel(self::ORGANIZATION, new Organization223($mainCrawler))
                ->addModel(self::LOTS, new LotList223($this->getCrawlerFor('lot-list', 223)))
                ->addModel(self::DOCUMENTS, new Documents223($this->getCrawlerFor('documents', 223)))
                ->addModel(self::EVENTS_JOURNAL, new Journal223($this->getCrawlerFor('journal', 223)));
        } catch (\Exception $exception) {
            Log::parseHttpError($this->procurementUrl, 'Failed Procurement 223 parse' . $exception->getTraceAsString());
        }

        $this->addAdditionalInfo();

        Saver223::make($this->models, $this->platform)->save();
    }

    /**
     * @param Crawler $mainCrawler
     * @throws \Throwable
     */
    protected function runLaw44(Crawler $mainCrawler)
    {
        try {
            $this
                ->addModel(self::GENERAL_INFO, new Procurement44($mainCrawler))
                ->addModel(self::ORGANIZATION, new Organization44($mainCrawler))
                ->addModel(self::LOTS, new LotList44($mainCrawler))
                ->addModel(self::DOCUMENTS, new Documents44($this->getCrawlerFor('documents', 44)))
                ->addModel(self::EVENTS_JOURNAL, new Journal44($this->getCrawlerFor('event-journal', 44)))
                ->addModel(self::CONTRACTS, new Contracts44($this->getCrawlerFor('supplier-results', 44)));
        } catch (\Exception $exception) {
            Log::parseHttpError($this->procurementUrl, 'Failed Procurement 44 parse' . $exception->getTraceAsString());
        }

        $this->addAdditionalInfo();

        Saver44::make($this->models, $this->platform)->save();
    }

    /**
     * @param string $key
     * @param int $law
     * @return Crawler
     * @throws \Throwable
     */
    protected function getCrawlerFor(string $key, int $law)
    {
        if ($law === 223) {
            $pageUrl = "http://zakupki.gov.ru/223/purchase/public/purchase/info/%s.html?regNumber={$this->regNumber}";
        } else {
            $pageUrl = "http://zakupki.gov.ru/epz/order/notice/ea44/view/%s.html?regNumber={$this->regNumber}";
        }
        $html = Connector::make(sprintf($pageUrl, $key))->connect();

        return new Crawler($html);
    }

    private function addAdditionalInfo()
    {
        $this->models[self::GENERAL_INFO]['version'] = -1;
        $this->models[self::GENERAL_INFO]['url_eis'] = $this->procurementUrl;
        $this->models[self::GENERAL_INFO] = array_merge($this->models[self::GENERAL_INFO], $this->additionalInfo);
    }

    private function adaptAdditionalInfo(): void
    {
        $price = trim(preg_replace('/[^0-9]+/', '', $this->additionalInfo['cost']));
        $price = mb_substr($price, 0, -2) . '.' . mb_substr($price, -2);
        $currency = Currency::make()->getCodeByName($this->additionalInfo['currency']);

        $this->additionalInfo = ['price' => $price, 'currency' => $currency];
    }
}

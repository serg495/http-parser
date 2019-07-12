<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law223\Lots;


use App\Services\Connectors\Http\Connector;
use App\Services\Parsers\Http\_Core\BaseModel;
use Symfony\Component\DomCrawler\Crawler;

class LotList extends BaseModel
{
    public function handle(): array
    {
        $xPath = '//table[@id = \'lot\']/tbody/tr/td/a/@href';
        $this->crawler->filterXPath($xPath)->each(function (Crawler $href, $counter) {
            $lotCrawler = $this->getCrawlerFromHref($href->text());
            $this
                ->addSubModel(new Lot($lotCrawler), $counter, 'lot')
                ->addSubModel(new LotData($lotCrawler), $counter, 'lot_data')
                ->addSubModel(new LotItems($lotCrawler), $counter, 'lot_items');
        });

        return $this->getModel();
    }

    /**
     * @param string $href
     * @return Crawler
     * @throws \Throwable
     */
    protected function getCrawlerFromHref(string $href)
    {
        $url = isset(parse_url($href)['host']) ?
            $href : config('parse-http.platforms.zakupki.host') . $href;
        $html = Connector::make($url)->connect();

        return new Crawler($html);
    }
}

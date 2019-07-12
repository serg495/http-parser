<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law44\Contracts;


use App\Services\Connectors\Http\Connector;
use App\Services\Parsers\Http\_Core\BaseModel;
use Symfony\Component\DomCrawler\Crawler;

class Contracts extends BaseModel
{
    public function handle(): array
    {
        $xPath = "//h2[contains(text(), 'Сведения о контракте')]/
            following-sibling::div/div/table/tbody/tr/td/a[starts-with(text(), '№')]/@href";

        $this->crawler->filterXPath($xPath)->each(function (Crawler $node, $counter) {
            $href = $node->text();
            $url = isset(parse_url($href)['host']) ? $href : config('parse-http.platforms.zakupki.host') . $href;
            $html = Connector::make($url)->connect();
            $crawler = new Crawler($html);

            $this
                ->addSubModel(new Contract($crawler), $counter, 'contract')
                ->addSubModel(new ContractStages($crawler), $counter, 'contract_stages');
        });

        return $this->getModel();
    }
}

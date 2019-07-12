<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law223\Lots;


use App\Services\Parsers\Http\_Core\BaseModel;
use Symfony\Component\DomCrawler\Crawler;

class LotItems extends BaseModel
{
    public function handle(): array
    {
        $xPath = '//table[@id = \'commodity\']/tbody/tr';

        $this->crawler->filterXPath($xPath)->each(function (Crawler $node, $counter) {
            $this->addSubModel(new LotItem($node), $counter);
        });

        return $this->getModel();
    }
}

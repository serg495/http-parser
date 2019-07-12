<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law44\Lots;


use App\Services\Parsers\Http\_Core\BaseModel;
use Symfony\Component\DomCrawler\Crawler;

class PurchaseObjects extends BaseModel
{
    public function handle(): array
    {
        //TODO: Найти и реализовать пример с пагинацией
        $xPath = "//h2[contains(text(), 'об объекте закупки')]/following-sibling::div[1]/table/tbody/tr/td
            /div/div/div/div/table/tbody/tr[not(contains(@class, 'tdHead')) 
            and not(contains(@class, 'displayNone')) and not(contains(@class, 'tdTotal'))]";

        $this->crawler->filterXPath($xPath)->each(function (Crawler $purchaseObjectCrawler, $counter) {
            $this->addSubModel(new PurchaseObject($purchaseObjectCrawler), $counter);
        });

        return $this->getModel();
    }
}

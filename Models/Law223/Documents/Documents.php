<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law223\Documents;


use App\Services\Parsers\Http\_Core\BaseModel;
use Symfony\Component\DomCrawler\Crawler;

class Documents extends BaseModel
{
    public function handle(): array
    {
        //TODO: Найти и реализовать пример с пагинацией
        $xPath = '//table[@id = \'attachmentForVersion\']/tbody/tr';

        $this->crawler->filterXPath($xPath)->each(function (Crawler $documentCrawler, $counter) {
            $this->addSubModel(new Document($documentCrawler), $counter);
        });

        return $this->getModel();
    }
}

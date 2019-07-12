<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law223\Journal;


use App\Services\Parsers\Http\_Core\BaseModel;
use Symfony\Component\DomCrawler\Crawler;

class Journal extends BaseModel
{
    public function handle(): array
    {
        //TODO: Найти и реализовать пример с пагинацией
        $xPath = '//table[@id = \'documentAction\']/tbody/tr';

        $this->crawler->filterXPath($xPath)->each(function (Crawler $eventsCrawler, $counter) {
            $this->addSubModel(new EventOfJournal($eventsCrawler), $counter);
        });

        return $this->getModel();
    }
}

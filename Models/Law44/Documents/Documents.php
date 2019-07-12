<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law44\Documents;


use App\Services\Parsers\Http\_Core\BaseModel;
use App\Services\Parsers\Http\_Core\Fields\Document\Actuality;
use App\Services\Parsers\Http\_Core\Fields\Document\PostedAtDate;
use Symfony\Component\DomCrawler\Crawler;

class Documents extends BaseModel
{
    public function handle(): array
    {
        $this->crawler->filterXPath('//tr')->each(function (Crawler $node) {
            if ($node->filterXPath('//td')->count() === 5) {
                $node->filterXPath('//td[5]/div[@class = \'attachment\']/div[@class = \'displayTable\']')
                    ->each(function (Crawler $documentCrawler, $counter) use ($node) {
                        $this
                            ->addSubModel(new Document($documentCrawler), $counter)
                            ->addField(new PostedAtDate($node, '//td[4]/div/span[2]'), $counter)
                            ->addField(new Actuality($node, '//td[4]/span'), $counter);
                    });
            }
        });

        return $this->getModel();
    }
}

<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Repositories\DBRepositories\Dictionaries\Okei as OkeiDictionary;
use App\Services\Parsers\Http\_Core\BaseField;
use Symfony\Component\DomCrawler\Crawler;

class Okei extends BaseField
{
    protected $fieldName = 'okei';

    public function __construct(Crawler $crawler, string $xPath, string $fieldName = null)
    {
        parent::__construct($crawler, $xPath);

        if ($fieldName) {
            $this->fieldName = $fieldName;
        }
    }

    protected function adapt(string $item): ?string
    {
        preg_match('/([а-яА-Я]+\s?){1,}/u', $item, $match);

        //Возможно, для других площадок код придется искать по другим полям
        return isset($match[0]) ? OkeiDictionary::make()->getCodeBy('full_name', trim($match[0])) : null;
    }
}

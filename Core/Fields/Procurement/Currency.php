<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Repositories\DBRepositories\Dictionaries\Currency as CurrencyDictionary;
use App\Services\Parsers\Http\_Core\BaseField;
use Symfony\Component\DomCrawler\Crawler;

class Currency extends BaseField
{
    /** Название поля в таблице */
    protected $fieldName = 'currency';

    public function __construct(Crawler $crawler, string $xPath, string $fieldName = null)
    {
        parent::__construct($crawler, $xPath);

        if ($fieldName) {
            $this->fieldName = $fieldName;
        }
    }

    /**
     * @param string $item
     * @return string
     */
    protected function adapt(string $item): string
    {
        preg_match('/([а-яА-Я]+\s?){1,}/u', $item, $matches);

        return isset($matches[0]) ? CurrencyDictionary::make()->getCodeByName($matches[0]) : $matches[0];
    }
}

<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Repositories\DBRepositories\Dictionaries\Currency as CurrencyDictionary;
use App\Services\Parsers\Http\_Core\BaseField;

class Currency extends BaseField
{
    /** Название поля в таблице */
    protected $fieldName = 'currency';

    /**
     * @param string $item
     * @return string
     */
    protected function adapt(string $item): string
    {
        preg_match('/([а-яА-Я]+\s?){1,}/u', $item, $matches);

        return isset($matches[0]) ? CurrencyDictionary::make()->getCodeByName(trim($matches[0])) : $matches[0];
    }
}

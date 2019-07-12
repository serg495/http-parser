<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Repositories\DBRepositories\Dictionaries\Currency;

class CurrencyCode extends BaseField
{
    protected $fieldName = 'currency_code';

    /**
     * @param string $item
     * @return string
     */
    protected function adapt(string $item): string
    {
        preg_match('/([а-яА-Я]+\s?){1,}/u', $item, $matches);

        return isset($matches[0]) ? Currency::make()->getCodeByName(trim($matches[0])) : $matches[0];
    }
}

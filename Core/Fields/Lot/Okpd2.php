<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\StringAdapter;

class Okpd2 extends BaseField
{
    protected $fieldName = 'okpd2';

    /**
     * @param string $item
     * @return string|null
     */
    protected function adapt(string $item): ?string
    {
        $item = preg_replace('/\s([а-яА-Я]+\s?){1,}/u', '', $item);

        return trim($item);
    }
}

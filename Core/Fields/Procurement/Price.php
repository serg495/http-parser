<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\PriceAdapter;

class Price extends BaseField
{
    use PriceAdapter;

    /** Название поля в таблице */
    protected $fieldName = 'price';
}

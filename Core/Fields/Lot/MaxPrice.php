<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\PriceAdapter;

class MaxPrice extends BaseField
{
    use PriceAdapter;

    protected $fieldName = 'max_price';
}

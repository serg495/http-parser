<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\PriceAdapter;

class ApplicationSupplySumm extends BaseField
{
    use PriceAdapter;

    protected $fieldName = 'application_supply_summ';
}

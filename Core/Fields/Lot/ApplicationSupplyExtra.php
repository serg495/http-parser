<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\StringAdapter;

class ApplicationSupplyExtra extends BaseField
{
    use StringAdapter;

    protected $fieldName = 'application_supply_extra';
}

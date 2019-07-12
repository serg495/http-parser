<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\StringAdapter;

class AdditionalInfo extends BaseField
{
    use StringAdapter;

    protected $fieldName = 'additional_info';
}

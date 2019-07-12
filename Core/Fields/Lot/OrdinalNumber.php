<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\NumberAdapter;

class OrdinalNumber extends BaseField
{
    use NumberAdapter;

    protected $fieldName = 'ordinal_number';
}

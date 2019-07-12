<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\StringAdapter;

class LotObjectInfo extends BaseField
{
    use StringAdapter;

    protected $fieldName = 'lot_object_info';
}

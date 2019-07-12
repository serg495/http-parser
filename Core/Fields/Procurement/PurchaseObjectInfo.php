<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\StringAdapter;

class PurchaseObjectInfo extends BaseField
{
    use StringAdapter;

    protected $fieldName = 'purchase_object_info';
}

<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\StringAdapter;

class DeliveryProcedure extends BaseField
{
    use StringAdapter;

    /** Название поля в таблице */
    protected $fieldName = 'procedure';
}

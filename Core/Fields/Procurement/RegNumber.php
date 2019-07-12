<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\NumberAdapter;

class RegNumber extends BaseField
{
    use NumberAdapter;

    /** Название поля в таблице */
    protected $fieldName = 'reg_number';

}

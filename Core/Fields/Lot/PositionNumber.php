<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\NumberAdapter;

class PositionNumber extends BaseField
{
    use NumberAdapter;

    /** Название поля в таблице */
    protected $fieldName = 'position_number';
}

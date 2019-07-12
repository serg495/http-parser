<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;

class SmallOrMiddle extends BaseField
{
    /** Название поля в таблице */
    protected $fieldName = 'for_small_or_middle';

    protected function adapt(string $item): string
    {
        return 1;
    }
}

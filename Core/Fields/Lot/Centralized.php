<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;

class Centralized extends BaseField
{
    /** Название поля в таблице */
    protected $fieldName = 'centralized';

    protected function adapt(string $item): ?string
    {
        return 1;
    }
}

<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;

class ApplicationSupplyNeeded extends BaseField
{
    /** Название поля в таблице */
    protected $fieldName = 'application_supply_needed';

    protected function adapt(string $item): ?string
    {
        return 1;
    }
}

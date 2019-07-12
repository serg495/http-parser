<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;

class SubcontractorsRequirement extends BaseField
{
    /** Название поля в таблице */
    protected $fieldName = 'subcontractors_requirement';

    protected function adapt(string $item): string
    {
        return 1;
    }
}

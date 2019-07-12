<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\NumberAdapter;

class PlanRegistrationNumber extends BaseField
{
    use NumberAdapter;

    /** Название поля в таблице */
    protected $fieldName = 'plan_registration_number';
}

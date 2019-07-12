<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\DateAdapter;

class ExaminationAtDate extends BaseField
{
    use DateAdapter;

    /** Название поля в таблице */
    protected $fieldName = 'examination_at';
}

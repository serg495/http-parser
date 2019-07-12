<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\DateAdapter;

class SummingupAtDate extends BaseField
{
    use DateAdapter;

    /** Название поля в таблице */
    protected $fieldName = 'summingup_at';
}

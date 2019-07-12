<?php

namespace App\Services\Parsers\Http\_Core\Fields\Document;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\DateAdapter;

class PostedAtDate extends BaseField
{
    use DateAdapter;

    /** Название поля в таблице */
    protected $fieldName = 'date';
}

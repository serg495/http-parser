<?php

namespace App\Services\Parsers\Http\_Core\Fields\Journal;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\DateAdapter;

class Date extends BaseField
{
    use DateAdapter;

    protected $fieldName = 'date';
}

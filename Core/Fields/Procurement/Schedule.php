<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\NumberAdapter;

class Schedule extends BaseField
{
    use NumberAdapter;

    protected $fieldName = 'schedule';
}

<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\DateAdapter;

class CollectingStartDate extends BaseField
{
    use DateAdapter;

    protected $fieldName = 'start_date_time';
}

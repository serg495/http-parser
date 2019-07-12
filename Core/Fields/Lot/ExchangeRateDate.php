<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\DateAdapter;

class ExchangeRateDate extends BaseField
{
    use DateAdapter;

    protected $fieldName = 'exchange_rate_date';
}

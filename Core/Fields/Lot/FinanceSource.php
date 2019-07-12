<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\StringAdapter;

class FinanceSource extends BaseField
{
    use StringAdapter;

    protected $fieldName = 'finance_source';
}

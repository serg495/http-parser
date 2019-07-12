<?php

namespace App\Services\Parsers\Http\_Core\Fields\Organization;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\NumberAdapter;

class Kpp extends BaseField
{
    use NumberAdapter;

    protected $fieldName = 'kpp';
}

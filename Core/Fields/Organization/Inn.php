<?php

namespace App\Services\Parsers\Http\_Core\Fields\Organization;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\NumberAdapter;

class Inn extends BaseField
{
    use NumberAdapter;

    protected $fieldName = 'inn';
}

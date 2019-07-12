<?php

namespace App\Services\Parsers\Http\_Core\Fields\Organization;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\StringAdapter;

class ShortName extends BaseField
{
    use StringAdapter;

    protected $fieldName = 'short_name';
}

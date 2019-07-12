<?php

namespace App\Services\Parsers\Http\_Core\Fields\Journal;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\StringAdapter;

class Content extends BaseField
{
    use StringAdapter;

    protected $fieldName = 'content';
}

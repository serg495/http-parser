<?php

namespace App\Services\Parsers\Http\_Core\Fields\Organization;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\StringAdapter;

class LegalAddress extends BaseField
{
    use StringAdapter;

    protected $fieldName = 'legal_address';
}

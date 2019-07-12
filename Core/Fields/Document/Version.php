<?php

namespace App\Services\Parsers\Http\_Core\Fields\Document;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\NumberAdapter;

class Version extends BaseField
{
    use NumberAdapter;

    protected $fieldName = 'version';
}

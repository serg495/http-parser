<?php

namespace App\Services\Parsers\Http\_Core\Fields\Document;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\StringAdapter;

class DocumentName extends BaseField
{
    use StringAdapter;

    /** Название поля в таблице */
    protected $fieldName = 'name';
}

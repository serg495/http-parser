<?php

namespace App\Services\Parsers\Http\_Core\Fields\Document;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\StringAdapter;

class Link extends BaseField
{
    /** Название поля в таблице */
    protected $fieldName = 'url';

    /**
     * @param string $item
     * @return string|null
     */
    protected function adapt(string $item): ?string
    {
        return $item;
    }
}

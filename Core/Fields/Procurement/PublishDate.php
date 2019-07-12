<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\DateAdapter;

class PublishDate extends BaseField
{
    use DateAdapter;

    protected $fieldName = 'publish_date';
}

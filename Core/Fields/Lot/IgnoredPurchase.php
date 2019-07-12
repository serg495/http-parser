<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;

class IgnoredPurchase extends BaseField
{
    /** Название поля в таблице */
    protected $fieldName = 'ignored_purchase';

    protected function adapt(string $item): string
    {
        return 1;
    }
}

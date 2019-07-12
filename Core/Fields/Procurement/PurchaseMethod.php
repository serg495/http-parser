<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Repositories\DBRepositories\Dictionaries\PurchaseMethods;
use App\Services\Parsers\Http\_Core\BaseField;

class PurchaseMethod extends BaseField
{
    /** Название поля в таблице */
    protected $fieldName = 'purchase_method';

    /**
     * Метод, предназначенный для валидации приходящих данных
     */
    protected function adapt(string $item): string
    {
        $purchaseMethodName = trim(preg_replace('/&nbsp;/', ' ', $item));

        return PurchaseMethods::make()->getCodeByName($purchaseMethodName);
    }
}

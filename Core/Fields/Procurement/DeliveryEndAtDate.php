<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Services\Parsers\Http\_Core\BaseField;
use Carbon\Carbon;

class DeliveryEndAtDate extends BaseField
{
    /** Название поля в таблице */
    protected $fieldName = 'endDateTime';

    /**
     * @param string $item
     * @return string
     */
    protected function adapt(string $item): ?string
    {
        preg_match_all('/\d{2}.\d{2}.\d{4}/u', $item, $matches);

        return $matches[0][1] ? Carbon::parse($matches[0][1])->format('Y-m-d') : null;
    }
}

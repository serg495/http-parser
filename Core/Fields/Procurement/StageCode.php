<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Services\Parsers\Http\_Core\BaseField;

class StageCode extends BaseField
{
    protected $fieldName = 'stage_code';

    /**
     * Метод, преобразовывающий данные с площадки в нужный нам вид
     *
     * @param string $item
     * @return string|null
     */
    protected function adapt(string $item): ?string
    {
        // TODO: Временное решение.
        return null;
    }
}

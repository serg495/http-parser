<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;

class Ktru extends BaseField
{
    protected $fieldName = 'ktru_code';

    /**
     * Метод, преобразовывающий данные с площадки в нужный нам вид
     *
     * @param string $item
     * @return string|null
     */
    protected function adapt(string $item): ?string
    {
        $item = trim(preg_replace('/\s([а-яА-Я]+\s?){1,}/u', '', $item));

        return $item ? $item : null;
    }
}

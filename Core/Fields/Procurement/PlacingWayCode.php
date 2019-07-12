<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Repositories\DBRepositories\Dictionaries\PlacingWays;
use App\Services\Parsers\Http\_Core\BaseField;

class PlacingWayCode extends BaseField
{
    protected $fieldName = 'placing_way_code';

    /**
     * Метод, преобразовывающий данные с площадки в нужный нам вид
     *
     * @param string $item
     * @return string|null
     */
    protected function adapt(string $item): ?string
    {
        return PlacingWays::make()->getCodeByName(trim($item));
    }
}

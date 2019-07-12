<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Services\Parsers\Http\_Core\BaseField;

class ProcurementType44 extends BaseField
{
    protected $fieldName = 'procurement_type';

    /**
     * Метод, преобразовывающий данные с площадки в нужный нам вид
     *
     * @param string $item
     * @return string|null
     */
    protected function adapt(string $item): ?string
    {
        $types = [
            1 => ['аукцион', 'Аукцион'],
            3 => ['конкурс', 'Конкурс'],
            4 => ['Закрытая закупка'],
            5 => ['запрос котировок', 'Запрос котировок'],
            6 => ['запрос предложений', 'Запрос предложений'],
        ];

        $currentType = trim(preg_replace('/&nbsp;/', ' ', $item));

        foreach ($types as $key => $type) {
            if (\Illuminate\Support\Str::contains($currentType, $type)) {
                $procurementType = $key;
            }
        }

        return $procurementType ?? 2;
    }
}

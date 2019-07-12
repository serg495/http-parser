<?php

namespace App\Services\Parsers\Http\_Core\Fields\Document;


use App\Services\Parsers\Http\_Core\BaseField;
use Illuminate\Support\Str;

class Actuality extends BaseField
{
    protected $fieldName = 'actual';

    /**
     * @param string $item
     * @return string|null
     */
    protected function adapt(string $item): ?string
    {
        $needles = [
            'действующая',
            'Действующая'
        ];
        return Str::contains($item, $needles) ? 1 : null;
    }
}

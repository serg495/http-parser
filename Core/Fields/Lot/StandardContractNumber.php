<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use Illuminate\Support\Str;

class StandardContractNumber extends BaseField
{
    protected $fieldName = 'standard_contract_number';

    protected function adapt(string $item): ?string
    {
        if (Str::contains($item, 'не установлена')) {
            return null;
        }

        return trim(preg_replace('/[^\d]+/', '', $item));
    }
}

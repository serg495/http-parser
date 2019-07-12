<?php

namespace App\Services\Parsers\Http\_Core\Fields\Procurement;


use App\Repositories\DBRepositories\Dictionaries\Etps;
use App\Services\Parsers\Http\_Core\BaseField;

class EtpCode extends BaseField
{
    /** Название поля в таблице */
    protected $fieldName = 'etp_code';

    /**
     * Метод, предназначенный для валидации приходящих данных
     */
    protected function adapt(string $item): string
    {
        $etpUrl = trim($item);

        return Etps::make()->getByUrlOrCreate($etpUrl);
    }
}

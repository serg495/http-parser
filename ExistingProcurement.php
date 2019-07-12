<?php

namespace App\Services\Parsers\Http\Zakupki;


use App\Repositories\DBRepositories\Procurement\_223fz\Procurements as Procurements223fz;
use App\Repositories\DBRepositories\Procurement\_44fz\Procurements as Procurements44fz;
use App\Services\Parsers\Http\_Core\ExistingProcurement as CoreExistingProcurement;

class ExistingProcurement extends CoreExistingProcurement
{
    /**
     * @param string $procurementNumber
     * @return array
     */
    protected function getNumbersFromDB(string $procurementNumber): array
    {
        return strlen($procurementNumber) === 11 ?
            $this->existingDBRegNumbers = Procurements223fz::make()->getTodayNumbers($this->platform) :
            $this->existingDBRegNumbers = Procurements44fz::make()->getTodayNumbers($this->platform);
    }
}

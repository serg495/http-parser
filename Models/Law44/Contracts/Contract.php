<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law44\Contracts;


use App\Services\Parsers\Http\_Core\BaseModel;
use App\Services\Parsers\Http\_Core\Fields\Contract\CurrentContractStage;
use App\Services\Parsers\Http\_Core\Fields\Contract\EnforcementCashAmount;
use App\Services\Parsers\Http\_Core\Fields\Contract\ExecutionPeriodEndDate;
use App\Services\Parsers\Http\_Core\Fields\Contract\ExecutionPeriodStartDate;
use App\Services\Parsers\Http\_Core\Fields\Contract\PublishDate;
use App\Services\Parsers\Http\_Core\Fields\Contract\RegistrationNumber;
use App\Services\Parsers\Http\_Core\Fields\Contract\SignDate;

class Contract extends BaseModel
{
    public function handle(): array
    {
        $baseXpath = '//td[contains(text(), %s)]/following-sibling::td';

        //TODO: Найти примеры закупок с авансом и банковскими гарантиями и дописать обработку
        $this
            ->addField(new RegistrationNumber($this->crawler, sprintf($baseXpath, "'Реестровый номер'")))
            ->addField(new SignDate($this->crawler, sprintf($baseXpath, "'Дата заключения контракта'")))
            ->addField(new PublishDate($this->crawler, sprintf($baseXpath, "'Дата размещения'")))
            ->addField(new ExecutionPeriodStartDate($this->crawler, sprintf($baseXpath, "'Дата начала исполнения'")))
            ->addField(new ExecutionPeriodEndDate($this->crawler, sprintf($baseXpath, "'Дата окончания исполнения'")))
            ->addField(new CurrentContractStage($this->crawler, sprintf($baseXpath, "'Статус контракта'")))
            ->addField(new EnforcementCashAmount($this->crawler, sprintf($baseXpath, "'Размер обеспечения исполнения'")));

        return $this->getModel();
    }
}

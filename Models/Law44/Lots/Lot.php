<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law44\Lots;


use App\Services\Parsers\Http\_Core\BaseModel;
use App\Services\Parsers\Http\_Core\Fields\Lot\CurrencyCode;
use App\Services\Parsers\Http\_Core\Fields\Lot\FinanceSource;
use App\Services\Parsers\Http\_Core\Fields\Lot\LotObjectInfo;
use App\Services\Parsers\Http\_Core\Fields\Lot\MaxPrice;
use App\Services\Parsers\Http\_Core\Fields\Lot\StandardContractNumber;

class Lot extends BaseModel
{
    public function handle(): array
    {
        $baseXPath = "//td[contains(text(), '%s')]/following-sibling::td";

        $this
            ->addField(new LotObjectInfo($this->crawler, sprintf($baseXPath, 'Наименование объекта закупки')))
            ->addField(new MaxPrice($this->crawler, sprintf($baseXPath, 'цена контракта')))
            ->addField(new CurrencyCode($this->crawler, sprintf($baseXPath, 'Валюта')))
            ->addField(new StandardContractNumber($this->crawler, sprintf($baseXPath, 'Номер типового контракта')))
            ->addField(new FinanceSource($this->crawler, sprintf($baseXPath, 'Источник финансирования')));

        return $this->getModel();
    }
}

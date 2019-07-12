<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law223\Lots;


use App\Services\Parsers\Http\_Core\BaseModel;
use App\Services\Parsers\Http\_Core\Fields\Lot\Currency;
use App\Services\Parsers\Http\_Core\Fields\Lot\DeliveryPlace;
use App\Services\Parsers\Http\_Core\Fields\Lot\InitialSum;

class LotData extends BaseModel
{
    public function handle(): array
    {
        $baseXPathType1 = "//td[contains(text(), '%s')]/following-sibling::td";
        $baseXPathType2 = "//td/span[contains(text(), '%s')]/parent::td/following-sibling::td";

        $this
            ->addField(new Currency($this->crawler, sprintf($baseXPathType1, 'Начальная (максимальная) цена')))
            ->addField(new InitialSum($this->crawler, sprintf($baseXPathType1, 'Начальная (максимальная) цена')))
            ->addField(new DeliveryPlace($this->crawler, sprintf($baseXPathType2, 'Место поставки')));

        return $this->getModel();
    }
}

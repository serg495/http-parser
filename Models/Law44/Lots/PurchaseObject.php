<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law44\Lots;


use App\Services\Parsers\Http\_Core\BaseModel;
use App\Services\Parsers\Http\_Core\Fields\Lot\Ktru;
use App\Services\Parsers\Http\_Core\Fields\Lot\Name;
use App\Services\Parsers\Http\_Core\Fields\Lot\Okei;
use App\Services\Parsers\Http\_Core\Fields\Lot\Quantity;
use App\Services\Parsers\Http\_Core\Fields\Lot\Sum;
use App\Services\Parsers\Http\_Core\Fields\Procurement\Price;

class PurchaseObject extends BaseModel
{
    public function handle(): array
    {
        $this
            ->addField(new Ktru($this->crawler, '//td[1]'))
            ->addField(new Name($this->crawler, '//td[2]'))
            ->addField(new Okei($this->crawler, '//td[3]', 'okei_code'))
            ->addField(new Quantity($this->crawler, '//td[4]', 'quantity'))
            ->addField(new Price($this->crawler, '//td[5]'))
            ->addField(new Sum($this->crawler, '//td[6]'));

        return $this->getModel();
    }
}

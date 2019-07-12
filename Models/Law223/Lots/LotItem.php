<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law223\Lots;


use App\Services\Parsers\Http\_Core\BaseModel;
use App\Services\Parsers\Http\_Core\Fields\Lot\AdditionalInfo;
use App\Services\Parsers\Http\_Core\Fields\Lot\Okei;
use App\Services\Parsers\Http\_Core\Fields\Lot\Okpd2;
use App\Services\Parsers\Http\_Core\Fields\Lot\Okved2;
use App\Services\Parsers\Http\_Core\Fields\Lot\OrdinalNumber;
use App\Services\Parsers\Http\_Core\Fields\Lot\Quantity;

class LotItem extends BaseModel
{
    public function handle(): array
    {
        $this
            ->addField(new OrdinalNumber($this->crawler, '//td[1]'))
            ->addField(new Okpd2($this->crawler, '//td[2]'))
            ->addField(new Okved2($this->crawler, '//td[3]'))
            ->addField(new Okei($this->crawler, '//td[4]'))
            ->addField(new Quantity($this->crawler, '//td[5]'))
            ->addField(new AdditionalInfo($this->crawler, '//td[6]'));

        return $this->getModel();
    }
}

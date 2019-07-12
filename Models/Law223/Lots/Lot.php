<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law223\Lots;


use App\Services\Parsers\Http\_Core\BaseModel;
use App\Services\Parsers\Http\_Core\Fields\Lot\ApplicationSupplyExtra;
use App\Services\Parsers\Http\_Core\Fields\Lot\ApplicationSupplyNeeded;
use App\Services\Parsers\Http\_Core\Fields\Lot\ApplicationSupplySumm;
use App\Services\Parsers\Http\_Core\Fields\Lot\Centralized;
use App\Services\Parsers\Http\_Core\Fields\Lot\IgnoredPurchase;
use App\Services\Parsers\Http\_Core\Fields\Lot\Name;
use App\Services\Parsers\Http\_Core\Fields\Lot\OriginalNumber;
use App\Services\Parsers\Http\_Core\Fields\Lot\PurchaseDescription;
use App\Services\Parsers\Http\_Core\Fields\Lot\SmallOrMiddle;
use App\Services\Parsers\Http\_Core\Fields\Lot\SubcontractorsRequirement;

class Lot extends BaseModel
{
    public function handle(): array
    {
        $baseXPath = "//td[contains(text(), '%s')]/following-sibling::td";

        $this
            ->addField(new OriginalNumber($this->crawler, sprintf($baseXPath, 'Номер лота')))
            ->addField(new Name($this->crawler, sprintf($baseXPath, 'Наименование предмета договора')))
            ->addField(new SmallOrMiddle($this->crawler, '//input[@id = \'forSmallOrMiddle\']/@checked'))
            ->addField(new SubcontractorsRequirement($this->crawler, '//input[@id = \'subcontractorsRequirement\']/@checked'))
            ->addField(new IgnoredPurchase($this->crawler, '//input[@id = \'ignoredPurchase\']/@checked'))
            ->addField(new Centralized($this->crawler, '//input[@id = \'centralized\']/@checked'))
            ->addField(new PurchaseDescription($this->crawler, sprintf($baseXPath, 'Краткое описание')))
            ->addField(new ApplicationSupplyNeeded($this->crawler, '//input[@id = \'applicationSupplyNeeded\']/@checked'))
            ->addField(new ApplicationSupplySumm($this->crawler, sprintf($baseXPath, 'Размер обеспечения заявки')))
            ->addField(new ApplicationSupplyExtra($this->crawler, sprintf($baseXPath, 'Иные требования')));

        return $this->getModel();
    }
}

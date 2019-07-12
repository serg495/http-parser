<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law44\Lots;


use App\Services\Parsers\Http\_Core\BaseModel;

class LotList extends BaseModel
{
    public function handle(): array
    {
        $this
            ->addSubModel(new Lot($this->crawler), 0, 'lot')
            ->addSubModel(new PurchaseObjects($this->crawler), 0, 'purchase_objects')
            ->addSubModel(new Requirements($this->crawler), 0, 'lot_requirements');

        return $this->getModel();
    }
}

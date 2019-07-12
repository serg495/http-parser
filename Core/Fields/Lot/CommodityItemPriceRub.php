<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use App\Services\Parsers\Http\_Traits\PriceAdapter;

class CommodityItemPriceRub extends BaseField
{
    use PriceAdapter;

    protected $fieldName = 'commodity_item_price_rub';
}

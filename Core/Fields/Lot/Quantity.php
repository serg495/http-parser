<?php

namespace App\Services\Parsers\Http\_Core\Fields\Lot;


use App\Services\Parsers\Http\_Core\BaseField;
use Symfony\Component\DomCrawler\Crawler;

class Quantity extends BaseField
{
    protected $fieldName = 'qty';

    public function __construct(Crawler $crawler, string $xPath, $fieldName = null)
    {
        parent::__construct($crawler, $xPath);

        if ($fieldName) {
            $this->fieldName = $fieldName;
        }
    }

    /**
     * @param string $item
     * @return string|null
     */
    protected function adapt(string $item): ?string
    {
        $quantity = trim(preg_replace('/[^\d]+/', '', $item));

        return $quantity ? $quantity : null;
    }
}

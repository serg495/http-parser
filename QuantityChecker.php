<?php

namespace App\Services\Parsers\Http\Zakupki;


use App\Services\Connectors\Http\Proxy;

class QuantityChecker
{
    protected $searchParams;

    public function __construct(array $searchParams = [])
    {
        $this->searchParams = $searchParams;
    }

    public static function make(array $searchParams = []): self
    {
        return new static($searchParams);
    }

    /**
     * @throws \App\Services\Connectors\Http\Exceptions\UnknownGetKeyException
     * @throws \Throwable
     */
    public function check()
    {
        $proxyHost = Proxy::make()->freeHost();
        // Получаем количество закупок из выборки
        $quantity = PortionChecker::make()->pingQuantityInPortion($proxyHost, $this->searchParams);
        dump('Procurements quantity - ' . $quantity);
        if (!$quantity) {
            return;
        }

        if ($quantity > 990) {
            // Добавляем дополнительные параметры поиска
            for ($counter = 1, $i = 100000; $i < 5000000; $i *= 3, $counter++) {
                if ($counter === 1) {
                    $this->searchParams['priceTo'] = $i;
                } elseif ($counter === 4) {
                    $this->searchParams['priceFrom'] = $i / 3;
                    unset($this->searchParams['priceTo']);
                } else {
                    $this->searchParams['priceFrom'] = $i / 3;
                    $this->searchParams['priceTo'] = $i;
                }
            }
        }
        $html = SearchConnector::make($proxyHost, $this->searchParams)->connect();
        Checker::make($proxyHost, $this->searchParams, new ExistingProcurement('zakupki', $this->searchParams))->check($html);
    }
}

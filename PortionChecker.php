<?php

namespace App\Services\Parsers\Http\Zakupki;


use App\Jobs\Parsers\Http\Zakupki\QuantityChecker;
use Symfony\Component\DomCrawler\Crawler;

class PortionChecker
{
    /**
     * Коды регионов
     *
     * @var array
     */
    protected $regions = [
        [5277349, 5277385, 5277409, 5277361, 5277407, 5277350, 5277360, 5277352, 5277378, 5277405, 5277345, 8408975, 5277359, 5277386, 5277387, 5277358, 5277408, 5277341],
        [5277388, 5277403, 5277339, 5277355, 5277318],
        [5277363, 5277319],
        [5277397, 5277320, 5277356, 5277323],
        [5277340, 5277321, 5277394, 5277322],
        [5277389, 5277351, 5277404, 5277337],
        [5277404, 5277390, 5277369, 5277324],
        [5277338, 5277398, 5277325],
        [5277353, 5277346],
        [8408974, 5277342, 5277326, 5277364, 5277365],
        [5277335],
        [5277327],
        [5277343, 5277370, 5277392, 5277328],
        [5277391, 5277371, 5277372],
        [5277373, 5277401, 5277344],
        [5277357, 5277332],
        [5277329, 5277374],
        [5277347],
        [5277400, 5277375, 5277406],
        [5277331, 5277383],
        [5277330, 5277366],
        [5277354, 5277393, 5277333],
        [5277381, 5277376],
        [5277380, 5277368],
        [5277379, 5277367, 5277402],
        [5277367, 5277382, 5277334]
    ];

    protected $params;

    public function __construct()
    {
        $this->params = config('parse-http.search-params');
    }

    public static function make(): self
    {
        return new static();
    }

    public function start(): void
    {
        foreach ($this->regions as $regions) {
            $this->check($regions);
        }
    }

    protected function check(array $regions): void
    {
        if (count($regions) === 1) {
            foreach ($this->params as $el) {
                $el["regions"] = implode(',', $regions);
                QuantityChecker::dispatch(Checker::getPortionParams($el))->onQueue('HttpHigh');
            }
        } else {
            $el["regions"] = implode(',', $regions);
            QuantityChecker::dispatch(Checker::getPortionParams($el))->onQueue('HttpHigh');
        }

    }

    /**
     * Парсит количество закупок из поисковой выдачи
     *
     * @param string $proxyHost
     * @param array $searchParams
     * @return int
     * @throws \App\Services\Connectors\Http\Exceptions\UnknownGetKeyException
     * @throws \Throwable
     */
    public function pingQuantityInPortion(string $proxyHost, array $searchParams): int
    {
        $html = SearchConnector::make($proxyHost, $searchParams)->connect();
        $crawler = new Crawler($html);
        $quantity = $crawler->filterXPath('//p[contains(text(), \'Всего записей\')]/strong')->first();

        return $quantity->count() ? (int)preg_replace('/[^\d]+/', '', $quantity->text()) : 0;
    }
}

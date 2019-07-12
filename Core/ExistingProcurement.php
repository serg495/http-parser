<?php

namespace App\Services\Parsers\Http\_Core;


use App\Repositories\DBRepositories\Dictionaries\Platforms;
use Illuminate\Support\Facades\Cache;

abstract class ExistingProcurement
{
    /**
     * @var string
     */
    protected $platform;

    /**
     * @var string
     */
    protected $pageNumberKey;

    /**
     * @var string
     */
    protected $cacheKey;

    /**
     * @var int
     */
    protected $emptyPages = 0;

    /**
     * @var array
     */
    protected $existingCacheRegNumbers;

    /**
     * @var array
     */
    protected $existingDBRegNumbers;

    /**
     * ExistingProcurement constructor.
     * @param array $searchParams
     * @param string $platform
     */
    public function __construct(string $platform, array $searchParams = [])
    {
        $this->cacheKey = $this->getCacheKey($searchParams, '|Number|');
        //TODO: В будущем заменить на конфиг
        $this->platform = Platforms::make()->findIdByName($platform);
    }

    /**
     * @param array $searchParams
     * @param string $platform
     * @return ExistingProcurement
     */
    public static function make(string $platform, array $searchParams): self
    {
        return new static( $platform, $searchParams);
    }

    /**
     * @param array $searchParams
     * @param string $type
     * @return string
     */
    protected function getCacheKey(array $searchParams, string $type): string
    {
        if (isset($searchParams[$this->pageNumberKey])) {
            unset($searchParams[$this->pageNumberKey]);
        }

        return $this->platform . $type . arrayHashSum($searchParams);
    }

    /**
     * @param string $procurementNumber
     * @return bool
     */
    public function isExists(string $procurementNumber): bool
    {
        if (!$existence = $this->isCacheExists($procurementNumber)) {
            $existence = $this->isDBExists($procurementNumber);
        }

        return $existence;
    }

    /**
     * @param string $procurementNumber
     * @return bool
     */
    protected function isCacheExists(string $procurementNumber): bool
    {
        return array_key_exists($procurementNumber, $this->getCacheNumbers());
    }

    /**
     * @param string $procurementNumber
     * @return bool
     */
    protected function isDBExists(string $procurementNumber): bool
    {
        return in_array($procurementNumber, $this->getDBNumbers($procurementNumber));
    }

    /**
     * @return array
     */
    protected function getCacheNumbers(): array
    {
        if ($this->existingCacheRegNumbers) {
            return $this->existingCacheRegNumbers;
        } else {
            $this->existingCacheRegNumbers = Cache::get($this->cacheKey);

            return $this->existingCacheRegNumbers ? $this->existingCacheRegNumbers : [];
        }
    }

    /**
     * @param $procurementNumber
     * @return array
     */
    protected function getDBNumbers($procurementNumber): array
    {
        return $this->existingDBRegNumbers ?
            $this->existingDBRegNumbers :
            $this->getNumbersFromDB($procurementNumber);
    }

    /**
     * @param string $procurementNumber
     * @return array
     */
    abstract protected function getNumbersFromDB(string $procurementNumber): array;

    /**
     * @param string $procurementNumber
     */
    public function addToExisting(string $procurementNumber)
    {
        // Указываем, что данная порция имеет новые закупки.
        $this->emptyPages = 0;
        $this->addToCache($procurementNumber);
    }

    /**
     * @param string $procurementNumber
     */
    public function addToCache(string $procurementNumber): void
    {
        $this->existingCacheRegNumbers[$procurementNumber] = time();
        Cache::put($this->cacheKey, $this->existingCacheRegNumbers, 60 * 24);
    }

    /**
     * @return bool
     */
    public function allowedLimit(): bool
    {
        return $this->emptyPages <= config('parse-http.parse-procurements.max-empty-pages-for-check');
    }

    public function incrementEmptyPage(): void
    {
        $this->emptyPages++;
    }
}

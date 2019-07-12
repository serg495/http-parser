<?php

namespace App\Services\Parsers\Http\_Core;


use App\Services\Parsers\Http\_Interfaces\ModelInterface;
use App\Services\Parsers\Http\_Interfaces\NewProcurementInterface;

abstract class NewProcurement implements NewProcurementInterface
{
    /**
     * @var string
     */
    protected $platform;

    /**
     * @var array
     */
    protected $additionalInfo;

    /**
     * @var string
     */
    protected $procurementUrl;

    /**
     * @var array
     */
    protected $models = [
        self::GENERAL_INFO => [],
        self::PROCUREMENT_OKPD2 => [],
        self::ORGANIZATION => [],
        self::LOTS => [],
        self::DOCUMENTS => [],
        self::CONTRACTS => [],
        self::EVENTS_JOURNAL => [],
    ];

    public function __construct(string $procurementUrl, array $additionalInfo = [])
    {
        $this->procurementUrl = $procurementUrl;
        $this->additionalInfo = $additionalInfo;
    }

    /**
     * @param string $procurementUrl
     * @param array $additionalInfo
     * @return NewProcurementInterface
     */
    public static function make(string $procurementUrl, array $additionalInfo = []): NewProcurementInterface
    {
        return new static($procurementUrl, $additionalInfo);
    }

    /**
     * @param string $html
     */
    abstract public function run(string $html): void;

    /**
     * @param string $modelKey
     * @param ModelInterface $field
     * @return NewProcurement
     */
    protected function addModel(string $modelKey, ModelInterface $field): self
    {
        $this->models[$modelKey] = array_merge($this->models[$modelKey], call_user_func($field));

        return $this;
    }
}

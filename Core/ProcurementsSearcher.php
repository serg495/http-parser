<?php

namespace App\Services\Parsers\Http\_Core;


use App\Services\Connectors\Http\Connector;
use App\Services\Connectors\Http\ConnectorInterface;
use App\Services\Connectors\Http\Exceptions\UnknownGetKeyException;

abstract class ProcurementsSearcher implements ConnectorInterface
{
    /**
     * Ссылка на страницу поиска
     *
     * @var string
     */
    protected $url;

    /**
     * Массив параметров поиска
     *
     * @var array
     */
    protected $getParams;

    /**
     * @var string
     */
    protected $proxyHost;

    /**
     * ProcurementsSearcher constructor.
     * @param string $proxyHost
     * @param array $searchParams
     * @throws UnknownGetKeyException
     */
    public function __construct(string $proxyHost, array $searchParams = [])
    {
        $this->proxyHost = $proxyHost;

        foreach ($searchParams as $key => $value) {
            $this->setParam($key, $value);
        }
    }

    /**
     * @param string $proxyHost
     * @param array $searchParams
     * @return ProcurementsSearcher
     * @throws UnknownGetKeyException
     */
    public static function make(string $proxyHost, array $searchParams = []): self
    {
        return new static($proxyHost, $searchParams);
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function connect(): string
    {
        return Connector::make($this->getUrl(), $this->proxyHost)->connect();
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url . '?' . http_build_query($this->cleanParams());
    }

    /**
     * @param string $key
     * @return mixed
     * @throws UnknownGetKeyException
     */
    public function getParam(string $key)
    {
        if (array_key_exists($key, $this->getParams)) {
            return $this->getParams[$key];
        } else {
            throw new UnknownGetKeyException($key);
        }
    }

    /**
     * @param string $key
     * @param $value
     * @throws UnknownGetKeyException
     */
    public function setParam(string $key, $value): void
    {
        if (array_key_exists($key, $this->getParams)) {
            $this->getParams[$key] = $value;
        } else {
            throw new UnknownGetKeyException($key);
        }
    }

    protected function cleanParams(): array
    {
        foreach ($this->getParams as $key => $param) {
            if ($param === '') {
                unset($this->getParams[$key]);
            }
        }

        return $this->getParams;
    }
}

<?php

namespace App\Services\Parsers\Http\_Core;


use App\Services\Parsers\Http\_Interfaces\FieldInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class BaseField implements FieldInterface
{
    /** Название поля в таблице */
    protected $fieldName;

    protected $xPath;

    protected $crawler;

    public function __construct(Crawler $crawler, string $xPath)
    {
        $this->crawler = $crawler;
        $this->xPath = $xPath;
    }

    public function __invoke(): array
    {
        return $this->handle();
    }

    /**
     * Метод, предназначенный для вытягивания необходимых данных
     * Возвращает ассоциативный массив типа: [название поля в таблице => полученное значение]
     */
    public function handle(): array
    {
        $node = $this->crawler->filterXPath($this->xPath())->first();

        $item = $node->count() ? $this->adapt($node->text()) : null;

        return [$this->fieldName => $item];
    }

    /**
     * Метод, преобразовывающий данные с площадки в нужный нам вид
     *
     * @param string $item
     * @return string|null
     */
    abstract protected function adapt(string $item): ?string;

    /**
     * Метод, возвращающий фильтр crawler а формате xPath
     */
    protected function xPath(): string
    {
        return $this->xPath;
    }
}

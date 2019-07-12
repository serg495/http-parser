<?php

namespace App\Services\Parsers\Http\_Core;

use App\Services\Parsers\Http\_Exceptions\InvalidHtmlExceptions;
use Symfony\Component\DomCrawler\Crawler;

abstract class Checker
{
    /**
     * @var ExistingProcurement
     */
    protected $existingService;

    /**
     * Запуск проверки новых закупок на странице.
     *
     * @param string $html
     * @throws \Throwable
     */
    public function check(string $html)
    {
        $boxes = $this->getBoxes($html);

        if ($boxes->count()) {
            $boxes->each(function (Crawler $box) {
                $this->parseIfNotExists($box);
            });
        } else {
            $this->existingService->incrementEmptyPage();
        }

        $this->nextPage();
    }

    /**
     * Получение блоков закупок на странице
     *
     * @param string $html
     * @return Crawler
     * @throws \Throwable
     */
    protected function getBoxes(string $html): Crawler
    {
        $crawler = new Crawler($html);
        throw_if(!$crawler, InvalidHtmlExceptions::class);

        return $crawler->filterXPath($this->boxesXPath());
    }

    /**
     * Передача номера парсеру если он не существует.
     *
     * @param Crawler $box
     */
    protected function parseIfNotExists(Crawler $box): void
    {
        $number = $this->getNumber($box);

        if (!$this->existingService->isExists($number)) {
            $parserData = $this->getParserData($box);
            $this->dispatchParser($parserData);
            $this->existingService->addToExisting($number);
        }
    }

    /**
     * Переход на следующую страницу.
     */
    abstract protected function nextPage(): void;

    /**
     * Получение номера из блока закупки.
     *
     * @param Crawler $box
     * @return string
     */
    abstract protected function getNumber(Crawler $box): string;

    /*
     * Получение селектора для нахождения блоков с закупками.
     */
    abstract protected function boxesXPath(): string;

    /*
     * Получение из блока закупки даных, необходимых для пасрера.
     * Например это может быть ссылка на страницу закупки или просто ее номер.
     */
    abstract protected function getParserData(Crawler $box);

    /*
     * Отправка задачи на парсинг закупки.
     */
    abstract protected function dispatchParser($data);

    /**
     * Добавляет к масиву базовых параметров дату переданную в параметры метода
     *
     * @param array $baseParams
     * @param int $day
     * @return array
     */
    abstract public static function getPortionParams(array $baseParams, int $day = 0): array;
}

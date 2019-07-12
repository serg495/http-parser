<?php

namespace App\Services\Parsers\Http\_Core;


use App\Services\Parsers\Http\_Interfaces\FieldInterface;
use App\Services\Parsers\Http\_Interfaces\ModelInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class BaseModel implements ModelInterface
{
    protected $crawler;

    protected $model = [];

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    public function __invoke()
    {
        return $this->handle();
    }

    abstract public function handle(): array;

    protected function addField(FieldInterface $field, string $key = null): self
    {
        !is_null($key) ?
            $this->model[$key] = array_merge($this->model[$key], call_user_func($field)) :
            $this->model = array_merge($this->model, call_user_func($field));

        return $this;
    }

    protected function addSubModel(ModelInterface $model, int $counter = 0, string $key = null): self
    {
        !is_null($key) ?
            $this->model[$counter][$key] = call_user_func($model) :
            $this->model[$counter] = call_user_func($model);

        return $this;
    }

    protected function addMultiField(string $key, FieldInterface ...$fields): self
    {
        $fieldValues = [];

        foreach ($fields as $field) {
            $fieldValues = array_merge($fieldValues, call_user_func($field));
        }

        $this->model[$key] = json_encode($fieldValues);

        return $this;
    }

    /**
     * Метод предназначенный для того, чтобы объеденить мульти поля
     * в многомерный массив и сохранить его в json по ключу.
     *
     * @param string $fieldKey
     * @param string ...$mergingKeys
     */
    protected function mergeJsonFields(string $fieldKey, string ...$mergingKeys)
    {
        $mergedFields = [];
        foreach ($mergingKeys as $mergingKey) {
            if ($this->model[$mergingKey]) {
                $mergedFields[$mergingKey] = json_decode($this->model[$mergingKey], true);
                unset($this->model[$mergingKey]);
            }
        }
        $this->model[$fieldKey] = json_encode($mergedFields);
    }

    protected function getModel(): array
    {
        return $this->model;
    }
}

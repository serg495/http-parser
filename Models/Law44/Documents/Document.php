<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law44\Documents;


use App\Services\Parsers\Http\_Core\BaseModel;
use App\Services\Parsers\Http\_Core\Fields\Document\DocumentName;
use App\Services\Parsers\Http\_Core\Fields\Document\Link;

class Document extends BaseModel
{
    public function handle(): array
    {
        $this
            ->addField(new DocumentName($this->crawler, '//a'))
            ->addField(new Link($this->crawler, '//a/@href'));

        return $this->getModel();
    }
}

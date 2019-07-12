<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law223\Documents;


use App\Services\Parsers\Http\_Core\BaseModel;
use App\Services\Parsers\Http\_Core\Fields\Document\Actuality;
use App\Services\Parsers\Http\_Core\Fields\Document\DocumentName;
use App\Services\Parsers\Http\_Core\Fields\Document\Link;
use App\Services\Parsers\Http\_Core\Fields\Document\PostedAtDate;
use App\Services\Parsers\Http\_Core\Fields\Document\Version;

class Document extends BaseModel
{
    public function handle(): array
    {
        $this
            ->addField(new DocumentName($this->crawler, '//td[2]'))
            ->addField(new Link($this->crawler, '//td[2]/a/@href'))
            ->addField(new PostedAtDate($this->crawler, '//td[4]'))
            ->addField(new Version($this->crawler, '//td[3]'))
            ->addField(new Actuality($this->crawler, '//td[3]'));

        return $this->getModel();
    }
}

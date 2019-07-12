<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law223\Journal;


use App\Services\Parsers\Http\_Core\BaseModel;
use App\Services\Parsers\Http\_Core\Fields\Journal\Content;
use App\Services\Parsers\Http\_Core\Fields\Journal\Date;

class EventOfJournal extends BaseModel
{

    public function handle(): array
    {
        $this
            ->addField(new Date($this->crawler, '//td[1]'))
            ->addField(new Content($this->crawler, '//td[2]'));

        return $this->getModel();
    }
}

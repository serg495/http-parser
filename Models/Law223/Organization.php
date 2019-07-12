<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law223;


use App\Repositories\DBRepositories\Organizations\_223fz\Organizations;
use App\Services\Parsers\Http\_Core\BaseModel;
use App\Services\Parsers\Http\_Core\Fields\Organization\FullName;
use App\Services\Parsers\Http\_Core\Fields\Organization\Inn;
use App\Services\Parsers\Http\_Core\Fields\Organization\Kpp;
use App\Services\Parsers\Http\_Core\Fields\Organization\LegalAddress;
use App\Services\Parsers\Http\_Core\Fields\Organization\Ogrn;
use App\Services\Parsers\Http\_Core\Fields\Organization\PostalAddress;

class Organization extends BaseModel
{
    public function handle(): array
    {
        $baseXPathType1 = "//td/span[contains(text(), '%s')]/parent::td/following-sibling::td";
        $baseXPathType2 = "//td[contains(text(), '%s')]/following-sibling::td";

        $this
            ->addField(new FullName($this->crawler, sprintf($baseXPathType2, 'Наименование организации')))
            ->addField(new Inn($this->crawler, sprintf($baseXPathType1, 'ИНН')))
            ->addField(new Kpp($this->crawler, sprintf($baseXPathType1, 'КПП')))
            ->addField(new Ogrn($this->crawler, sprintf($baseXPathType2, 'ОГРН')))
            ->addField(new LegalAddress($this->crawler, sprintf($baseXPathType2, 'Место нахождения')))
            ->addField(new PostalAddress($this->crawler, sprintf($baseXPathType2, 'Почтовый адрес')));

        $this->findOrganizationId();

        return $this->getModel();
    }

    protected function findOrganizationId(): void
    {
        $organization = Organizations::make()->findOneByInnAndKpp($this->model['inn'], $this->model['kpp']);
        $this->model['organization_id'] = $organization ? $organization->id : null;
    }
}

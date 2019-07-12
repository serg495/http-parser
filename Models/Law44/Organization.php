<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law44;


use App\Repositories\DBRepositories\Organizations\_44fz\Organizations;
use App\Services\Connectors\Http\Connector;
use App\Services\Parsers\Http\_Core\BaseModel;
use App\Services\Parsers\Http\_Core\Fields\Organization\FullName;
use App\Services\Parsers\Http\_Core\Fields\Organization\Inn;
use App\Services\Parsers\Http\_Core\Fields\Organization\Kpp;
use App\Services\Parsers\Http\_Core\Fields\Organization\LegalAddress;
use App\Services\Parsers\Http\_Core\Fields\Organization\Ogrn;
use App\Services\Parsers\Http\_Core\Fields\Organization\PostalAddress;
use Symfony\Component\DomCrawler\Crawler;

class Organization extends BaseModel
{
    /**
     * @return array
     * @throws \Throwable
     */
    public function handle(): array
    {
        $baseXPath = "//td[contains(text(), '%s')]/following-sibling::td";
        $crawler = $this->getOrganizationCrawler();

        $this
            ->addField(new FullName($crawler, sprintf($baseXPath, 'Полное наименование')))
            ->addField(new Inn($crawler, sprintf($baseXPath, 'ИНН')))
            ->addField(new Kpp($crawler, sprintf($baseXPath, 'КПП')))
            ->addField(new Ogrn($crawler, sprintf($baseXPath, 'ОГРН')))
            ->addField(new LegalAddress($crawler, sprintf($baseXPath, 'Место нахождения')))
            ->addField(new PostalAddress($crawler, sprintf($baseXPath, 'Почтовый адрес')));

        $this->findOrganizationId();

        return $this->getModel();
    }

    /**
     * @return Crawler
     * @throws \Throwable
     */
    protected function getOrganizationCrawler(): Crawler
    {
        $xPath = "//td[contains(text(), 'Размещение осуществляет')]/following-sibling::td/a/@href";
        $href = $this->crawler->filterXPath($xPath)->text();
        $url = isset(parse_url($href)['host']) ? $href : config('parse-http.platforms.zakupki.host') . $href;
        $html = Connector::make($url)->connect();

        return new Crawler($html);
    }

    protected function findOrganizationId(): void
    {
        $organization = Organizations::make()->findOneByInnAndKpp($this->model['inn'], $this->model['kpp']);
        $this->model['organization_id'] = $organization ? $organization->id : null;
    }
}

<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law223;


use App\Services\Connectors\Http\Connector;
use App\Services\Parsers\Http\_Core\BaseModel;
use App\Services\Parsers\Http\_Core\Fields\Procurement\CloseAtDate;
use App\Services\Parsers\Http\_Core\Fields\Procurement\DeliveryEndAtDate;
use App\Services\Parsers\Http\_Core\Fields\Procurement\DeliveryPlace;
use App\Services\Parsers\Http\_Core\Fields\Procurement\DeliveryProcedure;
use App\Services\Parsers\Http\_Core\Fields\Procurement\DeliveryStartAtDate;
use App\Services\Parsers\Http\_Core\Fields\Procurement\EtpCode;
use App\Services\Parsers\Http\_Core\Fields\Procurement\ExaminationAtDate;
use App\Services\Parsers\Http\_Core\Fields\Procurement\ExaminationPlace;
use App\Services\Parsers\Http\_Core\Fields\Procurement\ModificationAtDate;
use App\Services\Parsers\Http\_Core\Fields\Procurement\ProcurementName;
use App\Services\Parsers\Http\_Core\Fields\Procurement\ProcurementType223;
use App\Services\Parsers\Http\_Core\Fields\Procurement\PublicationAtDate;
use App\Services\Parsers\Http\_Core\Fields\Procurement\PurchaseMethod;
use App\Services\Parsers\Http\_Core\Fields\Procurement\RegNumber;
use App\Services\Parsers\Http\_Core\Fields\Procurement\SummingupAtDate;
use App\Services\Parsers\Http\_Core\Fields\Procurement\SummingupPlace;
use Symfony\Component\DomCrawler\Crawler;

class Procurement extends BaseModel
{
    /**
     * @return array
     * @throws \Throwable
     */
    public function handle(): array
    {
        $baseXPathType1 = "//td/span[contains(text(), '%s')]/parent::td/following-sibling::td";
        $baseXPathType2 = "//td[contains(text(), '%s')]/following-sibling::td";

        //TODO: procedural_conditions
        $this
            ->addField(new RegNumber($this->crawler, sprintf($baseXPathType1, 'Реестровый номер')))
            ->addField(new PurchaseMethod($this->crawler, sprintf($baseXPathType1, 'Способ размещения')))
            ->addField(new ProcurementType223($this->crawler, sprintf($baseXPathType1, 'Способ размещения')))
            ->addField(new ProcurementName($this->crawler, sprintf($baseXPathType1, 'Наименование закупки')))
            ->addField(new PublicationAtDate($this->crawler, '//div[@class = \'public\']'))
            ->addField(new ModificationAtDate($this->crawler, sprintf($baseXPathType2, 'Дата размещения текущей редакции')))
            ->addField(new EtpCode($this->crawler, sprintf($baseXPathType2, 'Адрес электронной площадки в')))
            ->addField(new CloseAtDate($this->crawler, sprintf($baseXPathType1, 'Дата и время окончания подачи заявок')))
            //TODO: Разобраться, почему ломает скрипт
//            ->addField(new ExaminationAtDate($this->crawler, sprintf($baseXPathType1, 'Дата рассмотрения заявок')))
            ->addField(new ExaminationPlace($this->crawler, sprintf($baseXPathType1, 'Место рассмотрения заявок')))
            ->addField(new SummingupAtDate($this->crawler, sprintf($baseXPathType1, 'Дата подведения итогов')))
            ->addField(new SummingupPlace($this->crawler, sprintf($baseXPathType1, 'Место подведения итогов')))
            ->addField(new SummingupPlace($this->crawler, sprintf($baseXPathType1, 'Место подведения итогов')))
            ->addMultiField('documentation_delivery',
                new DeliveryProcedure($this->crawler, sprintf($baseXPathType2, 'Порядок предоставления')),
                new DeliveryPlace($this->crawler, sprintf($baseXPathType2, 'Место предоставления')),
                new DeliveryStartAtDate($this->crawler, sprintf($baseXPathType2, 'Срок предоставления')),
                new DeliveryEndAtDate($this->crawler, sprintf($baseXPathType2, 'Срок предоставления')));

        $this->setProcedureLink();

        return $this->getModel();
    }

    /**
     * Получение ссылки на процедуру
     *
     * @throws \Throwable
     */
    protected function setProcedureLink(): void
    {
        $etpCode = $this->model['etp_code'] ?? null;
        $regNumber = $this->model['reg_number'];
        $procedureUrl = null;

        if ($etpCode) {
            switch ($etpCode) {
                case 'ETP_SBAST':
                    $params = "xmlData=<elasticrequest><filters><mainSearchBar><value>{$regNumber}</value>
                               </mainSearchBar></filters><fields><field>objectHrefTerm</field></fields>
                               </elasticrequest>&orgId=0&targetPageCode=UnitedPurchaseList";
                    $html = Connector::make('http://www.sberbank-ast.ru/SearchQuery.aspx?name=Main')->connect($params);
                    preg_match('/http:\/\/www\.sberbank-ast\.ru\/ZK\/purchaseview\.aspx\?id=\d+/', $html, $matches);
                    $procedureUrl = $matches[0] ?? null;
                    break;
                case 'ETP_EETP':
                    $procedureUrl = 'https://www.roseltorg.ru/procedure/' . $regNumber;
                    break;
                case 'ETP_RTS':
                    $url = 'https://www.rts-tender.ru/auctionsearch/ctl/procDetail/mid/691/number/%s/etpName/fks';
                    $procedureUrl = sprintf($url, $regNumber);
                    break;
                case 'ETP_TEKTORG':
                    $procedureUrl = 'https://44.tektorg.ru/common/auction/view/r/' . $regNumber;
                    break;
                case 'ETP_MMVB':
                    $url = "https://www.etp-ets.ru/procedure/catalog/?q={$regNumber}&simple-search=%D0%98%D1%81%D0%BA%D0%B0%D1%82%D1%8C";
                    $xPath = "//td[contains(@class, 'row-name')]/a/@href";
                    $procedureUrl = $this->parseProcedureLink($url, $xPath, 'https://www.etp-ets.ru');
                    break;
                case 'ETP_AVK':
                    $url = 'http://etp.zakazrf.ru/NotificationEx?IsPartialView=1&IsTableContentOnlyRequest=1';
                    $xPath = "//a[text()='{$regNumber}']/@href";
                    $postParams = 'Filter.FastFilter=0311200050119000008';
                    $procedureUrl = $this->parseProcedureLink($url, $xPath, 'http://etp.zakazrf.ru', $postParams);
                    break;
                case 'ETP_GPB':
                    $url = "https://gos.etpgpb.ru/44/catalog/procedure?q={$regNumber}&simple-search=%D0%9D%D0%B0%D0%B9%D1%82%D0%B8";
                    $xPath = "//td[contains(text(), '{$regNumber}')]/a/@href";
                    $procedureUrl = $this->parseProcedureLink($url, $xPath, 'https://gos.etpgpb.ru');
                    break;
                case 'ETP_RAD':
                    $url = 'https://gz.lot-online.ru/procedure/catalog/?simple-search=%D0%9D%D0%B0%D0%B9%D1%82%D0%B8&q=' . $regNumber;
                    $xPath = "//td/b[contains(text(), '{$regNumber}')]/parent::td/a/@href";
                    $procedureUrl = $this->parseProcedureLink($url, $xPath, 'https://gz.lot-online.ru');
                    break;
                default:
                    $procedureUrl = null;
            }
        }
        $this->model['procedure_url'] = $procedureUrl;
    }

    /**
     * @param string $url
     * @param string $xPath
     * @param string $host
     * @param string|null $postParams
     * @return string
     * @throws \Throwable
     */
    protected function parseProcedureLink(string $url, string $xPath, string $host, string $postParams = null): string
    {
        $html = Connector::make($url)->connect($postParams);
        $crawler = new Crawler($html);
        $href = $crawler->filterXPath($xPath)->first()->text();

        return isset(parse_url($href)['host']) ? $href : $host . $href;
    }
}

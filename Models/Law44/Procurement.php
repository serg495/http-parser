<?php

namespace App\Services\Parsers\Http\Zakupki\Models\Law44;


use App\Services\Connectors\Http\Connector;
use App\Services\Parsers\Http\_Core\BaseModel;
use App\Services\Parsers\Http\_Core\Fields\Procurement\BiddingAddInfo;
use App\Services\Parsers\Http\_Core\Fields\Procurement\BiddingDate;
use App\Services\Parsers\Http\_Core\Fields\Procurement\CollectingEndDate;
use App\Services\Parsers\Http\_Core\Fields\Procurement\CollectingOrder;
use App\Services\Parsers\Http\_Core\Fields\Procurement\CollectingPlace;
use App\Services\Parsers\Http\_Core\Fields\Procurement\CollectingStartDate;
use App\Services\Parsers\Http\_Core\Fields\Procurement\Currency;
use App\Services\Parsers\Http\_Core\Fields\Procurement\EtpCode;
use App\Services\Parsers\Http\_Core\Fields\Procurement\PlacingWayCode;
use App\Services\Parsers\Http\_Core\Fields\Procurement\Price;
use App\Services\Parsers\Http\_Core\Fields\Procurement\ProcurementType223;
use App\Services\Parsers\Http\_Core\Fields\Procurement\PublishDate;
use App\Services\Parsers\Http\_Core\Fields\Procurement\PurchaseObjectInfo;
use App\Services\Parsers\Http\_Core\Fields\Procurement\RegNumber;
use App\Services\Parsers\Http\_Core\Fields\Procurement\Schedule;
use App\Services\Parsers\Http\_Core\Fields\Procurement\ScoringDate;
use App\Services\Parsers\Http\_Core\Fields\Procurement\StageCode;
use Symfony\Component\DomCrawler\Crawler;

class Procurement extends BaseModel
{
    /**
     * @return array
     * @throws \Throwable
     */
    public function handle(): array
    {
        $baseXPath = "//td[contains(text(), '%s')]/following-sibling::td";
        $biddingAddInfoXPath = '//h2[contains(text(), \'Информация о процедуре\')]/following-sibling::div[1]
            /table/tbody/tr/td[text() = \'Дополнительная информация\']/following-sibling::td';

        $this
            ->addField(new ProcurementType223($this->crawler, sprintf($baseXPath, 'Способ определения поставщика')))
            ->addField(new RegNumber($this->crawler, '//h1'))
            ->addField(new Currency($this->crawler, sprintf($baseXPath, 'Валюта'), 'currency_code'))
            ->addField(new Price($this->crawler, sprintf($baseXPath, 'цена контракта')))
            ->addField(new PlacingWayCode($this->crawler, sprintf($baseXPath, 'Способ определения поставщика')))
            ->addField(new EtpCode($this->crawler, sprintf($baseXPath, 'Адрес электронной площадки')))
            ->addField(new PublishDate($this->crawler, '//div[@class = \'public\']'))
            ->addField(new PurchaseObjectInfo($this->crawler, sprintf($baseXPath, 'Наименование объекта закупки')))
            ->addField(new StageCode($this->crawler, sprintf($baseXPath, 'Этап закупки')))
            ->addField(new Schedule($this->crawler, sprintf($baseXPath, 'Сведения о связи с позицией плана-графика')))
            ->addMultiField('bidding',
                new BiddingDate($this->crawler, sprintf($baseXPath, 'Дата проведения аукциона')),
                new BiddingAddInfo($this->crawler, $biddingAddInfoXPath))
            ->addMultiField('scoring',
                new ScoringDate($this->crawler, sprintf($baseXPath, 'Дата окончания срока рассмотрения')))
            ->addMultiField('collecting',
                new CollectingOrder($this->crawler, sprintf($baseXPath, 'Порядок подачи заявок')),
                new CollectingPlace($this->crawler, sprintf($baseXPath, 'Место подачи заявок')),
                new CollectingStartDate($this->crawler, sprintf($baseXPath, 'Дата и время начала')),
                new CollectingEndDate($this->crawler, sprintf($baseXPath, 'Дата и время окончания')));

        $this->mergeJsonFields('procedure_info', 'bidding', 'scoring', 'collecting');
        $this->parseAmounts();
        $this->setProcedureLink();

        return $this->getModel();
    }

    /**
     * Метод выдергивает общую сумму по заявкам и контрактам
     */
    protected function parseAmounts(): void
    {
        $securityXPath = '//td[contains(text(), \'Размер обеспечения заявки\')]/following-sibling::td';
        $security = $this->crawler->filterXPath($securityXPath)
            ->each(function (Crawler $node) {
                $amount = trim(preg_replace('/[^0-9]+/', '', $node->text()));

                return $amount ? (double)mb_substr($amount, 0, -2) . '.' . mb_substr($amount, -2) : null;
            });

        $contractEnforcementXPath = '//td[contains(text(), \'Размер обеспечения исполнения контракта\')]/following-sibling::td';
        $contractEnforcement = $this->crawler->filterXPath($contractEnforcementXPath)
            ->each(function (Crawler $node) {
                $amount = trim(preg_replace('/[^0-9]+/', '', $node->text()));

                return $amount ? (double)mb_substr($amount, 0, -2) . '.' . mb_substr($amount, -2) : null;
            });

        $amounts = [
            'amount_of_security' => array_sum($security) ? array_sum($security) : null,
            'amount_of_contract_enforcement' => array_sum($contractEnforcement) ? array_sum($contractEnforcement) : null
        ];

        $this->model = array_merge($this->model, $amounts);
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

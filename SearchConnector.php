<?php

namespace App\Services\Parsers\Http\Zakupki;


use App\Services\Parsers\Http\_Core\ProcurementsSearcher;

class SearchConnector extends ProcurementsSearcher
{
    protected $url = 'http://www.zakupki.gov.ru/epz/order/quicksearch/search.html';

    protected $getParams = [
        'searchString' => '',
        'morphology' => 'on',
        'pageNumber' => 1,
        'sortDirection' => 'false',
        'recordsPerPage' => '_50',
        'showLotsInfoHidden' => 'false',
        'fz44' => 'on',
        'fz223' => 'on',
        'placingWaysList' => '',
        'placingWaysList223' => '',
        'af' => 'on',
        'ca' => 'on',
        'pc' => 'on',
        'pa' => 'on',
        'priceFrom' => '',
        'priceTo' => '',
        'currencyId' => -1,
        'selectedSubjects' => '',
        'agencyTitle' => '',
        'agencyCode' => '',
        'agencyFz94id' => '',
        'agencyFz223id' => '',
        'agencyInn' => '',
        'regions' => '',
        'regionDeleted' => 'false',
        'publishDateFrom' => '',
        'publishDateTo' => '',
        'updateDateFrom' => '',
        'updateDateTo' => '',
        'applSubmissionCloseDateFrom' => '',
        'applSubmissionCloseDateTo' => '',
        'sortBy' => 'PUBLISH_DATE',
    ];
}

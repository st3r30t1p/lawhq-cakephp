<?php


namespace App\Service\Docket;

use App\Repository\DocketServiceRepository;

/**
 * Class ParseDocketCourtsService
 * @package App\Service\Docket
 */
class ParseDocketCourtsService extends BaseDocketService
{

    private $docketRepository;

    /**
     * ParseDocketCourtsService constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->docketRepository = new DocketServiceRepository();

    }

    /**
     * @param $params
     * @return array|string[]|void
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function getRemoteDocketsReport($params)
    {

        $docRemoteData = [];

        $pacerCookie = $this->docketConnector->login();

        $docketCourts = $this->docketRepository->getCourtData($params['court_id']);

        if ($this->setPacerCookie($pacerCookie)) {

            if ($params['court_type'] == 'appellate') {
                $getCaseNum = $this->docketConnector->httpClient('https://ecf.' . $params['court_fed_abbr'] . '.uscourts.gov/n/beam/servlet/TransportRoom', ['csnum1' => $params['case_number'], 'servlet' => 'CaseSelectionTable.jsp'], $this->getPacerCookie(), 'POST')->getStringBody();
                if ($getCaseNum == '') {
                    return $docRemoteData = ['error' => 'Case number (' . $params['case_number'] . ') invalid; format is yy-nnnn or yy-nnnnn.'];
                }
                if (strpos($getCaseNum, 'No case found with the search criteria')) {
                    return $docRemoteData = ['error' => 'No cases found with this case number in the ' . $docketCourts->name];
                }

                $docRemoteData = $this->getRemoteDataForAppellate($getCaseNum, $docketCourts, $params['court_fed_abbr']);
            } else {
                $getCaseNum = $this->docketConnector->httpClient('https://ecf.' . $params['court_fed_abbr'] . '.uscourts.gov/cgi-bin/iquery.pl?' . $params['case_number'], [], $this->getPacerCookie(), 'GET')->getStringBody();

                if (strpos($getCaseNum, '404 Not Found') !== false) {
                    return $docRemoteData = ['error' => '404 Not Found'];
                }
                if (strpos($getCaseNum, 'Cannot find case ' . $params['case_number']) !== false) {
                    return $docRemoteData = ['error' => 'Cannot find case ' . $params["case_number"]];
                }
                if (strpos($params['case_number'], '-') == false || strpos($params['case_number'], ':') == false) {
                    return $docRemoteData = ['error' => $params['case_number'] . ' is not formatted correctly. Please enter a valid value.'];
                }
                preg_match('/<FORM ENCTYPE=.* method=POST action=".*\?(.*)"/', $getCaseNum, $matches);

                if (strlen(explode('-', $params['case_number'])[0]) > 4) {
                    $exp = explode(':',$params['case_number']);
                    $sliceSec = substr($exp[1], 2);
                    $caseNumber = implode([$exp[0], $sliceSec], ':');
                    $getCourtByCaseName = $this->docketConnector->httpClient('https://ecf.' . $params['court_fed_abbr'] . '.uscourts.gov/cgi-bin/iquery.pl?' . $matches[1], ['case_num' => $caseNumber], $this->getPacerCookie(), 'POST')->getStringBody();
                } else {
                    $getCourtByCaseName = $this->docketConnector->httpClient('https://ecf.' . $params['court_fed_abbr'] . '.uscourts.gov/cgi-bin/iquery.pl?' . $matches[1], ['case_num' => $params['case_number']], $this->getPacerCookie(), 'POST')->getStringBody();
                    if (strpos($params['case_number'], ':') !== false && strpos($getCourtByCaseName, $params['case_number'] . ' is not formatted correctly. Please enter a valid value.') !== false) {
                        $exp = explode(':', $params['case_number']);
                        $formatCaseNumber = strtolower($exp[1]);
                        $findCorrectlyCase = $this->docketConnector->httpClient('https://ecf.' . $params['court_fed_abbr'] . '.uscourts.gov/cgi-bin/possible_case_numbers.pl?' . $formatCaseNumber, [], $this->getPacerCookie(), 'GET')->getStringBody();
                        $this->getParseHtmlDom()->load($findCorrectlyCase);
                        $params['case_number'] = $this->getParseHtmlDom()->find('case')->getAttribute('number');

                        $getCourtByCaseName = $this->docketConnector->httpClient('https://ecf.' . $params['court_fed_abbr'] . '.uscourts.gov/cgi-bin/iquery.pl?' . $matches[1], ['case_num' => $params['case_number']], $this->getPacerCookie(), 'POST')->getStringBody();
                    }
                }

                if (strpos($getCourtByCaseName, $params['case_number']. ' is not formatted correctly. Please enter a valid value.') !== false) {
                    return $docRemoteData = ['error' => $params['case_number']. ' is not formatted correctly. Please enter a valid value.'];
                }

                if (strpos($getCourtByCaseName, $params['case_number'] . ' is not a valid case. Please enter a valid value.') !== false) {
                    return $docRemoteData = ['error' => $params['case_number'] . ' is not a valid case. Please enter a valid value.'];
                }
                if (strpos($getCourtByCaseName, 'No search criteria were entered.') !== false) {
                    return $docRemoteData = ['error' => 'No search criteria were entered.'];
                }

                $docRemoteData = $this->getRemoteData($getCourtByCaseName, $docketCourts, $params['court_fed_abbr']);
            }
        }

        return $docRemoteData;
    }

    /**
     * @param $htmlBody
     * @param $docketCourts
     * @param $fedAbbr
     * @return array|string[]
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function getRemoteData($htmlBody, $docketCourts, $fedAbbr)
    {
        $result = [];

        $this->getParseHtmlDom()->load($htmlBody);

        $caseNum = $this->getParseHtmlDom()->find('font')->text;

        $data = $this->getParseHtmlDom()->find('center')->innerHtml;

        $dataArr = explode('<br />', strip_tags($data, '<br>'));

        $result[] = [
            'court' => $docketCourts->name,
            'court_id' => $docketCourts->id,
            'case_number' => $caseNum,
            'case_name' => trim(substr($dataArr[0], strpos($dataArr[0], ' ')), ' '),
            'filed' => trim(substr($dataArr[3], strpos($dataArr[3], ':')), ': '),
            'judge' => strpos($data, 'presiding') == false ? '' : trim($dataArr[1], ', presiding'),
            'referal' => strpos($data, 'referal') == false ? '' : trim($dataArr[2], ', referal'),
            'fed_abbr' => $fedAbbr
        ];

        return $result;
    }

    /**
     * @param $htmlBody
     * @param $docketCourts
     * @param $fedAbbr
     * @return array
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function getRemoteDataForAppellate($htmlBody, $docketCourts, $fedAbbr)
    {
        $result = [];

        $this->getParseHtmlDom()->load($htmlBody);

        $case = $this->getParseHtmlDom()->find('table tr', 1)->innerHtml;

        $case = str_replace(['<br />', '<br>', '<br/>', '&nbsp;'], '||', $case);
        $case = explode('||', strip_tags($case));

        $result[] = [
            'court' => $docketCourts->name,
            'court_id' => $docketCourts->id,
            'case_number' => trim($case[0], ' '),
            'case_name' => $case[1],
            'fed_abbr' => $fedAbbr
        ];

        return $result;
    }
}

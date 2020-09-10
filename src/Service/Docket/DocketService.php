<?php

namespace App\Service\Docket;

use App\Lib\StringHelper;
use App\Repository\DocketServiceRepository;
use Cake\Log\Log;
use Cake\Routing\Router;

class DocketService extends BaseDocketService {

    private $docketRepository;

    public function __construct()
    {
        parent::__construct();

        $this->docketRepository = new DocketServiceRepository();

    }


    /**
     * @param $data
     * @return array
     *
     * Get all docket_entries existing in BD.
     */

    public function getDocketsReport($data)
    {
        $result  = [];

        if (!empty($data)) {

            $docket = $this->docketRepository->getDocketData($data['docketID']);

            foreach ($docket->docket_entries as $docketEntry) {

                $attachments = null;
                $docLink = null;
                $downloaded = null;

                if ($docketEntry->has_attachment != null) {

                    $description = $docketEntry->description;

                    foreach ($docket->docket_attachments as $attachment) {

                        if ($docketEntry->sequence_id == $attachment->sequence_id) {

                            $fileName = $docket->id . '-' . $docketEntry->sequence_id . '-';

                            if ($attachment->attachment_id == 0) {

                                $docLink = $this->getDocLink($fileName . $attachment->attachment_id);

                                $downloaded = $attachment->downloaded;

                            } else {
                                $attachments[] = $this->getAttachment($description, $fileName, $attachment);
                            }
                        }
                    }
                }

                $result[] = [
                    'sequenceID'  => $docketEntry->sequence_id,
                    'link'        => (!is_null($docLink)) ? '<a href="' . $docLink . '" target="_blank">' . $docketEntry->sequence_id . '</a>' : null,
                    'date'        => $docketEntry->date_filed,
                    'description' => $this->filterDescription($docketEntry->description),
                    'downloaded'  => $downloaded,
                    'attachments' => $attachments,
                    'hasDownloadUrl' => is_null($docketEntry->attachment_download_url) ? null : true
                ];
            }
        }
        return $result;
    }


    /**
     * @param $description
     * @return false|string
     *
     * Filter docket_entry description for main entry.
     */

    public function filterDescription($description)
    {
        if (strpos($description, 'Attachments:') !== false) {
            return strstr($description, '(Attachments: ', true);
        }
        if (strpos($description, '(Entered:') !== false) {
            return strstr($description, '(Entered:', true);
        }
        if (strpos($description, '(Filed') !== false) {
            return strstr($description, '(Filed', true);
        }
        return $description;
    }


    /**
     * @param $description
     * @param $fileName
     * @param $attachmentEntity
     * @return array
     *
     * Get attachment from docket_entry description.
     */

    public function getAttachment($description, $fileName, $attachmentEntity) {

        $attachments = [];

        if (strpos($description, 'Attachments:') !== false) {

            $newstring = preg_replace_callback(
                '/\(([0-9]{1,2})\)/i',
                function($match) {
                    return $match[1];
                },
                $description
            );
            if (strpos($newstring, '(')) {
                $newstring = str_replace('(', '', $newstring);
            }
            if (strpos($newstring, '),')) {
                $newstring = str_replace('),', ',', $newstring);
            }
            if (strpos($newstring, 's)')) {
                $newstring = str_replace('s)', 's', $newstring);
            }
            $a = array_slice(explode("# ", StringHelper::getSubString($newstring, 'Attachments: ', ')')), 1);

            foreach ($a as $item) {

                if (preg_match('/^'.$attachmentEntity->attachment_id.' /', $item)) {
                    $id             = strstr(trim($item), ' ', true);
                    $text           = strstr(trim($item), ' ');
                    $attachmentLink = $this->getDocLink($fileName.$id);
                    $attachments     = [
                            'attachmentID' => $id,
                            'link'         => $attachmentLink ? '<a href="' . $attachmentLink . '" target="_blank">' . $id . '</a>' : null,
                            'text'         => trim($text, ','),
                            'downloaded'   => $attachmentEntity->downloaded,
                            'hasDownloadUrl' => is_null($attachmentEntity->download_url) ? null : true
                    ];
                }

            }

            return $attachments;
        }

        return $description;
    }


    /**
     * @param $fileName
     * @return string|null
     *
     * Build links from existing files.
     */

    public function getDocLink($fileName)
    {
        $path = WWW_ROOT . 'dockets/files/';
        $files = $this->getListFiles($path);

        foreach ($files as $file) {

            if (preg_match("/^" . $fileName . "/", $file)) {

                return Router::fullBaseUrl() .'/dockets/files/' . $file;
            }

        }
        return null;
    }


    /**
     * @param $params
     * @return array|string[]
     *
     * Connect to remote service, get all remote data, handling and save docket_entries.
     */

    public function getRemoteDocketsReport($params)
    {
        $docRemoteTableData = [];

        $pacerCookie = $this->docketConnector->login();

        $docketEntity = $this->docketRepository->getDocketData($params['docketID']);

        if ($this->setPacerCookie($pacerCookie)) {
            if ($params['courtType'] == 'appellate') {
                $getCaseNum = $this->docketConnector->httpClient('https://ecf.' . $params['fedAbbr'] . '.uscourts.gov/n/beam/servlet/TransportRoom', ['csnum1' => $params['caseNumber'], 'servlet' => 'CaseSelectionTable.jsp'], $this->getPacerCookie(), 'POST')->getStringBody();

                if (strpos($getCaseNum, 'No case found with the search criteria')) {
                    return $docRemoteData = ['error' => 'No cases found with this case number in the ' . $params['courtName']];
                }

                $dktRpt = $this->docketConnector->httpClient('https://ecf.' . $params['fedAbbr'] . '.uscourts.gov/n/beam/servlet/TransportRoom', [
                    'servlet' => 'CaseSummary.jsp',
                    'CSRF' => 'csrf_-1407585579150937745',
                    'caseNum' => $params['caseNumber'],
                    'fullDocketReport' => 'Y',
                    'incDktEntries' => 'Y',
                    'dateFrom' => $params['documents_date_from_'],
                    'dateTo' => $params['documents_date_to_'],
                    'actionType' => 'Run Docket Report'
                ], $this->getPacerCookie())->getStringBody();

                $docRemoteTableData = $this->getRemoteDocketTableDataForAppellate($dktRpt, $docketEntity);
            } else {
                $getCaseNum = $this->docketConnector->httpClient('https://ecf.' . $params['fedAbbr'] . '.uscourts.gov/cgi-bin/possible_case_numbers.pl?' . trim($params['caseNumber'], ' '), [], $this->getPacerCookie(), 'GET')->getStringBody();

                if (strpos($getCaseNum, 'Cannot find case ' . $params['caseNumber']) !== false) {
                    return $docTableData = ['error' => 'Cannot find case ' . $params["caseNumber"]];
                }

                $this->getParseHtmlDom()->load($getCaseNum);

                $caseID = $this->getParseHtmlDom()->find('case', 0)->getAttribute('id');

                $dktRpt = $this->docketConnector->httpClient('https://ecf.' . $params['fedAbbr'] . '.uscourts.gov/cgi-bin/DktRpt.pl?610050380840943-L_1_0-1', [
                    'view_comb_doc_text' => '',
                    'all_case_ids' => $caseID,
                    'case_num' => $params['caseNumber'],
                    'date_range_type' => 'Filed',
                    'date_from' => '',
                    'date_to' => '',
                    'documents_numbered_from_' => $params['documents_numbered_from_'],
                    'documents_numbered_to_' => $params['documents_numbered_to_'],
//                    'list_of_parties_and_counsel' => 'on',
//                    'terminated_parties' => 'on',
                    'pdf_header' => 1,
                    'output_format' => 'html',
                    'PreResetField' => '',
                    'PreResetFields' => '',
                    'sort1' => 'oldest date first'
                ], $this->getPacerCookie())->getStringBody();

                $docRemoteTableData = $this->getRemoteDocketTableData($dktRpt, $docketEntity);
            }

            //$dktRpt = file_get_contents(ROOT.'/persistent/doc.html');

            if (!isset($docRemoteTableData['error'])) {

                if (!empty($docRemoteTableData) && !empty($docketEntity)) {
                    $rd = $this->saveRemoteDocketTableData($docRemoteTableData, $docketEntity);

                    foreach ($rd as $d) {
                        $this->docketRepository->insertDocketAttachments($docketEntity, $d['docketEntry']['sequence_id'], $d);
                    }
                }
            }
        }

        return $docRemoteTableData;
    }


    /**
     * @param $htmlBody
     * @param $docketEntity
     * @return array|string[] get dockets data from remote parsed content
     *
     * Get and extract all data from remote table.
     */

    public function getRemoteDocketTableData($htmlBody, $docketEntity)
    {
        $result = [];

        if (strpos($htmlBody, 'Case not found.') !== false) {
            return $result[] = ['error' => 'Case not found.'];
        }

        $this->getParseHtmlDom()->load($htmlBody);

        $table = $this->getParseHtmlDom()->find('table[rules="all"]', 0);

        if (is_null($table)) {
            return $result[] = ['error' => 'Documents not found.'];
        }

        $tr = $table->find('tr');

        $lastValue = null;
        $i = 1;

        foreach ($tr as $key => $row) {

            if ($key == 0) {
                continue;
            }

            if ($value = $row->find('td[align="right"] a', 0)->text ?? str_replace("&nbsp;", '', $row->find('td[align="right"]', 0)->text)) {
                $lastValue = $value;
                $i = 1;
            } else {
                $value = "{$lastValue}.{$i}";
                ++$i;
            }

            if ($link = $row->find('td[align="right"] a', 0)) {
                $link = str_replace("&nbsp;", '', $link);
            }

            $found = false;

            foreach ($docketEntity->docket_entries as $docket) {
                if ($docket->sequence_id == $value || ($docket->sequence_id == $value && !is_null($docket->attachment_download_url))) {
                    $found = true;
                }
            }

            if (!$found) {
                $result[] = [
                    'sequenceID'  => $value,
                    'link'        => $link,
                    'date'        => $row->find('td', 0)->text,
                    'description' => strip_tags($row->find('td', 2)->outerHtml),
                    'attachments' => $this->getRemoteAttachments($row->find('td', 2)->outerHtml)
                ];
            }

        }
        return $result;
    }

    /**
     * @param $htmlBody
     * @param $docketEntity
     * @return array|string[]
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function getRemoteDocketTableDataForAppellate($htmlBody, $docketEntity)
    {
        $result = [];

        if (strpos($htmlBody, 'Case not found.') !== false) {
            return $result[] = ['error' => 'Case not found.'];
        }
        if (strpos($htmlBody, 'No docket entries found.') !== false) {
            return $result[] = ['error' => 'No docket entries found.'];
        }

        $this->getParseHtmlDom()->load($htmlBody);

        $table = $this->getParseHtmlDom()->find('table[border=0][cellpadding=4]');

        if (is_null($table)) {
            return $result[] = ['error' => 'Documents not found.'];
        }

        $tr = $table->find('tr');

        foreach ($tr as $key => $row) {

            $value = trim(str_replace("&nbsp;", '', $row->find('td', 1)->text));
            $link = null;
            if ($value == '') {
                $value = trim(strip_tags(str_replace("&nbsp;", '', $row->find('td', 1)->innerHtml)));
                $link = str_replace("&nbsp;", '', $row->find('td a')->outerHtml);
            }

            $found = false;

            foreach ($docketEntity->docket_entries as $docket) {
                if (($docket->sequence_id == $value) && !is_null($docket->attachment_download_url)) {
                    $found = true;
                }
            }

            if (!$found) {
                $result[] = [
                    'sequenceID'  => $value,
                    'link'        => $link,
                    'date'        => $row->find('td', 0)->text,
                    'description' => strip_tags($row->find('td', 2)->innerHtml)
                ];
            }

        }
        return $result;
    }


    /**
     * @param $description
     * @return array get remote attachments link
     *
     * Get attachment link from remote table.
     */

    public function getRemoteAttachments($description)
    {
        $attachments = [];
        if (strpos($description, 'Attachments:') !== false) {

            $partContent = StringHelper::getSubString($description, '(Attachments: ', '</td>');
            $this->getParseHtmlDom()->load($partContent);

            $a = $this->getParseHtmlDom()->find('a');
            foreach ($a as $item) {
                $attachments[] = $item->outerHtml;
            }
        }

        return $attachments;
    }

    /**
     * @param $docTableData
     * @param $docketEntity
     * @return array
     *
     * When we get remote table data we save docket_entry and return attachments.
     */

    public function saveRemoteDocketTableData($docTableData, $docketEntity)
    {
        $result = [];

        foreach($docTableData as $index => $item) {

            $attachments = [];
            $docUrlData  = null;
            $result[$index]['attachments'] = [];

            if (!is_null($item['link'])) {

                $this->getParseHtmlDom()->load($item['link']);
                $a = $this->getParseHtmlDom()->find('a', 0);

                $attachments[] = $a->outerHtml;
                $docUrlData    = array_merge(['url' => $a->getAttribute('href')], $this->getUrlData($a->getAttribute('onclick')));

                if (!empty($item['attachments'])) {

                    $attachments = array_merge($attachments, $item['attachments']);

                }

                foreach ($attachments as $key => $att) {

                    $attLink = $this->getParseHtmlDom()->load($att)->find('a', 0);

                    $fileName = $docketEntity->id.'-'.$item['sequenceID'].'-'.$key;

                    $result[$index]['attachments'][$key] = [
                        'attachmentID' => $key,
                        'downloaded'   => $this->getDocLink($fileName) ? 1 : null,
                        'docUrl'       => $attLink->getAttribute('href'),
                        'restricted'   => $this->isRestricted($item['description']),
                        'sealed'       => $this->isSealed($item['description'])
                    ];

                }
            }

            $result[$index]['docketEntry'] = $this->docketRepository->insertDocketEntry([
                'sequenceID'    => $item['sequenceID'],
                'dateFiled'     => $item['date'],
                'dateEntered'   => $item['date'],
                'description'   => $item['description'],
                'docUrlData'    => $docUrlData,
                'restricted'    => $this->isRestricted($item['description']),
                'sealed'        => $this->isSealed($item['description'])
            ], $docketEntity);
        }
        return $result;
    }


    /**
     * @param $url
     * @return array
     *
     * Get url param from attachment link.
     * Need for fetching remote attachment
     */

    private function getUrlData($url)
    {
        if (strpos($url, 'doDocPostURL') !== false) {
            $array = explode('\'', $url);
            return [
                'caseid' => $array[3],
                'dls_id' => $array[1]
            ];
        }
        if (strpos($url, 'goDLS') !== false) {
            $array = explode(',', $url);
            return [
                'caseid' => trim($array[1], '\''),
                'de_seq_num' => trim($array[2], '\'')
            ];
        }
    }


    /**
     * @param $params
     * @return array|string[]
     *
     * Get attachment for docket_entry
     */

    public function getRemoteAttachment($params)
    {
        $docketEntry      = $this->docketRepository->getDocketEntry($params)->first();
        $docketAttachment = $this->docketRepository->getDocketAttachment($params)->first();

        $urlData        = json_decode($docketEntry->attachment_download_url, true);
        $urlData['url'] = $docketAttachment->download_url;


        try {

            $pacerCookie = $this->docketConnector->login();
            if (array_key_exists('de_seq_num', $urlData)) {
                $content = $this->getDocumentPage($urlData, $this->setPacerCookie($pacerCookie));
            } else {
                $content = $this->getDocumentAppellatePage($urlData, $this->setPacerCookie($pacerCookie));
            }

            $docName = $params['docketID'].'-'.$docketEntry->sequence_id . '-' . $params['attachmentID'];

            if (preg_match("/%PDF-/", $content)) {

                $saveDocs = $this->saveDocs(strstr($content, '%PDF-'), $docName);

                if ($saveDocs) {
                    $params['downloaded'] = 1;
                    $this->docketRepository->updateAttachments($params);
                    return ['url' => Router::fullBaseUrl() .'/dockets/files/'. $docName. '.pdf'];
                }

            } else if ((strpos($content, 'Multiple Documents') !== false) || (strpos($content, 'Document Selection Menu') !== false)) {

                $this->getParseHtmlDom()->load($content);

                $table = $this->getParseHtmlDom()->find('#cmecfMainContent table')->find('tr td a');

                foreach ($table as $key => $ch) {

                    if (($params['attachmentID'] == $ch->text) || ($params['sequenceID'] == $ch->text)) {

                        $urlData['url'] = $ch->getAttribute('href');
                        $content        = $this->getDocumentPage($urlData, $this->getPacerCookie());

                        if (preg_match("/%PDF-/", $content)) {

                            $saveDocs = $this->saveDocs(strstr($content, '%PDF-'), $docName);

                            if ($saveDocs) {
                                $params['downloaded'] = 1;
                                $this->docketRepository->updateAttachments($params);
                                return ['url' => Router::fullBaseUrl() .'/dockets/files/'. $docName. '.pdf'];
                            }

                        } else {
                            $this->getParseHtmlDom()->load($content);
                            $text = $this->getParseHtmlDom()->find('#cmecfMainContent')->text;

                            $params['downloaded'] = null;
                            $this->docketRepository->updateAttachments($params);

                            return ['error' => $text];
                        }

                    }
                }
            } else if (strpos($content, 'Documents are attached to this filing') !== false) {

                $this->getParseHtmlDom()->load($content);

                $table = $this->getParseHtmlDom()->find('table[border=0][cellpadding=5]')->find('tr td a');

                foreach ($table as $key => $ch) {

                        $urlData['url'] = $ch->getAttribute('href');

                        $link = explode('/', $urlData['url']);

                        if ($urlData['dls_id'] !== $link[4]) {
                            $urlData['dls_id'] = $link[4];
                        }

                        $content = $this->getDocumentAppellatePage($urlData, $this->getPacerCookie());

                        if (preg_match("/%PDF-/", $content)) {

                            $saveDocs = $this->saveDocs(strstr($content, '%PDF-'), $docName);

                            if ($saveDocs) {
                                $params['downloaded'] = 1;
                                $this->docketRepository->updateAttachments($params);
                                return ['url' => Router::fullBaseUrl() . '/dockets/files/' . $docName . '.pdf'];
                            }
                        }
                }
            } else {
                $this->getParseHtmlDom()->load($content);
                $text = $this->getParseHtmlDom()->find('#cmecfMainContent')->text;

                $params['downloaded'] = null;
                $this->docketRepository->updateAttachments($params);

                return ['error' => $text];
            }
        } catch (\Exception $exception) {

            Log::error($exception->getMessage());

            return ['error' => 'This document could not be downloaded. Please try again.'];
        }
        return [];
    }

}

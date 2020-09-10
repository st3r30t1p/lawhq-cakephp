<?php

namespace App\Service\Docket;

use App\Lib\DocketConnector;
use Cake\Log\Log;
use PHPHtmlParser\Dom;

class BaseDocketService {

    private $pacerCookie;
    public  $docketConnector;
    private $parseHtmlDom;

    public function __construct()
    {
        $this->docketConnector = new DocketConnector();
        $this->parseHtmlDom    = new Dom();
    }


    /**
     * @return mixed
     *
     * Get cookie for Pacer Service. Need for accessing login and attachments.
     */

    public function getPacerCookie()
    {
        return $this->pacerCookie;
    }


    public function setPacerCookie($cookie)
    {
        $this->pacerCookie = $cookie;

        if ($cookie) {
            return $this->pacerCookie;
        }
        return false;
    }


    /**
     * @return Dom
     *
     * Parse object for parsing html dom.
     */

    public function getParseHtmlDom()
    {
        return $this->parseHtmlDom;
    }


    /**
     * @param $content
     * @param $messageData
     * @param $docket
     * @param $docketEntry
     * @param null $breakStep
     * @return \null[][]
     *
     * When we got remote docket_attachments we checking that be contain PDF. If content contain PDF generate pdf file and
     * saving in local storage "webroot/dockets/files".
     */

    public function documentPageHandler($content, $messageData, $docket, $docketEntry, $breakStep = null) {

        $result = ['docData' => [
            'hasAttachments' => null
        ]];

        $docName = $docket->id.'-'.$docketEntry->sequence_id;

        //save doc if content is pdf
        if (preg_match("/%PDF-/", $content)) {

            $saveDocs = $this->saveDocs(strstr($content, '%PDF-'), $docName.'-0');

            if ($saveDocs) {

                $result['docData']['attachments'][] = [
                    'attachmentID' => 0,
                    'docUrl' => $messageData['docUrlData']['url'],
                    'downloaded' => 1,
                    'restricted' => $messageData['restricted'],
                    'sealed'     => $messageData['sealed']
                ];

                $result['docData']['hasAttachments'] = true;

            }

            return $result;

        } else if (strpos($content, 'This document is restricted.') !== false) {

            $result['docData']['restricted'] = true;

        } else if ((strpos($content, 'Multiple Documents') !== false) || (strpos($content, 'Document Selection Menu') !== false)) {

            $this->getParseHtmlDom()->load($content);

            $table = $this->getParseHtmlDom()->find('#cmecfMainContent table')->find('tr td a');

            foreach ($table as $key => $ch) {

                $fileName = $docName . '-' . $key;

                $messageData['docUrlData']['url'] = $ch->getAttribute('href');

                $content = $this->getDocumentPage($messageData['docUrlData'], $this->getPacerCookie());

                if (preg_match("/%PDF-/", $content)) {

                    $saveDocs = $this->saveDocs(strstr($content, '%PDF-'), $fileName);

                    if ($saveDocs) {
                        $result['docData']['attachments'][] = [
                            'attachmentID' => $key,
                            'docUrl' => $messageData['docUrlData']['url'],
                            'restricted' => $messageData['restricted'],
                            'sealed'     => $messageData['sealed'],
                            'downloaded' => 1
                        ];
                    }

                } else {

                    $result['docData']['attachments'][] = [
                        'attachmentID' => $key,
                        'docUrl' => $messageData['docUrlData']['url'],
                        'restricted' => $messageData['restricted'],
                        'sealed'     => $messageData['sealed'],
                        'downloaded' => null
                    ];

                }

                if (!is_null($breakStep) && ($key == $breakStep)) {
                    break;
                }
            }

            $result['docData']['hasAttachments'] = true;

        } else {

            $result['docData']['attachments'][] = [
                'attachmentID' => 0,
                'docUrl' => $messageData['docUrlData']['url'],
                'downloaded' => null,
                'restricted' => $messageData['restricted'],
                'sealed'     => $messageData['sealed']
            ];

        }
        return $result;
    }


    /**
     * @param $emailDocUrlData
     * @param $cookies
     * @return string
     *
     * Connected to remote service and get pdf content.
     */

    protected function getDocumentPage($emailDocUrlData, $cookies)
    {
        return $this->docketConnector->httpClient($emailDocUrlData['url'], [
            'caseid'              => $emailDocUrlData['caseid'],
            'de_seq_num'          => $emailDocUrlData['de_seq_num'],
            'got_receipt'         => 1,
            'pdf_toggle_possible' => 1,
            //'magic_num'           => $emailDocUrlData['magic_num']
        ], $cookies)->getStringBody();
    }

    /**
     * @param $emailDocUrlData
     * @param $cookies
     * @return string
     */
    protected function getDocumentAppellatePage($emailDocUrlData, $cookies)
    {
        $link = explode('/' ,$emailDocUrlData['url']);
        return $this->docketConnector->httpClient($link[0] . '//' . $link[2] . '/n/beam/servlet/TransportRoom', [
            'servlet' => 'ShowDoc',
            'incPdfHeader' => 'Y',
            'incPdfHeaderDisp' => 'Y',
            'dls_id' => (string) $emailDocUrlData['dls_id'],
            'caseId' => (string) $emailDocUrlData['caseid'],
            'pacer' => 't',
            'recp' => time()
        ], $cookies)->getStringBody();
    }


    /**
     * @param $content
     * @param $docName
     * @return mixed|string|null
     *
     *  Creating and saving attachment in local storage.
     */

    public function saveDocs($content, $docName)
    {

        try {
            $path = WWW_ROOT . 'dockets/files/';

            $saved = file_put_contents($path . $docName . '.pdf', $content);

            if ($saved) {

                return $docName;
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }

        return null;
    }


    /**
     * @param $path
     * @return array|false
     *
     * Return list file on the specified path.
     */

    public function getListFiles($path)
    {
        return array_diff(scandir($path), array('.', '..'));
    }


    /**
     * @param $text
     * @return int|null
     *
     * Check restricted or not docket_entry.
     */

    public function isRestricted($text)
    {
        return strpos($text, 'RESTRICTED DOCUMENT') ? 1 : null;
    }


    /**
     * @param $text
     * @return int|null
     *
     * Check sealed or not docket_entry.
     */

    public function isSealed($text)
    {
        return strpos($text, 'SEALED DOCUMENT') ? 1 : null;
    }

}

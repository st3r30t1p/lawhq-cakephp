<?php

namespace App\Service;

use Cake\Http\Client;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Google_Service_Gmail;
use Google_Service_Gmail_ModifyMessageRequest;

class DoNotCallService {

    //http request for register/verify
    private function httpPhoneRequest($url, $number, $params)
    {
        $http = new Client();

        $data = [
            'phone1' => $number,
            //'phone2' => '',
            //'phone3' => '',
            'email' => 'dncr+'.$number.'@lawhq.com',
            'language' => 'en-US'
        ];

        $options = [
            'headers' =>
            ['Connection' => 'keep-alive',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Content-Type' => 'application/json;charset=UTF-8',
            'Sec-Fetch-Site' => 'same-site',
            'Sec-Fetch-Mode' => 'cors',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Host' => 'www2.donotcall.gov',
            'Cache-Control' => 'no-cache',
            'Accept' => '*/*',
            'Referer' => 'https://www.donotcall.gov/' . $params['referer'] . '.html',
            'Origin' => 'https://www.donotcall.gov',
            'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) snap Chromium/79.0.3945.117 Chrome/79.0.3945.117 Safari/537.36']
        ];

        try {
            return $http->post($url, json_encode($data), $options)->getJson();
        } catch
        (\Exception $e) {
            return $e->getMessage();
        }
    }

    //send phone number on register
    public function sendPhoneToRegister()
    {

        $dncrTable = TableRegistry::getTableLocator()->get('DoNotCallRegistry');
        $dncr = $dncrTable->find();
        $countSubmit = 0;
        $countVerify = 0;
        $submitResult = 'Submitted count: ';
        $verifyResult = 'Verify count: ';

        foreach ($dncr as $d) {

            if (is_null($d->registration_submitted)) {
                $result = $this->httpPhoneRequest(env('REGISTER_URL_DONOTCALL'), $d->number, ['referer' => 'register']); //to register url
                if (isset($result['message']) && ($result['message'] == 'saved')) {
                    $d->registration_submitted = Time::now();
                    $dncrTable->save($d);
                    $countSubmit++;
                }else {
                    Log::write('donotcall', $result);
                }
            } else if (!is_null($d->registration_link) && is_null($d->verification_submitted)) {
                $result = $this->httpPhoneRequest(env('VERIFY_URL_DONOTCALL'), $d->number, ['referer' => 'verify']); //to verify url
                if (isset($result['message']) && ($result['message'] == 'saved')) {
                    $d->verification_submitted = Time::now();
                    $dncrTable->save($d);
                    $countVerify++;
                }else {
                    Log::write('donotcall', $result);
                }
            }
        }

        return $submitResult . $countSubmit . ', ' . $verifyResult . $countVerify;
    }

    //http request for complete registration
    private function httpCompleteRequest($key)
    {
        try {
            $http = new Client();

            $options = [
                'headers' =>
                    [
                        'Connection' => 'keep-alive',
                        'Accept-Encoding' => 'gzip, deflate, br',
                        'Content-Type' => 'application/json;charset=UTF-8',
                        'Sec-Fetch-Site' => 'same-site',
                        'Sec-Fetch-Mode' => 'cors',
                        'Accept-Language' => 'en-US,en;q=0.9',
                        'Host' => 'www2.donotcall.gov',
                        'Cache-Control' => 'no-cache',
                        'Accept' => '*/*',
                        'Referer' => 'https://www.donotcall.gov/confirm.html?key='.$key,
                        'Origin' => 'https://www.donotcall.gov',
                        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) snap Chromium/79.0.3945.117 Chrome/79.0.3945.117 Safari/537.36'
                    ]
            ];

            return $http->post(env('COMPLETE_REGISTER_URL'), json_encode(['key' => $key]), $options)->getJson();
        } catch
        (\Exception $e) {
            return $e->getMessage();
        }
    }

    //handler for complete registration
    private function completeRegistration($key, $number)
    {
        $dncrTable = TableRegistry::getTableLocator()->get('DoNotCallRegistry');
        $dncr = $dncrTable->find()->where(['number' => $number,])->first();

        $result = $this->httpCompleteRequest($key);

        if ($result) {
            if (!is_null($dncr->registration_submitted)) {
                $dncr->registration_link = Time::now();
                $dncrTable->save($dncr);
            }
        }

        return $result;
    }

    private function successVerify($number, $registeredDate)
    {
        $dncrTable = TableRegistry::getTableLocator()->get('DoNotCallRegistry');
        $dncr = $dncrTable->find()->where(['number' => $number,])->first();
        if (!is_null($dncr->verification_submitted)) {
            $dncr->registered = $registeredDate;
            return $dncrTable->save($dncr);
        }
        return [];
    }

    public function dncrCheckEmail()
    {
        $client = GoogleService::getClient('gmail');
        $gService = new Google_Service_Gmail($client);

        $optParams = [];
        $optParams['maxResults'] = 60; // Return Only 30 Messages
        $optParams['labelIds'] = ['INBOX', 'UNREAD']; // Only show messages in Inbox and unread
        $messages = $gService->users_messages->listUsersMessages('me', $optParams);

        $data = [];

        foreach ($messages->getMessages() as $item) {

            $messageId = $item->getId(); // Grab first Message (array)
            $optParamsGet = [];
            $optParamsGet['format'] = 'full'; // Display message in payload
            $message = $gService->users_messages->get('me', $messageId, $optParamsGet);

            $messagePayload = $message->getPayload();
            $number = str_replace('+', '', strstr(strstr($message->getPayload()->getHeaders()[0]->getValue(), '@', true), '+'));

            $messagePayload = strtr($messagePayload->getBody()->data, '-_', '+/');
            $decodedMessage = base64_decode($messagePayload);

            if (strpos($decodedMessage, 'Here is the link') !== false) { //link for complete register

                preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $decodedMessage, $match);
                $key = explode('=', $match[0][0]);
                $mods = new Google_Service_Gmail_ModifyMessageRequest();

                $completeResult = $this->completeRegistration($key[1], $number);

                if ($completeResult) {
                    //$gService->users_messages->delete('me', $messageId);
                    $mods->setRemoveLabelIds(array("UNREAD"));
                    $gService->users_messages->modify('me', $messageId, $mods);
                    $data['completeRegister'][] = $completeResult;
                }

            } else if(strpos($decodedMessage, 'You successfully registered') !== false) {  //success verified

                $subMessage = strstr($decodedMessage, substr($number, -4));
                $endPosition = strpos($subMessage, '.', 1) - 8;
                $parseDate = substr($subMessage,8, $endPosition);

                $registeredDate = date("Y-m-d", strtotime($parseDate));

                $mods = new Google_Service_Gmail_ModifyMessageRequest();

                $successVerify = $this->successVerify($number, $registeredDate);

                if (!empty($successVerify)) {
                    $mods->setRemoveLabelIds(array("UNREAD"));
                    $gService->users_messages->modify('me', $messageId, $mods);
                    $data['successVerify'][] = $successVerify;
                }
            }
        }
        return $data;
    }

}

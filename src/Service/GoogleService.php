<?php

namespace App\Service;

use Cake\Http\Client;
use Cake\Log\Log;
use Exception;
use Google_Client;
use Google_Exception;
use Google_Service_Docs;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Gmail;
use Google_Service_Script;
/**
 * Class GoogleService
 * @package App\Service
 */
class GoogleService
{
    public $client;
    public $service;

    /**
     * GoogleService constructor.
     * @throws Google_Exception
     */
    public function __construct()
    {
        $this->client = self::getClient();
        $this->service = new Google_Service_Docs($this->client);
    }

    /**
     * @param string $clientType
     * @return Google_Client
     * @throws Google_Exception
     */
    public static function getClient($clientType = 'document')
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Docs API PHP Quickstart');
        $client->setScopes([
            Google_Service_Docs::DOCUMENTS,
            Google_Service_Docs::DRIVE,
            Google_Service_Script::SCRIPT_DEPLOYMENTS,
            Google_Service_Gmail::MAIL_GOOGLE_COM,
            Google_Service_Gmail::GMAIL_COMPOSE,
            Google_Service_Gmail::GMAIL_LABELS,
            "https://www.googleapis.com/auth/script.projects"
        ]);

        $client->setAuthConfig(ROOT.'/persistent/credentials.json');
        $client->setAccessType('offline');
        $client->setApprovalPrompt ("force");

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        switch ($clientType) {
            case 'document':
                $tokenPath = ROOT.'/persistent/token.json';
                break;
            case 'gmail':
                $tokenPath = ROOT.'/persistent/gmail_token.json';
                break;
            case 'docket':
                $tokenPath = ROOT.'/persistent/dockets_token.json';
                break;
        }

        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        return $client;
    }

    /**
     * Expands the home directory alias '~' to the full path.
     * @param string $path the path to expand.
     * @return string the expanded path.
     */
    public static function expandHomeDirectory($path)
    {
        $homeDirectory = getenv('HOME');
        if (empty($homeDirectory)) {
            $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
        }
        return str_replace('~', realpath($homeDirectory), $path);
    }

    /**
     * @param string $templateDocId
     * @param array $params
     * @return mixed
     */
    public function getNewDoc($templateDocId, $params, $matterID)
    {
        try {
            $newDocId = $this->saveNewDoc($templateDocId, $matterID);
            (new GoogleScriptService($this->client))->docProcessing($newDocId, $templateDocId, $params);
            return $newDocId;
        } catch (Google_Exception $e) {
            Log::write('debug', $e->getMessage());
        }
    }

    /**
     * @param $templateDocId
     * @return string
     */
    public function createNewDoc($templateDocId, $parentID)
    {
        $copy = new Google_Service_Drive_DriveFile(['name' => 'Generated Document', 'parents' => [$parentID]]);
        $newDoc = (new Google_Service_Drive($this->client))->files->copy($templateDocId, $copy);

        return $newDoc->id;
    }

    public function saveNewDoc($templateDocId, $matterID) {

        $folderName = env('GOOGLE_DOC_FOLDER_NAME');

        $optParams = array(
            //'pageSize' => 10,
            'fields' => "nextPageToken, files(contentHints/thumbnail,fileExtension,iconLink,id,name,size,thumbnailLink,webContentLink,webViewLink,mimeType,parents,kind,properties)",
            'q' => "name='".$folderName."'"
        );

        $googleDrive = new Google_Service_Drive($this->client);
        $fileList = $googleDrive->files->listFiles($optParams);
        $newDocId = null;

        foreach ($fileList as $file) {

            foreach ($file->getParents() as $parentID) {

                $parent = $googleDrive->files->get($parentID);

                if (substr($parent->getName(), 0, strlen($matterID)) == $matterID) {
                    $newDocId = $this->createNewDoc($templateDocId, $file->getID());
                }
            }
        }
        return $newDocId;
    }

    public static function notification($action)
    {
        $http = new Client();
        $userId = getEnv('GOOGLE_DOCKET_EMAIL');
        $access_token = self::getClient('docket')->getAccessToken()['access_token'];
        $options = [
            'headers' =>
                [
                    'Authorization' => 'Bearer ' . $access_token,
                    'Content-Type' => 'application/json'
                ]
        ];
        $data = [];

        switch ($action) {
            case 'watch' :
                $data = [
                    'labelIds' => ["INBOX", "UNREAD"],
                    'topicName' => 'projects/lawhq-project/topics/docket-lawhq',
                ];
                break;
            case 'stop' :
                $data = [];
                break;
        }

        try {
            return $http->post('https://www.googleapis.com/gmail/v1/users/' . $userId . '/'.$action, json_encode($data), $options)->getJson();
        } catch
        (\Exception $e) {
            return $e->getMessage();
        }
    }

}

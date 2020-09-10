<?php

require __DIR__ . '/vendor/autoload.php';

ini_set('register_argc_argv', 'On');

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 * @throws Google_Exception
 */
function getClient()
{
    $options = getopt('f:', ['file:']);

    $fileName = isset($options['file']) ? $options['file'] : 'token.json';

    $client = new Google_Client();
    $client->setApplicationName('Google Docs API PHP Quickstart');
    $client->setScopes([
        Google_Service_Docs::DOCUMENTS,
        Google_Service_Docs::DRIVE,
        Google_Service_Script::SCRIPT_DEPLOYMENTS,
        Google_Service_Gmail::MAIL_GOOGLE_COM,
        Google_Service_Gmail::GMAIL_COMPOSE,
        Google_Service_Gmail::GMAIL_LABELS
    ]);
    $client->setAuthConfig('persistent/credentials.json');
    $client->setAccessType('offline');
    $client->setApprovalPrompt ("force");

    // Load previously authorized credentials from a file.
    $credentialsPath = expandHomeDirectory('persistent/'.$fileName);
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
    } else {
        $client->setRedirectUri('http://localhost');
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Store the credentials to disk.
        if (!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0777, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        printf("Credentials saved to %s\n", $credentialsPath);
    }
    $client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path)
{
    $homeDirectory = getenv('HOME');
    if (empty($homeDirectory)) {
        $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
    }
    return str_replace('~', realpath($homeDirectory), $path);
}

// Get the API client and construct the service object.
try {
    $client = getClient();
} catch (Google_Exception $e) {
}
//$service = new Google_Service_Docs($client);

// Prints the title of the requested doc:
// https://docs.google.com/document/d/195j9eDD3ccgjQRttHhJPymLJUCOUjs-jmwTrekvdjFE/edit
//$documentId = '1nwJO-FQHd5OTnJaNR4CyoYrIifflF9FQt5LTdEM01E0';
//$doc = $service->documents->get($documentId);

//printf("The document title is: %s\n", $doc->getTitle());

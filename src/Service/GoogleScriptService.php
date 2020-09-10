<?php

namespace App\Service;

use Cake\ORM\Locator\TableLocator;
use Exception;
use Google_Client;
use Google_Service_Script;
use Google_Service_Script_ExecutionRequest;

/**
 * Class GoogleScriptService
 * @package App\Service
 */
class GoogleScriptService
{
    private $service;
    private $projId;

    /**
     * GoogleScriptService constructor.
     * @param Google_Client $client
     */
    public function __construct($client)
    {
        $this->service = new Google_Service_Script($client);

        // script page, file -> project properties -> project key
        $this->projId = env('GOOGLE_SCRIPT_PROJECT_ID');
    }

    /**
     * @param string $newDocId
     * @param string $templateDocId
     * @param array $params
     */
    public function docProcessing($newDocId, $templateDocId, $params)
    {

        $template = (new TableLocator)->get('templates')->find('all', [
            'contain' => ['SectionTemplates']
        ])->where(['google_doc_id' => $templateDocId])->first();

        $scriptParams = ['newDocId' => $newDocId];
        $scriptParams['dictionary'] = $params;

        foreach ($template->section_templates as $key => $sectionTemplate) {
            $scriptParams['sections'][$sectionTemplate['name']] = $sectionTemplate['google_doc_id'];
        }

        self::runScript('insertParagraph', $scriptParams);
        self::runScript('replaceAll', $scriptParams);
        self::runScript('appendStyle', $scriptParams);
    }

    /**
     * @param string $scriptName
     * @param string|array $parameters
     */
    public function runScript($scriptName, $parameters)
    {
        $request = new Google_Service_Script_ExecutionRequest();
        $request->setFunction($scriptName);
        $request->setParameters([$parameters]);
        $request->setDevMode(true);

        try {
            // call google script
            $response = $this->service->scripts->run($this->projId, $request);

            if ($response->getError()) {
                // The API executed, but the script returned an error.

                // Extract the first (and only) set of error details. The values of this
                // object are the script's 'errorMessage' and 'errorType', and an array of
                // stack trace elements.
                $error = $response->getError()['details'][0];
                printf("Script error message: %s\n", $error['errorMessage']);

                if (array_key_exists('scriptStackTraceElements', $error)) {
                    // There may not be a stacktrace if the script didn't start executing.
                    print "Script error stacktrace:\n";
                    foreach ($error['scriptStackTraceElements'] as $trace) {
                        printf("\t%s: %d\n", $trace['function'], $trace['lineNumber']);
                    }
                }
            }
        } catch (Exception $e) {
            // The API encountered a problem before the script started executing.
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }
}

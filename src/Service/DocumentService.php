<?php

namespace App\Service;

use Google_Exception;

/**
 * Class GoogleService
 * @package App\Service
 */
class DocumentService
{
    /**
     * @param array $data
     * @return array
     * @throws Google_Exception
     */
    public static function getProcessedData($entity, $data)
    {
        $gdk = include (ROOT .'/persistent/google_document_keys.php');
        $parameters = [];

        foreach ($gdk as $field => $value) {

            $parameters[$field] = $gdk[$field]($entity, $field);

        }

        $docId = (new GoogleService())->getNewDoc($data['template'], $parameters, $data['matter_id']);

        if (!is_null($docId)) {
            return ['matter_id' => $data['matter_id'], 'link' => "https://docs.google.com/document/d/{$docId}"];
        }

        return [];
    }
}

<?php

namespace App\Repository;

use App\Lib\ArrayHelper;
use Cake\Database\Expression\QueryExpression;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class DocketServiceRepository {


    /**
     * @param $docketID
     * @return array docketEntries
     */

    public function getDocketData($docketID)
    {
        $docketEntriesTable = TableRegistry::getTableLocator()->get('Dockets');
        $docket = $docketEntriesTable->findById($docketID)->contain(['DocketAttachments', 'DocketEntries']);
        return $docket->first();
    }

    /**
     * @param $courtId
     * @return mixed
     */
    public function getCourtData($courtId)
    {
        $docketCourtTable = TableRegistry::getTableLocator()->get('Courts');
        $court = $docketCourtTable->findById($courtId);
        return $court->first();
    }


    public function getDocketEntry($params)
    {
        $docketEntriesTable = TableRegistry::getTableLocator()->get('DocketEntries');

        return $docketEntriesTable->find()->where(function (QueryExpression $exp, Query $q) use ($params) {
            return $exp
                ->eq('docket_id', $params['docketID'])
                ->eq('sequence_id', $params['sequenceID']);

        });
    }


    public function getDocketAttachment($params)
    {
        $docketAttachmentTable = TableRegistry::getTableLocator()->get('DocketAttachments');

        return $docketAttachment = $docketAttachmentTable->find()->where(function (QueryExpression $exp, Query $q) use ($params) {
            return $exp
                ->eq('docket_id', $params['docketID'])
                ->eq('sequence_id', $params['sequenceID'])
                ->eq('attachment_id', $params['attachmentID']);

        });
    }


    public function checkMatterCourt($docket)
    {
        $result            = null;
        $matterCourtsTable = TableRegistry::getTableLocator()->get('MatterCourts');
        $matterCourt       = $matterCourtsTable->query()
                ->where(function (QueryExpression $exp, Query $q) use ($docket) {
                    return $exp
                        ->like('case_number', '%' . $docket['case_number'] . '%')
                        ->eq('court_id', $docket['court_id']);
                })->first();

        if (isset($matterCourt)) {

            $matterCourt->case_number = $docket['case_number'] . $docket['fed_case_number_judges'];
            $result                   = $matterCourtsTable->save($matterCourt);

        }

        return $result;
    }


    public function insertDocketRow($messageData)
    {
        $pre              = preg_split('/(-)/', $messageData['caseNumber'], 0, PREG_SPLIT_DELIM_CAPTURE);
        $newCaseNumber    = ArrayHelper::filterArray($pre, '<', 5);
        $caseNumberJudges = ArrayHelper::filterArray($pre, '>=', 5);

        $courtTable   = TableRegistry::getTableLocator()->get('Courts');
        $court        = $courtTable->query()->where(['fed_abbr' => $messageData['fedAbbr']])->first();

        $docketsTable = TableRegistry::getTableLocator()->get('Dockets');
        $docket       = $docketsTable->query()->where(function (QueryExpression $exp, Query $q) use ($newCaseNumber, $court) {
            return $exp
                ->like('case_number', '%' . $newCaseNumber . '%')
                ->eq('court_id', $court->id);
        });


        if ($docket->isEmpty()) {

            $docketNewEntity = $docketsTable->newEntity();

            $docketNewEntity->case_name              = $messageData['caseName'];
            $docketNewEntity->case_number            = $newCaseNumber;
            $docketNewEntity->fed_case_number_judges = $caseNumberJudges;
            $docketNewEntity->court_fed_abbr         = $messageData['fedAbbr'];
            $docketNewEntity->court_id               = $court->id;

            $docket = $docketsTable->save($docketNewEntity);

        } else {

            $docketEntity = $docket->first();

            $docketEntity->case_name        = $messageData['caseName'];
            //$docket->case_number = $newCaseNumber;
            $docket->fed_case_number_judges = $caseNumberJudges;
            $docketEntity->court_fed_abbr   = $messageData['fedAbbr'];
            //$docketEntity->court_id = $court->id;

            $docket = $docketsTable->save($docketEntity);

        }

        $matterCourt = $this->checkMatterCourt($docket);

        if (is_null($docket->matter_id) && !is_null($matterCourt)) {
            $docket->matter_id = $matterCourt->matter_id;
            $docket            = $docketsTable->save($docket);
        }

        return $docket;

    }


    public function insertDocketEntry($data, $docket)
    {
        $docketEntriesTable = TableRegistry::getTableLocator()->get('DocketEntries');

        $docketEntry = $docketEntriesTable->query()->where(function (QueryExpression $exp, Query $q) use ($docket, $data) {
            return $exp
                ->eq('docket_id', $docket->id)
                ->eq('sequence_id', $data['sequenceID']);
        });

        $docUrlData    = (!empty($data['docUrlData'])) ? json_encode($data['docUrlData']) : null;
        $hasAttachment = (!is_null($docUrlData)) ? 1 : null;

        if ($docketEntry->isEmpty()) {

            $docketEntry              = $docketEntriesTable->newEntity();
            $docketEntry->docket_id   = $docket->id;
            $docketEntry->sequence_id = $data['sequenceID'];

        } else {
            $docketEntry = $docketEntry->first();
        }

        $docketEntry->date_filed              = Time::parse($data['dateFiled']);
        $docketEntry->date_entered            = Time::parse($data['dateEntered']);
        $docketEntry->description             = $data['description'];
        $docketEntry->restricted              = $data['restricted'];
        $docketEntry->sealed                  = $data['sealed'];
        $docketEntry->attachment_download_url = $docUrlData;
        $docketEntry->has_attachment          = $hasAttachment;

        return $docketEntriesTable->save($docketEntry);

    }


    public function insertDocketAttachments($docket, $sequenceID, $attachmentData)
    {
        $result = [];

        $docketAttachmentsTable = TableRegistry::getTableLocator()->get('DocketAttachments');

        if (!is_null($attachmentData)) {

            foreach ($attachmentData['attachments'] as $attachment) {

                $docketAttachment = $docketAttachmentsTable->query()->where(function (QueryExpression $exp, Query $q) use ($docket, $sequenceID, $attachment) {
                    return $exp
                        ->eq('docket_id', $docket->id)
                        ->eq('sequence_id', $sequenceID)
                        ->eq('attachment_id', $attachment['attachmentID']);
                });

                $attachmentObject = $docketAttachment->first();

                if (!empty($attachmentObject)) {
                    $attachmentObject->downloaded   = $attachment['downloaded'];
                    $attachmentObject->download_url = $attachment['docUrl'];
                    $attachmentObject->restricted   = $attachment['restricted'];
                    $attachmentObject->sealed       = $attachment['sealed'];

                    $result[] = $docketAttachmentsTable->save($attachmentObject);

                } else {
                    $docketAttachment = $docketAttachmentsTable->newEntity();

                    $docketAttachment->docket_id     = $docket->id;
                    $docketAttachment->sequence_id   = $sequenceID;
                    $docketAttachment->downloaded    = $attachment['downloaded'];
                    $docketAttachment->attachment_id = $attachment['attachmentID'];
                    $docketAttachment->download_url  = $attachment['docUrl'];
                    $docketAttachment->restricted    = $attachment['restricted'];
                    $docketAttachment->sealed        = $attachment['sealed'];

                    $result[] = $docketAttachmentsTable->save($docketAttachment);
                }
            }

        }
        return $result;
    }


    public function insertDocketParties($docket, $attorneyInfo)
    {
        $result = [];

        $docketPartiesTable = TableRegistry::getTableLocator()->get('DocketParties');

        $docketParties      = $docketPartiesTable->find()->where(function (QueryExpression $exp, Query $q) use ($docket) {
            return $exp
                ->eq('docket_id', $docket->id)
                ->eq('type', 'attorney');

        });

        if ($docketParties->isEmpty()) {

            foreach ($attorneyInfo as $attorney) {
                $docketPartyNew = $docketPartiesTable->newEntity();

                $docketPartyNew->docket_id = $docket->id;
                $docketPartyNew->type      = 'attorney';
                $docketPartyNew->name      = $attorney['name'];
                $docketPartyNew->email     = $attorney['email'];
                $docketPartyNew->active    = 1;

                $result[] = $docketPartiesTable->save($docketPartyNew);
            }
        } else {

            $emails = [];
            $names  = [];

            foreach ($attorneyInfo as $attorney) {
                $emails[] = $attorney['email'];
                $names[]  = $attorney['name'];
            }

            $subquery = $docketParties->toArray();
            $dbemails = [];

            foreach ($subquery as $q) {
                $dbemails[] = $q->email;
            }

            $e = array_diff($emails, $dbemails);

            foreach ($e as $key => $v) {
                $docketParty = $docketPartiesTable->newEntity();

                $docketParty->docket_id = $docket->id;
                $docketParty->type      = 'attorney';
                $docketParty->name      = $names[$key];
                $docketParty->email     = $emails[$key];
                $docketParty->active    = 1;

                $result[] = $docketPartiesTable->save($docketParty);
            }

            $partiesNotIn = $docketParties->where(function (QueryExpression $exp) use ($emails, $names) {
                return $exp
                    ->notIn('email', $emails)
                    ->notIn('name', $names);
            });

            foreach ($partiesNotIn->all() as $partyNotIn) {

                $partyNotIn->active = 0;

                $result[] = $docketPartiesTable->save($partyNotIn);
            }

        }

        return $result;
    }


    public function updateAttachments($params)
    {
        $docketAttachmentTable = TableRegistry::getTableLocator()->get('DocketAttachments');

        return $docketAttachmentTable
            ->query()
            ->update()
            ->set(['downloaded' => $params['downloaded']])
            ->where(function (QueryExpression $exp, Query $q) use ($params) {
                return $exp
                    ->eq('docket_id', $params['docketID'])
                    ->eq('sequence_id', $params['sequenceID'])
                    ->eq('attachment_id', $params['attachmentID']);

            })
            ->execute();
    }

}

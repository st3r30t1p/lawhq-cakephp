<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\ORM\TableRegistry;

class DomainRelationshipsCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
    	$io->out('Starting');

        $urlDetailsTable = TableRegistry::getTableLocator()->get('UrlDetails');
        $domainsTable = TableRegistry::getTableLocator()->get('Domains');

        $domains = $domainsTable->find();

        foreach ($domains as $domain) {
            $domainParts = (substr_count($domain->domain, ".") == 1) ? explode('.', $domain->domain) : false;

            $url_ids = $urlDetailsTable->find()
            ->select(['url_id'])
            ->distinct(['url_id']);

            if ($domainParts) {
                $url_ids->where(['domain' => $domainParts[0], 'tld' => $domainParts[1]]);
            } else {
                $url_ids->where(["domain" => $domain->domain]);
            }

            $relationships = $urlDetailsTable->find()
            ->join([
                'table' => 'domains',
                'alias' => 'domains',
                'type' => 'INNER',
                'conditions' => 'domains.domain = CONCAT(UrlDetails.`domain`, ".", UrlDetails.`tld`)',
            ]);

            $relationships->select(['domain', 'tld', 'count' => $relationships->func()->count('DISTINCT(url_id)'), 'domains.id'])
            ->where(['url_id IN' => $url_ids])
            ->group(['UrlDetails.`domain`', 'UrlDetails.`tld`']);

            $this->saveRelationships($relationships, $domain->id);
        }

        $io->out('Finished');
    }

    public function saveRelationships($relationships, $domainId)
    {
    	$domainRelationshipsTable = TableRegistry::getTableLocator()->get('DomainRelationships');

        foreach ($relationships as $relationship) {
            if ($domainId == $relationship->domains['id']) {
                continue;
            }

            $primaryId = ($relationship->domains['id'] < $domainId) ? $relationship->domains['id'] : $domainId;
            $childId = ($primaryId != $domainId) ? $domainId : $relationship->domains['id'];

            $saveRelationship = $domainRelationshipsTable->find()
            ->where(['domain_id' => $primaryId, 'domain_id_link' => $childId])->first();

            if (!$saveRelationship) {
                $saveRelationship = $domainRelationshipsTable->newEntity();
                $saveRelationship->domain_id = $primaryId;
                $saveRelationship->domain_id_link = $childId;
            }

            $saveRelationship->count = $relationship->count;
            $domainRelationshipsTable->save($saveRelationship);
        }

    }
}
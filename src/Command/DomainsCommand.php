<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\ORM\TableRegistry;

class DomainsCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
    	$io->out('Starting');

    	$urlDetailsTable = TableRegistry::getTableLocator()->get('UrlDetails');

    	$domains = $urlDetailsTable->find();
    	$domains->select([
    		'domain',
    		'tld',
    		'count' => $domains->func()->count('DISTINCT mu.`imported_message_id`')
    	])
        ->join([
            'table' => 'message_urls',
            'alias' => 'mu',
            'type' => 'INNER',
            'conditions' => 'mu.`url_id` = UrlDetails.`url_id`',
        ])
    	->group(['domain'])
    	->order(['domain' => 'ASC']);

    	foreach ($domains as $domain) {
    		$this->saveDomain($domain);
    	}

        $io->out('Finished');
    }

    public function saveDomain($entity)
    {
        $domainsTable = TableRegistry::getTableLocator()->get('Domains');

        $domain = $domainsTable->find()
        ->where(['domain' => $entity->domain .'.'. $entity->tld])->first();

    	if (!$domain) {
    		$domain = $domainsTable->newEntity();
    		$domain->domain = $entity->domain . '.' . $entity->tld;
    	}

    	$domain->message_frequency = $entity->count;

    	$domainsTable->save($domain);
    }
}
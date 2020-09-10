<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class DomainRelationshipsController extends AppController
{
	public function messages($id)
	{
		$urlDetailsTable = TableRegistry::getTableLocator()->get('UrlDetails');
		$messageUrlsTable = TableRegistry::getTableLocator()->get('MessageUrls');

		$relationship = $this->DomainRelationships->find()
		->contain(['Domains', 'DomainsLink'])
		->where(['DomainRelationships.id' => $id])->first();

		$domainOneUrlIds = $urlDetailsTable->getUrlIdsByDomain($relationship->domain->domain);
		$domainTwoUrlIds = $urlDetailsTable->getUrlIdsByDomain($relationship->domains_link->domain);
		$matches = array_intersect($domainOneUrlIds, $domainTwoUrlIds);


		$filtered = [];
		foreach ($matches as $match) {
			array_push($filtered, $match->url_id);
		}
		$filtered = '("' . implode('", "', $filtered) . '")';

		$messages = $messageUrlsTable->find()
		->contain(['ImportedMessages'])
		->where(["url_id IN {$filtered}"]);

		$this->set(compact('relationship', 'messages'));
	}
}
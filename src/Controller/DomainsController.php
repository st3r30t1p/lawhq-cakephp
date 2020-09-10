<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Lib\DomainInfo;
use Cake\I18n\Time;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;

class DomainsController extends AppController {

	public function index() {
		$limit = 250;
		$domain = null;
		$sort = ['sort' => 'message_frequency', 'direction' => 'DESC'];
		$this->paginate = [
			'limit' => $limit,
			'maxLimit' => $limit
		];
		if ($this->request->getData()) {
			$domain = $this->request->getData('domain');
			$limit = $this->request->getData('limit');
			$sort = json_decode($this->request->getData('sort'), true);
		}

		$domains = $this->Domains->find();
		if (isset($domain)) {
			$domains->where(['domain LIKE' => "%{$domain}%"]);
		}
		$domains->order([$sort['sort'] => $sort['direction']]);
		if ($limit != 'all') {
			$domains->limit($limit);
			$domains = $this->paginate($domains);
		}

		$this->set(compact('sort', 'limit', 'domain', 'domains'));
	}

	public function view() {
		$domain = $this->request->getParam('id');
		$this->set('domain', $domain);

		$domainDetailsTable = TableRegistry::getTableLocator()->get('DomainDetails');
		if ($this->request->getQuery('refresh')) {
			$domainInfo = new DomainInfo();
			$domainiq = $domainInfo->domainiq($domain);
			$dd = $domainDetailsTable->newEntity();
			$dd->domain = $domain;
			$dd->info_type = 'domainiq';
			$dd->info = $domainiq;
			$dd->created = Time::now();
			$domainDetailsTable->save($dd);
		}

		$domainDetails = $domainDetailsTable->findByDomain($domain);
		$this->set('domainDetails', $domainDetails);
	}

	public function edit($domain)
	{	
		$domain = $this->Domains->findByDomain($domain)->first();
		if (!$domain) {
			$this->redirect(['action' => 'index']);
		}
		$ignore = (isset($this->request->query()['ignore']) && !empty($this->request->query('ignore')) || $domain->ignore_on_system_generated_rules) ? true : false;

		if ($this->request->getData()) {
			$domain->set($this->request->getData());
			if ($this->Domains->save($domain)) {
				$this->Domains->SystemGeneratedRules->ignoreByDomainId($domain->id);
				if ($ignore) {
					$this->redirect(['controller' => 'Rules', 'action' => 'approve']);
				} else {
					$this->redirect(['controller' => 'Domains', 'action' => 'index']);
				}
			}
		}

		$this->set(compact('domain', 'ignore'));
	}

	public function search() {
		$urlDetailsTable = TableRegistry::getTableLocator()->get('DomainDetails');

		$query = $urlDetailsTable->find()
	    ->where(function (QueryExpression $exp, Query $q) {
	        $jsonExtract = $q->func()->json_extract([
	            'info' => 'identifier', '$.data.' . $this->request->getQuery('jspath')
	        ]);
	        $jsonContains = $q->func()->json_contains([
	        	$jsonExtract, $this->request->getQuery('val')
	        ]);
	        return $exp->eq($jsonContains, true);
	    });

	    $domains = [];
		foreach ($query as $domainDetails) {
			$domains[] = $domainDetails->domain;
		}
		$domains = array_unique($domains);
		$this->set('domains', $domains);
		$this->set('key', $this->request->getQuery('jspath'));
		$this->set('value', $this->request->getQuery('val'));
	}

	public function relationships()
	{
		$domainsTable = $this->Domains;
		$domain = $this->request->getParam('id');
		$domain = $this->Domains->findByDomain($domain)->first();

		if (!$domain) { return; }

		$domainRelationshipsTable = TableRegistry::getTableLocator()->get('domainRelationships');

		$domainRelationships = $domainRelationshipsTable->find()
		->where([
			'OR' => [['domain_id' => $domain->id], ['domain_id_link' => $domain->id]]
		])
		->order('count DESC');

		$this->set(compact('domain', 'domainRelationships', 'domainsTable'));
	}
}
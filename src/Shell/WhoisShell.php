<?php
namespace App\Shell;

use App\Lib\DomainInfo;
use App\Lib\GoogleFirebase;
use App\Lib\UrlParts;
use Cake\Console\Shell;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
//use Google\Cloud\Firestore\FirestoreClient;

class WhoisShell extends Shell
{
    public function main()
    {
    	$this->whois();
    }

    public function whois() {
        $domainsTable = TableRegistry::getTableLocator()->get('Domains');
    	$domainDetailsTable = TableRegistry::getTableLocator()->get('DomainDetails');

        $checkedDomains = [];
    	$requests = 0;
    	$domains = $domainsTable->find()->order('id');
    	foreach ($domains as $domain) {

            // check if we've already queried, or tried to query this domain
            if (isset($checkedDomains[$domain->domain])) {
                continue;
            }
            $checkedDomains[$domain->domain] = 1;

            // check if there is already a successful whois result for this domain
    		$count = $domainDetailsTable->find()->where(['domain' => $domain->domain, 'info_type' => 'domainiq'])->count();
    		if ($count > 0) {
                $this->err("{$domain->domain} already queried");
                continue;
            }

    		$requests++;
    		$domainTools = new DomainInfo();
    		$whois = $domainTools->domainiq($domain->domain);
            if (!$whois) {
                $this->err("{$domain->domain} failed");
                continue;
            }

            $dd = $domainDetailsTable->newEntity();
            $dd->domain_id = $domain->id;
            $dd->domain = $domain->domain;
            $dd->info_type = 'domainiq';
            $dd->info = $whois;
            $dd->created = Time::now();
            $domainDetailsTable->save($dd);
            $this->out("{$domain->domain} success");
    	}

    	$this->out("\tRequests: {$requests}");
    }
}
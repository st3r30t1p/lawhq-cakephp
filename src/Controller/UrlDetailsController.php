<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Lib\DomainInfo;
use App\Lib\UrlParts;
use Cake\Cache\Cache;
use Cake\I18n\Time;

class UrlDetailsController extends AppController {

	public function view() {
		$urlsTable = TableRegistry::getTableLocator()->get('Urls');

		$urlParts = UrlParts::parse($this->request->getQuery('url'));
		$urlArray = $urlParts->toArray();
		$urlArray['port IS'] = $urlArray['port'];
		unset($urlArray['port']);
		$url = $urlsTable->find()->where($urlArray)->first();

		if ($this->request->getQuery('refresh')) {
			$domainInfo = new DomainInfo();
			$domainInfo->crawl($url);
		}

		$urlParts = UrlParts::parse($this->request->getQuery('url'));
		$urlDetailsTable = TableRegistry::getTableLocator()->get('UrlDetails');
		$urlDetails = $urlDetailsTable->find()
		->where(['url' => $urlParts->buildUrl()]);

		$sessions = [];
		foreach ($urlDetails as $urlDetail) {
			$sessions[] = $urlDetail->session;
		}

		if (count($sessions) == 0) {
			$urlDetails = [];
		} else {
			$urlDetails = $urlDetailsTable->find()
			->where(['session IN' => $sessions])
			->order(['session', 'req_num'])
			->contain(['Files']);
		}

		$sessions = [];
		foreach ($urlDetails as $urlDetail) {
			if (!isset($sessions[$urlDetail->session])) {
				$sessions[$urlDetail->session] = [];
			}
			$sessions[$urlDetail->session][] = $urlDetail;
		}

		$this->set('sessions', $sessions);
	}

	private function getUrlDetail($urlDetail) {
	    $domainInfo = new DomainInfo();
	    $urlDetails = [];

	    // follow up to 10 redirects
	    for ($i = 0; $i < 10; $i++) {
	        if ($urlDetail['domain'] == 'goo' && $urlDetail['tld'] == 'gl') $urlDetail['https'] = true;

	        $res = $domainInfo->httpGet($urlDetail, 'mobile');
	        $urlDetail += $res;
	        $redirect = $domainInfo->isRedirect($urlDetail);
	        if ($redirect['isRedirect']) {
	            $urlDetail['last'] = false;
	            $urlDetails[] = $urlDetail;
	            try {
	                $urlDetail = $domainInfo->urlParts($redirect['redirects'][0]['url']);
	            } catch (\Exception $e) {
	                $urlDetail['last'] = true;
	                break;
	            }
	        } else {
	            $urlDetail['last'] = true;
	            $urlDetails[] = $urlDetail;
	            break;
	        }
	    }

	    $urlDetailsTable = TableRegistry::getTableLocator()->get('UrlDetails');
	    $sessionId = uniqid('', true);
	    foreach ($urlDetails as $index => $urlDetail) {
	        $urlDetail['session'] = $sessionId;
	        $urlDetail['req_num'] = $index+1;
	        $urlDetail['created'] = Time::now();
	        $urlDetailsTable->save($urlDetailsTable->newEntity($urlDetail));
	    }

	    return $sessionId;
	}
}
<?php
namespace App\Lib;

use Cake\Cache\Cache;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class DomainInfo {
	private $domainIqKey = 'ekb3z1z6a68z9hq8b87gu5rfw3qtc42m';
	private $result;
	private $hasIp;

	public function query($url, $referer = null) {
		$this->result = [];
		$this->hasIp = true;

		$domain = parse_url($url, PHP_URL_HOST);
		if ($domain === false || $domain === null) {
			throw new \Exception('Invalid url: ' . $url);
		}

		$this->dns($domain);
		$this->domainiq($domain);

		$result = $this->result;
		$this->result = null;
		return $result;
	}

	public function dns($domain) {
		$dnsA = dns_get_record($domain, DNS_A);
		if (count($dnsA) === 0) {
			$this->hasIp = false;
			$this->result[] = [
				'domain' => $domain,
				'info_type' => 'dns_a',
				'info' => 'domain_has_no_ip_addresses'
			];
		}
		foreach ($dnsA as $aRecord) {
			$this->result[] = [
				'domain' => $domain,
				'info_type' => 'dns_a',
				'info' => $aRecord['ip']
			];
		}
	}

	public function domainiq($domain) {
		$cacheKey = "domainiq_{$domain}";
		if (($whois = Cache::read($cacheKey)) === false) {
			$whois = file_get_contents("https://www.domainiq.com/api?key={$this->domainIqKey}&service=domain_report&output_mode=json&domain={$domain}");
		    Cache::write($cacheKey, $whois);
		}
		return json_decode($whois, true);
	}

	public function crawl($url) {
		$urlParts = UrlParts::fromArray($url->toArray());
		$urlStr = $urlParts->buildUrl();

		$contents = false;
		$crawlers = \Cake\Core\Configure::read('LawHQ.crawler');
		foreach ($crawlers as $crawler) {
			$contents = file_get_contents("http://{$crawler}/?url=" . urlencode($urlStr));
			if ($contents !== false) {
				$contents = json_decode($contents, true);
				break;
			}
		}

		if (!$contents) return false;

		$urlDetailsTable = TableRegistry::getTableLocator()->get('UrlDetails');
		$filesTable = TableRegistry::getTableLocator()->get('Files');
		$sessionId = uniqid('', true);

		foreach ($contents['reqs'] as $index => $req) {
			$urlDetail = UrlParts::parse($req['url'])->toArray();
		    $urlDetail['session'] = $sessionId;
		    $urlDetail['url_id'] = $url->id;
		    $urlDetail['req_num'] = $index+1;
			$urlDetail['url'] = $req['url'];
			$urlDetail['res_code'] = $req['code'];
			$urlDetail['res_error'] = $req['error'];
			$urlDetail['headers'] = implode("\n", $req['headers']);
			$urlDetail['body'] = $req['body'];
			$urlDetail['last'] = false;
		    $urlDetail['created'] = Time::now();
		    if ($index == (count($contents['reqs'])-1)) {
				$urlDetail['last'] = true;

				if (!empty($contents['screenshot'])) {
					$contents['screenshot'] = base64_decode($contents['screenshot']);
					$sha1 = sha1($contents['screenshot']);
					$filename = ROOT . DS . '..' . DS . 'uploaded_files' . DS . $sha1;
					if (!file_exists($filename) || sha1_file($filename) != $sha1) {
						file_put_contents($filename, $contents['screenshot']);
					}
					$file = $filesTable->newEntity();
					$file->filename = "screnshot_url_{$url->id}.png";
					$file->size = filesize($filename);
					$file->sha1 = $sha1;
					$file->created = Time::now();;
					$filesTable->save($file);
					$urlDetail['file_id'] = $file->id;
				}
		    }
		    $urlDetailsTable->save($urlDetailsTable->newEntity($urlDetail));
		}

		return $contents;
	}
}

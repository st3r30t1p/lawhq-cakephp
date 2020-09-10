<?php
namespace App\Lib;

class UrlParts {
	public $protocol = null;
	public $subdomain = null;
	public $domain = null;
	public $tld = null;
	public $path = null;
	public $port;

	public function buildUrl(): string {
		$url = $this->protocol;
		$url .= '://';
		if ($this->subdomain) $url .= $this->subdomain . '.';
		$url .= $this->domain;
		if ($this->tld) $url .= '.' . $this->tld;
		if ($this->port) $url .= ':' . $this->port;
		$url .= $this->path;
		return $url;
	}

	public function normalize() {
		$protocols = ['http', 'https'];
		if ($this->protocol) strtolower($this->protocol);
		if (!in_array($this->protocol, $protocols)) $this->protocol = 'http';
		$this->subdomain = strtolower($this->subdomain);
		$this->domain = strtolower($this->domain);
		$this->tld = strtolower($this->tld);
		if (!$this->path) $this->path = '/';
		$this->port = intval($this->port);
		if ($this->port === 0) $this->port = null;
	}

	public function toArray(): array {
		return [
			'protocol' => $this->protocol,
			'subdomain' => $this->subdomain,
			'domain' => $this->domain,
			'tld' => $this->tld,
			'path' => $this->path,
			'port' => $this->port
		];
	}

	public function isRelative() {
		if (empty($this->domain)) return true;
		return false;
	}

	public function applyHost(self $rhs) {
		$this->protocol = $rhs->protocol;
		$this->subdomain = $rhs->subdomain;
		$this->domain = $rhs->domain;
		$this->tld = $rhs->tld;
		$this->port = $rhs->port;

		// is it a relative path?
		if ($this->path[0] == '.') {
			throw new \Exception('Relative path');
		} else if ($this->path[0] != '/') {
			$lastPathSep = strrpos($rhs->path, '/');
			if ($lastPathSep !== false) {
				$folder = substr($rhs->path, 0, $lastPathSep);
				$this->path = "$folder{$this->path}";
				debug($folder);
				debug($this->path);
				exit();
			}
		}
	}

	static public function fromArray(array $array): self {
		$urlParts = new UrlParts();
		$urlParts->protocol = $array['protocol'];
		$urlParts->subdomain = $array['subdomain'];
		$urlParts->domain = $array['domain'];
		$urlParts->tld = $array['tld'];
		$urlParts->path = $array['path'];
		$urlParts->port = $array['port'];
		return $urlParts;
	}

	static public function parse(string $url): self {
		$urlParts = new UrlParts();

		$parsed = parse_url($url);

		// check if it only has a path, it is likely a domain
		// e.g. fnd.to is not a relative path
		if (count($parsed) == 1 && isset($parsed['path']) && $parsed['path'][0] != '/') {
			$parsed = parse_url("http://{$url}");
		}

		if (array_key_exists('scheme', $parsed)) {
			$urlParts->protocol = $parsed['scheme'];
		}

		if (array_key_exists('host', $parsed)) {
			// is it an ip address?
			if (preg_match('/\d{1,3}+\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $parsed['host'])) {
				$urlParts->domain = $parsed['host'];
			} else {
				$domainParts = explode('.', $parsed['host']);
				// is there no tld?
				if (count($domainParts) == 1) {
					$urlParts->domain = $parsed['host'];
				} else {
					$urlParts->tld = array_pop($domainParts);
					$urlParts->domain = array_pop($domainParts);
					$urlParts->subdomain = implode('.', $domainParts);
				}
			}
		}

		if (array_key_exists('port', $parsed)) {
			$urlParts->port = $parsed['port'];
		}

		if (array_key_exists('user', $parsed) || array_key_exists('pass', $parsed)) {
			throw new \Exception("User/pass part of url: {$url}");
		}

		$path = '';
		if (array_key_exists('path', $parsed)) {
			$path .= $parsed['path'];
		} else {
			$path = '/';
		}
		if (array_key_exists('query', $parsed)) {
			$path .= '?' . $parsed['query'];
		}
		if (array_key_exists('fragment', $parsed)) {
			$path .= '#' . $parsed['fragment'];
		}
		$urlParts->path = $path;

		$urlParts->normalize();
		return $urlParts;
	}

	static public function extract(string $msg): array {
		$urls = [];
		if (preg_match_all('#\b((?<protocol>\w+)://)?((?<subdomain>[\w\.\-]+)\.)?(?<domain>[\w\-]+)\.(?<tld>[a-z]+)(:(?<port>\d+))?(?<path>/[\w\?\/\=\-]*)?#', $msg, $match) > 0) {
		    // debug($match);
		    foreach ($match[0] as $i => $url) {
		    	$full = $match[0][$i];
		    	if (isset($urls[$full])) continue;
		    	$url = new UrlParts();
	    		$url->protocol = $match['protocol'][$i];
		        $url->subdomain = $match['subdomain'][$i];
		        $url->domain = $match['domain'][$i];
		        $url->port = $match['port'][$i];
		        $url->tld = $match['tld'][$i];
		        $url->path = $match['path'][$i];
		        $url->normalize();
		    	$urls[$full] = $url;
		    }
		}
		return $urls;
	}
}
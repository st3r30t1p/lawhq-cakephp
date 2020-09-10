<?php
namespace App\Lib;

use Google\Google_Client;

class GoogleFirebase {

	private static $httpClient;
	private $project = "spamhappy-45e15";

	public function __construct() {
		if (self::$httpClient === null) {
			$client = new \Google_Client();
			$client->setAuthConfig(ROOT . DS . '..' . DS . 'spamhappy-45e15-7e5a44ac4032.json');
			$client->addScope('https://www.googleapis.com/auth/userinfo.email');
			$client->addScope('https://www.googleapis.com/auth/firebase.database');
			$client->addScope('https://www.googleapis.com/auth/datastore');
			self::$httpClient = $client->authorize();
		}
	}

	public function list($path, $options = [], $absolute = false) {
		if (!$absolute) {
			$path = "projects/{$this->project}/databases/(default)/documents/{$path}";
		}
		$requestUrl = "https://firestore.googleapis.com/v1/{$path}";
		$request = ['query' => [
			//'orderBy' => 'modified',
			'pageSize' => 250,
			'pageToken' => $options['pageToken'] ?? null
		]];
		$response = self::$httpClient->request('GET', $requestUrl, $request, ['verify' => false]);
		// debug($response);
		// debug($response->getStatusCode());
		// debug($response->getHeaders());
		// debug($response->getBody()->__toString());

		// assert($response->getStatusCode() == 200);
		$json = json_decode($response->getBody()->__toString(), true);
		return new FirebaseDocumentList($path, $json);
	}

	public function collection($path) {
		$requestUrl = "https://firestore.googleapis.com/v1/projects/{$this->project}/databases/(default)/documents/{$path}:listCollectionIds";

		$collectionIds = [];
		$nextPageToken = null;
		$request = ['pageSize' => 250];
		do {
			$nextPage = false;
			// debug($requestUrl);
			$response = self::$httpClient->request('POST', $requestUrl, ['body' => json_encode($request)], ['verify' => false]);
			// debug($response->getStatusCode());
			// debug($response->getHeaders());
			// debug($response->getBody()->__toString());

			$json = json_decode($response->getBody()->__toString(), true);
			if (array_key_exists('collectionIds', $json)) {
				$collectionIds = array_merge($collectionIds, $json['collectionIds']);
			}

			if (array_key_exists('nextPageToken', $json)) {
				$request['pageToken'] = $json['nextPageToken'];
				$nextPage = true;
			}
		} while ($nextPage);

		return $collectionIds;
	}

}

class FirebaseDocumentList implements \Iterator {
	private $path;
	private $documents;
	private $nextPageToken;
	private $count;
	private $current;

	public function __construct($path, $json) {
		$this->path = $path;
		$this->nextPageToken = $json['nextPageToken'] ?? null;
		$this->documents = [];
		if (array_key_exists('documents', $json)) {
			foreach ($json['documents'] as $doc) {
				$this->documents[] = new FirebaseDocument($doc);
			}
		}
	}

	public function rewind() {
		$this->count = 0;
		$this->current = array_shift($this->documents);
	}

	public function current() {
		return $this->current;
	}

	public function key() {
		return $this->current->key();
	}

	public function next() {
		$this->count++;
		$this->current = array_shift($this->documents);
		if ($this->current === null) {
			$this->loadNextPage();
		}
		return $this->current;
	}

	public function valid() {
		return $this->current !== null;
	}

	private function loadNextPage() {
		if ($this->nextPageToken === null) return;
		$firebase = new GoogleFirebase();
		$docList = $firebase->list($this->path, ['pageToken' => $this->nextPageToken], true);
		$this->documents = $docList->documents;
		$this->nextPageToken = $docList->nextPageToken;
		$this->current = array_shift($this->documents);
	}
}

class FirebaseDocument {
	private $name;
	private $fields = [];
	public $createTime;
	public $updateTime;

	public function __construct($data) {
		$this->name = $data['name'];
		$this->fields = $this->mapMap($data['fields']);
		$this->createTime = $data['createTime'];
		$this->updateTime = $data['updateTime'];
	}

	public function key() {
		// return last part of name/path
		$parts = explode('/', $this->name);
		return array_pop($parts);
	}

	public function get($key, $default = null) {
		return $this->fields[$key] ?? $default;
	}

	public function list($subpath) {
		$firebase = new GoogleFirebase();
		$path = str_replace('+', '%2B', "{$this->name}/{$subpath}");
		return $firebase->list($path, [], true);
	}

	private function mapValue($value) {
		// https://firebase.google.com/docs/firestore/reference/rest/v1/Value
		if (array_key_exists('nullValue', $value)) {
			// type: null - no conversion
			return $value['nullValue'];
		} else if (array_key_exists('booleanValue', $value)) {
			// type: boolean - no conversion
			return $value['booleanValue'];
		} else if (array_key_exists('integerValue', $value)) {
			// type: string - convert to int
			return intval($value['integerValue']);
		} else if (array_key_exists('doubleValue', $value)) {
			// type: number - no conversion
			return $value['doubleValue'];
		} else if (array_key_exists('timestampValue', $value)) {
			// type: A timestamp in RFC3339 UTC "Zulu" format
			return new DateTime($value['timestampValue']);
		} else if (array_key_exists('stringValue', $value)) {
			// type: string - no conversio
			return $value['stringValue'];
		} else if (array_key_exists('bytesValue', $value)) {
			// type: string - no conversio
			return $value['bytesValue'];
		} else if (array_key_exists('referenceValue', $value)) {
			// type: path to document
			throw new \Exception('referenceValue may need additional processing: ' . $value['referenceValue']);
			return $value['referenceValue'];
		} else if (array_key_exists('geoPointValue', $value)) {
			// type: object(LatLng)
			return $value['geoPointValue'];
		} else if (array_key_exists('arrayValue', $value)) {
			// type: object(ArrayValue)
			return $this->mapArray($value['arrayValue']);
		} else if (array_key_exists('mapValue', $value)) {
			// type: object(MapValue)
			return $this->mapMap($value['mapValue']['fields']);
		}
		throw new \Exception('Invalid Firebase datatype: ' . (array_keys($value)[0]));
	}

	private function mapArray($values) {
		$arr = [];
		foreach ($values['values'] as $val) {
			$arr[] = $this->mapValue($val);
		}
		return $arr;
	}

	private function mapMap($fields) {
		$map = [];
		foreach ($fields as $key => $val) {
			$map[$key] = $this->mapValue($val);
		}
		return $map;
	}
}
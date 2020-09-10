<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Lib\UrlParts;

class ImportedMessage extends Entity {

	public function formattedBody() {
		if ($this->type == 'mms') {
			$parts = json_decode($this->body);
			$contents = [];
			foreach ($parts as $part) {
				if ($part->type == 'Text') {
					$contents[] = "Text: " . $this->textWithLinks($part->text);
				} else if (array_key_exists('download_url', $part)) {
					$contents[] = "<a href=\"{$part->download_url}\">{$part->type}</a>";
				} else {
					$contents[] = $part->type;
				}
			}
			if (count($contents) == 0) $contents[] = '<i>No MMS content</i>';
			return "<b>MMS:</b><br>" . implode('<br>', $contents);
		}

		if (empty($this->body)) {
			return '<i>Empty message body</i>';
		}

		return $this->textWithLinks($this->body);
	}

	public function formattedBodyNoLinks() {
		if ($this->type == 'mms') {
			$parts = json_decode($this->body);
			$contents = [];
			foreach ($parts as $part) {
				if ($part->type == 'Text') {
					$contents[] = "Text: {$part->text}";
				} else if (array_key_exists('download_url', $part)) {
					$contents[] = $part->type;
				} else {
					$contents[] = $part->type;
				}
			}
			if (count($contents) == 0) $contents[] = '<i>No MMS content</i>';
			return "[MMS] " . implode(', ', $contents);
		}

		if (empty($this->body)) {
			return '<i>Empty message body</i>';
		}

		return $this->body;
	}

	public function extractUrls() {
		if ($this->type == 'mms') {
			$urls = [];
			$parts = json_decode($this->body);
			foreach ($parts as $part) {
				if ($part->type == 'Text') {
					$urls = array_merge($urls, UrlParts::extract($part->text));
				}
			}
			return $urls;
		} else {
			return UrlParts::extract($this->body);
		}
	}

	private function textWithLinks($text) {
		$urls = UrlParts::extract($text);
		$search = [];
		$replace = [];
		foreach ($urls as $key => $urlParts) {
			$search[] = $key;
			$replace[] = "<a href=\"" . \Cake\Routing\Router::url(['controller' => 'UrlDetails', 'action' => 'view', 'url' => $urlParts->buildUrl()]) . "\">{$key}</a>";
		}
		return str_replace($search, $replace, $text);
	}
}
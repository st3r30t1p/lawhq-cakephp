<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class RuleConditionSet extends Entity
{
	public function tableInfo()
	{
		$data = [
			'table' => 'ImportedMessages',
			'joins' => [
				'thread' => [
					'table' => 'threads',
					'type' => 'LEFT',
					'conditions' => 'ImportedMessages.thread_id = thread.id',
				]
			]
		];

		foreach ($this->rule_conditions as $set) {
			if (!in_array($set->type, ['message_text', 'phone_number'])) {
				$data['table'] = 'UrlDetails';

				$data['joins'] = [
					'mu' => [
					    'table' => 'message_urls',
					    'type' => 'LEFT',
					    'conditions' => 'UrlDetails.url_id = mu.url_id',
					], 
					'im' => [
						'table' => 'imported_messages',
						'type' => 'LEFT',
						'conditions' => 'im.id = mu.imported_message_id',
					],
					'thread' => [
						'table' => 'threads',
						'type' => 'LEFT',
						'conditions' => 'im.thread_id = thread.id',
					]
				];

			}

			if (in_array($set->type, ['ips', 'reg_email', 'reg_name'])) {
				$data['joins']['dd'] = [
					'table' => 'domain_details',
					'type' => 'LEFT',
					'conditions' => 'dd.domain = CONCAT(UrlDetails.domain, ".", UrlDetails.tld)',
				];
			}
		}

		return $data;
	}
}

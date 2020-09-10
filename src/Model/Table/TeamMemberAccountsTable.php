<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;

class TeamMemberAccountsTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
    	$this->belongsTo('TeamMembers');
        $this->belongsTo('States');
	}

	public function beforeMarshal(Event $event, ArrayObject $data)
	{
		if (empty($data['state_id'])) {
			$data['state_id'] = null;
		}
		if (!empty($data['un'])) {
		    $data['username'] = $data['un'];
		}
		if (!empty($data['pw'])) {
		    $data['password'] = $data['pw'];
		}
	}
}
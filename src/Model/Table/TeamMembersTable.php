<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;

class TeamMembersTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
    	$this->hasMany('BlogPosts');
    	$this->hasMany('TeamMemberLicenses');
		$this->hasMany('TeamMemberAccounts');
		$this->hasMany('Conferences');
        $this->belongsTo('States');
    	$this->setDisplayField('full_name');
	}

	public function validationDefault(Validator $validator) 
	{
	    return $validator->notEmpty('username');
	}

    public function beforeMarshal(Event $event, ArrayObject $data)
    {
        if (!empty($data['un'])) {
            $data['username'] = $data['un'];
        }
        if (!empty($data['pw'])) {
            $data['password'] = $data['pw'];
        }
        if (!empty($data['fn'])) {
            $data['first_name'] = $data['fn'];
        }
        if (!empty($data['ln'])) {
            $data['last_name'] = $data['ln'];
        }
        if (!empty($data['pe'])) {
            $data['personal_email'] = $data['pe'];
        }
        if (!empty($data['pn'])) {
            $data['phone_number'] = preg_replace("/[^0-9,.]/", "", $data['pn']);
        }
        if (!empty($data['phone_number'])) {
            $data['phone_number'] = preg_replace("/[^0-9,.]/", "", $data['phone_number']);
        } 
        if (empty($data['pn']) && empty($data['phone_number'])) {
            $data['phone_number'] = null;
        }
    }
}
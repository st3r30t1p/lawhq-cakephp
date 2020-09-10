<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class StatesTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('Contacts');
        $this->belongsTo('ContactAddresses');
        $this->hasOne('TeamMemberLicenses');
    }

    public function list()
    {
    	return $this->find('list', [
			'keyField' => 'code',
			'valueField' => 'state',
		])->all();
    }

    public function altList()
    {
        return $this->find('list', [
            'keyField' => 'id',
            'valueField' => 'state',
        ])->all();
    }
}
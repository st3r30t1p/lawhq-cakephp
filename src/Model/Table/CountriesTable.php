<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class CountriesTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->hasMany('contactAddresses');
    }

    public function list()
    {
    	return $this->find('list', [
			'keyField' => 'code',
			'valueField' => 'nicename',
		])->all();
    }
}
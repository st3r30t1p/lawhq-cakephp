<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ContactNotesTable extends Table {

    public function initialize(array $config) {
    	$this->addBehavior('Timestamp');
        $this->belongsTo('Contacts');
        $this->belongsTo('TeamMembers');
    }

}
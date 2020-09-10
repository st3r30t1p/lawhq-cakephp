<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class MattersTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
        $this->hasMany('Threads', ['sort' => 'Threads.last_message_received DESC']);
        $this->hasMany('matterNotes', [
            'sort' => ['matterNotes.created' => 'ASC'],
            'conditions' => ['matterNotes.is_deleted IS NULL']
        ]);

        $this->hasMany('matterContacts', [
            'sort' => ['matterContacts.type' => 'ASC'],
            'conditions' => ['matterContacts.is_deleted IS NULL']
        ]);

        $this->hasOne('ResponsibleAttorneys', [
            'className' => 'matterContacts',
            'conditions' => ['ResponsibleAttorneys.type' => 'attorney_for_plaintiff']
        ]);

        $this->hasOne('ResponsibleParalegals', [
            'className' => 'matterContacts',
            'conditions' => ['ResponsibleParalegals.type' => 'paralegal_for_plaintiff']
        ]);

        $this->hasMany('Documents');
        $this->hasMany('MatterCourts');
    }
}

<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class SystemGeneratedRulesTable extends Table
{
    public function initialize(array $config)
    {
    	$this->addBehavior('Timestamp');
    	$this->belongsTo('Domains');
    	$this->belongsTo('RuleConditionSets');
    	$this->belongsTo('Rules');
    	$this->belongsTo('BasedOffRule' ,[
    		'className' => 'SystemGeneratedRules',
    		'foreignKey' => 'system_generated_rule_id'
    	]);
        $this->belongsTo('RedirectDomain' ,[
            'className' => 'Domains',
            'foreignKey' => 'redirects_with_domain_id'
        ]);
    }

    public function ignoreByDomainId($id)
    {
        $this->updateAll(
           ['status' => 'ignore'],
           ['domain_id' => $id]
        );
    }
}
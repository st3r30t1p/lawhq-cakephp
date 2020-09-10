<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Lib\AssignRule;
use App\Lib\Rules;

class RulesController extends AppController
{
	public $paginate = [
	    'limit' => 100
	];

	public function index()
	{
		$type = 'all';
		if ($this->request->getData()) {
			$type = $this->request->getData()['type'];
		}
		$dbRules = $this->Rules->find()
		->contain(['Contacts', 'RuleConditionSets', 'RuleConditionSets.RuleConditions', 'SystemGeneratedRules', 'SystemGeneratedRules.Domains', 'SystemGeneratedRules.RedirectDomain', 'SystemGeneratedRules.BasedOffRule', 'SystemGeneratedRules.BasedOffRule.RuleConditionSets', 'SystemGeneratedRules.RuleConditionSets', 'SystemGeneratedRules.RuleConditionSets.RuleConditions'])
		->where(['ignore_rule' => 0]);

		// 'SystemGeneratedRules.Domains',
		// 'SystemGeneratedRules.RuleConditionSets',
		// 'SystemGeneratedRules.RuleConditionSets.RuleConditions',
		// 'SystemGeneratedRules.RedirectDomain',
		// 'SystemGeneratedRules.BasedOffRule',
		// 'SystemGeneratedRules.BasedOffRule.Domains',
		// 'SystemGeneratedRules.BasedOffRule.BasedOffRule',
		// 'SystemGeneratedRules.BasedOffRule.RedirectDomain',
		// 'SystemGeneratedRules.BasedOffRule.RuleConditionSets',
		// 'SystemGeneratedRules.BasedOffRule.RuleConditionSets.Rules',
		// 'SystemGeneratedRules.BasedOffRule.RuleConditionSets.Rules.Contacts'
		if ($type != 'all') {
			$dbRules->where(['Rules.type' => $type]);
		}
		$dbRules->order(['Rules.id', 'Contacts.id']);

		$rules = [];
		foreach ($this->paginate($dbRules) as $rule) {
			$rules[] = new Rules($rule);
		}

		$this->set(compact('type', 'rules'));
	}

	public function add()
	{
		$domainsTable = TableRegistry::getTableLocator()->get('Domains');
		$rule = $this->Rules->newEntity();

		if ($this->request->getData()) {
			$this->Rules->patchEntity($rule, $this->request->getData(), [
				'associated' => ['RuleConditionSets', 'RuleConditionSets.RuleConditions']
			]);

			if ($this->Rules->save($rule)) {
				// Update rule assignments
				if (!empty($rule->rule_condition_sets)) {
					$assignRule = new AssignRule($rule);
				}
				$this->redirect(['action' => 'index']);
			}
		}

		$this->set(compact('rule'));
		$this->set('contacts', $this->Rules->Contacts->list());
	}

	public function edit($id)
	{
		$rule = $this->Rules->find()
		->where(['Rules.id' => $id])
		->contain([
			'Contacts',
			'RuleFiles',
			'RuleFiles.Files',
			'RuleConditionSets', 
			'RuleConditionSets.RuleConditions', 
			'SystemGeneratedRules', 
			'SystemGeneratedRules.Domains', 
			'SystemGeneratedRules.BasedOffRule',
			'SystemGeneratedRules.BasedOffRule.RuleConditionSets',
			'SystemGeneratedRules.RedirectDomain', 
			'SystemGeneratedRules.RuleConditionSets', 
			'SystemGeneratedRules.RuleConditionSets.RuleConditions',
			])->first();

		if (!$rule) {
			$this->redirect(['action' => 'index']);
		}

		if ($this->request->getData()) {
			if (isset($this->request->getData()['file'])) {
				$file = $this->request->getData()['file']; //put the data into a var for easy use
				$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
				$newFileName = time() . "_" . rand(000000, 999999);

				$sha1 = sha1_file($file['tmp_name']);
				$filename = ROOT . DS . '..' . DS . 'uploaded_files' . DS . $sha1;

				move_uploaded_file($file['tmp_name'], $filename);

				$newFile = $this->Rules->RuleFiles->Files->newEntity();
				$newFile->filename = "rule_{$newFileName}.{$ext}";
				$newFile->size = $file['size'];
				$newFile->sha1 = $sha1;
				if ($this->Rules->RuleFiles->Files->save($newFile)) {

					$ruleFile = $this->Rules->RuleFiles->newEntity();
					$ruleFile->rule_id = $id;
					$ruleFile->file_id = $newFile->id;
					$ruleFile->title = $this->request->getData()['title'];
					$ruleFile->type = $this->request->getData()['type'];
					$ruleFile->date_captured = date('Y-m-d', strtotime($this->request->getData()['date_captured']));
					$this->Rules->RuleFiles->save($ruleFile);

					$this->redirect(['action' => 'edit', 'id' => $id]);
				}
			} else {
				$associated = ['RuleConditionSets', 'RuleConditionSets.RuleConditions'];
				// if ($rule->type == 'system') {
				// 	array_push($associated, 'RulesSystemGenerated', 'RulesSystemGenerated.Domains');
				// }
				$this->Rules->patchEntity($rule, $this->request->getData(), [
					'associated' => $associated
				]);

				if ($this->Rules->save($rule)) {
					if ($rule->ignore_rule) {
						$this->Rules->RuleConditionSets->deleteSetsByRuleId($rule->id);
					}
					// Update rule assignments
					if (!empty($rule->rule_condition_sets)) {
						$assignRule = new AssignRule($rule);
					}
					
					if (isset($this->request->getData()['view'])) {
						$this->redirect(['action' => 'index']);
					} else {
						$this->redirect(['action' => 'edit', $rule->id]);
					}
				}
			}
		}

		$rule = new Rules($rule);
		$this->set(compact('rule'));
		$this->set('contacts', $this->Rules->Contacts->list());
		$this->addToHistory('Rule', $rule->rule->id);
	}

	public function assignments()
	{
		$ruleId = $this->request->getQuery('rule');
		$rule = $this->Rules->find()
		->where(['Rules.`id`' => $ruleId])
		->contain(['Contacts', 'RuleConditionSets', 'RuleConditionSets.RuleAssignments', 'RuleConditionSets.RuleAssignments.ImportedMessages', 'RuleConditionSets.RuleAssignments.ImportedMessages.Threads', 'RuleConditionSets.RuleAssignments.ImportedMessages.Threads.ImportedUsers', 'RuleConditionSets.RuleAssignments.ImportedMessages.Threads.ThreadGroups'])
		->first();

		$conflicts = [];
		$rule_threads = $this->Rules->RuleAssignments->find()
		->select(['imported_message_id'])->where(['rule_id' => $ruleId]);

		$duplicates = $this->Rules->RuleAssignments->find()
		->where(['imported_message_id IN' => $rule_threads]);

		foreach ($duplicates as $duplicate) {
			if (isset($conflicts[$duplicate->imported_message_id])) {
				array_push($conflicts[$duplicate->imported_message_id], $duplicate->rule_id);
			} else {
				$conflicts[$duplicate->imported_message_id] = [$duplicate->rule_id];
			}
		}

		$this->set(compact('rule', 'conflicts'));
	}

	public function approve()
	{
		$status = 'pending';
		$page = (isset($this->request->query()['page'])) ? $this->request->query('page') : 1;
		if ($this->request->getData()) {
			$status = $this->request->getData()['status'];
		}

		$systemRules = $this->Rules->SystemGeneratedRules->find()
		->contain(['Domains', 'RuleConditionSets', 'RuleConditionSets.Rules', 'RuleConditionSets.Rules.Contacts', 'RuleConditionSets.RuleConditions', 'BasedOffRule', 'BasedOffRule.Rules', 'BasedOffRule.Rules.Contacts', 'RedirectDomain']);
		if ($status != 'all') {
			$systemRules->where(['SystemGeneratedRules.status' => $status]);
		}
		$systemRules->order(['SystemGeneratedRules.id' => 'ASC']);

		$systemGeneratedRules = [];
		
		foreach ($this->paginate($systemRules) as $rule) {
			$systemGeneratedRules[] = new Rules($rule);
		}
		$this->set(compact('status', 'page', 'systemGeneratedRules'));
	}
}
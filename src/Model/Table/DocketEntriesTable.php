<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DocketEntries Model
 *
 * @property \App\Model\Table\DocketsTable|\Cake\ORM\Association\BelongsTo $Dockets
 *
 * @method \App\Model\Entity\DocketEntry get($primaryKey, $options = [])
 * @method \App\Model\Entity\DocketEntry newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DocketEntry[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DocketEntry|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DocketEntry saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DocketEntry patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DocketEntry[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DocketEntry findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DocketEntriesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('docket_entries');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Dockets', [
            'foreignKey' => 'docket_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->date('date_filed')
            ->allowEmptyDate('date_filed');

        $validator
            ->date('date_entered')
            ->allowEmptyDate('date_entered');

        $validator
            ->scalar('docket_type')
            ->maxLength('docket_type', 255)
            ->allowEmptyString('docket_type');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->boolean('has_attachment')
            ->allowEmptyString('has_attachment');

        $validator
            ->decimal('sequence_id')
            ->maxLength('sequence_id', 11)
            ->allowEmptyString('sequence_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['docket_id'], 'Dockets'));

        return $rules;
    }
}

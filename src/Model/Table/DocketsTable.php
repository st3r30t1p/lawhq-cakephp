<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Dockets Model
 *
 * @property \App\Model\Table\CourtsTable|\Cake\ORM\Association\BelongsTo $Courts
 * @property \App\Model\Table\MattersTable|\Cake\ORM\Association\BelongsTo $Matters
 * @property \App\Model\Table\DocketAttachmentsTable|\Cake\ORM\Association\HasMany $DocketAttachments
 * @property \App\Model\Table\DocketEntriesTable|\Cake\ORM\Association\HasMany $DocketEntries
 *
 * @method \App\Model\Entity\Docket get($primaryKey, $options = [])
 * @method \App\Model\Entity\Docket newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Docket[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Docket|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Docket saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Docket patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Docket[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Docket findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DocketsTable extends Table
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

        $this->setTable('dockets');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Courts', [
            'foreignKey' => 'court_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Matters', [
            'foreignKey' => 'matter_id'
        ]);
        $this->hasMany('DocketAttachments', [
            'foreignKey' => 'docket_id'
        ]);
        $this->hasMany('DocketEntries', [
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
            ->scalar('case_name')
            ->maxLength('case_name', 255)
            ->allowEmptyString('case_name');

        $validator
            ->scalar('case_number')
            ->maxLength('case_number', 255)
            ->requirePresence('case_number', 'create')
            ->allowEmptyString('case_number', false);

        $validator
            ->scalar('fed_case_number_judges')
            ->maxLength('fed_case_number_judges', 255)
            ->allowEmptyString('fed_case_number_judges');

        $validator
            ->scalar('court_fed_abbr')
            ->maxLength('court_fed_abbr', 255)
            ->allowEmptyString('court_fed_abbr');

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
        $rules->add($rules->existsIn(['court_id'], 'Courts'));
        $rules->add($rules->isUnique(['court_id', 'case_number']));
        $rules->add($rules->existsIn(['matter_id'], 'Matters'));

        return $rules;
    }
}

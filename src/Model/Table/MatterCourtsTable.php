<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MatterCourts Model
 *
 * @property \App\Model\Table\MattersTable|\Cake\ORM\Association\BelongsTo $Matters
 * @property \App\Model\Table\CourtsTable|\Cake\ORM\Association\BelongsTo $Courts
 * @property \App\Model\Table\DocketsTable|\Cake\ORM\Association\HasMany $DocketsOld
 *
 * @method \App\Model\Entity\MatterCourt get($primaryKey, $options = [])
 * @method \App\Model\Entity\MatterCourt newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MatterCourt[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MatterCourt|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MatterCourt saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MatterCourt patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MatterCourt[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MatterCourt findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MatterCourtsTable extends Table
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

        $this->setTable('matter_courts');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Matters', [
            'foreignKey' => 'matter_id'
        ]);
        $this->belongsTo('Courts', [
            'foreignKey' => 'court_id'
        ]);
        $this->hasMany('Dockets', [
            'foreignKey' => 'matter_court_id'
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
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('case_number')
            ->maxLength('case_number', 255)
            ->allowEmptyString('case_number');

        $validator
            ->scalar('start')
            ->maxLength('start', 10)
            ->allowEmptyString('start');

        $validator
            ->scalar('end')
            ->maxLength('end', 10)
            ->allowEmptyString('end');

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
        $rules->add($rules->existsIn(['matter_id'], 'Matters'));
        $rules->add($rules->existsIn(['court_id'], 'Courts'));

        return $rules;
    }
}

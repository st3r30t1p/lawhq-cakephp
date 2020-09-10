<?php
namespace App\Model\Table;

use App\Model\Entity\Document;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Documents Model
 *
 * @property MattersTable|BelongsTo $Matters
 *
 * @method Document get($primaryKey, $options = [])
 * @method Document newEntity($data = null, array $options = [])
 * @method Document[] newEntities(array $data, array $options = [])
 * @method Document|bool save(EntityInterface $entity, $options = [])
 * @method Document saveOrFail(EntityInterface $entity, $options = [])
 * @method Document patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Document[] patchEntities($entities, array $data, array $options = [])
 * @method Document findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin TimestampBehavior
 */
class DocumentsTable extends Table
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

        $this->setTable('documents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Matters', [
            'foreignKey' => 'matter_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator Validator instance.
     * @return Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('link')
            ->maxLength('link', 255)
            ->requirePresence('link', 'create')
            ->allowEmptyString('link', false);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules The rules object to be modified.
     * @return RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['matter_id'], 'Matters'));

        return $rules;
    }
}

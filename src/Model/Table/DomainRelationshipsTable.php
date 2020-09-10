<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DomainRelationships Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 *
 * @method \App\Model\Entity\DomainRelationship get($primaryKey, $options = [])
 * @method \App\Model\Entity\DomainRelationship newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DomainRelationship[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DomainRelationship|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DomainRelationship saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DomainRelationship patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DomainRelationship[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DomainRelationship findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DomainRelationshipsTable extends Table
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

        $this->setTable('domain_relationships');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->BelongsTo('Domains');
        $this->belongsTo('DomainsLink' ,[
            'className' => 'Domains',
            'foreignKey' => 'domain_id_link'
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
            ->integer('domain_id_link')
            ->allowEmptyString('domain_id_link');

        $validator
            ->integer('count')
            ->allowEmptyString('count');

        return $validator;
    }

    public function getRelationship($id1, $id2)
    {
        $lesser = ($id1 < $id2) ? $id1 : $id2;
        $higher = ($id1 > $id2) ? $id1 : $id2;

        $relationship = $this->find()
        ->where(['domain_id' => $lesser, 'domain_id_link' => $higher])->first();

        if (!$relationship) {
            return 'unknown';
        }

        return $relationship;
    }

}

<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DocketAttachments Model
 *
 * @property \App\Model\Table\DocketsTable|\Cake\ORM\Association\BelongsTo $Dockets
 *
 * @method \App\Model\Entity\DocketAttachment get($primaryKey, $options = [])
 * @method \App\Model\Entity\DocketAttachment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DocketAttachment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DocketAttachment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DocketAttachment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DocketAttachment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DocketAttachment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DocketAttachment findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DocketAttachmentsTable extends Table
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

        $this->setTable('docket_attachments');
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
            ->boolean('downloaded')
            ->allowEmptyString('downloaded');

        $validator
            ->boolean('restricted')
            ->allowEmptyString('restricted');

        $validator
            ->integer('sequence_id')
            ->maxLength('sequence_id', 11)
            ->allowEmptyString('sequence_id');

        $validator
            ->integer('attachment_id')
            ->maxLength('attachment_id', 11)
            ->allowEmptyString('attachment_id');

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

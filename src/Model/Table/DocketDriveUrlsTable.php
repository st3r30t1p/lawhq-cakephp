<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DocketDriveUrls Model
 *
 * @property \App\Model\Table\DocketsTable|\Cake\ORM\Association\BelongsTo $Dockets
 *
 * @method \App\Model\Entity\DocketDriveUrl get($primaryKey, $options = [])
 * @method \App\Model\Entity\DocketDriveUrl newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DocketDriveUrl[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DocketDriveUrl|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DocketDriveUrl saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DocketDriveUrl patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DocketDriveUrl[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DocketDriveUrl findOrCreate($search, callable $callback = null, $options = [])
 */
class DocketDriveUrlsTable extends Table
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

        $this->setTable('docket_drive_urls');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Dockets', [
            'foreignKey' => 'docket_id',
            'joinType' => 'INNER'
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
            ->scalar('doc_name')
            ->maxLength('doc_name', 255)
            ->requirePresence('doc_name', 'create')
            ->allowEmptyString('doc_name', false);

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
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['docket_id'], 'Dockets'));

        return $rules;
    }
}

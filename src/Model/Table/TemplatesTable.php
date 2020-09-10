<?php
namespace App\Model\Table;

use App\Model\Entity\Template;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Templates Model
 *
 * @method Template get($primaryKey, $options = [])
 * @method Template newEntity($data = null, array $options = [])
 * @method Template[] newEntities(array $data, array $options = [])
 * @method Template|bool save(EntityInterface $entity, $options = [])
 * @method Template saveOrFail(EntityInterface $entity, $options = [])
 * @method Template patchEntity(EntityInterface $entity, array $data, array $options = [])
 * @method Template[] patchEntities($entities, array $data, array $options = [])
 * @method Template findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin TimestampBehavior
 */
class TemplatesTable extends Table
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

        $this->setTable('templates');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('SectionTemplates', [
            'through' => 'TemplateSectionTemplates',
            'dependent' => true
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('google_doc_id')
            ->maxLength('google_doc_id', 255)
            ->requirePresence('google_doc_id', 'create')
            ->allowEmptyString('google_doc_id', false);

//        $validator
//            ->requirePresence('parameters', 'create')
//            ->allowEmptyString('parameters', false);

        return $validator;
    }
}

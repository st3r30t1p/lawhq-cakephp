<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TemplateSectionTemplates Model
 *
 * @property \App\Model\Table\TemplatesTable|\Cake\ORM\Association\BelongsTo $Templates
 * @property \App\Model\Table\SectionTemplatesTable|\Cake\ORM\Association\BelongsTo $SectionTemplates
 *
 * @method \App\Model\Entity\TemplateSectionTemplate get($primaryKey, $options = [])
 * @method \App\Model\Entity\TemplateSectionTemplate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TemplateSectionTemplate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TemplateSectionTemplate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TemplateSectionTemplate saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TemplateSectionTemplate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TemplateSectionTemplate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TemplateSectionTemplate findOrCreate($search, callable $callback = null, $options = [])
 */
class TemplateSectionTemplatesTable extends Table
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

        $this->setTable('template_section_templates');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Templates', [
            //'foreignKey' => 'template_id',
        ]);
        $this->belongsTo('SectionTemplates', [
            //'foreignKey' => 'section_template_id',
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
        $rules->add($rules->existsIn(['template_id'], 'Templates'));
        $rules->add($rules->existsIn(['section_template_id'], 'SectionTemplates'));

        return $rules;
    }
}

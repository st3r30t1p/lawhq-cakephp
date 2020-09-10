<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TemplateSectionTemplatesFixture
 */
class TemplateSectionTemplatesFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'template_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'section_template_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'template_id' => ['type' => 'index', 'columns' => ['template_id'], 'length' => []],
            'section_template_id' => ['type' => 'index', 'columns' => ['section_template_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'template_section_templates_ibfk_1' => ['type' => 'foreign', 'columns' => ['template_id'], 'references' => ['templates', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'template_section_templates_ibfk_2' => ['type' => 'foreign', 'columns' => ['section_template_id'], 'references' => ['section_templates', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'template_id' => 1,
                'section_template_id' => 1
            ],
        ];
        parent::init();
    }
}

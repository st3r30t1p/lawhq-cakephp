<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TemplateSectionTemplatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TemplateSectionTemplatesTable Test Case
 */
class TemplateSectionTemplatesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TemplateSectionTemplatesTable
     */
    public $TemplateSectionTemplates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.TemplateSectionTemplates',
        'app.Templates',
        'app.SectionTemplates'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('TemplateSectionTemplates') ? [] : ['className' => TemplateSectionTemplatesTable::class];
        $this->TemplateSectionTemplates = TableRegistry::getTableLocator()->get('TemplateSectionTemplates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TemplateSectionTemplates);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

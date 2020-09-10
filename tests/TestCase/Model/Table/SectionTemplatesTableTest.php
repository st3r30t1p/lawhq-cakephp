<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SectionTemplatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SectionTemplatesTable Test Case
 */
class SectionTemplatesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SectionTemplatesTable
     */
    public $SectionTemplates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
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
        $config = TableRegistry::getTableLocator()->exists('SectionTemplates') ? [] : ['className' => SectionTemplatesTable::class];
        $this->SectionTemplates = TableRegistry::getTableLocator()->get('SectionTemplates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SectionTemplates);

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
}

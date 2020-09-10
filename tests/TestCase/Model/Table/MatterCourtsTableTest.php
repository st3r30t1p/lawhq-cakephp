<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MatterCourtsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MatterCourtsTable Test Case
 */
class MatterCourtsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MatterCourtsTable
     */
    public $MatterCourts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MatterCourts',
        'app.Matters',
        'app.Courts',
        'app.DocketsOld'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MatterCourts') ? [] : ['className' => MatterCourtsTable::class];
        $this->MatterCourts = TableRegistry::getTableLocator()->get('MatterCourts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MatterCourts);

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

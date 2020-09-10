<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DocketsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DocketsTable Test Case
 */
class DocketsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DocketsTable
     */
    public $Dockets;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Dockets',
        'app.Courts',
        'app.Matters',
        'app.DocketAttachments',
        'app.DocketEntries',
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
        $config = TableRegistry::getTableLocator()->exists('Dockets') ? [] : ['className' => DocketsTable::class];
        $this->Dockets = TableRegistry::getTableLocator()->get('Dockets', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Dockets);

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

<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DocketPartiesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DocketPartiesTable Test Case
 */
class DocketPartiesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DocketPartiesTable
     */
    public $DocketParties;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.DocketParties',
        'app.Dockets'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('DocketParties') ? [] : ['className' => DocketPartiesTable::class];
        $this->DocketParties = TableRegistry::getTableLocator()->get('DocketParties', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DocketParties);

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

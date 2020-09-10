<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DocketDriveUrlsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DocketDriveUrlsTable Test Case
 */
class DocketDriveUrlsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DocketDriveUrlsTable
     */
    public $DocketDriveUrls;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.DocketDriveUrls',
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
        $config = TableRegistry::getTableLocator()->exists('DocketDriveUrls') ? [] : ['className' => DocketDriveUrlsTable::class];
        $this->DocketDriveUrls = TableRegistry::getTableLocator()->get('DocketDriveUrls', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DocketDriveUrls);

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

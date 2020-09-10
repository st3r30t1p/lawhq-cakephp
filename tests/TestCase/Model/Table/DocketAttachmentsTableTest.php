<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DocketAttachmentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DocketAttachmentsTable Test Case
 */
class DocketAttachmentsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DocketAttachmentsTable
     */
    public $DocketAttachments;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.DocketAttachments',
        'app.Dockets',
        'app.Sequences',
        'app.Attachments'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('DocketAttachments') ? [] : ['className' => DocketAttachmentsTable::class];
        $this->DocketAttachments = TableRegistry::getTableLocator()->get('DocketAttachments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DocketAttachments);

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

<?php
namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\MpMessagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\MpMessagesTable Test Case
 */
class MpMessagesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Api\Model\Table\MpMessagesTable
     */
    public $MpMessages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.api.mp_messages',
        'plugin.api.mp_rules'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MpMessages') ? [] : ['className' => MpMessagesTable::class];
        $this->MpMessages = TableRegistry::getTableLocator()->get('MpMessages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MpMessages);

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

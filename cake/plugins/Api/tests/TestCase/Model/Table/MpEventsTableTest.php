<?php
namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\MpEventsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\MpEventsTable Test Case
 */
class MpEventsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Api\Model\Table\MpEventsTable
     */
    public $MpEvents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.api.mp_events',
        'plugin.api.mps'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MpEvents') ? [] : ['className' => MpEventsTable::class];
        $this->MpEvents = TableRegistry::getTableLocator()->get('MpEvents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MpEvents);

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

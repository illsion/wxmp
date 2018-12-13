<?php
namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\MpRulesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\MpRulesTable Test Case
 */
class MpRulesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Api\Model\Table\MpRulesTable
     */
    public $MpRules;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.api.mp_rules',
        'plugin.api.mps',
        'plugin.api.mp_messages'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MpRules') ? [] : ['className' => MpRulesTable::class];
        $this->MpRules = TableRegistry::getTableLocator()->get('MpRules', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MpRules);

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

<?php
namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\MpMemberOpenidTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\MpMemberOpenidTable Test Case
 */
class MpMemberOpenidTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Api\Model\Table\MpMemberOpenidTable
     */
    public $MpMemberOpenid;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.api.mp_member_openid',
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
        $config = TableRegistry::getTableLocator()->exists('MpMemberOpenid') ? [] : ['className' => MpMemberOpenidTable::class];
        $this->MpMemberOpenid = TableRegistry::getTableLocator()->get('MpMemberOpenid', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MpMemberOpenid);

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

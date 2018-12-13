<?php
namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\MpsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\MpsTable Test Case
 */
class MpsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Api\Model\Table\MpsTable
     */
    public $Mps;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.api.mps',
        'plugin.api.users',
        'plugin.api.origins'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Mps') ? [] : ['className' => MpsTable::class];
        $this->Mps = TableRegistry::getTableLocator()->get('Mps', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Mps);

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

<?php
namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\MpNewsListsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\MpNewsListsTable Test Case
 */
class MpNewsListsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Api\Model\Table\MpNewsListsTable
     */
    public $MpNewsLists;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.api.mp_news_lists',
        'plugin.api.mp_news',
        'plugin.api.thumb_media'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MpNewsLists') ? [] : ['className' => MpNewsListsTable::class];
        $this->MpNewsLists = TableRegistry::getTableLocator()->get('MpNewsLists', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MpNewsLists);

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

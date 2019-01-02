<?php
namespace Api\Test\TestCase\Model\Table;

use Api\Model\Table\MpNewsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Api\Model\Table\MpNewsTable Test Case
 */
class MpNewsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Api\Model\Table\MpNewsTable
     */
    public $MpNews;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.api.mp_news',
        'plugin.api.mps',
        'plugin.api.mp_news_lists'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MpNews') ? [] : ['className' => MpNewsTable::class];
        $this->MpNews = TableRegistry::getTableLocator()->get('MpNews', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MpNews);

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

<?php
namespace Api\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MpEventsFixture
 *
 */
class MpEventsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'mp_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '关联公众号', 'precision' => null, 'autoIncrement' => null],
        'mp_rule_keywords' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '规则关键字', 'precision' => null, 'fixed' => null],
        'name' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '事件类型', 'precision' => null, 'fixed' => null],
        'status' => ['type' => 'tinyinteger', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '1:开启,2:关闭', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'mp_id' => 1,
                'mp_rule_keywords' => 'Lorem ipsum dolor sit amet',
                'name' => 'Lorem ipsum dolor sit amet',
                'status' => 1
            ],
        ];
        parent::init();
    }
}

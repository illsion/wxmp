<?php
namespace Api\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MpRulesFixture
 *
 */
class MpRulesFixture extends TestFixture
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
        'mp_message_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '关联信息id', 'precision' => null, 'autoIncrement' => null],
        'keywords' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '关键字', 'precision' => null, 'fixed' => null],
        'type' => ['type' => 'string', 'length' => 24, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '类型,text/video/...', 'precision' => null, 'fixed' => null],
        'status' => ['type' => 'tinyinteger', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '状态,1:开启,2:关闭', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'keywords_index' => ['type' => 'unique', 'columns' => ['keywords'], 'length' => []],
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
                'mp_message_id' => 1,
                'keywords' => 'Lorem ipsum dolor sit amet',
                'type' => 'Lorem ipsum dolor sit ',
                'status' => 1,
                'created' => '2018-12-07 10:32:54'
            ],
        ];
        parent::init();
    }
}

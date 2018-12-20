<?php
namespace Api\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * MpMembersFixture
 *
 */
class MpMembersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'openid' => ['type' => 'string', 'length' => 64, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'mp_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'nickname' => ['type' => 'string', 'length' => 64, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '昵称', 'precision' => null, 'fixed' => null],
        'sex' => ['type' => 'tinyinteger', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '用户的性别，值为1时是男性，值为2时是女性，值为0时是未知', 'precision' => null],
        'city' => ['type' => 'string', 'length' => 24, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'province' => ['type' => 'string', 'length' => 24, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'country' => ['type' => 'string', 'length' => 24, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'headimgurl' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '头像地址', 'precision' => null, 'fixed' => null],
        'subscribe_time' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '关注时间', 'precision' => null, 'autoIncrement' => null],
        'unsubscribe_time' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '取消关注时间', 'precision' => null, 'autoIncrement' => null],
        'subscribe' => ['type' => 'tinyinteger', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '关注状态', 'precision' => null],
        '_indexes' => [
            'openid_index' => ['type' => 'index', 'columns' => ['openid'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
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
                'openid' => 'Lorem ipsum dolor sit amet',
                'mp_id' => 1,
                'nickname' => 'Lorem ipsum dolor sit amet',
                'sex' => 1,
                'city' => 'Lorem ipsum dolor sit ',
                'province' => 'Lorem ipsum dolor sit ',
                'country' => 'Lorem ipsum dolor sit ',
                'headimgurl' => 'Lorem ipsum dolor sit amet',
                'subscribe_time' => 1,
                'unsubscribe_time' => 1,
                'subscribe' => 1
            ],
        ];
        parent::init();
    }
}

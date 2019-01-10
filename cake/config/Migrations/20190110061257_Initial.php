<?php
use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Initial extends AbstractMigration
{

    public $autoId = false;

    public function up()
    {

        $this->execute('set names utf8');

        $this->table('miniapps')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'comment' => '小程序名称',
                'default' => null,
                'limit' => 32,
                'null' => false,
            ])
            ->addColumn('appid', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => false,
            ])
            ->addColumn('secret', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => false,
            ])
            ->addColumn('token', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('mp_events')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('mp_id', 'integer', [
                'comment' => '关联公众号',
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('mp_rule_id', 'integer', [
                'comment' => '关联规则',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'comment' => '事件类型',
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'comment' => '1:开启,2:关闭',
                'default' => '2',
                'limit' => MysqlAdapter::INT_TINY,
                'null' => false,
            ])
            ->create();

        $this->table('mp_member_openid')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('mp_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('openid', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => false,
            ])
            ->addIndex(
                [
                    'openid',
                ]
            )
            ->create();

        $this->table('mp_members')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('openid', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => false,
            ])
            ->addColumn('mp_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('nickname', 'string', [
                'comment' => '昵称',
                'default' => null,
                'limit' => 64,
                'null' => false,
            ])
            ->addColumn('sex', 'integer', [
                'comment' => '用户的性别，值为1时是男性，值为2时是女性，值为0时是未知',
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'null' => false,
            ])
            ->addColumn('city', 'string', [
                'default' => null,
                'limit' => 24,
                'null' => true,
            ])
            ->addColumn('province', 'string', [
                'default' => null,
                'limit' => 24,
                'null' => true,
            ])
            ->addColumn('country', 'string', [
                'default' => null,
                'limit' => 24,
                'null' => true,
            ])
            ->addColumn('headimgurl', 'string', [
                'comment' => '头像地址',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('subscribe_time', 'integer', [
                'comment' => '关注时间',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('unsubscribe_time', 'integer', [
                'comment' => '取消关注时间',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('subscribe', 'integer', [
                'comment' => '关注状态',
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'null' => false,
            ])
            ->addIndex(
                [
                    'openid',
                ]
            )
            ->create();

        $this->table('mp_menus')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('parent_id', 'integer', [
                'comment' => '父id',
                'default' => '0',
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('mp_id', 'integer', [
                'comment' => '关联公众号',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('name', 'string', [
                'comment' => '菜单名称',
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('type', 'string', [
                'comment' => '菜单类型',
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('content', 'string', [
                'comment' => '菜单内容',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('sort', 'integer', [
                'comment' => '排序',
                'default' => '0',
                'limit' => MysqlAdapter::INT_TINY,
                'null' => false,
            ])
            ->create();

        $this->table('mp_messages')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('title', 'string', [
                'comment' => '标题',
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('description', 'string', [
                'comment' => '描述',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('content', 'text', [
                'comment' => '内容',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('url', 'string', [
                'comment' => '链接地址',
                'default' => null,
                'limit' => 120,
                'null' => true,
            ])
            ->addColumn('media_url', 'string', [
                'comment' => '图片链接/其他资源链接',
                'default' => null,
                'limit' => 120,
                'null' => true,
            ])
            ->create();

        $this->table('mp_news')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('mp_id', 'integer', [
                'comment' => '公众号id',
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('media_id', 'string', [
                'comment' => '永久media_id',
                'default' => null,
                'limit' => 500,
                'null' => true,
            ])
            ->addColumn('title', 'text', [
                'comment' => '标题',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('type', 'integer', [
                'comment' => '1:文本,2:单图文',
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'comment' => '1:已群发，2:未群发',
                'default' => '2',
                'limit' => MysqlAdapter::INT_TINY,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('mp_news_lists')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('mp_news_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('title', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('author', 'string', [
                'comment' => '作者',
                'default' => null,
                'limit' => 120,
                'null' => true,
            ])
            ->addColumn('content_source_url', 'string', [
                'comment' => '原文链接',
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('digest', 'text', [
                'comment' => '描述',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('content', 'text', [
                'comment' => '内容',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('thumb_media_id', 'string', [
                'comment' => '媒体id',
                'default' => null,
                'limit' => 500,
                'null' => true,
            ])
            ->addColumn('thumb_media_path', 'string', [
                'comment' => '媒体路径',
                'default' => null,
                'limit' => 500,
                'null' => true,
            ])
            ->create();

        $this->table('mp_rules')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('mp_id', 'integer', [
                'comment' => '关联公众号',
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('mp_message_id', 'integer', [
                'comment' => '关联信息id',
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('keywords', 'string', [
                'comment' => '关键字',
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('type', 'string', [
                'comment' => '类型,text/video/...',
                'default' => null,
                'limit' => 24,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'comment' => '状态,1:开启,2:关闭',
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'keywords',
                ],
                ['unique' => true]
            )
            ->create();

        $this->table('mps')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'integer', [
                'comment' => '用户Id',
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('name', 'string', [
                'comment' => '公众号名称',
                'default' => null,
                'limit' => 32,
                'null' => false,
            ])
            ->addColumn('appid', 'string', [
                'comment' => 'appid',
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('secret', 'string', [
                'comment' => 'appsecret',
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('token', 'string', [
                'comment' => 'token',
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('origin_id', 'string', [
                'comment' => '公众号原始id',
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('type', 'integer', [
                'comment' => '公众号类型,1:订阅号,2:服务号',
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'comment' => '描述',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('qrcode', 'string', [
                'comment' => '二维码',
                'default' => null,
                'limit' => 80,
                'null' => true,
            ])
            ->create();

        $this->table('users')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('username', 'string', [
                'comment' => '用户名',
                'default' => null,
                'limit' => 32,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'comment' => '密码',
                'default' => null,
                'limit' => 64,
                'null' => false,
            ])
            ->addColumn('status', 'integer', [
                'comment' => '1:正常,2:冻结',
                'default' => '1',
                'limit' => MysqlAdapter::INT_TINY,
                'null' => false,
            ])
            ->addIndex(
                [
                    'username',
                ],
                ['unique' => true]
            )
            ->insert([
                'username' => 'admin',
                'password' => '$2y$10$v5bE3wc3AASZSK05CLUvf.hhjWxWEfXZGz.1LAVtNn/70n6DsVFOi',
                'status' => 1
            ])
            ->create();
    }

    public function down()
    {
        $this->table('miniapps')->drop()->save();
        $this->table('mp_events')->drop()->save();
        $this->table('mp_member_openid')->drop()->save();
        $this->table('mp_members')->drop()->save();
        $this->table('mp_menus')->drop()->save();
        $this->table('mp_messages')->drop()->save();
        $this->table('mp_news')->drop()->save();
        $this->table('mp_news_lists')->drop()->save();
        $this->table('mp_rules')->drop()->save();
        $this->table('mps')->drop()->save();
        $this->table('users')->drop()->save();
    }
}

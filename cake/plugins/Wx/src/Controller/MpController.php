<?php
/**
 * Created by PhpStorm.
 * User: jzaaa
 * Date: 2018/12/6
 * Time: 14:27
 */

namespace Wx\Controller;


use Cake\Filesystem\File;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\News;

class MpController extends AppController
{

    protected $config = [
        /**
         * Debug 模式，bool 值：true/false
         *
         * 当值为 false 时，所有的日志都不会记录
         */
        'debug' => true,
        /**
         * 账号基本信息，请从微信公众平台/开放平台获取
         */
        'app_id' => null,
        'secret' => null,
        'token' => null,
        'aes_key' => null, // 可选

        /**
         * 日志配置
         *
         * level: 日志级别, 可选为：
         *         debug/info/notice/warning/error/critical/alert/emergency
         * permission：日志文件权限(可选)，默认为null（若为null值,monolog会取0644）
         * file：日志文件位置(绝对路径!!!)，要求可写权限
         */
        'log' => [
            'level' => 'error',
            'file' => LOGS . 'wechat.log',
        ],

        /**
         * OAuth 配置
         *
         * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
         * callback：OAuth授权完成后的回调页地址
         */
        'oauth' => [
            'scopes' => ['snsapi_userinfo'],
            'callback' => '/admin/welcome/callauth'
        ]
    ];

    protected $mpId = null;

    protected $app;


    public function initialize()
    {
        parent::initialize();
    }

    /**
     * 公众号入口文件
     * @param null $id
     * @throws \EasyWeChat\Core\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Server\BadRequestException
     */
    public function index($id = null)
    {
        $data = TableRegistry::getTableLocator()->get('Api.Mps')->find()
            ->where([
                'id' => $id
            ])
            ->first();

        $this->mpId = $id;


        if (!empty($data)) {

            $this->config['app_id'] = $data['appid'];
            $this->config['secret'] = $data['secret'];
            $this->config['token'] = $data['token'];

            $app = new Application($this->config);

            $this->app = $app;

            $server = $app->server;

            $server->setMessageHandler(function ($message) {
                switch ($message->MsgType) {
                    case 'event':
                        return $this->replyEvent($message);
                        break;
                    case 'text':
                        return $this->replyMsg($message->Content);
                        break;
                    case 'image':
                        return $this->replyEvent('image');
                        break;
                    case 'voice':
                        return $this->replyEvent('voice');
                        break;
                    case 'video':
                        return $this->replyEvent('video');
                        break;
                    case 'location':
                        return $this->replyEvent('location');
                        break;
                    case 'link':
                        return $this->replyEvent('link');
                        break;
                    // ... 其它消息
                    default:
                        return null;
                        break;
                }
            });

            $response = $server->serve();

            $response->send();

        }

        exit;

    }


    /**
     * 消息分发
     * @param $type
     * @param null $mpMessage
     * @return Image|null
     */
    protected function messageFilter($type, $mpMessage = null)
    {
        switch ($type) {
            case 'text':
                //文字消息
                return $mpMessage['content'];
                break;
            case 'image':
                return $this->setImageMsg($mpMessage);
                break;
            default:
                return null;
                break;
        }
    }


    /**
     * 返回消息
     * @param null $keywords
     * @param null $mp_id
     * @return Image|null
     */
    protected function replyMsg($keywords = null)
    {

        $entity = TableRegistry::getTableLocator()->get('Api.MpRules');

        $data = $entity->find()
            ->contain([
                'MpMessages'
            ])
            ->where([
                'MpRules.keywords' => $keywords,
                'MpRules.mp_id' => $this->mpId,
                'MpRules.status' => 1
            ])
            ->first();

        if(!empty($data)) {
            return $this->messageFilter($data['type'], $data['mp_message']);
        }

        return null;

    }


    /**
     * 关注添加用户至数据库
     * @param $open_id
     * @return bool
     */
    protected function subsribeAction($open_id)
    {
        $app = $this->app;

        try {
            $info = $app->user->get($open_id)->toArray();
        } catch (\Exception $e) {
            $info = false;
        }

        if ($info) {
            return TableRegistry::getTableLocator()->get('Api.MpMembers')
                ->subscribe($info, $this->mpId);
        }
        return false;
    }

    /**
     * 取消关注数据库动作
     * @param $open_id
     * @return mixed
     */
    protected function unsubsribeAction($open_id)
    {
        return TableRegistry::getTableLocator()->get('Api.MpMembers')
            ->unsubscribe($open_id, $this->mpId);
    }

    /**
     * 事件回复
     * @param null|object $message
     * @param null $mp_id
     * @return Image|null
     */
    protected function replyEvent($message = null)
    {
        $event = $message->Event;
        $lowerEvent = strtolower($event);

        // 菜单点击事件
        if ($lowerEvent == 'click') {
            return $this->replyMsg($message->EventKey);
        } elseif ($lowerEvent == 'subscribe') {
            // 订阅
            $this->subsribeAction($message->FromUserName);
        } elseif ($lowerEvent == 'unsubscribe') {
            // 取消订阅
            $this->unsubsribeAction($message->FromUserName);
        }

        $entity = TableRegistry::getTableLocator()->get('Api.MpEvents');

        $data = $entity->find()
            ->select([
                'MpEvents.id'
            ])
            ->contain([
                'MpRules' => [
                    'conditions' => [
                        'MpRules.status' => 1
                    ],
                    'fields' => [
                        'id', 'mp_message_id', 'type'
                    ]
                ]
            ])
            ->where([
                'MpEvents.name' => $event,
                'MpEvents.mp_id' => $this->mpId,
                'MpEvents.status' => 1
            ])
            ->first();

        if (!empty($data)) {
            $message = TableRegistry::getTableLocator()->get('Api.MpMessages')->find()
                ->where([
                    'id' => $data['mp_rule']['mp_message_id']
                ])
                ->first();

            return $this->messageFilter($data['mp_rule']['type'], $message);
        }

        return null;

    }

    /**
     * 判断文件是否存在，存在返回true,否则返回false
     * @param $path
     * @return bool
     */
    private function validateFile($path)
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            $path = str_replace('/', '\\', $path);
        }
        $path = trim(trim($path, DS));
        $file = new File($path, false);

        return $file->exists();
    }

    /**
     * 创建图文消息
     * @param null $mpMessage
     * @return Image
     */
    private function setImageMsg($mpMessage = null)
    {
        if ($this->validateFile($mpMessage['media_url'])) {
            $url = filter_var($mpMessage['url'], FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) ? $mpMessage['url'] : null;
            return new News([
                'title' => $mpMessage['title'],
                'description' => $mpMessage['description'],
                'url' => $url,
                'image' => Router::url($mpMessage['media_url'], true)
            ]);
        }
        return null;
    }

}

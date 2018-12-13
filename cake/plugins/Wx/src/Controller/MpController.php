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
        'debug' => false,
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
            'level' => 'debug',
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


        if (!empty($data)) {

            $this->config['app_id'] = $data['appid'];
            $this->config['secret'] = $data['secret'];
            $this->config['token'] = $data['token'];

            $app = new Application($this->config);

            $server = $app->server;

            $server->setMessageHandler(function ($message) use ($id) {
                switch ($message->MsgType) {
                    case 'event':
                        return $this->replyEvent($message->Event, $id);
                        break;
                    case 'text':
                        return $this->replyMsg($message->Content, $id);
                        break;
                    case 'image':
                        return '收到图片消息';
                        break;
                    case 'voice':
                        return '收到语音消息';
                        break;
                    case 'video':
                        return '收到视频消息';
                        break;
                    case 'location':
                        return '收到坐标消息';
                        break;
                    case 'link':
                        return '收到链接消息';
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


    public function test()
    {
        debug($this->replyEvent('subscribe', 1));
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
    protected function replyMsg($keywords = null, $mp_id = null)
    {
        $entity = TableRegistry::getTableLocator()->get('Api.MpRules');

        $data = $entity->find()
            ->contain([
                'MpMessages'
            ])
            ->where([
                'MpRules.keywords' => $keywords,
                'MpRules.mp_id' => $mp_id,
                'MpRules.status' => 1
            ])
            ->first();

        if(!empty($data)) {
            return $this->messageFilter($data['type'], $data['mp_message']);
        }

        return null;

    }


    /**
     * 事件回复
     * @param null $name
     * @param null $mp_id
     * @return Image|null
     */
    protected function replyEvent($name = null, $mp_id = null)
    {

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
                'MpEvents.name' => $name,
                'MpEvents.mp_id' => $mp_id,
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
<?php
/**
 * 基于EasyWeChat 封装CakePHP 3.x 组件
 * Created by PhpStorm.
 * User: jzaaa
 * Date: 2018/11/20
 * Time: 14:24
 */

namespace App\Controller\Component;


use Cake\Controller\Component;
use Cake\Core\Exception\Exception;
use EasyWeChat\Foundation\Application;
use Symfony\Component\HttpFoundation\Response;

class WeChatComponent extends Component
{

    protected $_defaultConfig = [

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
        ],

    ];

    protected $_config = [];


    /**
     * @var Application
     */
    private static $_app;


    public function initialize(array $config = [])
    {
    }


    public function implementedEvents()
    {
        return [
//            'Controller.initialize' => 'checkOptions'
        ];
    }


    /**
     * 检查基本配置是否合法
     */
    public function checkOptions()
    {
        $required = [
            'app_id', 'secret', 'token'
        ];

        foreach ($required as $item) {
            if (empty($this->_config[$item])) {
                throw new Exception('微信 ' . $item . ' 未设置.');
            }
        }

    }


    /**
     * 获取初始化
     * @return Application
     */
    protected function _getApp()
    {
        if (!self::$_app instanceof Application) {
            self::$_app = new Application($this->_config);
        }

        return self::$_app;
    }


    /**
     * 返回初始化实例
     * @return Application
     */
    public function getApp()
    {
        return $this->_getApp();
    }


    /**
     * magic accessor
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (parent::__get($name) === null) {
            if ($this->_getApp()->$name) {
                return $this->_getApp()->$name;
            } else {
                throw new Exception('未找到该方法');
            }
        } else {
            return parent::__get($name);
        }

    }

    /**
     * @param Response $response
     */
    protected function _response(Response $response)
    {
        $response->send();

        die();
    }


    /**
     * 服务端验证
     * @throws \EasyWeChat\Server\BadRequestException
     */
    public function validator()
    {
        $response = $this->_getApp()->server->serve();

        $this->_response($response);
    }

    /**
     * 获取用户实例
     * @return \EasyWeChat\User\User
     */
    protected function _getUserService()
    {
        return $this->_getApp()->user;
    }


    /**
     * 获取用户信息
     * @param $openId array|string
     * @return \EasyWeChat\Support\Collection|null
     */
    public function getUser($openId)
    {
        if (!empty($openId)) {
            $userService = $this->_getUserService();
            if (is_array($openId)) {
                return $userService->batchGet($openId);
            }

            if (is_string($openId)) {
                return $userService->get($openId);
            }
        }

        return null;

    }


    /**
     * 获取用户列表
     * @param null|string $nextOpenId
     * @return \EasyWeChat\Support\Collection
     */
    public function getUserList($nextOpenId = null)
    {
        $userService = $this->_getUserService();

        return $userService->lists($nextOpenId);

    }

    /**
     * 获取菜单实例
     * @return \EasyWeChat\Menu\Menu
     */
    protected function _getMenu()
    {
        return $this->_getApp()->menu;
    }

    /**
     * 查询菜单
     * @param $type mixed 1为查询菜单，非1为自定义菜单
     * @return \EasyWeChat\Support\Collection
     */
    public function getMenus($type = 1)
    {
        if ($type === 1) {
            return $this->_getMenu()->all();
        }
        return $this->_getMenu()->current();
    }


    /**
     * 添加菜单
     * @param $button
     * @param array $matchRule
     * @return \EasyWeChat\Support\Collection
     */
    public function addMenu($button, $matchRule = [])
    {
        return $this->_getMenu()->add($button, $matchRule);

    }


    /**
     * 发送模板消息
     * @param $openID string
     * @param $tplID string 模板id
     * @param $data array 消息内容
     * @param null $url 跳转链接
     * @return \EasyWeChat\Support\Collection
     * @throws \EasyWeChat\Core\Exceptions\InvalidArgumentException
     */
    public function sendTpl($openID, $tplID, $data, $url = null)
    {
        return $this->_getApp()->notice->send([
            'touser' => $openID,
            'template_id' => $tplID,
            'url' => $url,
            'data' => $data
        ]);
    }

    /**
     * Oauth 网页授权
     */
    public function oauth()
    {
        $response = $this->_getApp()->oauth
            ->scopes($this->_config['oauth']['scopes'])
            ->redirect();

        $this->_response($response);
    }


    /**
     * 获取Oauth 授权结果用户信息
     * @return \Overtrue\Socialite\Providers\WeChatProvider|\Overtrue\Socialite\User
     */
    public function getOauthUser()
    {
        return $this->_getApp()->oauth->user();
    }



}
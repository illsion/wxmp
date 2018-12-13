<?php

namespace Api\Controller;

use App\Controller\ApiController as BaseController;
use Cake\Cache\Cache;
use Cake\Event\Event;

/**
 * Class AppController
 * @package Api\Controller
 * @property \App\Controller\Component\WeChatComponent $WeChat
 */
class AppController extends BaseController
{

    protected $TOKEN_NAME = 'token';
    protected $MP_NAME = 'mpInfo';

    public $MP_KEY = 'mp'; // 公众号标识
    public $MINI_KEY = 'mini'; // 小程序标识

    // 设置不需要token验证的controller
    protected $authController = [
    ];

    // 设置controller中允许不验证的action
    protected $authAllow = [
        'Users' => [
            'login'
        ]
    ];

    public function beforeRender(Event $event)
    {
        $this->apiResponse([], 300, null);
    }

    public function initialize()
    {
        parent::initialize();

        $this->setApiAuth();

        // 微信接口组件
        $weChatCache = $this->getMpCache();
        $this->loadComponent('WeChat', [
            'app_id' => $weChatCache['appid'],
            'secret' => $weChatCache['secret'],
            'token' => $weChatCache['token']
        ]);

    }


    /**
     * api 返回接口
     * @param $data
     * @param int $code
     * @param null $message
     * @param bool|string $tipType 返回方式，主要为分配前端的请求返回方式，各配置功能如下
     *
     * 可选：true|false|auto|no_err，默认 auto
     * true: 提示成功/错误消息，成功返回response,失败reject处理
     * false: 不提示消息，成功返回response,失败reject处理
     * auto: code=200 tipType设置为true,否则设置为false
     * no_err: code非200时生效, 不提示消息,失败返回response
     *
     */
    public function apiResponse($data, $code = 200, $message = null, $tipType = 'auto')
    {
        if (empty($message)) {
            switch ($code) {
                case 200:
                    $message = '操作成功';
                    break;
                case 300:
                    $message = '操作失败';
                    break;
                case 403:
                    $message = '无权限访问';
                    break;
                default:
                    $message = '未知错误';
            }
        }

        if ($tipType === 'auto') {
            if ($code == 200) {
                $tipType = false;
            } else {
                $tipType = true;
            }
        }

        return parent::apiResponse([
            'data' => $data,
            'code' => $code,
            'message' => $message,
            'tipType' => $tipType
        ]);
    }

    /**
     * 返回错误提示
     * @param $entity object 数据实体
     */
    public function setError($entity)
    {
        try{
            $message = current(current($entity->getErrors()));
        } catch (\Exception $e) {
            $message = null;
        }

        $this->apiResponse([], 300, $message);

    }


    /**
     * 获取token ,当前token保存在header中
     * @return mixed
     */
    public function getRequestToken()
    {
        $x_token = $this->getRequest()->getHeader('x-token');
        return !empty($x_token) ? $x_token[0] : null;
    }


    /**
     * 权限查看
     */
    protected function setApiAuth()
    {
        $controller = $this->getRequest()->getParam('controller');
        $action = $this->getRequest()->getParam('action');

        if (!in_array($controller, $this->authController)) {
            $allow = isset($this->authAllow[$controller]) ? $this->authAllow[$controller] : [];
            if (empty($allow) || !in_array($action, $allow)) {
                //验证是否登录
                $token = $this->getRequestToken();
                if (empty($token) || (is_null($this->getUserInfo($token)))) {
                    $this->apiResponse([], 403, '无权限访问');
                    exit;
                }

            }
        }

    }


    /**
     * 根据token获取用户信息
     * @param $token
     * @param $field
     * @return mixed
     */
    public function getUserInfo($token = null, $field = null)
    {
        if (empty($token)) {
            $token = $this->getRequestToken();
        }

        if (empty($token)) {
            return null;
        }

        $data = Cache::read($token, $this->TOKEN_NAME) ?: null;

        if (is_null($field)) {
            return $data;
        }
        return $data[$field];
    }


    public function setToken($token, $info) {
        Cache::write($token, $info, $this->TOKEN_NAME);
    }

    /**
     * 删除用户token
     * @param null $token
     */
    public function deleteToken($token = null)
    {
        if (empty($token)) {
            $token = $this->getRequestToken();
        }

        Cache::delete($token, $this->TOKEN_NAME);
    }


    /**
     * 分页初始化
     * @param int $limit
     * @return array
     */
    public function setPage($limit = 20)
    {
        $page = !empty($this->getRequest()->getData('page')) ? $this->getRequest()->getData('page') :
            (!empty($this->getRequest()->getQuery('page')) ? $this->getRequest()->getQuery('page') : 1);

        $limit = !empty($this->getRequest()->getData('limit')) ? $this->getRequest()->getData('limit') :
            (!empty($this->getRequest()->getQuery('limit')) ? $this->getRequest()->getQuery('limit') : $limit);

        $this->paginate['page'] = $page;
        $this->paginate['limit'] = $limit;

        return [
            'page' => $page,
            'limit' => $limit,
        ];
    }


    /**
     * 返回分页信息
     * @param $request object 请求实体
     * @param $entity string 分页 model
     * @param string $find 返回信息类型,默认为 总数量， null 则返回全部
     * @return mixed
     */
    public function getPageParams($request, $entity, $find = 'count')
    {
        $params = $request->params['paging'][$entity->alias()];

        if (empty($find)) {
            return $params;
        }

        return $params[$find];
    }


    /**
     * 检测是否为最后一页, 是则返回 true
     * @param $request
     * @param $entity
     * @return bool
     */
    public function checkPageEnd($request, $entity)
    {
        $data = $this->getPageParams($request, $entity, 'nextPage');

        if ($data == false) {
            return true;
        }

        return false;

    }

    /**
     * 设置公众号/小程序缓存
     * @param $data
     * @param $mpType string 公众号/小程序标识
     * @return bool
     */
    public function setMpCache($data, $mpType)
    {
        $token = $this->getRequestToken();

        $userInfo = $this->getUserInfo($token);

        if (is_null($userInfo)) {
            return false;
        }

        $data['mpType'] = $mpType;

        $userInfo[$this->MP_NAME] = $data;

        return Cache::write($token, $userInfo, $this->TOKEN_NAME);

    }

    /**
     * 获取公众号/小程序缓存
     * @param $field string|null
     * @return array|null
     */
    public function getMpCache($field = null)
    {
        $token = $this->getRequestToken();

        if (is_null($token)) {
            return null;
        }

        $data = Cache::read($token, $this->TOKEN_NAME) ?: null;

        $mpData = empty($data[$this->MP_NAME]) ? null : $data[$this->MP_NAME];

        return is_null($field) ? $mpData : $mpData[$field];
    }



}

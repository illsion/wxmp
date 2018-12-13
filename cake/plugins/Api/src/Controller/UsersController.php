<?php
namespace Api\Controller;

use Api\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Utility\Security;

/**
 * Users Controller
 *
 * @property \Api\Model\Table\UsersTable $Users
 *
 * @method \Api\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    /**
     * 用户登录
     */
    public function login()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $tableData = $this->Users->find()
                ->where([
                    'username' => $data['username']
                ])
                ->first();


            if (!empty($tableData)) {
                $res = (new DefaultPasswordHasher())->check($data['password'], $tableData['password']);

                if ($res) {
                    if ($tableData['status'] == 1) {
                        $token = Security::hash(Security::randomBytes(32), 'sha256', false);

                        $user = $tableData->toArray();

                        $this->setToken($token, $user);

                        $this->apiResponse([
                            'token' => $token,
                            'user' => $user
                        ]);

                    }

                    $this->apiResponse([], 300, '该账号已被禁用');

                }

            }

            $this->apiResponse([], 300, '用户名密码错误');

        }
    }

    /**
     * 登出
     */
    public function logout()
    {
        $this->deleteToken();

        $this->apiResponse([]);
    }

    /**
     * 获取用户信息
     */
    public function info()
    {
        $userInfo = $this->getUserInfo();

        if ($userInfo) {
            $this->apiResponse($userInfo);
        }

    }


    /**
     * 用户列表
     */
    public function getList()
    {
        if ($this->request->is('post')) {
            $pageData = $this->setPage();

            $query = $this->Users->find();
            $status = $this->Users->getStatus();

            $data = $this->paginate($query)->each(function ($value) use ($status) {
                $value['statusText'] = $status[$value['status']];
            });

            $count = $this->getPageParams($this->request, $this->Users);
            $pageData['count'] = $count;

            $this->apiResponse([
                'items' => $data,
                'statusIndex' => $status,
                'pageData' => $pageData
            ]);
        }
    }


    /**
     * 更新user
     */
    public function update()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (!empty($data['id'])) {
                // 编辑
                $beforeData = $this->Users->find()
                    ->where([
                        'id' => $data['id']
                    ])
                    ->first();

                if (empty($data['password'])) {
                    unset($data['password']);
                }

                $data = $this->Users->patchEntity($beforeData, $data);

            } else {
                // 添加
                $entity = $this->Users->newEntity();
                $data = $this->Users->patchEntity($entity, $data);
            }

            if ($this->Users->save($data)) {
                $this->apiResponse($data);
            }

            $this->setError($data);
        }
    }




}

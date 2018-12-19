<?php
namespace Api\Controller;

use Api\Controller\AppController;
use Cake\Routing\Router;

/**
 * 微信公众号
 * Mps Controller
 *
 * @property \Api\Model\Table\MpsTable $Mps
 *
 * @method \Api\Model\Entity\Mp[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MpsController extends AppController
{
    protected $userId = null;

    public function initialize()
    {
        parent::initialize();
        $this->userId = $this->getUserInfo(null, 'id');
    }

    /**
     * 获取公众号列表
     */
    public function getList()
    {
        if ($this->request->is('post')) {


            $pageData = $this->setPage();

            $type = $this->Mps->getType();

            $query = $this->Mps->find()
                ->where([
                    'user_id' => $this->userId
                ]);


            $data = $this->paginate($query)
                ->each(function ($value) use ($type) {
                    $value['created'] = date('Y-m-d H:i:s', strtotime($value['created']));
                    $value['qrcode'] = !empty($value['qrcode']) ? Router::url($value['qrcode'], true) : null;
                    $value['typeText'] = !empty($type[$value['type']]) ? $type[$value['type']] : '未知';
                    $value['url'] = Router::url(['plugin' => 'Wx', 'controller' => 'Mp', 'action' => 'index', $value['id']], true);
                });

            $pageData['count'] = $this->getPageParams($this->request, $this->Mps);

            $this->apiResponse([
                'items' => $data,
                'typeIndex' => $type,
                'pageData' => $pageData
            ]);
        }
    }


    /**
     * 添加/编辑公众号
     */
    public function update()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (!empty($data['id'])) {
                // 编辑

                $beforeData = $this->Mps->find()
                    ->where([
                        'id' => $data['id'],
                        'user_id' => $this->userId
                    ])
                    ->first();

                $data = $this->Mps->patchEntity($beforeData, $data);
            } else {
                // 添加
                $data['user_id'] = $this->userId;

                $entity = $this->Mps->newEntity();
                $data = $this->Mps->patchEntity($entity, $data);

            }

            if ($this->Mps->save($data)) {
                $this->apiResponse($data);
            }

            $this->setError($data);
        }
    }


    /**
     * 删除
     */
    public function delete()
    {
        if ($this->request->is('post')) {
            $id = $this->request->getData('id');

            $data = $this->Mps->find()
                ->where([
                    'id' => $id,
                    'user_id' => $this->userId
                ])
                ->first();

            if ($this->Mps->delete($data)) {
                $this->apiResponse([]);
            }
        }
    }

    /**
     * 验证公众号属于当前用户
     */
    public function validateMp()
    {
        if ($this->request->is('post')) {
            $res = [
                'result' => false,
            ];

            $data = $this->request->getData();

            if (!empty($data['id'])) {
                if ($data['type'] == $this->MP_KEY) {
                    // 公众号验证
                    $mpData =  $this->Mps->validateMp($data['id'], $this->userId, false);

                    $res['result'] = !empty($mpData);

                    // 验证成功
                    if ($res['result']) {
                        // 设置微信公众号缓存
                        $this->setMpCache($mpData, $this->MP_KEY);
                    }


                }
            }

            $this->apiResponse($res, $res['result']? 200 : 300, null, false);

        }
    }



}

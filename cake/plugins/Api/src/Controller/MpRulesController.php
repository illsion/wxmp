<?php
namespace Api\Controller;

use Api\Controller\AppController;
use Cake\Routing\Router;

/**
 * MpRules Controller
 *
 * @property \Api\Model\Table\MpRulesTable $MpRules
 *
 * @method \Api\Model\Entity\MpRule[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MpRulesController extends AppController
{

    protected $mpId = null;

    public function initialize()
    {
        parent::initialize();

        $this->mpId = $this->getMpCache('id');
    }

    /**
     * 获取规则列表
     */
    public function getList()
    {
        if ($this->request->is('post')) {

            $conditions = [];

            $conditions['MpRules.mp_id'] = $this->mpId;

            if (!empty($this->request->getData('type'))) {
                $conditions['MpRules.type'] = $this->request->getData('type');
            }

            $status = $this->MpRules->getStatus();
            $type = $this->MpRules->getType();

            $fields = $this->MpRules->getTypeFields();

            $pageData = $this->setPage();

            $query = $this->MpRules->find()
                ->contain([
                    'MpMessages'
                ])
                ->where($conditions);

            $data = $this->paginate($query)
                ->each(function($value) use ($status, $type) {
                    $value['statusText'] = $status[$value['status']];
                    $value['typeText'] = $type[$value['type']];
                    $value['mp_message']['full_media_url'] = empty($value['mp_message']['media_url']) ? '' : Router::url($value['mp_message']['media_url'], true);
                });

            $pageData['count'] = $this->getPageParams($this->request, $this->MpRules);

            $this->apiResponse([
                'items' => $data,
                'typeIndex' => $type,
                'statusIndex' => $status,
                'pageData' => $pageData,
                'fields' => $fields
            ]);

        }

    }


    /**
     * 获取指定规则
     */
    public function getItem()
    {
        if ($this->request->is('post')) {
            $id = $this->request->getData('id');

            $data = $this->MpRules->find()
                ->contain([
                    'MpMessages'
                ])
                ->where([
                    'MpRules.id' => $id,
                    'MpRules.mp_id' => $this->mpId
                ])
                ->first();

            $this->apiResponse($data);
        }
    }

    /**
     * 更新/添加规则
     */
    public function update()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (!empty($data['id'])) {
                // 编辑

                $beforeData = $this->MpRules->find()
                    ->contain([
                        'MpMessages'
                    ])
                    ->where([
                        'MpRules.id' => $data['id'],
                        'MpRules.mp_id' => $this->mpId
                    ])
                    ->first();

                $data = $this->MpRules->patchEntity($beforeData, $data, [
                    'associated' => [
                        'MpMessages'
                    ]
                ]);
            } else {
                // 添加
                $data['mp_id'] = $this->mpId;

                $data = $this->MpRules->newEntity($data, [
                    'associated' => [
                        'MpMessages'
                    ]
                ]);

            }

            if ($this->MpRules->save($data)) {
                $this->apiResponse($data);
            }

            $this->setError($data);
        }
    }


    /**
     * 删除,关联回复消息删除
     */
    public function delete()
    {
        if ($this->request->is('post')) {
            $id = $this->request->getData('id');

            $data = $this->MpRules->find()
                ->where([
                    'id' => $id,
                    'mp_id' => $this->mpId
                ])
                ->first();

            if ($this->MpRules->delete($data)) {
                $this->apiResponse([]);
            }
        }
    }
}

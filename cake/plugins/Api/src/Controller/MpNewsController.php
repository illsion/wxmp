<?php
namespace Api\Controller;

use Api\Controller\AppController;

/**
 * MpNews Controller
 *
 * @property \Api\Model\Table\MpNewsTable $MpNews
 *
 * @method \Api\Model\Entity\MpNews[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MpNewsController extends AppController
{
    public $mpId = null;
    
    public function initialize()
    {
        parent::initialize();

        $this->mpId = $this->getMpCache('id');
    }

    /**
     * 获取群发消息列表
     */
    public function getList()
    {
        if ($this->request->is('post')) {

            $pageData = $this->setPage();

            $query = $this->MpNews->find()
                ->contain([
                    'MpNewsLists'
                ])
                ->where([
                    'mp_id' => $this->mpId
                ]);

            $data = $this->paginate($query)->each(function ($value) {
                $value['status'] = ($value['status'] == 1) ? '已群发' : '未群发';
            });

            $count = $this->getPageParams($this->request, $this->MpNews);
            $pageData['count'] = $count;

            $this->apiResponse([
                'items' => $data,
                'pageData' => $pageData
            ]);
        }
    }


    /**
     * 更新与新增
     */
    public function update()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (!empty($data['id'])) {
                // 编辑
                $beforeData = $this->MpNews->find()
                    ->where([
                        'id' => $data['id'],
                        'mp_id' => $this->mpId
                    ])
                    ->first();

                $data = $this->MpNews->patchEntity($beforeData, $data);
            } else {
                // 添加
                $data['mp_id'] = $this->mpId;

                $entity = $this->MpNews->newEntity();
                $data = $this->MpNews->patchEntity($entity, $data);

            }

            if ($this->MpNews->save($data)) {
                $this->apiResponse($data);
            }

            $this->setError($data);
        }
    }


    /**
     * 删除图文
     */
    public function delete()
    {
        if ($this->request->is('post')) {
            $id = $this->request->getData('id');

            $data = $this->MpNews->find()
                ->where([
                    'id' => $id,
                    'mp_id' => $this->mpId
                ])
                ->first();

            if ($this->MpNews->delete($data)) {
                $this->apiResponse([]);
            }
        }

    }


    /**
     * 群发消息
     */
    public function send()
    {

        if ($this->request->is('post')) {

            $id = $this->request->getData('id');

            $data = $this->MpNews->find()
                ->contain([
                    'MpNewsLists'
                ])
                ->where([
                    'MpNews.id' => $id,
                    'MpNews.mp_id' => $this->mpId
                ])
                ->first();

            if (!empty($data)) {
                switch ($data['type']) {
                    case 1:
                        $res = $this->sendText($data['title']);
                        break;
                    case 2:
                        $res = $this->sendNews($data);
                        break;
                    default:
                        $res = null;
                        break;
                }

                if ($res['errcode'] == 0) {
                    unset($data['mp_news_lists']);
                    $data = $this->MpNews->patchEntity($data, [
                        'id' => $data['id'],
                        'status' => 1
                    ]);
                    $this->MpNews->save($data);
                    $this->apiResponse([], 200, '群发成功！', true);
                } elseif (!is_null($res['errcode'])) {
                    $this->apiResponse([], 300, $res['errmsg']);
                }
            }

        }

    }


    /**
     * 发送文本消息
     */
    protected function sendText($text)
    {
        $app = $this->WeChat->getApp();
        $broadcast = $app->broadcast;



        try {
            $res = $broadcast->send($broadcast::MSG_TYPE_TEXT, $text);
        } catch (\Exception $e) {
            $res = [
                'errcode' => -1,
                'errmsg' => '群发失败,请检查是否重复发送消息'
            ];
        }

        return $res;


    }


    /**
     * 发送图文消息
     */
    protected function sendNews($data)
    {
        $app = $this->WeChat->getApp();
        $broadcast = $app->broadcast;

        return $broadcast->send($broadcast::MSG_TYPE_NEWS, $data['title']);
    }


}

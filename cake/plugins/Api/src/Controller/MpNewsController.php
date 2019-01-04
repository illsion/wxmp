<?php
namespace Api\Controller;

use Api\Controller\AppController;
use Cake\Routing\Router;
use EasyWeChat\Message\Article;

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

                foreach ($value['mp_news_lists'] as $key => $item) {
                    if (!empty($item['thumb_media_path'])) {
                        $value['mp_news_lists'][$key]['thumb_media_path_full'] = Router::url($item['thumb_media_path'], true);
                    }
                }
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
                    ->contain([
                        'MpNewsLists'
                    ])
                    ->where([
                        'MpNews.id' => $data['id'],
                        'MpNews.mp_id' => $this->mpId
                    ])
                    ->first();

                $data = $this->MpNews->patchEntity($beforeData, $data, [
                    'associated' => [
                        'MpNewsLists' => [
                            'accessibleFields' => ['id' => true]
                        ]
                    ]
                ]);
            } else {
                // 添加
                $data['mp_id'] = $this->mpId;

                $data = $this->MpNews->newEntity($data, [
                    'associated' => ['MpNewsLists']
                ]);
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

                if ($res['errcode'] == 0 || isset($res['media_id'])) {

                    $newData = [
                        'id' => $data['id']
                    ];
                    if ($res['errcode'] == 0) {
                        $newData['status'] = 1;
                    }
                    if (isset($res['media_id']) && !empty($res['media_id'])) {
                        $newData['media_id'] = $res['media_id'];
                    }
                    unset($data['mp_news_lists']);
                    $data = $this->MpNews->patchEntity($data, $newData);
                    $this->MpNews->save($data);

                    if ($res['errcode'] == 0) {
                        $this->apiResponse($res, 200, '群发成功！', true);
                    }
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
     * @param $data array
     * @return array|\EasyWeChat\Support\Collection|mixed
     */
    protected function sendNews($data)
    {
        $app = $this->WeChat->getApp();

        $media_id = null;

        $res = [
            'errcode' => -1,
            'errmsg' => '群发失败,请检查是否重复发送消息'
        ];

        if (empty($data['media_id'])) {
            $article = [];

            foreach ($data['mp_news_lists'] as $item) {

                if (!empty($item)) {
                    $article[] = new Article([
                        'title' => $item['title'],
                        'thumb_media_id' => $item['thumb_media_id'],
                        'author' => $item['author'],
                        'content' => $item['content'],
                        'content_source_url' => $item['content_source_url'],
                        'digest' => $item['digest']
                    ]);
                }

            }


            if (!empty($article)) {
                $material = $app->material;

                try {
                    $res = $material->uploadArticle($article);
                    $media_id = $res['media_id'];
                } catch (\Exception $e) {
                }
            }
        } else {
            $res['media_id'] = $data['media_id'];
        }



        if (isset($res['media_id'])) {
            $broadcast = $app->broadcast;
            try {
                $res = $broadcast->send($broadcast::MSG_TYPE_NEWS, $res['media_id']);
            } catch (\Exception $e) {
            }

        }

        $res['media_id'] = $media_id;

        return $res;

    }


    public function test()
    {
        $app = $this->WeChat->getApp();

        $material = $app->material;

        $data = $material->get('sK7eg7d5_1hBwTN3g4EP7oeJW8TTg1jRsUK3uvbAtQA');
        $this->apiResponse($data);
    }


}

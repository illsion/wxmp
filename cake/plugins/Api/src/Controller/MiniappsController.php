<?php
namespace Api\Controller;

use Api\Controller\AppController;

/**
 * Miniapps Controller
 *
 * @property \Api\Model\Table\MiniappsTable $Miniapps
 *
 * @method \Api\Model\Entity\Miniapp[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MiniappsController extends AppController
{
    protected $userId = null;

    public function initialize()
    {
        parent::initialize();

        $this->userId = $this->getUserInfo(null, 'id');

    }


    /**
     * 获取小程序列表
     */
    public function getList()
    {
        if ($this->request->is('post')) {

            $pageData = $this->setPage();

            $query = $this->Miniapps->find()
                ->where([
                    'user_id' => $this->userId
                ]);


            $data = $this->paginate($query)
                ->each(function ($value) {
                    $value['created'] = date('Y-m-d H:i:s', strtotime($value['created']));
                });

            $pageData['count'] = $this->getPageParams($this->request, $this->Miniapps);

            $this->apiResponse([
                'items' => $data,
                'pageData' => $pageData
            ]);
        }


    }

    /**
     * 编辑/新增
     */
    public function update()
    {

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (!empty($data['id'])) {
                // 编辑

                $beforeData = $this->Miniapps->find()
                    ->where([
                        'id' => $data['id'],
                        'user_id' => $this->userId
                    ])
                    ->first();

                $data = $this->Miniapps->patchEntity($beforeData, $data);
            } else {
                // 添加
                $data['user_id'] = $this->userId;

                $entity = $this->Miniapps->newEntity();
                $data = $this->Miniapps->patchEntity($entity, $data);

            }

            if ($this->Miniapps->save($data)) {
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

            $data = $this->Miniapps->find()
                ->where([
                    'id' => $id,
                    'user_id' => $this->userId
                ])
                ->first();

            if ($this->Miniapps->delete($data)) {
                $this->apiResponse([]);
            }
        }

    }


    /**
     * 查看统计信息
     * @todo 暂为固定日期动态获取统计数据，后期变为可选日期写入数据库查询
     */
    public function getStats()
    {
        if ($this->request->is('post')) {
            $id = $this->request->getData('id');

            $data = $this->Miniapps->find()
                ->where([
                    'id' => $id,
                    'user_id' => $this->userId
                ])
                ->first();

            if (!empty($data)) {
                $app = $this->WeChat->getApp();

                $app['config']->set('mini_program', [
                    'app_id' => $data['appid'],
                    'secret' => $data['secret'],
                    'token' => $data['token'],
                    'aes_key' => null
                ]);

                $stats = $app->mini_program->stats;

//                $today = date('Ymd', time());
                $yesterday = date('Ymd', strtotime("-1 day"));

                $monday = date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
                $sunday = date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600));

                try {
                    $summary = $stats->summaryTrend($yesterday, $yesterday); // 昨日概况趋势
                    $dailyVisit = $stats->dailyVisitTrend($yesterday, $yesterday); // 昨日访问日趋势
                    $weeklyVisit = $stats->weeklyVisitTrend($monday, $sunday); // 本周访问周趋势
                    $visit = $stats->visitDistribution($yesterday, $yesterday); // 昨日访问分布
                    $retain = $stats->dailyRetainInfo($yesterday, $yesterday); // 昨日访问日留存
                } catch (\Exception $e) {
                    $summary = $dailyVisit = $weeklyVisit = $visit = $retain = false;
                }

                if ($summary !== false) {
                    $this->apiResponse(compact('summary', 'dailyVisit', 'weeklyVisit', 'visit', 'retain'));
                } else {
                    $this->apiResponse([], 300, '获取信息失败，请检查配置项是否正确！');
                }

            }
        }

    }

}

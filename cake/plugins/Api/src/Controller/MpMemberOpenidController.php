<?php
namespace Api\Controller;

use Api\Controller\AppController;
use Cake\Collection\Collection;
use Cake\ORM\TableRegistry;

/**
 * MpMemberOpenid Controller
 *
 * @property \Api\Model\Table\MpMemberOpenidTable $MpMemberOpenid
 *
 * @method \Api\Model\Entity\MpMemberOpenid[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MpMemberOpenidController extends AppController
{

    protected $mpId = null;

    public function initialize()
    {
        parent::initialize();

        $this->mpId = $this->getMpCache('id');

    }

    /**
     * 获取关注用户
     */
    public function getList()
    {
        if ($this->request->is('post')) {
            // 由于用户为动态调用接口，所以每页数量增大
            $pageData = $this->setPage(50);

            $query = $this->MpMemberOpenid->find()
                ->where([
                    'mp_id' => $this->mpId
                ]);

            $data = $this->paginate($query);

            $openids = $data->map(function ($value) {
                return $value['openid'];
            })->toList();

            $userInfo = $this->WeChat->getUser($openids);

            $count = $this->getPageParams($this->request, $this->MpMemberOpenid);
            $pageData['count'] = $count;

            $this->apiResponse([
                'items' => $userInfo['user_info_list'],
                'pageData' => $pageData
            ]);
        }

    }


    /**
     * 同步关注公众号openid
     */
    public function synchronize()
    {
        if ($this->request->is('post')) {
            ini_set('max_execution_time', '0');

            $this->MpMemberOpenid->deleteAll(
                ['mp_id' => $this->mpId]
            );
            
            $res = $this->setData();
            
            $this->apiResponse([], $res ? 200 : 300, '同步失败！');
        }

    }

    public function test()
    {
        $this->mpId = 1;
        $this->getList();
        exit;
    }


    /**
     * 获取openid并同步数据库
     * @param null $next
     * @return bool
     * @throws \Exception
     */
    protected function setData($next = null)
    {

        try {
            $data = $this->WeChat->getUserList($next);
        } catch (\Exception $e) {
            $data = false;
        }

        if ($data !== false) {

            if (empty($data['data'])) {
                $this->apiResponse([], 300, '该公众号无关注用户!');
            }

            $collection = new Collection($data['data']['openid']);

            $rowData = $collection->map(function ($value) {
                return [
                    'mp_id' => $this->mpId,
                    'openid' => $value
                ];
            })
                ->toList();

            $entity = $this->MpMemberOpenid->newEntities($rowData);

            if ($this->MpMemberOpenid->saveMany($entity)) {
                if ($data['count'] < 10000) {
                    return true;
                } else {
                    $this->setData($data['next_openid']);
                }
            }
            
        }
        return false;
    }
}

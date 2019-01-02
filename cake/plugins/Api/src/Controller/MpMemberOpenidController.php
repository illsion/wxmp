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
            
            $this->apiResponse([], $res ? 200 : 300);
        }

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
                if (!$this->setMembers($data['data']['openid'])) {
                    return false;
                }
                if ($data['count'] < 10000) {
                    return true;
                } else {
                    $this->setData($data['next_openid']);
                }
            }
            
        }
        return false;
    }


    /**
     * 用户信息同步
     * @param array $openid
     * @return bool
     */
    protected function setMembers($openid = [])
    {
        try {
            $info = $this->WeChat->getUser($openid)->toArray();
        }catch (\Exception $e) {
            $info = null;
        }

        if (!empty($info['user_info_list'])) {
            return TableRegistry::getTableLocator()->get('Api.MpMembers')
                ->synchronizeData($info['user_info_list'], $this->mpId);
        }

        return false;
    }
}

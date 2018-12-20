<?php
namespace Api\Controller;

use Api\Controller\AppController;

/**
 * MpMembers Controller
 *
 * @property \Api\Model\Table\MpMembersTable $MpMembers
 *
 * @method \Api\Model\Entity\MpMember[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MpMembersController extends AppController
{

    protected $mpId = null;
    public function initialize()
    {
        parent::initialize();

        $this->mpId = $this->getMpCache('id');
    }

    /**
     * 获取关注用户列表
     */
    public function getList()
    {
        if ($this->request->is('post')) {
            $pageData = $this->setPage();

            $query = $this->MpMembers->find()
                    ->where([
                        'mp_id' => $this->mpId,
                        'subscribe' => 1
                    ]);

            $data = $this->paginate($query)->each(function ($value) {
                if (!empty($value['subscribe_time'])) {
                    $value['subscribe_time'] = date('Y-m-d H:i:s', $value['subscribe_time']);
                }

                if (!empty($value['unsubscribe_time'])) {
                    $value['unsubscribe_time'] = date('Y-m-d H:i:s', $value['unsubscribe_time']);
                }
            });

            $count = $this->getPageParams($this->request, $this->MpMembers);
            $pageData['count'] = $count;

            $this->apiResponse([
                'items' => $data,
                'pageData' => $pageData
            ]);
        }
    }
}

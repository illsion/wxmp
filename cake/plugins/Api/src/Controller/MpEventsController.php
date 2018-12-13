<?php
namespace Api\Controller;

use Api\Controller\AppController;

/**
 * MpEvents Controller
 *
 * @property \Api\Model\Table\MpEventsTable $MpEvents
 *
 * @method \Api\Model\Entity\MpEvent[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MpEventsController extends AppController
{

    protected $mpId = null;


    public function initialize()
    {
        parent::initialize();

        $this->mpId = $this->getMpCache('id');
    }


    public function getList()
    {
        if ($this->request->is('post')) {
            $name = $this->MpEvents->getName();

            $status = $this->MpEvents->getStatus();

            $conditions = [];

            $conditions['MpEvents.mp_id'] = $this->mpId;

            $data = $this->MpEvents->find()
                ->contain([
                    'MpRules' => [
                        'fields' => [
                            'id', 'keywords'
                        ]
                    ]
                ])
                ->where($conditions)
                ->all()
                ->each(function($value) {
                    $value['mp_rule_keywords'] = $value['mp_rule']['keywords'];
                    unset($value['mp_rule']);
                })
                ->indexBy('name')
                ->toArray();


            $this->apiResponse([
                'items' => $data,
                'names' => $name,
                'statusIndex' => $status
            ]);
        }


    }


    /**
     * 规则编辑/新增
     */
    public function update()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // 必须存在name值
            if (!empty($data['name'])) {

                $beforeData = $this->MpEvents->find()
                    ->where([
                        'name' => $data['name'],
                        'mp_id' => $this->mpId
                    ])
                    ->first();

                $keywords = $data['mp_rule_keywords'];

                if (!empty($beforeData)) {
                    // 编辑

                    $data['id'] = $beforeData['id'];

                    $data['mp_rule_id'] = $this->MpEvents->getRuleId($this->mpId, $data['mp_rule_keywords']);

                    $data = $this->MpEvents->patchEntity($beforeData, $data);
                } else {
                    // 添加
                    $data['mp_id'] = $this->mpId;

                    $data['mp_rule_id'] = $this->MpEvents->getRuleId($this->mpId, $data['mp_rule_keywords']);

                    $entity = $this->MpEvents->newEntity();

                    $data = $this->MpEvents->patchEntity($entity, $data);
                }

                if ($this->MpEvents->save($data)) {
                    $data['mp_rule_keywords'] = $keywords;
                    $this->apiResponse($data);
                }

                $this->setError($data);
            }
        }
    }


}

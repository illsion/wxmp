<?php
namespace Api\Controller;

use Api\Controller\AppController;

/**
 * MpMenus Controller
 *
 * @property \Api\Model\Table\MpMenusTable $MpMenus
 *
 * @method \Api\Model\Entity\MpMenu[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MpMenusController extends AppController
{

    protected $mpId = null;

    public function initialize()
    {
        parent::initialize();

        $this->mpId = $this->getMpCache('id');

    }

    /**
     * 获取指定菜单
     */
    public function getMenuItem()
    {
        if ($this->request->is('post')) {
            $id = $this->request->getData('id');
            $data = [];

            if (!empty($id)) {
                $data = $this->MpMenus->find()
                    ->where([
                        'id' => $id,
                        'mp_id' => $this->mpId
                    ])
                    ->first();
                $data['content'] = $this->MpMenus->decodeContent($data['content']);
            }

            $this->apiResponse($data);

        }
    }


    /**
     * 获取所有菜单
     */
    public function getMenus()
    {

        if ($this->request->is('post')) {
            $data = $this->MpMenus->find('threaded')
                ->where([
                    'mp_id' => $this->mpId
                ])
                ->order([
                    'sort' => 'desc'
                ])
                ->each(function ($value) {
                    if (!empty($value['content'])) {
                        $value['content'] = json_decode($value['content'], true);
                    }

                    if (!empty($value['children'])) {
                        foreach ($value['children'] as $key => $item) {
                            if (!empty($item['content'])) {
                                $value['children'][$key]['content'] = json_decode($item['content'], true);
                            }
                        }
                    }
                })
                ->toArray();

            $this->apiResponse($data);
        }

    }


    /**
     * 同步菜单
     */
    public function synchronize()
    {
        if ($this->request->is('post')) {
            $data = $this->WeChat->getMenus();

            $menu = $data['menu']['button'];

            $saveData = [];

            foreach ($menu as $key => $item) {

                $saveData[$key] = [
                    'name' => $item['name'],
                    'mp_id' => $this->mpId
                ];

                if (empty($item['sub_button'])) {
                    // 无二级菜单
                    $saveData[$key]['type'] = $item['type'];
                    $saveData[$key]['content'] = $this->MpMenus->encodeContent($item);
                } else {
                    // 有二级菜单

                    foreach ($item['sub_button'] as $value) {
                        $saveData[$key]['child_mp_menus'][] = [
                            'mp_id' => $this->mpId,
                            'name' => $value['name'],
                            'type' => $value['type'],
                            'content' => $this->MpMenus->encodeContent($value)
                        ];
                    }
                }

            }

            $this->MpMenus->deleteAll([
                'mp_id' => $this->mpId
            ]);

            $saveData = $this->MpMenus->newEntities($saveData, [
                'associated' => ['ChildMpMenus']
            ]);


            if ($this->MpMenus->saveMany($saveData)) {
                $this->apiResponse($saveData);
            }

        }
    }


    /**
     * 更新/添加菜单
     */
    public function update()
    {
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $content = null;

            foreach ($data['content'] as $key => $item) {
                if (!empty($item)) {
                    $content[$key] = $item;
                }
            }

            if (!is_null($content)) {
                $data['content'] = json_encode($content);
            } else {
                $data['content'] = $content;
            }

            if (!empty($data['id'])) {
                // 编辑

                $beforeData = $this->MpMenus->find()
                    ->where([
                        'id' => $data['id'],
                        'mp_id' => $this->mpId
                    ])
                    ->first();

                $data = $this->MpMenus->patchEntity($beforeData, $data);
            } else {
                // 添加
                if (empty($data['parent_id'])) {
                    unset($data['parent_id']);
                }
                $data['mp_id'] = $this->mpId;

                $entity = $this->MpMenus->newEntity();
                $data = $this->MpMenus->patchEntity($entity, $data);

            }

            if ($this->MpMenus->save($data)) {
                $this->apiResponse($data);
            }

            $this->setError($data);

        }
    }


    /**
     * 删除菜单,同时会删除子菜单
     */
    public function delete()
    {
        if ($this->request->is('post')) {
            $id = $this->request->getData('id');

            $data = $this->MpMenus->find()
                ->where([
                    'id' => $id,
                    'mp_id' => $this->mpId
                ])
                ->first();

            if ($this->MpMenus->delete($data)) {
                $this->apiResponse([]);
            }
        }

    }


    /**
     * 发布菜单
     */
    public function releaseMenus()
    {
        if ($this->request->is('post')) {

            $data = $this->MpMenus->find('threaded')
                ->where([
                    'mp_id' => $this->mpId
                ])
                ->order([
                    'sort' => 'desc'
                ])
                ->map(function ($value) {
                    $arr = [
                        'name' => $value['name']
                    ];

                    $children = [];


                    if (!empty($value['children'])) {
                        foreach ($value['children'] as $key => $item) {
                            if (!empty($item['content'])) {
                                $children[] = array_merge([
                                    'type' => $item['type'],
                                    'name' => $item['name']
                                ], json_decode($item['content'], true));
                            } else {
                                $children[] = [
                                    'type' => $item['type'],
                                    'name' => $item['name']
                                ];
                            }
                        }

                        $arr['sub_button'] = $children;
                    } else {
                        $arr['type'] = $value['type'];
                        if (!empty($value['content'])) {
                            $arr = array_merge($arr, json_decode($value['content'], true));
                        }
                    }

                    return $arr;
                })
                ->toArray();

            $res = true;
            try {
                $this->WeChat->addMenu($data);
            } catch (\Exception $e) {
                $res = false;
            }

            $this->apiResponse([], $res ? 200 : 300);

        }
    }






}

<?php
/**
 * Created by PhpStorm.
 * User: jzaaa
 * Date: 2018/12/4
 * Time: 15:20
 */

namespace Api\Controller;


use App\Lib\FileUpload;
use Cake\Filesystem\Folder;
use Cake\Routing\Router;

class UploadController extends AppController
{

    // media_temp为临时素材,article为上传图文消息内图片
    private $typeArray = [ 'media_temp', 'article'];

    /**
     * 图片上传
     */
    public function uploadImg()
    {
        if ($this->request->is('post') && !empty($this->request->getData('file'))) {
            $data = $this->comm('files/');

            if ($data) {
                $this->apiResponse($data);
            }
        }
    }


    /**
     * 公共方法
     * @param $filePath
     * @return array|bool
     */
    protected function comm($filePath)
    {
        $folder = new Folder(WWW_ROOT);
        if (!is_dir($filePath)) {
            $folder->create($filePath);
        }
        $filePath = $filePath . date('Ymd');
        $uploadPath = WWW_ROOT . $filePath;
        $upload = new FileUpload(array(
            'filePath' => $uploadPath
        ));

        $result = false;
        if ($upload->uploadFile($this->request->getData('file')) == 0) {
            $fileName = $upload->getNewFileName();
            $filePath = $filePath . '/' . $fileName;
            $result = [
                'path' =>  $filePath,
                'fullPath' => Router::url($filePath, true)
            ];

            $type = $this->request->getData('send');
            // 是否上传微信服务器
            if (in_array($type, $this->typeArray)) {
                $mpResult = $this->sendMedia($type, $result['path']);

                if ($mpResult) {
                    $result = array_merge($result, $mpResult);
                } else {
                    $this->apiResponse([], 300, '上传微信服务器失败！');
                }
            }
        }
        return $result;

    }

    /**
     * 微信服务器图片素材上传
     * @param $path string 完整路径
     * @param $type string 类型
     * @return array|bool
     */
    protected function sendMedia($type, $path)
    {

        switch ($type) {
            case 'media_temp':
                return $this->materialTemporary($path);
                break;
            case 'article':
                return $this->articleImage($path);
            default:
                return false;
                break;
        }

    }

    /**
     * 上传临时图片素材
     * @param $path
     * @return array|bool
     */
    protected function materialTemporary($path)
    {
        $app = $this->WeChat->getApp();
        // 临时素材
        $temporary = $app->material;
        $res = [];
        try {
            $res = $temporary->uploadImage($path);
        } catch (\Exception $e) {

        }

        if (isset($res['media_id'])) {
            return [
                'media_id' => $res['media_id']
            ];
        } else {
            return false;
        }
    }

    /**
     * 上传图文图片
     * @param $path
     * @return array|bool
     */
    protected function articleImage($path)
    {
        $app = $this->WeChat->getApp();

        $material = $app->material;

        $res = [];

        try {
            $res = $material->uploadArticleImage($path);
        } catch (\Exception $e) {

        }

        if (isset($res['url'])) {
            return [
                'fullPath' => $res['url']
            ];
        } else {
            return false;
        }
    }




}

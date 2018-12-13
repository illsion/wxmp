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
        }
        return $result;

    }




}
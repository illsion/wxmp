<?php
/**
 * Created by PhpStorm.
 * User: jzaaa
 * Date: 2018/12/17
 * Time: 14:53
 */

namespace Api\Controller;


class CommonController extends AppController
{
    /**
     * 获取服务器信息
     */
    public function getServerInfo()
    {
        $data = $this->getRequest()->getServerParams();

        $env = [
            [
                'value' => $data['HTTP_HOST'],
                'label' => '服务器域名'
            ],
            [
                'value' => $data['SERVER_SOFTWARE'],
                'label' => '服务器环境'
            ],
            [
                'value' => $data['SERVER_ADDR'],
                'label' => '服务器IP'
            ],
            [
                'value' => PHP_OS,
                'label' => '服务器操作系统'
            ],
            [
                'value' => function_exists('gzclose') ? 'YES' : 'NO',
                'label' => 'Zlib支持'
            ],
            [
                'value' => (boolean) ini_get('safe_mode') ? 'YES' : 'NO',
                'label' => '服务器操作系统'
            ],
            [
                'value' => phpversion(),
                'label' => 'PHP版本'
            ],
            [
                'value' => ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknown',
                'label' => '文件上传限制'
            ],
            [
                'value' => ini_get("max_execution_time") . 's',
                'label' => '脚本最大执行时间'
            ],
            [
                'value' => ini_get('memory_limit'),
                'label' => '最大占用内存'
            ]

        ];


        $this->apiResponse($env);
    }

}
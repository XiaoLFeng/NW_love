<?php

/**
 * nw_love API
 * @copyright 2022-2023 ZCW and YN. All Rights Reserved.
 */

/**
 * @var mysqli $SqlConn 数据库链接信息
 * @var array $config 设置相关的数据
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/Aliyun/aliyun-oss-php-sdk-2.6.0.phar';
require_once $_SERVER['DOCUMENT_ROOT'].'/Aliyun/autoload.php';

use OSS\OssClient;
use OSS\Core\OssException;

// 载入前置组件
include $_SERVER['DOCUMENT_ROOT'] . "/api/api-loader.php";

// 载入类
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/modules/ApiFunction.php';
$AFT = new ApiFunction();

// 获取参数
// POST
$PostData = file_get_contents('php://input');
$PostData = json_decode($PostData,true);

// 逻辑构建
if ($AFT->Get_Session($PostData['session'])) {
    if (preg_match('/[0-9]+$/',$PostData['album'])) {
        // 数据库查找
        $Result_Album = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['AlbumList']." WHERE `id`='".$PostData['album']."'");
        $Result_Album_Object = mysqli_fetch_object($Result_Album);

        // 确认数据
        if ($Result_Album_Object->id != null) {
            // 处理OSS
            // 阿里云主账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM用户进行API访问或日常运维，请登录RAM控制台创建RAM用户。
            $accessKeyId = $config['Aliyun']['AccessKeyID'];
            $accessKeySecret = $config['Aliyun']['AccessKeySecret'];
            // Endpoint以华东1（杭州）为例，其它Region请按实际情况填写。
            $endpoint = "https://oss-cn-shenzhen-internal.aliyuncs.com";
            // 填写Bucket名称。
            $bucket= "nw-love";

            try {
                $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint, false);
                $option = array(
                    OssClient::OSS_MARKER => null,
                    // 填写待删除目录的完整路径，完整路径中不包含Bucket名称。
                    OssClient::OSS_PREFIX => "Upload/".$PostData['album']."/",
                );
                $bool = true;
                while ($bool){
                    $result = $ossClient->listObjects($bucket,$option);
                    $objects = array();
                    if(count($result->getObjectList()) > 0){
                        foreach ($result->getObjectList() as $key => $info){
                            // printf("key name:".$info->getKey().PHP_EOL);
                            $objects[] = $info->getKey();
                        }
                        // 删除目录及目录下的所有文件。
                        $delObjects = $ossClient->deleteObjects($bucket, $objects);
                        foreach ($delObjects as $info){
                            $obj = strval($info);
                            // printf("Delete ".$obj." : Success" . PHP_EOL);
                        }
                    }

                    if($result->getIsTruncated() === 'true'){
                        $option[OssClient::OSS_MARKER] = $result->getNextMarker();
                    }else{
                        $bool = false;
                    }
                }

                // 执行数据库删除
                // 载入图片库
                $Result_Picture = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['Album']." WHERE `album_id`='".$PostData['album']."'");
                while ($Result_Picture_Object = mysqli_fetch_object($Result_Picture)) {
                    if (!mysqli_query($SqlConn,"DELETE FROM ".$config['TABLE']['Album']." WHERE `id`='".$Result_Picture_Object->id."'")) {
                        // 输出结果
                        $data = [
                            'output' => 'DATA_DELETE_FAIL',
                            'code' => 403,
                            'info' => '数据库删除失败',
                        ];
                        header('HTTP/1.1 403 Forbidden');
                    }
                }
                if (mysqli_query($SqlConn,"DELETE FROM ".$config['TABLE']['AlbumList']." WHERE `id`='".$PostData['album']."'")) {
                    // 输出结果
                    $data = [
                        'output' => 'SUCCESS',
                        'code' => 200,
                        'info' => '数据全部删除',
                    ];
                } else {
                    // 输出结果
                    $data = [
                        'output' => 'DATA_DELETE_FAIL',
                        'code' => 403,
                        'info' => '数据库删除失败',
                    ];
                    header('HTTP/1.1 403 Forbidden');
                }
            } catch (OssException $e) {
                // 输出结果
                $data = [
                    'output' => 'ALIYUN_DELETE_FAIL',
                    'code' => 403,
                    'info' => '远端删除失败',
                ];
                header('HTTP/1.1 403 Forbidden');
                return;
            }
        } else {
            // 输出结果
            $data = [
                'output' => 'ALBUM_NONE',
                'code' => 403,
                'info' => '没有这个图库',
            ];
            header('HTTP/1.1 403 Forbidden');
        }
    } else {
        // 输出结果
        $data = [
            'output' => 'ALBUM_FALSE',
            'code' => 403,
            'info' => '图库编号不符合标准',
        ];
        header('HTTP/1.1 403 Forbidden');
    }
} else {
    // 输出结果
    $data = [
        'output' => 'SESSION_DENY',
        'code' => 403,
        'info' => '参数 Post[session] 缺失/错误',
    ];
    header('HTTP/1.1 403 Forbidden');
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);
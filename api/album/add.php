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

// 逻辑搭建
if ($AFT->Get_Session($PostData['ssid'])) {
    if (preg_match('/^[\一-\龥A-Za-z0-9_]{2,40}$/',$PostData['name'])) {
        if (preg_match('/^\d{4}-\d{1,2}-\d{1,2}/',$PostData['date'])) {
            if (preg_match('/[0-1]/',$PostData['open'])) {
                // 上传数据
                if (mysqli_query($SqlConn,"INSERT INTO ".$config['TABLE']['AlbumList']." (`name`,`date`,`open`) VALUES ('".$PostData['name']."','".$PostData['date']."','".$PostData['open']."')")) {
                    $Result = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['AlbumList']." ORDER BY id DESC");
                    $Result_Object = mysqli_fetch_object($Result);

                    $accessKeyId = $config['Aliyun']['AccessKeyID'];
                    $accessKeySecret = $config['Aliyun']['AccessKeySecret'];
                    // Endpoint以华东1（杭州）为例，其它Region请按实际情况填写。
                    $endpoint = "https://oss-cn-shenzhen-internal.aliyuncs.com";
                    // 填写Bucket名称。
                    $bucket= "nw-love";
                    // 填写不包含Bucket名称在内的Object的完整路径。
                    $object = "Upload/".$Result_Object->id."/";
                    $content = "";
                    try{
                        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

                        $ossClient->putObject($bucket, $object, $content);
                    } catch(OssException $e) {
                        // 输出结果
                        $data = [
                            'output' => 'UPLOAD_ALIYUN_FAIL',
                            'code' => 403,
                            'info' => '参数 Sql[Upload_Fail] 缺失/错误',
                        ];
                        header('HTTP/1.1 403 Forbidden');
                        return;
                    }
                    // 输出结果
                    $data = [
                        'output' => 'SUCCESS',
                        'code' => 200,
                        'info' => '新建好了',
                    ];
                } else {
                    // 输出结果
                    $data = [
                        'output' => 'UPLOAD_FAIL',
                        'code' => 403,
                        'info' => '参数 Sql[Upload_Fail] 缺失/错误',
                    ];
                    header('HTTP/1.1 403 Forbidden');
                }
            } else {
                // 输出结果
                $data = [
                    'output' => 'OPEN_FALSE',
                    'code' => 403,
                    'info' => '参数错误',
                ];
                header('HTTP/1.1 403 Forbidden');
            }
        } else {
            // 输出结果
            $data = [
                'output' => 'DATE_FALSE',
                'code' => 403,
                'info' => '参数错误',
            ];
            header('HTTP/1.1 403 Forbidden');
        }
    } else {
        // 输出结果
        $data = [
            'output' => 'NAME_FALSE',
            'code' => 403,
            'info' => '参数错误',
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
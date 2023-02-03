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
    // 获取图库ID号
    if (preg_match('/[0-9]+$/',$PostData['album'])) {
        // 数据库筛查
        $Result_AlbumList = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['AlbumList']." WHERE `id`='".$PostData['album']."'");
        $Result_AlbumList_Object = mysqli_fetch_object($Result_AlbumList);

        // 检查数据
        if ($Result_AlbumList_Object->id != null) {
            // 数据检查
            if (preg_match('/^[\一-\龥A-Za-z0-9_]{2,40}$/',$PostData['data']['name'])) {
                if (preg_match('/^\d{4}-\d{1,2}-\d{1,2}/',$PostData['data']['date'])) {
                    if (preg_match('/[0-1]/',$PostData['data']['open'])) {
                        // 数据修改
                        if (mysqli_query($SqlConn,"UPDATE ".$config['TABLE']['AlbumList']." SET `name`='".$PostData['data']['name']."',`date`='".$PostData['data']['date']."',`open`='".$PostData['data']['open']."' WHERE `id`='".$PostData['album']."' ")) {
                            // 输出结果
                            $data = [
                                'output' => 'SUCCESS',
                                'code' => 200,
                                'info' => '数据修改成功',
                            ];
                        } else {
                            // 输出结果
                            $data = [
                                'output' => 'UPLOAD_FAIL',
                                'code' => 403,
                                'info' => '数据修改失败',
                            ];
                            header('HTTP/1.1 403 Forbidden');
                        }
                    } else {
                        // 输出结果
                        $data = [
                            'output' => 'OPEN_FALSE',
                            'code' => 403,
                            'info' => '是否开放不符',
                        ];
                        header('HTTP/1.1 403 Forbidden');
                    }
                } else {
                    // 输出结果
                    $data = [
                        'output' => 'DATE_FALSE',
                        'code' => 403,
                        'info' => '日期不符',
                    ];
                    header('HTTP/1.1 403 Forbidden');
                }
            } else {
                // 输出结果
                $data = [
                    'output' => 'NAME_FALSE',
                    'code' => 403,
                    'info' => '起名不符',
                ];
                header('HTTP/1.1 403 Forbidden');
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
            'info' => '参数 Post[album] 缺失/错误，格式不正确',
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
<?php
/**
 * nw_love API
 * @copyright 2022-2023 ZCW and YN. All Rights Reserved.
 */

/**
 * @var mysqli $SqlConn 数据库链接信息
 * @var array $config 设置相关的数据
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/Aliyun/aliyun-oss-php-sdk-2.6.0.phar';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Aliyun/autoload.php';

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
$PostData = json_decode($PostData, true);

// 逻辑构建
if ($AFT->Get_Session($PostData['session'])) {
    if (preg_match('/[0-9]+$/',$PostData['album'])) {
        if (count($PostData['data']) > 0) {
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
                    // 填写需要删除的多个文件完整路径。文件完整路径中不能包含Bucket名称。
                    $objects = array();
                    for ($i=0; $i<count($PostData['data']); $i++) {
                        $Result_Picture = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['Album']." WHERE `id`='".$PostData['data'][$i]."'");
                        $Result_Picture_Object = mysqli_fetch_object($Result_Picture);
                        preg_match('/(?<=https:\/\/img.na-wen.love\/Upload\/'.$PostData['album'].'\/).*/',$Result_Picture_Object->url,$datas);
                        $objects[] = "Upload/".$PostData['album']."/".$datas[0];
                        mysqli_free_result($Result_Picture);
                        mysqli_query($SqlConn,"DELETE FROM ".$config['TABLE']['Album']." WHERE `id`='".$Result_Picture_Object->id."'");
                    }
                    $result = $ossClient->deleteObjects($bucket, $objects);

                    foreach ($result as $info){
                        $obj = strval($info);
                        //printf("Delete ".$obj." : Success" . "\n");
                    }
                    // 输出结果
                    $data = [
                        'output' => 'SUCCESS',
                        'code' => 200,
                        'info' => '删除成功',
                    ];
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
                'output' => 'IMAGE_SELECT',
                'code' => 403,
                'info' => '请选择图片',
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
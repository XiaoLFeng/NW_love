<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/modules/header-loader.php';
include_once $_SERVER['DOCUMENT_ROOT']."/plugins/SqlConn.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/Aliyun/aliyun-oss-php-sdk-2.6.0.phar';
require_once $_SERVER['DOCUMENT_ROOT'].'/Aliyun/autoload.php';

use OSS\OssClient;
use OSS\Core\OssException;

/**
 * @var array $data 数据
 * @var array $config setting
 * @var mysqli $SqlConn MySQL Connect
 */

if (htmlspecialchars($_POST['P_open'])) $open = 1;
else $open = 0;

if (!empty($_FILES['P_image']['name'][0])) {
    // 阿里云账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM用户进行API访问或日常运维，请登录RAM控制台创建RAM用户。
    $accessKeyId = $config['Aliyun']['AccessKeyID'];
    $accessKeySecret = $config['Aliyun']['AccessKeySecret'];
    // Endpoint以华东1（杭州）为例，其它Region请按实际情况填写。
    $endpoint = "https://oss-cn-shenzhen-internal.aliyuncs.com";
    // 填写Bucket名称。
    $bucket= "nw-love";

    for ($i=0; $i<count($_FILES['P_image']['name']); $i++) {
        $tmpFilePatch = $_FILES['P_image']['tmp_name'][$i];
        $data = explode('.',$_FILES['P_image']['name'][$i]);
        $new_name = rand(1000,9999).time().rand(1000,9999).'.'.$data[1];
        $_FILES['P_image']['name'][$i] = $new_name;
        if ($tmpFilePatch != '') {
            move_uploaded_file($_FILES["P_image"]["tmp_name"][$i], $_SERVER['DOCUMENT_ROOT']."/cache/" . $_FILES["P_image"]["name"][$i]);
            $FilePatch[$i] = $_SERVER['DOCUMENT_ROOT']."/cache/" . $_FILES["P_image"]["name"][$i];
        }
    }

    try{
        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        // 第一次追加上传。第一次追加的位置是0，返回值为下一次追加的位置。后续追加的位置是追加前文件的长度。
        for ($i=0; $i<count($_FILES['P_image']['name']); $i++) {
            $object = "OtherCandid/".$_FILES["P_image"]["name"][$i];
            // 填写不包含Bucket名称在内的Object的完整路径。
            $ossClient->uploadFile($bucket, $object, $FilePatch[$i]);
            mysqli_query($SqlConn,"INSERT INTO ".$config['TABLE']['candid']." (`url`,`date`,`open`,`uploader`) VALUES ('https://img.na-wen.love/OtherCandid/".$_FILES["P_image"]["name"][$i]."','".date('Y-m-d')."','".$open."','".$_COOKIE['user']."')");
            unlink($_SERVER['DOCUMENT_ROOT']."/cache/" . $_FILES["P_image"]["name"][$i]);
        }
    } catch(OssException $e) {
        // 输出结果
        $data = [
            'output' => 'UPLOAD_ALIYUN_FAIL',
            'code' => 403,
            'info' => '上传失败',
        ];
        return;
    }
    // 输出结果
    $data = [
        'output' => 'SUCCESS',
        'code' => 200,
        'info' => '完成',
        'data' => $_FILES['P_image']['tmp_name'],
    ];
} else {
    // 输出结果
    $data = [
        'output' => 'IMAGE_NONE',
        'code' => 403,
        'info' => '你的图片呢？',
    ];
}
echo $data['output'];
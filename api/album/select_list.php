<?php
/**
 * nw_love API
 * @copyright 2022-2023 ZCW and YN. All Rights Reserved.
 */

/**
 * @var mysqli $SqlConn 数据库链接信息
 * @var array $config 设置相关的数据
 */

// 载入前置组件
include $_SERVER['DOCUMENT_ROOT']."/api/api-loader.php";

// 载入类
require_once $_SERVER['DOCUMENT_ROOT'].'/api/modules/ApiFunction.php';
$AFT = new ApiFunction();

// 获取参数（GET）
$GetData = [
    'session'=>urldecode(htmlspecialchars($_GET['session'])),
];

// 函数构建
if ($AFT->Get_Session($GetData['session'])) {
    // 查询数据库
    $Result_AlbumList = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['AlbumList']." ORDER BY `date` DESC");
    $number = 0;
    while ($Result_AlbumList_Object = mysqli_fetch_object($Result_AlbumList)) {
        $Result_Album = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['Album']." WHERE album_id='".$Result_AlbumList_Object->id."'");
        $Result_Album_Object = mysqli_fetch_object($Result_Album);
        if ($Result_Album_Object->url == null) {
            $Url = 'https://www.na-wen.love/sources/img/no-image.jpg';
        } else {
            $Url = $Result_Album_Object->url.'!pw60';
        }
        $array[$number] = [
            'id'=>$Result_AlbumList_Object->id,
            'name'=>$Result_AlbumList_Object->name,
            'date'=>$Result_AlbumList_Object->date,
            'open'=>$Result_AlbumList_Object->open,
            'first_image'=>$Url,
        ];
        $number ++;
    }

    // 输出结果
    $data = [
        'output'=>'SUCCESS',
        'code'=>200,
        'info'=>'输出成功',
        'data'=>$array
    ];
} else {
    // 输出结果
    $data = [
        'output'=>'SESSION_DENY',
        'code'=>403,
        'info'=>'参数 Query[session] 缺失/错误',
    ];
    header('HTTP/1.1 403 Forbidden');
}
echo json_encode($data,JSON_UNESCAPED_UNICODE);
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
include $_SERVER['DOCUMENT_ROOT'] . "/api/api-loader.php";

// 载入类
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/modules/ApiFunction.php';
$AFT = new ApiFunction();

// 获取参数（GET）
$GetData = [
    'session' => urldecode(htmlspecialchars($_GET['session'])),
    'album'=>urldecode(htmlspecialchars($_GET['album'])),
];

// 函数构建
if ($AFT->Get_Session($GetData['session'])) {
    if (preg_match('/[0-9]+$/',$GetData['album'])) {
        // 查询数据库
        $Result_Album = mysqli_query($SqlConn, "SELECT * FROM " . $config['TABLE']['Album'] . " WHERE `album_id`='".$GetData['album']."' ORDER BY `id` DESC");
        $number = 0;
        while ($Result_Album_Object = mysqli_fetch_object($Result_Album)) {
            $array[$number] = [
                'id' => $Result_Album_Object->id,
                'url' => $Result_Album_Object->url,
                'date' => $Result_Album_Object->date,
                'open' => $Result_Album_Object->open,
                'uploader' => $Result_Album_Object->uploader,
            ];
            $number++;
        }

        // 输出结果
        $data = [
            'output' => 'SUCCESS',
            'code' => 200,
            'info' => '输出成功',
            'data' => $array
        ];
    } else {
        // 输出结果
        $data = [
            'output' => 'ALBUM_FALSE',
            'code' => 403,
            'info' => '参数 Query[album] 缺失/错误',
        ];
        header('HTTP/1.1 403 Forbidden');
    }
} else {
    // 输出结果
    $data = [
        'output' => 'SESSION_DENY',
        'code' => 403,
        'info' => '参数 Query[session] 缺失/错误',
    ];
    header('HTTP/1.1 403 Forbidden');
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);
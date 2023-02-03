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

// 构建函数
if ($AFT->Get_Session($GetData['session'])) { // 判断密钥正确
    // 数据查询
    $Result_info = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['info']." ORDER BY id");
    while ($Result_info_Object = mysqli_fetch_object($Result_info)) {
        if ($Result_info_Object->value != 'session') { // 去除密钥输出
            $Array[$Result_info_Object->value] = [
                'data'=>$Result_info_Object->data,
                'autoload'=>$Result_info_Object->autoload,
            ];
        }
    }

    // 输出结果
    $data = [
        'output'=>'SUCCESS',
        'code'=>200,
        'info'=>'DATA OUTPUT DONE',
        'data'=>$Array,
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
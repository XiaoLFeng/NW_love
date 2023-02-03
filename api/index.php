<?php
/**
 * nw_love API
 * @copyright 2022-2023 ZCW and YN. All Rights Reserved.
 */

/**
 * @var string $SqlConn 数据库链接信息
 * @var string $config 设置相关的数据
 */

// 载入前置组件
include $_SERVER['DOCUMENT_ROOT']."/api/api-loader.php";

// 编译数据
$data = [
    'output'=>'SUCCESS',
    'code'=>200,
    'info'=>'API正常运行',
];

// 数据输出
echo json_encode($data,JSON_UNESCAPED_UNICODE);
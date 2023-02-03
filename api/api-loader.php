<?php
/**
 * nw_love API head_loader
 * @copyright 2022-2023 ZCW and YN. All Rights Reserved.
 */

/**
 * @var string $SqlConn 数据库链接信息
 * @var string $config 设置相关的数据
 */

// 设置请求头
header('Content-Type: application/json;charset=utf-8');

// 获取数据
include $_SERVER['DOCUMENT_ROOT']."/config.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/plugins/SqlConn.php";
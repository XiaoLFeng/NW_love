<?php
/**
 * nw_love API head_loader
 * @copyright 2022-2023 ZCW and YN. All Rights Reserved.
 */

/** @var string $config */

$SqlHost = $config['SQL']['host'];
$SqlDBname = $config['SQL']['dbname'];
$SqlUsername = $config['SQL']['username'];
$SqlPassword = $config['SQL']['password'];

// 判断数据库的端口
if ($config['SQL']['port'] == 3306 or $config['SQL']['port'] == NULL) {
    $SqlPort = 3306;
} else {
    $SqlPort = $config['SQL']['port'];
}

// 链接数据库
$SqlConn = new MySQLi($SqlHost,$SqlUsername,$SqlPassword,$SqlDBname,$SqlPort);

// 检查错误
if ($config['WEB']['DeBUG']) {
    die('数据库链接失败！ErrorCode: '.$SqlConn->connect_error);
}
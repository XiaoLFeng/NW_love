<?php
// 获取数据
include $_SERVER['DOCUMENT_ROOT']."/config.inc.php";

// 获取类
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Functions.php';
$F = new Functions();

// 网站基本数据
/** @var string $config */
$Normal_url = $F->Current_HTTP().'/api/web/index.php?session='.$config['SESSION'];
$Normal_ch = curl_init($Normal_url);
curl_setopt($Normal_ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
curl_setopt($Normal_ch, CURLOPT_RETURNTRANSFER, true);
$Normal = curl_exec($Normal_ch);
$Normal = json_decode($Normal,true);
<?php

/**
 * @var array $config
 */

include $_SERVER['DOCUMENT_ROOT'].'/config.inc.php';

// 时间转换
$date = htmlspecialchars($_POST['P_date']);
$date = strtotime($date);
$date = date('Y-m-d',$date);

if (htmlspecialchars($_POST['P_open'])) {
    $open = 1;
} else {
    $open = 0;
}

// 函数处理
$PostUrl = 'https://www.na-wen.love/api/album/add.php';
$data = [
    'ssid'=>$config['SESSION'],
    'name'=>htmlspecialchars($_POST['P_name']),
    'date'=>$date,
    'open'=>$open,
];

$JsonStr = json_encode($data); //转换为json格式
$PostData = http_post_json($PostUrl, $JsonStr);
$PostData = json_decode($PostData,true);
echo $PostData['output'];

// 发送POST
/**
 * @param $url
 * @param $jsonStr
 * @return bool|string
 */
function http_post_json($url, $jsonStr) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($jsonStr)
        )
    );
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
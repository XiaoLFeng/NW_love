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
require_once $_SERVER['DOCUMENT_ROOT'].'/api/modules/Mailer.php';
$AFT = new ApiFunction();
$Mail = new SendMail();

// 获取参数
// POST
$PostData = file_get_contents('php://input');
$PostData = json_decode($PostData,true);

// 逻辑构建
if ($AFT->Get_Session((string)$PostData['session'])) {
    if (!empty($PostData['user'])) {
        // 获取用户数据
        $Result_BoyData = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['info']." WHERE `value`='boy_user'");
        $Result_GirlData = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['info']." WHERE `value`='girl_user'");
        $Result_BoyData_Object = mysqli_fetch_object($Result_BoyData);
        $Result_GirlData_Object = mysqli_fetch_object($Result_GirlData);

        if ($PostData['user'] == $Result_BoyData_Object->data or $PostData['user'] == $Result_GirlData_Object->data) {
            if ($PostData['miss'] == 1) {
                // 数据库抽取
                $Result_DailyData = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['daily']." ORDER BY `id` DESC");
                $Result_DailyData_Object = mysqli_fetch_object($Result_DailyData);

                // 检查日期是否是今天
                if ($Result_DailyData_Object->date != date('Y-m-d')) {
                    // 生成今日数据
                    mysqli_query($SqlConn,"INSERT INTO ".$config['TABLE']['daily']." (`date`) VALUES ('".date('Y-m-d')."')");
                    $Result_DailyData = null;
                    $Result_DailyData = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['daily']." ORDER BY `id` DESC");
                    $Result_DailyData_Object = mysqli_fetch_object($Result_DailyData);
                }

                if ($PostData['user'] == $Result_BoyData_Object->data) {
                    // 男孩想女孩
                    if (!$Result_DailyData_Object->boy_miss) {
                        $Result_Girl = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['info']." WHERE `value`='girl_email'");
                        $Result_Girl_Object = mysqli_fetch_object($Result_Girl);
                        // 修改数据库
                        if (mysqli_query($SqlConn,"UPDATE ".$config['TABLE']['daily']." SET `boy_miss`=1 WHERE `id`='".$Result_DailyData_Object->id."'")) {
                            if ($Result_DailyData_Object->girl_miss) {
                                $Mail->Mailer('over_miss',$Result_Girl_Object->data,$Result_GirlData_Object->data);
                            } else {
                                $Mail->Mailer('miss',$Result_Girl_Object->data,$Result_GirlData_Object->data);
                            }
                            // 输出结果
                            $data = [
                                'output' => 'SUCCESS',
                                'code' => 200,
                                'info' => '我想你了！',
                            ];
                        }
                    } else {
                        // 输出结果
                        $data = [
                            'output' => 'MISS_ALREADY',
                            'code' => 200,
                            'info' => '今天已经想过他了呢',
                        ];
                    }
                } else {
                    // 女孩想男孩
                    if (!$Result_DailyData_Object->girl_miss) {
                        $Result_Boy = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['info']." WHERE `value`='boy_email'");
                        $Result_Boy_Object = mysqli_fetch_object($Result_Boy);
                        // 修改数据库
                        if (mysqli_query($SqlConn,"UPDATE ".$config['TABLE']['daily']." SET `girl_miss`=1 WHERE `id`='".$Result_DailyData_Object->id."'")) {
                            if ($Result_DailyData_Object->boy_miss) {
                                $Mail->Mailer('over_miss',$Result_Boy_Object->data,$Result_BoyData_Object->data);
                            } else {
                                $Mail->Mailer('miss',$Result_Boy_Object->data,$Result_BoyData_Object->data);
                            }
                            // 输出结果
                            $data = [
                                'output' => 'SUCCESS',
                                'code' => 200,
                                'info' => '我想你了！',
                            ];
                        }
                    } else {
                        // 输出结果
                        $data = [
                            'output' => 'MISS_ALREADY',
                            'code' => 200,
                            'info' => '今天已经想过他了呢',
                        ];
                    }
                }


            } else {
                // 输出结果
                $data = [
                    'output' => 'MISS_ERROR',
                    'code' => 403,
                    'info' => '参数 Post[miss] 参数错误',
                ];
                header('HTTP/1.1 403 Forbidden');
            }
        } else {
            // 输出结果
            $data = [
                'output' => 'USER_DENY',
                'code' => 403,
                'info' => '参数 Cookie[user] 非法用户！',
            ];
            header('HTTP/1.1 403 Forbidden');
        }
    } else {
        // 输出结果
        $data = [
            'output' => 'USER_ERROR',
            'code' => 403,
            'info' => '参数 Cookie[user] 非法请求！',
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
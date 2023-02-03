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

// 获取参数
// GET
$GetData = [
    'session'=>urldecode(htmlspecialchars($_GET['session'])),
    'type'=>urldecode(htmlspecialchars($_GET['type'])),
    'date'=>urldecode(htmlspecialchars($_GET['date']))
];

// 函数构建
if ($AFT->Get_Session($GetData['session'])) {
    // 类型选择
    if ($GetData['type'] == 'today') {
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

        // 输出结果
        $data = [
            'output' => 'SUCCESS',
            'code' => 200,
            'info' => '输出成功',
            'data' => $Result_DailyData_Object,
        ];
    } elseif ($GetData['type'] == 'all') {
        // 数据库抽取
        $Result_DailyData = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['daily']." ORDER BY `id` DESC");
        $Result_DailyData_Object = mysqli_fetch_object($Result_DailyData);

        // 检查日期是否是今天
        if ($Result_DailyData_Object->date != date('Y-m-d')) {
            // 生成今日数据
            mysqli_query($SqlConn,"INSERT INTO ".$config['TABLE']['daily']." (`date`) VALUES ('".date('Y-m-d')."')");
        }

        $Result_DailyData = null;
        $Result_DailyData = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['daily']." ORDER BY `id` DESC");
        $num = 0;
        while ($Result_DailyData_Object = mysqli_fetch_object($Result_DailyData)) {
            $Array[$num] = [
                'id'=>$Result_DailyData_Object->id,
                'date'=>$Result_DailyData_Object->date,
                'boy_miss'=>$Result_DailyData_Object->boy_miss,
                'girl_miss'=>$Result_DailyData_Object->girl_miss,
                'boy_sleep_time'=>$Result_DailyData_Object->boy_sleep_time,
                'girl_sleep_time'=>$Result_DailyData_Object->girl_sleep_time,
                'boy_getup_time'=>$Result_DailyData_Object->boy_getup_time,
                'girl_getup_time'=>$Result_DailyData_Object->girl_getup_time
            ];
            $num ++;
        }

        // 输出结果
        $data = [
            'output' => 'SUCCESS',
            'code' => 200,
            'info' => '输出成功',
            'data' => $Array,
        ];
    } elseif ($GetData['type'] == 'single') {
        if (!empty($GetData['date'])) {
            // 数据库抽取
            $Result_DailyData = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['daily']." ORDER BY `id` DESC");
            $Result_DailyData_Object = mysqli_fetch_object($Result_DailyData);
            // 检查日期是否是今天
            if ($Result_DailyData_Object->date != date('Y-m-d')) {
                // 生成今日数据
                mysqli_query($SqlConn,"INSERT INTO ".$config['TABLE']['daily']." (`date`) VALUES ('".date('Y-m-d')."')");
            }

            $Result_DailyData = null;
            $Result_DailyData = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['daily']." WHERE `date`='".$GetData['date']."'");
            $Result_DailyData_Object = mysqli_fetch_object($Result_DailyData);

            if (!empty($Result_DailyData_Object)) {
                // 输出结果
                $data = [
                    'output' => 'SUCCESS',
                    'code' => 200,
                    'info' => '输出成功',
                    'data' => $Result_DailyData_Object,
                ];
            } else {
                // 输出结果
                $data = [
                    'output' => 'DATA_NONE',
                    'code' => 403,
                    'info' => '没有数据',
                ];
                header('HTTP/1.1 403 Forbidden');
            }
        } else {
            // 输出结果
            $data = [
                'output' => 'DATE_NONE',
                'code' => 403,
                'info' => '参数 Query[date] 缺失/错误',
            ];
            header('HTTP/1.1 403 Forbidden');
        }
    } else {
        // 输出结果
        $data = [
            'output' => 'TYPE_ERROR',
            'code' => 403,
            'info' => '参数 Query[type] 缺失/错误',
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
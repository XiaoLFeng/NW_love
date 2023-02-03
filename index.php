<?php
// 载入
include_once $_SERVER['DOCUMENT_ROOT'].'/modules/header-loader.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Functions.php';
$F = new Functions();

/**
 * @var array $Normal
 * @var array $config
 */

$page = 1;

// MissYou API
$ApiMiss_url = $F->Current_HTTP().'/api/tools/miss_you_logs.php?type=all&session='.$config['SESSION'];
$ApiMiss_ch = curl_init($ApiMiss_url);
curl_setopt($ApiMiss_ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
curl_setopt($ApiMiss_ch, CURLOPT_RETURNTRANSFER, true);
$ApiMiss = curl_exec($ApiMiss_ch);
$ApiMiss = json_decode($ApiMiss,true);
?>
<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $Normal['data']['web_title']['data'] ?> - <?php echo $Normal['data']['web_desc']['data'] ?></title>
    <link rel="icon" href="<?php echo $Normal['data']['web_icon']['data'] ?>">
    <meta name="description" content="<?php echo $Normal['data']['web_desc']['data'] ?>">
    <meta name="keywords" content="<?php echo $Normal['data']['web_keyword'] ?>">
    <meta name="full-screen" content="yes"><!--UC强制全屏-->
    <meta name="browsermode" content="application"><!--UC应用模式-->
    <meta name="x5-fullscreen" content="true"><!--QQ强制全屏-->
    <meta name="x5-page-mode" content="app"><!--QQ应用模式-->
    <!-- CSS -->
    <link rel="stylesheet" href="/sources/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://at.alicdn.com/t/c/font_3866622_ur298ayiu2.css">
    <link rel="stylesheet" href="/sources/icons/bootstrap-icons.css">
</head>
<body style="background-color: rgba(255,192,203,0.25)">
<!-- 页首 -->
<?php include $_SERVER['DOCUMENT_ROOT']."/modules/header.php"; ?>
<!-- 页中 -->
<div class="container">
    <div class="row text-center">
        <div class="col-12 mt-4">
            <div class="progress" role="progressbar">
                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: <?php echo round((time()-strtotime(date('Y-m-d').'00:00'))/86400,2)*100 ?>%;background-color: #ffc0cb">今天过了<?php echo round((time()-strtotime(date('Y-m-d').'00:00'))/86400,2)*100 ?>%</div>
            </div>
        </div>
        <div class="col-12 my-5">
            <div class="row">
                <div class="col-4">
                    <div class="row">
                        <div class="col-12 mb-1"><img src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo $Normal['data']['boy_qq']['data'] ?>&s=640" class="rounded-circle container-fluid"></div>
                        <div class="col-12"><?php echo $Normal['data']['boy_name']['data'] ?></div>
                    </div>
                </div>
                <div class="col-4"><img src="/sources/img/love.png" class="container-fluid"></div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-12 mb-1"><img src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo $Normal['data']['girl_qq']['data'] ?>&s=640" class="rounded-circle container-fluid"></div>
                        <div class="col-12"><?php echo $Normal['data']['girl_name']['data'] ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if (!empty($_COOKIE['user'])) {
            if ($_COOKIE['user'] == $Normal['data']['boy_user']['data']) {
                // 判断为是否是男方
                if (!$ApiMiss['data'][0]['boy_miss']) {
                    ?>
                        <div class="col-12 mb-3">
                        <div class="card shadow-sm rounded-3 border-light" style="background-color: rgba(255,192,203,0.2); color: #ff8097">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 align-self-center fs-3 fw-bold" style="color: #ff8097">想念对方</div>
                                    <div class="col-6 align-self-center" id="button_miss">
                                        <button class="btn btn-outline-info text-center" onclick="miss_button()"><i class="bi bi-balloon-heart"></i> 想你了</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // 判断为女方
                if (!$ApiMiss['data'][0]['girl_miss']) {
                    ?>
                    <div class="col-12 mb-3">
                        <div class="card shadow-sm rounded-3 border-light" style="background-color: rgba(255,192,203,0.2); color: #ff8097">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 align-self-center fs-3 fw-bold" style="color: #ff8097">想念对方</div>
                                    <div class="col-6 align-self-center" id="button_miss">
                                        <button class="btn btn-outline-info text-center" onclick="miss_button()"><i class="bi bi-balloon-heart"></i> 想你了</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
            <?php
        }
        ?>
        <div class="col-12 mb-3">
            <div class="card shadow-sm rounded-3 border-light" style="background-color: rgba(255,192,203,0.2); color: #ff8097">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 fs-3 fw-bold">我们相恋了 <?php echo round((time()-strtotime($Normal['data']['in_love_time']['data']))/86400+1,2) ?> 天</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="card shadow-sm rounded-3 border-light" style="background-color: rgba(0,157,255,0.2); color: #266dcc">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-start fw-bold"><?php echo $Normal['data']['girl_name']['data'] ?> の 生日</div>
                        <div class="col-6 text-end">
                            <div class="row">
                                <div class="col-12 fw-bold"><?php echo $Normal['data']['girl_birthday']['data'] ?></div>
                                <div class="col-12">还有
                                    <?php
                                    $date = date("Y").'-'.substr($Normal['data']['girl_birthday']['data'],5,5);
                                    $date_unix = strtotime($date);
                                    if (time() >= $date_unix) {
                                        $date = (date("Y")+1).'-'.substr($Normal['data']['girl_birthday']['data'],5,5);
                                        $date_unix = strtotime($date);
                                    }
                                    echo round(($date_unix-time())/86400 + 1,0);
                                    ?>
                                    天</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="card shadow-sm rounded-3 border-light" style="background-color: rgba(0,157,255,0.2); color: #266dcc">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-start fw-bold"><?php echo $Normal['data']['boy_name']['data'] ?> の 生日</div>
                        <div class="col-6 text-end">
                            <div class="row">
                                <div class="col-12 fw-bold"><?php echo $Normal['data']['boy_birthday']['data'] ?></div>
                                <div class="col-12">还有
                                    <?php
                                    $date = date("Y").'-'.substr($Normal['data']['boy_birthday']['data'],5,5);
                                    $date_unix = strtotime($date);
                                    if (time() >= $date_unix) {
                                        $date = (date("Y")+1).'-'.substr($Normal['data']['girl_birthday']['data'],5,5);
                                        $date_unix = strtotime($date);
                                    }
                                    echo round(($date_unix-time())/86400 + 1,0);
                                    ?>

                                    天</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3">
            <div class="col-12"><a href="https://beian.miit.gov.cn/" class="text-decoration-none text-info" target="_blank"><i class="iconfont icon-ICPbeian"></i> <?php echo $Normal['data']['web_icp']['data'] ?></a></div>
        </div>
    </div>
</div>
<!-- 底部菜单 -->
<?php include $_SERVER['DOCUMENT_ROOT']."/modules/menu.php"; ?>
</body>
<script type="text/javascript" src="/sources/js/jquery.min.js"></script>
<script type="text/javascript" src="/sources/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/sources/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    const LiveToast = document.getElementById('liveToast')
    const toast = new bootstrap.Toast(LiveToast)
    function miss_button() {
        $.ajax({
            async: true,
            type: "GET",
            url: "/plugins/miss_you.php",
            success: function(result) {
                if (result == 'SUCCESS') {
                    $('#ajax_return').text('你想TA了，并传达给了TA~')
                    toast.show()
                    $('#button_miss').html('<button class="btn btn-success text-center" disabled><i class="bi bi-balloon-heart"></i> 想过啦</button>')
                    setTimeout(function () {
                        window.location.reload()
                    }, 2000)
                } else if (result == 'MISS_ALREADY') {
                    $('#ajax_return').text('今天想过他了')
                    toast.show()
                } else if (result == 'MISS_ERROR') {
                    $('#ajax_return').text('参数错误')
                    toast.show()
                } else if (result == 'USER_DENY') {
                    $('#ajax_return').text('登陆错误')
                    toast.show()
                } else if (result == 'USER_ERROR') {
                    $('#ajax_return').text('登陆错误')
                    toast.show()
                } else {
                    $('#ajax_return').text('未知反应')
                    toast.show()
                }
            },
            error: function () {
                $('#ajax_return').text('错误了，问问管理员吧')
                toast.show()
            }
        });
    }

    function miss_button_notuser() {
        $('#button_miss').html('<button class="btn btn-danger text-center" disabled>你有问题</button>')
    }
</script>
</html>
<?php
// 载入
include_once $_SERVER['DOCUMENT_ROOT'].'/modules/header-loader.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Functions.php';
$F = new Functions();

/**
 * @var array $Normal
 * @var array $config
 */

$page = 3;

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
    <meta name="keywords" content="<?php echo $Normal['data']['web_keyword'] ?>"></meta>
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
        <div class="col-12 my-5">
            <div class="row">
                <div class="col-4">
                    <div class="row">
                        <div class="col-12 mb-1"><img src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo $Normal['data']['boy_qq']['data'] ?>&s=640" class="rounded-circle container-fluid"></div>
                        <div class="col-12"><?php echo $Normal['data']['boy_name']['data'] ?></div>
                    </div>
                </div>
                <div class="col-4 align-self-center" id="button_miss">
                    <?php
                    if (!empty($_COOKIE['user'])) {
                        if ($_COOKIE['user'] == $Normal['data']['boy_user']['data']) {
                            if (!$ApiMiss['data'][0]['boy_miss']) {
                                ?>
                                <button class="btn btn-outline-info text-center" onclick="miss_button()"><i class="bi bi-balloon-heart"></i> 想你了</button>
                                <?php
                                } else {
                                ?>
                                <button class="btn btn-success text-center" disabled><i class="bi bi-balloon-heart"></i> 想过啦</button>
                                <?php
                            }
                        } else {
                            if (!$ApiMiss['data'][0]['girl_miss']) {
                                ?>
                                <button class="btn btn-outline-info text-center" onclick="miss_button()"><i class="bi bi-balloon-heart"></i> 想你了</button>
                                <?php
                                } else {
                                ?>
                                <button class="btn btn-success text-center" disabled><i class="bi bi-balloon-heart"></i> 想过啦</button>
                                <?php
                            }
                        }
                    } else {
                        echo '<button class="btn btn-outline-info text-center" onclick="miss_button_notuser()"><i class="bi bi-balloon-heart"></i> 想你了</button>';
                    }
                    ?>
                </div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-12 mb-1"><img src="https://q1.qlogo.cn/g?b=qq&nk=<?php echo $Normal['data']['girl_qq']['data'] ?>&s=640" class="rounded-circle container-fluid"></div>
                        <div class="col-12"><?php echo $Normal['data']['girl_name']['data'] ?></div>
                    </div>
                </div>
            </div>
        </div>
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
            <div class="card shadow-sm rounded-3 border-light" style="background-color: rgba(255,213,0,0.2); color: #bd9c1c">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-start fw-bold">我们一起想念对方有</div>
                        <div class="col-6 text-end">
                            <div class="row">
                                <div class="col-12 fw-bold">
                                    <?php
                                    $Number = 0;
                                    for ($i=0; $i < count($ApiMiss['data']); $i++) {
                                        if ($ApiMiss['data'][$i]['girl_miss'] and $ApiMiss['data'][$i]['boy_miss']) $Number++;
                                    }
                                    echo $Number;
                                    ?> 天
                                </div>
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
                        <div class="col-6 text-start fw-bold"><?php echo $Normal['data']['girl_name']['data'] ?></div>
                        <div class="col-6 text-end">
                            <div class="row">
                                <div class="col-12 fw-bold"><?php if ($ApiMiss['data'][0]['girl_miss']) echo '想了 '.$Normal['data']['boy_name']['data']; else echo '还没想 '.$Normal['data']['boy_name']['data']; ?></div>
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
                        <div class="col-6 text-start fw-bold"><?php echo $Normal['data']['boy_name']['data'] ?></div>
                        <div class="col-6 text-end">
                            <div class="row">
                                <div class="col-12 fw-bold"><?php if ($ApiMiss['data'][0]['boy_miss']) echo '想了 '.$Normal['data']['girl_name']['data']; else echo '还没想 '.$Normal['data']['girl_name']['data']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="card shadow-sm rounded-3 border-light" style="background-color: rgba(161,0,255,0.2); color: #7126cc">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-start fw-bold"><?php echo $Normal['data']['girl_name']['data'] ?> 想 <?php echo $Normal['data']['boy_name']['data'] ?></div>
                        <div class="col-6 text-end">
                            <div class="row">
                                <div class="col-12 fw-bold">
                                    <?php
                                    $Number = 0;
                                    for ($i=0; $i < count($ApiMiss['data']); $i++) {
                                        if ($ApiMiss['data'][$i]['girl_miss']) $Number++;
                                    }
                                    echo $Number;
                                    ?> 天
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="card shadow-sm rounded-3 border-light" style="background-color: rgba(161,0,255,0.2); color: #7126cc">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-start fw-bold"><?php echo $Normal['data']['boy_name']['data'] ?> 想 <?php echo $Normal['data']['girl_name']['data'] ?></div>
                        <div class="col-6 text-end">
                            <div class="row">
                                <div class="col-12 fw-bold">
                                    <?php
                                    $Number = 0;
                                    for ($i=0; $i < count($ApiMiss['data']); $i++) {
                                        if ($ApiMiss['data'][$i]['boy_miss']) $Number++;
                                    }
                                    echo $Number;
                                    ?> 天
                                </div>
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
<!-- Toast -->
<div class="toast-container position-fixed top-0 start-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bi bi-info-circle"></i><strong class="me-auto">提醒</strong>
            <small>现在</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="ajax_return"></div>
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
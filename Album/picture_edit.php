<?php
// 载入
include_once $_SERVER['DOCUMENT_ROOT'] . '/modules/header-loader.php';
// 获取类
require_once $_SERVER['DOCUMENT_ROOT'] . '/modules/Functions.php';
$F = new Functions();

/**
 * @var array $Normal 普通事件API
 * @var array $config 配置文件
 * @var array $ApiAlbum 图库API
 */

$ApiAlbum_url = $F->Current_HTTP().'/api/album/select_list.php?session='.$config['SESSION'];
$ApiAlbum_ch = curl_init($ApiAlbum_url);
curl_setopt($ApiAlbum_ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
curl_setopt($ApiAlbum_ch, CURLOPT_RETURNTRANSFER, true);
$ApiAlbum = curl_exec($ApiAlbum_ch);
$ApiAlbum = json_decode($ApiAlbum,true);

$page = 2;
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
<?php include $_SERVER['DOCUMENT_ROOT'] . "/modules/header.php"; ?>
<!-- 页中 -->
<div class="container-fluid my-4">
    <div class="row">
        <?php
        if (!empty($_COOKIE['user'])) {
            ?>
            <div class="col-12 mb-3">
                <div class="card shadow-sm rounded-3 border-light" style="background-color: rgba(255,192,203,0.2); color: #ff8097">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 fs-3 fw-bold text-center">正在开发</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="col-12 mb-3">
                <div class="card shadow-sm rounded-3 border-light" style="background-color: rgba(255,192,203,0.2); color: #ff8097">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 fs-3 fw-bold text-center">您无权限访问此页</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="col-12 mt-3 text-center">
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
<?php include $_SERVER['DOCUMENT_ROOT'] . "/modules/menu.php"; ?>
</body>
<script type="text/javascript" src="/sources/js/jquery.min.js"></script>
<script type="text/javascript" src="/sources/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/sources/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    const LiveToast = document.getElementById('liveToast')
    const toast = new bootstrap.Toast(LiveToast)
    function create()
    {
        $.ajax({
            async: true,
            type: "POST",
            data: $('#forms').serialize(),
            url: "/plugins/album_list_check.php",
            success: function(result) {
                if (result == 'SUCCESS') {
                    $('#ajax_return').text('新建好了')
                    toast.show()
                    setTimeout(function () {
                        window.location.reload()
                    },2000)
                } else if (result == 'UPLOAD_FAIL') {
                    $('#ajax_return').text('新建失败')
                    toast.show()
                } else if (result == 'OPEN_FALSE') {
                    $('#ajax_return').text('是否开放啊？有问题')
                    toast.show()
                } else if (result == 'DATE_FALSE') {
                    $('#ajax_return').text('日期出错了额')
                    toast.show()
                } else if (result == 'NAME_FALSE') {
                    $('#ajax_return').text('起名不符，只允许中文、英文、数字组合')
                    toast.show()
                } else if (result == 'SESSION_DENY') {
                    $('#ajax_return').text('呼叫管理员')
                    toast.show()
                }
                $('#data').load(result)
            },
            error: function () {
                alert('未知错误，请联系管理员（JS_ERROR）')
            }
        });
    }
</script>
</html>
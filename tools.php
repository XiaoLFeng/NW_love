<?php
// 载入
include_once $_SERVER['DOCUMENT_ROOT'].'/modules/header-loader.php';

/**
 * @var array $Normal
 * @var array $config
 */

$page = 3;
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
    <div class="row my-3">
        <div class="col-12 mb-3 fs-5 fw-bold"><i class="bi bi-brush"></i> 功能</div>
        <div class="col-6 mb-3">
            <a href="/tools/miss_you.php" class="text-decoration-none">
                <div class="card shadow-sm rounded-3 border-light" style="background-color: rgba(255,192,203,0.2); color: #ff8097">
                    <div class="card-body">
                        <i class="bi bi-calendar-heart"></i>&nbsp;&nbsp;每日想你
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 mb-3">
            <a href="/tools/the_first.php" class="text-decoration-none">
                <div class="card shadow-sm rounded-3 border-light" style="background-color: rgba(255,192,203,0.2); color: #ff8097">
                    <div class="card-body">
                        <i class="bi bi-1-square"></i>&nbsp;&nbsp;我们的第一次
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 mb-3 fs-5 fw-bold"><i class="bi bi-ui-checks-grid"></i> 附属相册</div>
        <div class="col-6 mb-3">
            <a href="/tools/other_candid.php" class="text-decoration-none">
                <div class="card shadow-sm rounded-3 border-light" style="background-color: rgba(255,192,203,0.2); color: #ff8097">
                    <div class="card-body">
                        <i class="bi bi-camera-fill"></i>&nbsp;&nbsp;别人偷拍
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 mb-3">
            <a href="/Album/picture.php?album=28" class="text-decoration-none">
                <div class="card shadow-sm rounded-3 border-light" style="background-color: rgba(255,192,203,0.2); color: #ff8097">
                    <div class="card-body">
                        <i class="bi bi-activity"></i>&nbsp;&nbsp;咱俩美照
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 mt-3 text-center">
            <div class="col-12"><a href="https://beian.miit.gov.cn/" class="text-decoration-none text-info" target="_blank"><i class="iconfont icon-ICPbeian"></i> <?php echo $Normal['data']['web_icp']['data'] ?></a></div>
        </div>
    </div>
</div>
<!-- 底部菜单 -->
<?php include $_SERVER['DOCUMENT_ROOT']."/modules/menu.php"; ?>
</body>
<script src="/sources/js/bootstrap.min.js"></script>
<script src="/sources/js/bootstrap.bundle.min.js"></script>
</html>
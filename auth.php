<?php
// 载入
include_once $_SERVER['DOCUMENT_ROOT'].'/modules/header-loader.php';

if (!empty($_COOKIE['user'])) {
    header('location: /');
}

/**
 * @var array $Normal
 * @var array $config
 */

$page = 1;
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
    <link rel="stylesheet" href="https://at.alicdn.com/t/c/font_3866622_q7rcnjbxpn.css">
    <link rel="stylesheet" href="/sources/icons/bootstrap-icons.css">
</head>
<body style="background-color: rgba(255,192,203,0.25)">
<!-- 页首 -->
<?php include $_SERVER['DOCUMENT_ROOT']."/modules/header.php"; ?>
<!-- 页中 -->
<div class="container my-5">
    <div class="row text-center">
        <div class="col-12">
            <div class="card shadow rounded-3" style="background-color: rgba(255,192,203,0.15)">
                <div class="card-body">
                    <form action="/plugins/login_check.php" method="post">
                        <div class="row">
                            <div class="col-12 fs-3 fw-bold my-3">登录</div>
                            <div class="col-12 mb-3 text-start">
                                <label for="exampleFormControlInput1" class="form-label"><i class="iconfont icon-yonghu"></i> 账户</label>
                                <input type="text" class="form-control" id="user" name="user" placeholder="Petrichor" required>
                            </div>
                            <div class="col-12 mb-3 text-start">
                                <label for="exampleFormControlInput1" class="form-label"><i class="iconfont icon-mima"></i> 密码</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="******" required>
                            </div>
                            <div class="col-12 my-3"><button class="btn btn-info" type="submit">> 登入 <</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 页尾 -->
<?php include $_SERVER['DOCUMENT_ROOT']."/modules/footer.php"; ?>
<!-- 底部菜单 -->
<?php include $_SERVER['DOCUMENT_ROOT']."/modules/menu.php"; ?>
</body>
<script src="/sources/js/bootstrap.min.js"></script>
<script src="/sources/js/bootstrap.bundle.min.js"></script>
</html>
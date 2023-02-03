<?php
// 载入
include_once $_SERVER['DOCUMENT_ROOT'].'/modules/header-loader.php';
// 获取类
require_once $_SERVER['DOCUMENT_ROOT'].'/modules/Functions.php';
$F = new Functions();

/**
 * @var array $Normal 普通事件API
 * @var array $config 配置文件
 * @var array $ApiAlbum 图库API
 */

$ApiAlbum_url = $F->Current_HTTP().'/api/album/other_candid.php?session='.$config['SESSION'];
$ApiAlbum_ch = curl_init($ApiAlbum_url);
curl_setopt($ApiAlbum_ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
curl_setopt($ApiAlbum_ch, CURLOPT_RETURNTRANSFER, true);
$ApiAlbum = curl_exec($ApiAlbum_ch);
$ApiAlbum = json_decode($ApiAlbum,true);

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
<div class="container-fluid my-4">
    <div class="row">
        <div class="col-12 mb-3"><a href="/tools/other_candid.php" class="btn btn-outline-info"><i class="bi bi-backspace"></i> 返回图库</a></div>
        <div class="col-12 mb-5">
            <div class="container-fluid">
                <img src="<?php echo $ApiAlbum['data'][urldecode(htmlspecialchars($_GET['id']))]['url'] ?>" class="container-fluid">
            </div>
        </div>
        <div class="col-12 mt-5">
            <nav style="margin: 0 auto;display: table" aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link text-info <?php if (urldecode(htmlspecialchars($_GET['id'])) == 0) echo 'disabled'; ?>" href="?album=<?php echo urldecode(htmlspecialchars($_GET['album'])); ?>&id=<?php echo urldecode(htmlspecialchars($_GET['id']))-1; ?>">上一张</a></li>
                    <li class="page-item"><a class="page-link text-info <?php if (urldecode(htmlspecialchars($_GET['id'])) == count($ApiAlbum['data'])-1) echo 'disabled'?>" href="?album=<?php echo urldecode(htmlspecialchars($_GET['album'])); ?>&id=<?php echo urldecode(htmlspecialchars($_GET['id']))+1; ?>">下一张</a></li>
                </ul>
            </nav>
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
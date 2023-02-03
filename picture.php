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

$ApiAlbumList_url = $F->Current_HTTP().'/api/album/select_list.php?session='.$config['SESSION'];
$ApiAlbumList_ch = curl_init($ApiAlbumList_url);
curl_setopt($ApiAlbumList_ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
curl_setopt($ApiAlbumList_ch, CURLOPT_RETURNTRANSFER, true);
$ApiAlbumList = curl_exec($ApiAlbumList_ch);
$ApiAlbumList = json_decode($ApiAlbumList,true);

if (!empty(urldecode(htmlspecialchars($_GET['album'])))) {
    $ApiAlbum_url = $F->Current_HTTP().'/api/album/select.php?session='.$config['SESSION'].'&album='.urldecode(htmlspecialchars($_GET['album']));
    $ApiAlbum_ch = curl_init($ApiAlbum_url);
    curl_setopt($ApiAlbum_ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ApiAlbum_ch, CURLOPT_RETURNTRANSFER, true);
    $ApiAlbum = curl_exec($ApiAlbum_ch);
    $ApiAlbum = json_decode($ApiAlbum,true);
} else {
    header('location: ./album.php');
}

$page = 2;

if (empty($_COOKIE['user'])) {
    $num = 0;
    while ($ApiAlbumList['data'][$num]['id'] != null) {
        if ($ApiAlbumList['data'][$num]['id'] == urldecode(htmlspecialchars($_GET['album']))) {
            if ($ApiAlbumList['data'][$num]['open'] == "0") {
                header('location: ./album.php');
            }
        }
        $num ++;
    }
}
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
        <div class="col-6 mb-3 text-start">
            <a href="./album.php" class="btn btn-outline-info"><i class="bi bi-backspace"></i> 返回主页</a>
        </div>
        <?PHP
        if (!empty($_COOKIE['user'])) {
            ?>
            <div class="col-6 mb-3 text-end">
                <button class="btn btn-outline-info text-end" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-cloud-plus"></i> 新增图片</button>
            </div>
            <?php
        }
        ?>
        <div class="col-12 d-flex flex-wrap text-center" id="ImageFile">
            <?PHP
            $num = 0;
            while ($ApiAlbum['data'][$num]['id'] != null) {
                if ($ApiAlbum['data'][$num]['open'] == 1) {
                    ?>
                    <a class="p-1" href="./picture_look.php?album=<?php echo urldecode(htmlspecialchars($_GET['album'])) ?>&id=<?php echo $num ?>"><img src="<?php echo $ApiAlbum['data'][$num]['url'] ?>!pw80" style="width: 50px" class="rounded-1"></a>
                    <?php
                } else {
                    if ($ApiAlbum['data'][$num]['uploader'] == $_COOKIE['user']) {
                        ?>
                        <a class="p-1" href="./picture_look.php?album=<?php echo urldecode(htmlspecialchars($_GET['album'])) ?>&id=<?php echo $num ?>"><img src="<?php echo $ApiAlbum['data'][$num]['url'] ?>!pw80" style="width: 50px" class="rounded-1"></a>
                        <?php
                    }
                }
                $num++;
            }
            ?>
        </div>
        <div class="col-12 mt-3 text-center">
            <div class="col-12"><a href="https://beian.miit.gov.cn/" class="text-decoration-none text-info" target="_blank"><i class="iconfont icon-ICPbeian"></i> <?php echo $Normal['data']['web_icp']['data'] ?></a></div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">图片上传</h1>
            </div>
            <div class="modal-body">
                <form method="post" id="forms" action="#" onsubmit="return false" enctype ='multipart/form-data'>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-check-label">图库编号</label>
                            <input type="text" class="form-control" id="P_album" name="P_album" readonly value="<?php echo urldecode(htmlspecialchars($_GET['album'])) ?>">
                        </div>
                        <div class="col-12 mb-3">
                            <input type="file" class="form-control" id="P_image[]" name="P_image[]" multiple accept='image/*'>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="P_open" name="P_open" checked>
                                <label class="form-check-label">这组照片是否公开（不开放对方也看不见）</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="button_cancel" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> 取消创建</button>
                <button type="button" id="button_upload" class="btn btn-success" onclick="upload()"><i class="bi bi-cloud-arrow-up"></i> 上传图片</button>
            </div>
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
    window.onload = function () {
        var screen = window.screen.width;
        $('#ImageFile').find('img').css('width', (screen - 56) / 4)
    }
    function upload()
    {
        var formData = new FormData($('#forms')[0]);
        $.ajax({
            async: true,
            type: "POST",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            url: "/plugins/album_add.php",
            beforeSend: function () {
                $('#button_upload').html('<div class="spinner-border text-light spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div> 正在上传')
                $('#button_upload').attr('disabled',"true");
                $('#button_cancel').attr('disabled',"true");
            },
            success: function(result) {
                if (result == 'SUCCESS') {
                    $('#ajax_return').text('新建好了')
                    toast.show()
                    $('#button_upload').html('<i class="bi bi-check-circle"></i> 已完成')
                    $('#button_upload').removeAttr("disabled");
                    $('#button_cancel').attr('disabled',"true");
                    setTimeout(function () {
                        window.location.reload()
                    },2000)
                } else if (result == 'UPLOAD_ALIYUN_FAIL') {
                    $('#ajax_return').text('上传失败')
                    toast.show()
                    $('#button_upload').html('<i class="bi bi-arrow-repeat"></i> 重新上传')
                    $('#button_upload').removeAttr("disabled");
                    $('#button_cancel').removeAttr("disabled");
                } else if (result == 'IMAGE_NONE') {
                    $('#ajax_return').text('你图片呢')
                    toast.show()
                    $('#button_upload').html('<i class="bi bi-arrow-repeat"></i> 重新上传')
                    $('#button_upload').removeAttr("disabled");
                    $('#button_cancel').removeAttr("disabled");
                }
                $('#data').load(result)
            },
            error: function () {
                $('#button_upload').html('<i class="bi bi-x-circle"></i> 发生错误')
                $('#button_upload').removeAttr("disabled");
                $('#button_cancel').removeAttr("disabled");
                alert('未知错误，请联系管理员（JS_ERROR）')
            }
        });
    }
</script>
</html>
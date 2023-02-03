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
 * @var array $Album 整理后API
 */

$ApiAlbum_url = $F->Current_HTTP().'/api/album/select_list.php?session='.$config['SESSION'];
$ApiAlbum_ch = curl_init($ApiAlbum_url);
curl_setopt($ApiAlbum_ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
curl_setopt($ApiAlbum_ch, CURLOPT_RETURNTRANSFER, true);
$ApiAlbum = curl_exec($ApiAlbum_ch);
$ApiAlbum = json_decode($ApiAlbum,true);

// 筛查指定图库
for ($i=0; $i<count($ApiAlbum['data']); $i++) {
    if ($ApiAlbum['data'][$i]['id'] == urldecode(htmlspecialchars($_GET['album']))) {
        $Album =  $ApiAlbum['data'][$i];
    }
}

if ($Album['id'] == null) {
    header('location: ./album.php');
}

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
            <div class="col-6 mb-3 text-start">
                <a href="./picture.php?album=<?php echo urldecode(htmlspecialchars($_GET['album'])) ?>" class="btn btn-outline-info"><i class="bi bi-backspace"></i> 返回相册</a>
            </div>
            <div class="col-6 mb-3 text-end">
                <a href="./picture_edit.php?album=<?php echo urldecode(htmlspecialchars($_GET['album'])) ?>" class="btn btn-outline-info text-center"><i class="bi bi-gear"></i> 照片管理</a>
            </div>
            <div class="col-12 mb-3 fs-4 fw-bold"><i class="bi bi-journal-album"></i> 相册修改</div>
            <div class="col-12">
                <form method="post" id="forms" onsubmit="return false" action="#">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label"><i class="bi bi-images"></i> 图库名字</label>
                            <input type="text" class="form-control" id="P_name" name="P_name" value="<?php echo $Album['name'] ?>" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label"><i class="bi bi-calendar-heart"></i> 相册日期</label>
                            <input type="date" class="form-control" id="P_date" name="P_date" value="<?php echo $Album['date'] ?>" required>
                        </div>
                        <div class="col-12 mb-5">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="P_open" name="P_open" <?php if ($Album['open']) echo 'checked'; ?>>
                                <label class="form-check-label">这个图库是否对外公开</label>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#DelAlbum"><i class="bi bi-x-circle"></i> 删除图库</button>
                            <button type="button" id="button_upload" class="btn btn-success" onclick="edit()"><i class="bi bi-check-lg"></i> 提交修改</button>
                        </div>
                    </div>
                </form>
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
<!-- DelAlbum -->
<div class="modal fade" id="DelAlbum" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">删除图库</h1>
            </div>
            <div class="modal-body row">
                <div class="col-12">你确认是否删除图库嘛？这将会失去很久...很久......</div>
            </div>
            <div class="modal-footer">
                <button type="button" id="button_cancel" class="btn btn-success" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> 取消删除</button>
                <button type="button" id="button_upload" class="btn btn-danger" onclick="deleted()"><i class="bi bi-trash"></i> 确认删除</button>
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
<?php include $_SERVER['DOCUMENT_ROOT'] . "/modules/menu.php"; ?>
</body>
<script type="text/javascript" src="/sources/js/jquery.min.js"></script>
<script type="text/javascript" src="/sources/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/sources/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    const LiveToast = document.getElementById('liveToast')
    const toast = new bootstrap.Toast(LiveToast)
    function edit()
    {
        $.ajax({
            async: true,
            type: "POST",
            data: $('#forms').serialize(),
            url: "/plugins/album_edit.php?album=<?PHP echo urldecode(htmlspecialchars($_GET['album'])) ?>",
            success: function(result) {
                if (result == 'SUCCESS') {
                    $('#ajax_return').text('修改完毕')
                    toast.show()
                    setTimeout(function () {
                        window.location.href = "./picture.php?album=<?PHP echo urldecode(htmlspecialchars($_GET['album'])) ?>"
                    },2000)
                } else if (result == 'UPLOAD_FAIL') {
                    $('#ajax_return').text('修改失败')
                    toast.show()
                } else if (result == 'OPEN_FALSE') {
                    $('#ajax_return').text('是否开放修改失败')
                    toast.show()
                } else if (result == 'DATE_FALSE') {
                    $('#ajax_return').text('日期不符')
                    toast.show()
                } else if (result == 'ALBUM_NONE') {
                    $('#ajax_return').text('没有这个图库')
                    toast.show()
                } else if (result == 'ALBUM_FALSE') {
                    $('#ajax_return').text('图库ID不符')
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

    function deleted()
    {
        $.ajax({
            async: true,
            type: "POST",
            url: "/plugins/album_delete.php?album=<?PHP echo urldecode(htmlspecialchars($_GET['album'])) ?>",
            success: function(result) {
                if (result == 'SUCCESS') {
                    $('#ajax_return').text('已删除！')
                    toast.show()
                    setTimeout(function () {
                        window.location.href = "./album.php"
                    },2000)
                } else if (result == 'DATA_DELETE_FAIL') {
                    $('#ajax_return').text('数据删除失败')
                    toast.show()
                } else if (result == 'ALIYUN_DELETE_FAIL') {
                    $('#ajax_return').text('远端删除失败')
                    toast.show()
                } else if (result == 'ALBUM_NONE') {
                    $('#ajax_return').text('没有这个图库')
                    toast.show()
                } else if (result == 'ALBUM_FALSE') {
                    $('#ajax_return').text('图库ID不符')
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
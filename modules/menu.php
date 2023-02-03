<?php
function color($num) {
    global $page;
    if ($num == $page) {
        echo 'color: #ff8097';
    } else {
        echo 'color: #000000';
    }
}
?>
<div class="container-fluid fixed-bottom" style="background-color: #ffc0cb">
    <div class="row text-center">
        <div class="col-3">
            <a href="/index.php" class="text-decoration-none" style="<?php color(1); ?>">
                <div class="row my-1">
                    <div class="col-12"><i class="iconfont icon-shouye"></i></div>
                    <div class="col-12">首页</div>
                </div>
            </a>
        </div>
        <div class="col-3">
            <a href="/Album/album.php" class="text-decoration-none" style="<?php color(2); ?>">
                <div class="row my-1">
                    <div class="col-12"><i class="iconfont icon-xiangce"></i></div>
                    <div class="col-12">掠影</div>
                </div>
            </a>
        </div>
        <div class="col-3">
            <a href="/tools.php" class="text-decoration-none" style="<?php color(3); ?>">
                <div class="row my-1">
                    <div class="col-12"><i class="iconfont icon-gongneng"></i></div>
                    <div class="col-12">功能</div>
                </div>
            </a>
        </div>
        <div class="col-3">
            <a href="/personal.php" class="text-decoration-none" style="<?php color(4); ?>">
                <div class="row my-1">
                    <div class="col-12"><i class="bi bi-person"></i></div>
                    <div class="col-12">个人</div>
                </div>
            </a>
        </div>
    </div>
</div>
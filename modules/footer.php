<?php
/**
 * @var string $Normal 基本网站信息API获取
 */
?>
<footer>
    <div class="container-fluid sticky-bottom">
        <div class="row my-5 text-center">
            <div class="col-12">© <?php echo date('Y') ?> <a href="https://www.na-wen.love/" class="text-decoration-none text-info" target="_blank">青空</a>. All Rights Reserved.</div>
            <div class="col-12"><a href="https://beian.miit.gov.cn/" class="text-decoration-none text-info" target="_blank"><i class="iconfont icon-ICPbeian"></i> <?php echo $Normal['data']['web_icp']['data'] ?></a></div>
            <div class="col-12"><a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=<?php echo $Normal['data']['web_beian_02']['data'] ?>" class="text-decoration-none text-info" target="_blank"><i class="iconfont icon-beianxinxi-ICP-gonganbeian"></i> <?php echo $Normal['data']['web_beian_01']['data'].' '.$Normal['data']['web_beian_02']['data'].'号' ?></a></div>
            <div class="col-12"><a href="https://icp.gov.moe/?keyword=20230227" class="text-decoration-none text-info" target="_blank">萌ICP备20230227号</a></div>
        </div>
    </div>
</footer>
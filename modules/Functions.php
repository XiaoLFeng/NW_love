<?php

// 获取数据
include $_SERVER['DOCUMENT_ROOT']."/config.inc.php";


class Functions
{
    public function Current_HTTP() {
        if ($_SERVER['SERVER_PORT'] == 443) {
            return 'https://'.$_SERVER['HTTP_HOST'];
        } else {
            return 'http://'.$_SERVER['HTTP_HOST'];
        }
    }
}
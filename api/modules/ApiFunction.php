<?php

// 获取数据
include $_SERVER['DOCUMENT_ROOT']."/config.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/plugins/SqlConn.php";

class ApiFunction
{
    /**
     * @param $data string
     * @return bool
     */
    public function Get_Session(string $data): bool {
        /*
         * 判断 Session 是否正确
         */
        global $config,$SqlConn;
        $Result_Session = mysqli_query($SqlConn,"SELECT * FROM ".$config['TABLE']['info']." WHERE `value`='session'");
        $Result_Session_Object = mysqli_fetch_object($Result_Session);
        if ($data == $Result_Session_Object->data) {
            return true;
        } else {
            return false;
        }
    }
}
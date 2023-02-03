<?php
include $_SERVER['DOCUMENT_ROOT'] . "/modules/header-loader.php";
/**
 * @var array $config
 * @var array $Normal
 */

if ($_POST['user'] == $Normal['data']['boy_user']['data'] or $_POST['user'] == $Normal['data']['girl_user']['data']) {
    $Api_Login_url = $Normal['data']['xf_api']['data'].'/auth/login/?ukey='.$Normal['data']['xf_key']['data'].'&username='.$_POST['user'].'&password='.$_POST['password'];
    $Api_Login_ch = curl_init($Api_Login_url);
    curl_setopt($Api_Login_ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    curl_setopt($Api_Login_ch, CURLOPT_RETURNTRANSFER, true);
    $Api_Login = curl_exec($Api_Login_ch);
    $Api_Login = json_decode($Api_Login,true);
    if ($Api_Login['output'] == 'SUCCESS') {
        setcookie('user',$_POST["user"],time()+86400*30,'/');
        echo <<<EOF
                <script>
                    alert('登陆成功');
                    window.location.href = "/";
                </script>
            EOF;
    } else {
        echo <<<EOF
                <script>
                    alert('用户名或密码错误');
                    window.history.go(-1);
                </script>
            EOF;
    }
} else {
    echo <<<EOF
                <script>
                    alert('非对应用户哦~');
                    window.history.go(-1);
                </script>
            EOF;
}

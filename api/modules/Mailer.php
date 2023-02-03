<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require $_SERVER["DOCUMENT_ROOT"].'/plugins/PHPMailer/Exception.php';
require $_SERVER["DOCUMENT_ROOT"].'/plugins/PHPMailer/PHPMailer.php';
require $_SERVER["DOCUMENT_ROOT"].'/plugins/PHPMailer/SMTP.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/api/modules/Mail_Templates.php';
$SendMail = new Mail_Templates();

class SendMail
{
    // 发送邮件函数
    /**
     * @param string $type 类型
     * @param string $email 发件对象
     * @return bool
     */
    public function Mailer(string $type,string $email,string $user): bool {
        global $SendMail;
        global $config;
        $Mail = new PHPMailer(true);
        try {
            // 服务器配置
            $Mail->CharSet = "UTF-8";
            $Mail->SMTPDebug = 0;
            $Mail->isSMTP();
            $Mail->Host = $config['SMTP']['HOST'];
            $Mail->SMTPAuth = true;
            $Mail->Username = $config['SMTP']['USER'];
            $Mail->Password = $config['SMTP']['PASSWORD'];
            $Mail->SMTPSecure = $this->SSL_Check('secure');
            $Mail->Port = $this->SSL_Check('port');
            $Mail->setFrom('noreplay@x-lf.cn', '筱锋机器人');
            $Mail->addAddress($email);

            $Mail->isHTML(true);
            $Mail->Subject = '筱锋工具箱 - '.$type; // 邮箱标题
            $Mail->Body = $SendMail->Templates($type,$user); // 邮箱正文
            $Mail->AltBody = '筱锋工具箱 - '.$type.'：'.$email; // 不支持HTML显示内容

            $Mail->send();
            return true;
        } catch (Exception $e) {
            echo '邮件发送失败：', $Mail->ErrorInfo;
            return false;
        }
    }
    // 检查是否是SSL
    private function SSL_Check($type) {
        global $config;
        if ($type == 'port') {
            if ($_SERVER['SERVER_PORT'] != '443') {
                return $config['SMTP']['NOSSL'];
            } else {
                return $config['SMTP']['SSL'];
            }
        } elseif ($type == 'secure') {
            if ($_SERVER['SERVER_PORT'] != '443') {
                return 'TLS';
            } else {
                return 'ssl';
            }
        } else {
            return null;
        }
    }

}
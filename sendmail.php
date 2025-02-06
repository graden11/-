<?php
// 导入 PHPMailer 类
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 引入 PHPMailer 自动加载文件
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// 实例化 PHPMailer 对象
$mail = new PHPMailer(true);

try {
    // 配置 SMTP 服务器
    $mail->isSMTP();
    $mail->Host       = 'smtp.qq.com'; // QQ 邮箱 SMTP 服务器地址
    $mail->SMTPAuth   = true;           // 启用 SMTP 认证
    $mail->Username   = '2731084818@qq.com'; // QQ 邮箱用户名
    $mail->Password   = 'kahcdcncfyjeddej'; // QQ 邮箱授权码，不是登录密码
    $mail->SMTPSecure = 'ssl';          // 使用 SSL 加密连接
    $mail->Port       = 465;            // SMTP 端口号，QQ 邮箱 SSL 协议端口号为 465

    // 设置发件人和收件人
    $mail->setFrom('2731084818@qq.com', '郭江伟'); // 发件人邮箱和姓名
    $mail->addAddress('3030402820@example.com', '衍生'); // 收件人邮箱和姓名

    // 设置邮件主题和内容
    $mail->isHTML(true); // 将邮件内容设置为 HTML 格式
    $mail->Subject = 'Test Email'; // 邮件主题
    $mail->Body    = 'This is a test email sent from PHPMailer.'; // 邮件内容

    // 发送邮件
    $mail->send();
    echo 'Email has been sent successfully.';
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}
?>

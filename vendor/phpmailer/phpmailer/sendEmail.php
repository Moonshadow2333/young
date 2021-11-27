<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

// echo dirname(dirname(dirname(__DIR__)));
// exit;
require './src/Exception.php';
require './src/PHPMailer.php';
require './src/SMTP.php';
date_default_timezone_set('PRC');

function sendEmail($Mailer, $Recipient, $info)
{
    $all_user_info = getAllUserInfo();
    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    try {
        //服务器配置
        $mail->CharSet   = "UTF-8"; //设定邮件编码
        $mail->SMTPDebug = 0; // 调试模式输出
        $mail->isSMTP(); // 使用SMTP
        $mail->Host       = 'smtp.qq.com'; // SMTP服务器
        $mail->SMTPAuth   = true; // 允许 SMTP 认证
        $mail->Username   = $Mailer; // SMTP 用户名  即邮箱的用户名
        $mail->Password   = 'kdflqebpzfnmejcd'; // SMTP 密码  部分邮箱是授权码(例如163邮箱)
        $mail->SMTPSecure = 'ssl'; // 允许 TLS 或者ssl协议
        $mail->Port       = 465; // 服务器端口 25 或者465 具体要看邮箱服务器支持

        $mail->setFrom($Mailer, '谢伟东'); //发件人
        $mail->addAddress($Recipient, 'Joe'); // 收件人
        //$mail->addAddress('ellen@example.com');  // 可添加多个收件人
        $mail->addReplyTo($Mailer, 'info'); //回复的时候回复给哪个邮箱 建议和发件人一致
        //$mail->addCC('cc@example.com');                    //抄送
        //$mail->addBCC('bcc@example.com');                    //密送

        //发送附件
        // $mail->addAttachment('../xy.zip');         // 添加附件
        // $mail->addAttachment('../thumb-1.jpg', 'new.jpg');    // 发送附件并且重命名

        //Content
        $mail->isHTML(true); // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
        $mail->Subject = '青年大学习观看提醒' . time();
        $mail->Body    = '<h1>'. $info.'</h1>' . date('Y-m-d H:i:s');
        $mail->AltBody = '如果邮件客户端不支持HTML则显示此内容';

        $mail->send();
        echo '向'.$all_user_info[$Recipient].'|'.$Recipient.'发送邮件成功<br/>';
    } catch (Exception $e) {
        echo '邮件发送失败: ', $mail->ErrorInfo;
    }
}

function getAllUserInfo()
{
    // 全部同学的信息。(因为sendEmail（）函数内部需要全部同学的信息，所以就封装成了函数。)
    $dir = getDir();
    return include($dir.'\info.php');
}
function getDir()
{
    return dirname(dirname(dirname(__DIR__)));
}
function getUndoneMails()
{
    $dir = getDir();
    // 未观看青年大学习的同学。
    $undone = include($dir.'\undone.php');
    // 全部同学的信息。
    $all_user_info = getAllUserInfo();
    $result = array_intersect($all_user_info, $undone);
    // 未观看青年大学习的同学的邮箱。
    $recipients = array_keys($result);
    return $recipients;
}
function pages($recipients, $sendEmailNums)
{
    $current = isset($_GET['current']) ? $_GET['current'] : 1;
    $totalUsers = count($recipients);
    // 每次发送邮件的数量。
    // 总次数（类似于总页数）
    if ($totalUsers <= $sendEmailNums) {
        $totalPages = 1;
    } else {
        $totalPages = ceil($totalUsers / $sendEmailNums);
    }
    $next = $current + 1;
    if ($next > $totalPages) {
        $next = $totalPages;
        // exit;
    }
    $start = ($current - 1) * $sendEmailNums;
    $mailto = array_slice($recipients, $start, $sendEmailNums);
    // var_dump($mailto);
    for ($i=0;$i<count($mailto);$i++) {
        $Recipient = $mailto[$i];
        if ($Recipient == '1104505901@qq.com') {
            // 估计李攀把我给屏蔽了，就不给他发邮件了。
            continue;
        }
        // echo $Recipient.'<br/>';
        sendEmail('1815265375@qq.com', $Recipient, '工业181团支书谢伟东友情提醒你：花几分钟看一下青年大学习。');
        // sleep(1);
    }
    echo '<br/>';
    echo '共'.$totalPages.'页 当前第'.$current.'页 <a href=sendEmail.php?current='.$next.'>下一页》》</a>';
}
$recipients = getUndoneMails();
pages($recipients, 5);


/*
    测试内容
    $Mailer = '1815265375@qq.com';
    $Recipient = '3523421745@qq.com';
    $info = '这里是邮件内容';
    sendEmail('1815265375@qq.com', '3523421745@qq.com', '工业181团支书谢伟东友情提醒你：花几分钟看一下青年大学习。',$all_user_info);
    exit;
*/

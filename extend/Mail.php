<?php
/**
 * User: ruoning
 * Date: 2021/3/13
 * motto: 知行合一!
 */
use \PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    /**
     * 发送邮件
     * @param $from // 收件人
     * @param $name //
     * @param $title
     * @param $message
     * @return bool
     * @throws \app\exception\EmailException
     */
    public static function sendEmail($from, $name, $title, $message)
    {
        //实例化
        $mail = new PHPMailer();
        try{
            //邮件调试模式
            $mail->SMTPDebug = 0; // // SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
            //设置邮件使用SMTP
            $mail->isSMTP();
            // 设置邮件程序以使用SMTP
            $mail->Host = 'smtp.qq.com';
            // 设置邮件内容的编码
            $mail->CharSet='UTF-8';
            // 启用SMTP验证
            $mail->SMTPAuth = true;
            // SMTP username
            $mail->Username = config("mail")['host'];
            // SMTP password
            $mail->Password = config("mail")['password'];
            // 启用TLS加密，`ssl`也被接受
            //$mail->SMTPSecure = 'tls';
            // 连接的TCP端口
            $mail->Port = 587;
            //设置发件人
            $mail->setFrom("3248774731@qq.com", "lrn");

            //  添加收件人1
            $mail->addAddress($from, $name); // Add a recipient
            //$mail->addAddress('ellen@example.com');   // Name is optional
            //收件人回复的邮箱
//            $mail->addReplyTo('415813765@qq.com', 'laowang');

            //抄送
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
            //附件
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            //Content
            // 将电子邮件格式设置为HTML
            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body    = $message;
            //$mail->AltBody = '这是非HTML邮件客户端的纯文本';
            if (!$mail->send()) {
                throw new \think\Exception();
            }
            //echo 'Message has been sent';
            //发送邮件过程信息
        
            $mail->isSMTP();
            \think\Log::info("Send Email code Success email: {$from} name: {$name}");

            return true;
        }catch (\Exception $e){
           // echo 'Mailer Error: ' . $mail->ErrorInfo;
            \think\Log::info("Send Email code Fail email: {$from} name: {$name} ErrorMsg: {$mail->ErrorInfo}");
        }

        return false;
    }
}

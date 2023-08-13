<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/src/SMTP.php';
require $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/src/Exception.php';

class Mail
{
    public function sendMail($subject, $content, $to_mail, $to_name)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.daum.net';                         // smtp 주소
            $mail->SMTPAuth = true;                                // smtp 인증 사용여부
            $mail->Port = 465;                                     // smtp 포트
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;       // smtp 암호화 SSL
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // smtp 암호화 TLS
            $mail->Username = '';        // 이메일 주소
            $mail->Password = '';                     // 비밀번호

            $mail->CharSet = 'utf-8'; // 인코딩
            $mail->Encoding = "base64"; // 인코딩

            // 보내는 사람
            $mail->SetFrom('', '');
            // 받는 사람
            $mail->AddAddress($to_mail, $to_name);

            //Content
            $mail->isHTML(true);                                  // HTML 태그 사용 여부
            $mail->Subject = $subject;
            $mail->Body    = $content;

            if($mail->send()){
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
            return 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }
}

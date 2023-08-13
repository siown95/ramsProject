<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require('PHPMailer/src/PHPMailer.php');
require('PHPMailer/src/SMTP.php');
require('PHPMailer/src/Exception.php');

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.daum.net'; // smtp 주소
$mail->SMTPAuth = true; // smtp 인증 사용여부
$mail->Port = 465; // smtp 포트
$mail->SMTPSecure = "ssl"; // smtp 암호화
$mail->Username = "siwon95@daum.net"; // 이메일 주소
$mail->Password = "masfio177!"; // 비밀번호

$mail->CharSet = 'utf-8'; // 인코딩
$mail->Encoding = "base64"; // 인코딩
// 보내는 사람
$mail->setFrom('siwon95@daum.net', 'test');
// 받는 사람
$mail->AddAddress("siwon95@daum.net", "test");
// HTML 태그 사용 여부
$mail->isHTML(true);

// 제목
$mail->Subject = 'Here is the subject';
// 본문 (HTML 태그 사용 가능)
$mail->Body = 'This is the HTML message body <b>in bold!</b>
                        <br>
                    <img src="https://search1.kakaocdn.net/thumb/R120x174.q85/?fname=http%3A%2F%2Ft1.daumcdn.net%2Flbook%2Fimage%2F1452601%3Ftimestamp%3D20210915172751">';
try {
    $mail->send(); // 발송
} catch (Exception $e) {
    echo $e->errorMessage();
}

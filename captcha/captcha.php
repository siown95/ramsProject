<?php
class Captcha
{
  // (A) PRIME THE CAPTCHA - GENERATE RANDOM STRING IN SESSION
  function prime()
  {
    $length = rand(4, 7); // 5~8자리
    $char = "0123456789";
    $max = strlen($char) - 1;
    $random = "";
    for ($i = 0; $i <= $length; $i++) {
      $random .= substr($char, rand(0, $max), 1);
    }
    $_SESSION["captcha"] = $random;
  }

  // (B) DRAW THE CAPTCHA IMAGE
  function draw($output = 1, $width = 200, $height = 35, $fontsize = 18)
  {
    // (B1) OOPS.
    $i = rand(0, 4);
    $font = $_SERVER['DOCUMENT_ROOT'] . "/captcha/captcha" . $i . ".ttf";
    // $font = $_SERVER['DOCUMENT_ROOT'] . "/captcha/captcha4.ttf";
    
    if (!isset($_SESSION["captcha"])) {
      throw new Exception("자동입력방지 문구가 생성되지 않았습니다.");
    }

    // (B2) CREATE BLANK IMAGE
    $captcha = imagecreatetruecolor($width, $height);

    // (B4) THE TEXT SIZE
    $text_size = imagettfbbox($fontsize, 0, $font, $_SESSION["captcha"]);
    $text_width = max([$text_size[2], $text_size[4]]) - min([$text_size[0], $text_size[6]]);
    $text_height = max([$text_size[5], $text_size[7]]) - min([$text_size[1], $text_size[3]]);

    // (B5) CENTERING THE TEXT BLOCK
    $centerX = CEIL(($width - $text_width) / 2);
    $centerX = $centerX < 0 ? 0 : $centerX;
    $centerX = CEIL(($height - $text_height) / 2);
    $centerY = $centerX < 0 ? 0 : $centerX;

    $noiceLines = 3;
    $noiceDots = 250;
    $noiceColor = imagecolorallocate($captcha, 150, 150, 150);
    for ($i = 0; $i < $noiceLines; $i++) {
      imageline(
        $captcha,
        mt_rand(0, CEIL(($width - $text_width) / 2)),
        mt_rand(0, CEIL(($height - $text_height) / 2)),
        mt_rand(0, $width),
        mt_rand(0, $height),
        $noiceColor
      );
    }
    for ($i = 0; $i < $noiceDots; $i++) {
      imagefilledellipse(
        $captcha,
        mt_rand(0, $width),
        mt_rand(0, $width),
        3,
        3,
        $noiceColor
      );
    }

    // (B6) RANDOM OFFSET POSITION OF THE TEXT + COLOR
    $colornow = imagecolorallocate($captcha, 255, 255, 255); // 글자 색 -> 흰색 (색맹, 색약)
    imagettftext($captcha, $fontsize, 0, $centerX, $centerY, $colornow, $font, $_SESSION["captcha"]); // 3번째 각도

    header("Content-type: image/png");
    imagepng($captcha);
    imagedestroy($captcha);
  }
}
// (D) CREATE CAPTCHA OBJECT
session_start(); // Remove if session already started
$PHPCAP = new Captcha();

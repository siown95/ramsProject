<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/Mail.php";

$user_no = !empty($_POST['user_no']) ? $_POST['user_no'] : '';
$status = false;
if (empty($user_no)) {
    throw new Exception('잘못된 접근입니다.', 602);
} else {
    $permissionCmp = new permissionCmp();
    $rs = $permissionCmp->searchMemberInfo($user_no, 'adm');
    $user_info = $permissionCmp->getDataAssoc($rs);

    $mail = new Mail();
    $subject = '아이디 찾기 결과입니다.';

    $user_name = $user_info['user_name'];
    $user_email = $user_info['email'];

    $params = array("user_id" => $user_info['user_id']);

    $content = import_ob($_SERVER['DOCUMENT_ROOT'] . '/common/mailform.html', $params);

    $result = $mail->sendMail($subject, $content, $user_email, $user_name);

    if ($result) {
        $status = true;
        $return_data['msg'] = '메일이 발송되었습니다.';
    } else {
        throw new Exception('잘못된 접근입니다.', 602);
    }
}

$jsonResult = array(
    'success' => $status,
    'msg'     => $return_data['msg']
);

echo json_encode($jsonResult);
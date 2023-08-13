<?php
if(empty($_POST)){
    header('HTTP/2 403 Forbidden');
    exit;
}
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/captcha/index.php";

$permissionCls = new permissionCmp();
$infoClassCmp = new infoClassCmp();

$status = 'fail';
$returnMsg = 'no data';
$now_date = date("Y-m-d H:i:s");

$user_id  = !empty($_POST['user_id']) ? $_POST['user_id'] : '';
$password = !empty($_POST['password']) ? $_POST['password'] : '';
$center_idx = !empty($_POST['center']) ? $_POST['center'] : '';
$captcha  = !empty($_POST['captcha']) ? $_POST['captcha'] : '';

if (empty($user_id) || empty($password) || empty($center_idx) ) {
    $result_arr = array(
        "status" => $status,
        "msg"    => $returnMsg
    );
    $result = json_encode($result_arr);
    echo $result;
    exit;
}

$centerIdx = $infoClassCmp->getFranchiseeInfo($center_name);

$idCheck = $permissionCls->idCheck($user_id, 'student');
$idData = $permissionCls->getDataAssoc($idCheck);

$failLog = $permissionCls->getLog();

if (!empty($failLog)) {
    $maxFailDate = date("Y-m-d H:i:s", strtotime($failLog . "+ 30 minute"));
    if ($now_date < $maxFailDate) {
        $returnMsg = "5회 이상 잘못된 로그인 시도가 감지되었습니다.\n30분 후 다시 로그인 시도가 가능합니다.";
        $status = 'lock';

        $result_arr = array(
            "status" => $status,
            "msg"    => $returnMsg
        );

        $result = json_encode($result_arr);
        echo $result;
        exit;
    }
}

//등록된 아이디일때
if (!empty($idData['cnt'])) {
    if (isset($_SESSION['captcha'])) { //로그인 실패 후 captcha 적용
        if (empty($captcha)) {
            $returnMsg = '자동입력 방지 문구를 입력하세요.';

            $result_arr = array(
                "status" => $status,
                "msg"    => $returnMsg
            );

            $result = json_encode($result_arr);
            echo $result;
            exit;
        }

        if ($_SESSION['captcha'] != $captcha) {
            $returnMsg = '자동입력 방지 문구를 확인하세요.';

            $result_arr = array(
                "status" => $status,
                "msg"    => $returnMsg
            );

            $result = json_encode($result_arr);
            echo $result;
            exit;
        }
    }

    $rs = $permissionCls->getPermission($user_id, $password, 'student', $center_idx);
    $user_data = $permissionCls->getDataAssoc($rs);

    if (!empty($user_data)) {
        if ($user_data['state'] == '99') {
            $returnMsg = '승인대기 상태입니다. 관리자에게 문의 바랍니다.';
        } else if ($user_data['state'] == '01') {
            $returnMsg = '휴직 상태입니다. 관리자에게 문의 바랍니다.';
        } else if ($user_data['state'] == '02') {
            $returnMsg = '퇴직 상태입니다. 관리자에게 문의 바랍니다.';
        } else {
            $status = 'success';
            $_SESSION['logged_no'] = $user_data['user_no'];
            $_SESSION['logged_id'] = $user_data['user_id'];
            $_SESSION['logged_name'] = $user_data['user_name'];
            $_SESSION['logged_phone'] = phoneFormat($user_data['user_phone']);
            $_SESSION['logged_email'] = $user_data['email'];
            $_SESSION['center_idx'] = $center_idx;

            if($user_data['is_admin'] == 'Y'){
                $_SESSION['is_admin'] = 'Y';
            }

            unset($_SESSION['captcha']);
            unset($_SESSION['fail_cnt']);
            session_write_close();
        }
    } else {
        $returnMsg = "아이디 혹은 비밀번호를 확인해주세요. (" . ++$_SESSION['fail_cnt'] . "회 실패)";
        $captcha_page = getCaptcha();

        if ($_SESSION['fail_cnt'] >= 5) {
            unset($_SESSION['fail_cnt']);
            $permissionCls->failLogInsert();
        }
    }
} else {
    $returnMsg = "아이디 혹은 비밀번호를 확인해주세요. (" . ++$_SESSION['fail_cnt'] . "회 실패)";
    $captcha_page = getCaptcha();

    if ($_SESSION['fail_cnt'] >= 5) {
        unset($_SESSION['fail_cnt']);
        $permissionCls->failLogInsert();
    }
}

$result_arr = array(
    "status" => $status,
    "captcha_page" => $captcha_page,
    "msg"    => $returnMsg
);

$result = json_encode($result_arr);
echo $result;
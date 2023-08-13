<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";

$db = new DBCmp();

include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";

$permissionCmp = new permissionCmp();

$user_no = !empty($_SESSION['logged_no']) ? $_SESSION['logged_no'] : '';

if (!empty($user_no)) {
    $result = $permissionCmp->getMyInfo($user_no, 'student');
    $user_info = $permissionCmp->getDataAssoc($result);
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php
    $stat = "student";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/common/common_script.php";
    ?>
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <script src="/center/student/js/user_setting.js?v=<?= date("YmdHis") ?>"></script>
</head>

<body class="size-14">
    <!-- 헤더 -->
    <?php include "navbar.php"; ?>
    <script>
        var center_idx = '<?= $_SESSION['center_idx'] ?>';
        var student_no = '<?= $_SESSION['logged_no'] ?>';
    </script>
    <!-- 컨텐츠 -->
    <main style="min-height: 79vh;">
        <div class="container-fluid">
            <div class="card border-left-primary shadow mt-2">
                <div class="card-header">
                    <h6>개인정보 수정</h6>
                </div>
                <div class="card-body">
                    <form id="userSettingForm">
                        <input type="hidden" id="user_no" value="<?= $user_no ?>">
                        <div class="input-group justify-content-end mb-3">
                            <button type="button" id="btnSaveUserSet" class="btn btn-sm btn-primary" onclick="changeUserInfo()"><span class="icon mr-1"><i class="fas fa-save"></i></span>저장</button>
                        </div>
                        <div class="input-group mb-2">
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <input type="text" id="txtName" class="form-control" value="<?= $user_info['user_name'] ?>" maxlength="10" placeholder="이름">
                                    <label for="txtName">이름</label>
                                </div>
                            </div>
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <input type="text" id="txtId" class="form-control bg-white" value="<?= $user_info['user_id'] ?>" placeholder="아이디" autocomplete="username" disabled>
                                    <label for="txtId">아이디</label>
                                </div>
                            </div>
                            <div class="form-inline">
                                <div class="form-floating">
                                    <input type="password" id="txtPassword" class="form-control" maxlength="20" placeholder="비밀번호" autocomplete="new-password">
                                    <label for="txtPassword">비밀번호</label>
                                </div>
                            </div>
                            <div class="form-inline form-check align-self-center">
                                <label class="form-check-label me-2">성별</label>
                                <div class="form-check form-check-inline">
                                    <input id="rdoSex1" class="form-check-input" type="radio" name="gender" value="M" <?= ($user_info['gender'] == 'M') ? 'checked' : '' ?>>
                                    <label class="form-check-label">남</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input id="rdoSex2" class="form-check-input" type="radio" name="gender" value="F" <?= ($user_info['gender'] == 'F') ? 'checked' : '' ?>>
                                    <label class="form-check-label">여</label>
                                </div>
                            </div>

                        </div>
                        <div class="input-group mb-2">
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <input type="date" id="dtBirthday" class="form-control small" value="<?= $user_info['birth'] ?>">
                                    <label for="dtBirthday">생년월일</label>
                                </div>
                            </div>
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <input type="text" id="txtHp" class="form-control" placeholder="연락처(휴대전화번호)" maxlength="11" value="<?= phoneFormat($user_info['user_phone']) ?>">
                                    <label for="txtHp">연락처(휴대전화번호)</label>
                                </div>
                            </div>
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <input type="email" id="txtEmail" class="form-control" placeholder="이메일" maxlength="50" value="<?= $user_info['email'] ?>">
                                    <label for="txtEmail">이메일</label>
                                </div>
                            </div>
                            <div class="form-inline">
                                <div class="form-floating">
                                    <input type="text" id="txtSchool" class="form-control" placeholder="학교" value="<?= $user_info['school_name'] ?>" maxlength="30">
                                    <label for="txtSchool">학교</label>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="form-inline me-2">
                                <input type="text" id="txtUserSettingZipCode" class="form-control bg-white" value="<?= $user_info['zipcode'] ?>" placeholder="우편번호" disabled>
                            </div>
                            <input type="text" id="txtAddr" class="form-control me-1" value="<?= $user_info['address'] ?>" maxlength="100" placeholder="주소">
                            <div class="input-group-append">
                                <button id="btnAddrUserSetting" class="btn btn-outline-success" type="button"><span class="icon mr-1"><i class="fas fa-search-location"></i></span>주소찾기</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
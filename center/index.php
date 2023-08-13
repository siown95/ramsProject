<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";
$permissionCls = new permissionCmp();
$franchiseInfo = new infoClassCmp();
$engName = explode('/', $_SERVER['REQUEST_URI']);
$franchise_idx = $franchiseInfo->getFranchiseeInfo($engName[1]);

//관리자 센터페이지 접근시 원장 선생님으로 로그인

if (!empty($_SESSION['logged_id_adm']) && $_SESSION['logged_id_adm'] == 'admin') {
    $_SESSION['center_idx'] = $franchise_idx;
    $owner_id = $franchiseInfo->getOwnerId($franchise_idx);

    if (empty($owner_id)) {
        echo "<script>
                      alert('잘못된 접근입니다1.');
                      location.href = '/';
                  </script>";
    } else {
        $rs = $franchiseInfo->getFranchiseeLogin($owner_id, $_SESSION['center_idx']);
        $owner_data = $permissionCls->getDataAssoc($rs);

        if (!empty($owner_data)) {
            $_SESSION['logged_no'] = $owner_data['user_no'];
            $_SESSION['logged_id'] = $owner_data['user_id'];
            $_SESSION['logged_name'] = $owner_data['user_name'];
            $_SESSION['logged_phone'] = phoneFormat($owner_data['user_phone']);
            $_SESSION['logged_email'] = $owner_data['email'];

            if ($owner_data['is_admin'] == 'Y') {
                $_SESSION['is_admin'] = 'Y';
            } else {
                $_SESSION['is_admin'] = 'N';
            }
        } else {
            echo "<script>
                          alert('잘못된 접근입니다.');
                          location.href = '/';
                      </script>";
        }
    }
} else if(empty($_SESSION['center_idx']) || empty($_SESSION['logged_id'])) {
    echo "<script>
                  alert('잘못된 접근입니다.');
                  location.href = '/';
              </script>";
}

?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <?php
    $stat = 'center';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
</head>

<body class="size-14">
    <?php
    include_once('navbar.php');
    include_once('menu.php');
    ?>
    <main>
        <div class="container-fluid">
            <div class="row" style="min-height: 75vh;">
                <div id="contents" class="container-fluid col-12"></div>
                <div id="contents_div1" class="col-6"></div>
                <div id="contents_div2" class="col-6"></div>
            </div>
        </div>
    </main>
    <?php
    include_once('footer.php');
    ?>
    <script>
        $(document).ready(function() {
            $('#contents').load('dashboard.php');
        });
    </script>
</body>

</html>
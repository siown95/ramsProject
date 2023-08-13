<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/CommuteController.php";

$db = new DBCmp();
$commuteController = new CommuteController();
if ($_SESSION['logged_id'] == 'admin' || $_SESSION['is_admin'] == 'Y') {
    $user_data = $commuteController->selectEmployee($_SESSION['center_idx']);
} else {
    $user_data = $commuteController->selectEmployee($_SESSION['center_idx'], $_SESSION['logged_no']);
}

$now_date = date("Y-m");
?>

<script src="/center/js/workcheck.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var center_idx = '<?=$_SESSION['center_idx']?>';
</script>
<!-- 콘텐츠 -->
<div class="row mt-2 size-14">
    <div class="col-4">
        <div class="card border-left-primary shadow h-100">
            <div class="card-header">
                <h6>직원목록</h6>
            </div>
            <div class="card-body">
                <div class="container">
                    <table id="workCheckTable" class="table table-bordered table-hover">
                        <thead class="text-center align-middle">
                            <th>이름</th>
                            <th>직책</th>
                            <th class="d-none">code_num</th>
                        </thead>
                        <tbody class="text-center">
                            <?php if (!empty($user_data)) { ?>
                                <?php foreach ($user_data as $key => $val) { ?>
                                    <tr class="emt" data-user-no="<?= $val['user_no'] ?>">
                                        <td><?= $val['user_name'] ?></td>
                                        <td><?= $val['code_name'] ?></td>
                                        <td class="d-none"><?= $val['position'] ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-8">
        <div class="card border-left-primary shadow h-100">
            <div class="card-header">
                <h6>근태정보</h6>
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="input-group justify-content-end mb-1">
                        <div class="form-floating">
                            <input type="month" class="form-control" id="now_date" value="<?= $now_date ?>">
                            <label for="now_date">기준년월</label>
                        </div>
                    </div>
                    <table id="commuteLogTable" class="table table-bordered table-hover">
                        <thead class="align-middle text-center">
                            <th>일자</th>
                            <th>출근시간</th>
                            <th>퇴근시간</th>
                            <th>휴게시간(분)</th>
                            <th>근무시간(시간/분)</th>
                            <th>휴일</th>
                        </thead>
                        <tbody id="commuteLogList">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";

$db = new DBCmp();

include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";

$permissionCmp = new permissionCmp();
$infoClassCmp = new infoClassCmp();
$codeInfoCmp = new codeInfoCmp();

$user_no = !empty($_SESSION['logged_no']) ? $_SESSION['logged_no'] : '';

if (!empty($user_no)) {
    $result = $permissionCmp->getMyInfo($user_no, 'center');
    $user_info = $permissionCmp->getDataAssoc($result);
}
$teacherList = $infoClassCmp->teacherList($_SESSION['center_idx']);
$gradeList = $codeInfoCmp->getCodeInfo('02');
?>
<script>
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
    var user_idx = '<?= $_SESSION['logged_no'] ?>';
    var grp_idx = '';
    var grp_arr = [];
    var grpd_arr = [];
    var msg_arr = [];
</script>
<script type="text/javascript" src="js/msg_group.js?v=<?= date('YmdHis') ?>"></script>
<div class="mt-2 size-14">
    <div class="card border-left-primary shadow mt-2">
        <div class="card-header">
            <h6>메시지 그룹 관리</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- 메시지 그룹 목록 및 생성, 공유 -->
                <div class="col-4">
                    <div class="input-group mb-2">
                        <div class="form-floating me-2">
                            <input type="text" id="txtMgGrpName" class="form-control" placeholder="이름" maxlength="25" />
                            <label for="txtMgGrpName">이름</label>
                        </div>
                        <div class="form-inline align-self-center">
                            <button type="button" id="btnMsgGroupSave" class="btn btn-sm btn-primary"><i class="fas fa-save me-1"></i>그룹생성</button>
                            <button type="button" id="btnMsgGroupSelDelete" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash me-1"></i>선택그룹삭제</button>
                        </div>
                    </div>
                    <div class="input-group mb-2">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select id="selTargetGroup" class="form-select">
                                    <option value="">선택</option>
                                    <?php
                                    foreach ($teacherList as $key => $val) {
                                        if ($val['user_no'] != $_SESSION['logged_no']) {
                                            echo "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selTargetGroup">공유그룹</label>
                            </div>
                        </div>
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select id="selTargetTeacher" class="form-select">
                                    <option value="">선택</option>
                                    <?php
                                    foreach ($teacherList as $key => $val) {
                                        if ($val['user_no'] != $_SESSION['logged_no']) {
                                            echo "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selTargetTeacher">공유대상</label>
                            </div>
                        </div>
                        <div class="form-inline align-self-center">
                            <button type="button" id="btnMsgGroupShare" class="btn btn-sm btn-primary"><i class="fa-solid fa-square-share-nodes me-1"></i>그룹공유</button>
                        </div>
                    </div>
                    <table id="MsgGroupTable" class="table table-sm table-bordered table-hover">
                        <thead class="align-middle text-center">
                            <th width="10%"><input type="checkbox" id="chkAllMsgGrp" class="form-check-input" /></th>
                            <th width="75%">그룹명</th>
                            <th width="15%"></th>
                        </thead>
                        <tbody class="align-middle text-center">
                        </tbody>
                    </table>
                </div>
                <!-- 메시지 그룹 상세목록 -->
                <div class="col-4">
                    <div class="text-end mb-2">
                        <button type="button" id="btnMsgGroupDSelDelete" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash me-1"></i>선택삭제</button>
                    </div>
                    <table id="MsgGroupDetailTable" class="table table-sm table-bordered table-hover">
                        <thead class="align-middle text-center">
                            <th width="10%"><input type="checkbox" id="chkAllMsgGrpDetail" class="form-check-input" /></th>
                            <th width="30%">이름</th>
                            <th width="45%">전화번호</th>
                            <th width="15%"></th>
                        </thead>
                        <tbody class="align-middle text-center">
                        </tbody>
                    </table>
                </div>
                <!-- 메시지 그룹 생성 수정 -->
                <div class="col-4">
                    <div class="input-group mb-2">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select id="selMsgGroup" class="form-select">
                                    <option value="" selected disabled>선택</option>
                                </select>
                                <label for="selMsgGroup">그룹</label>
                            </div>
                        </div>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <div class="form-inline align-self-center me-2">
                            <div class="form-floating">
                                <select id="selKind" class="form-select">
                                    <option value="">선택</option>
                                    <option value="1">학생</option>
                                    <option value="2">직원</option>
                                    <option value="4">수업</option>
                                    <option value="5">휴원</option>
                                    <option value="6">퇴원</option>
                                    <option value="7">신규상담</option>
                                    <option value="8">정규상담</option>
                                </select>
                                <label for="selKind">분류</label>
                            </div>
                        </div>
                        <div id="divGrade" class="form-inline align-self-center searchdiv me-2">
                            <div class="form-floating">
                                <select id="selMsgGrade" class="form-select">
                                    <option value="">선택</option>
                                    <?php
                                    foreach ($gradeList as $key => $val) {
                                        echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                                <label for="selMsgGrade">학년</label>
                            </div>
                        </div>

                        <div id="divTeacher" class="form-inline align-self-center searchdiv me-2">
                            <div class="form-floating">
                                <select id="selInCharge" class="form-select">
                                    <option value="">선택</option>
                                    <?php
                                    foreach ($teacherList as $key => $val) {
                                        echo "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                                <label for="selInCharge">담당</label>
                            </div>
                        </div>

                        <div id="divLesson" class="form-inline align-self-center searchdiv me-2">
                            <div class="form-floating">
                                <select id="selLesson" class="form-select">
                                    <option value="">선택</option>
                                </select>
                                <label for="selLesson">수업</label>
                            </div>
                        </div>

                        <div id="divRest" class="form-inline align-self-center searchdiv me-2">
                            <div class="form-floating">
                                <input type="month" id="dtRestMonth" class="form-control" value="<?= date('Y-m') ?>">
                                <label for="dtRestMonth">휴원</label>
                            </div>
                        </div>

                        <div id="divOut" class="form-inline align-self-center searchdiv me-2">
                            <div class="form-floating">
                                <input type="month" id="dtOutMonth" class="form-control" value="<?= date('Y-m') ?>">
                                <label for="dtOutMonth">퇴원</label>
                            </div>
                        </div>

                        <div id="divNewCounsel" class="form-inline align-self-center searchdiv me-2">
                            <div class="form-floating">
                                <input type="month" id="dtNewCounselMonth" class="form-control" value="<?= date('Y-m') ?>">
                                <label for="dtNewCounselMonth">신규상담</label>
                            </div>
                        </div>

                        <div id="divCounsel" class="form-inline align-self-center searchdiv me-2">
                            <div class="form-floating">
                                <input type="month" id="dtCounselMonth" class="form-control" value="<?= date('Y-m') ?>">
                                <label for="dtCounselMonth">정규상담</label>
                            </div>
                        </div>

                        <div class="form-inline align-self-center me-2">
                            <button id="btnSearchMsgList" class="btn btn-sm btn-outline-secondary" type="button"><i class="fa-solid fa-magnifying-glass me-1"></i>검색</button>
                        </div>
                    </div>
                    <div class="text-end mb-2">
                        <button type="button" id="btnMsgListMove" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-arrow-left"></i></button>
                    </div>
                    <table id="MsgInfoTable" class="table table-sm table-bordered table-hover">
                        <thead class="text-center align-middle">
                            <th><input type="checkbox" id="chkAllCheck" class="form-check-input" /></th>
                            <th>이름</th>
                            <th>전화번호</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
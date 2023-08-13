<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

$infoClassCmp = new infoClassCmp();
$selTeacherList = $infoClassCmp->teacherList($_SESSION['center_idx']);
$selStudentList = $infoClassCmp->studentList($_SESSION['center_idx']);
?>
<script src="/center/js/center_notice.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var userInfo = {
        user_no: '<?= $_SESSION['logged_no'] ?>',
        user_id: '<?= $_SESSION['logged_id'] ?>',
        user_name: '<?= $_SESSION['logged_name'] ?>',
        user_phone: '<?= phoneFormat($_SESSION['logged_phone']) ?>',
        user_email: '<?= $_SESSION['logged_email'] ?>',
        is_admin: '<?= $_SESSION['is_admin'] ?>',
    }
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
</script>
<div class="card border-left-primary shadow mt-2 mb-2 size-14">
    <div class="card-header">
        <h6 class="card-title">센터 알림</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover" id="centerNoticeTable">
            <thead class="text-center align-middle">
                <th width="10%">번호</th>
                <th width="10%">대상</th>
                <th width="70%">제목</th>
                <th width="10%">작성일</th>
            </thead>
            <tbody id="centerNoticeList"></tbody>
        </table>

        <div class="text-end">
            <button type="button" id="btnCenterNoticeAdd" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#CenterNoticeWriteModal"><i class="fa-solid fa-plus me-1"></i>추가</button>
        </div>
    </div>
</div>

<div class="modal fade" id="CenterNoticeModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">알림</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-2">
                    <input type="text" id="txtViewTitle" class="form-control bg-white" placeholder="제목" disabled>
                    <label for="txtViewTitle">제목</label>
                </div>
                <div class="form-floating mb-2" id="div_Target" style="display: none;">
                    <input type="text" id="txtTarget" class="form-control bg-white" placeholder="대상" disabled>
                    <label for="txtTarget">대상</label>
                </div>
                <div class="input-group">
                    <label for="txtViewContents">내용</label>
                </div>
                <div class="input-group">
                    <textarea id="txtViewContents" class="form-control bg-white" rows="15" disabled></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="notice_idx">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                <button type="button" id="btnCenterNoticeDelete" class="btn btn-danger"><i class="fas fa-trash me-1"></i>삭제</button>
                <button type="button" id="btnCenterNoticeEdit" class="btn btn-success"><i class="fa-regular fa-pen-to-square me-1"></i>수정</button>
                <button type="button" id="btnCenterNoticeEditSave" class="btn btn-primary" style="display: none;"><i class="fas fa-save me-1"></i>저장</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="CenterNoticeWriteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">알림</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-2">
                    <input type="text" id="txtWriteTitle" class="form-control" placeholder="제목" maxlength="50">
                    <label for="txtWriteTitle">제목</label>
                </div>
                <div class="input-group mb-2">
                    <div class="form-check-inline ms-2 align-self-center me-4">
                        <input type="radio" id="chkAllNotice" name="chkNoticeTarget" class="form-check-input" checked />
                        <label class="form-check-label" for="chkAllNotice">전체공지</label>
                    </div>
                    <?php
                    if ($_SESSION['is_admin'] == "Y") {
                    ?>
                        <div class="form-check-inline align-self-center me-4">
                            <input type="radio" id="chkTeacher" name="chkNoticeTarget" class="form-check-input" />
                            <label class="form-check-label" for="chkTeacher">직원공지</label>
                        </div>
                    <?php } ?>
                    <div class="form-check-inline align-self-center me-4">
                        <input type="radio" id="chkStudent" name="chkNoticeTarget" class="form-check-input" />
                        <label class="form-check-label" for="chkStudent">학생공지</label>
                    </div>
                    <?php
                    if ($_SESSION['is_admin'] == "Y") {
                    ?>
                    <div id="div_selTeacher" class="form-inline" style="display: none;">
                        <div class="form-floating">
                            <select id="selNoticeTeacher" class="form-select">
                                <option value="">선택</option>
                                <?php
                                if (!empty($selTeacherList)) {
                                    foreach ($selTeacherList as $key => $val) {
                                        echo "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <label for="selNoticeTeacher">알림대상</label>
                        </div>
                    </div>
                    <?php } ?>
                    <div id="div_selStudent" class="form-inline" style="display: none;">
                        <div class="form-floating">
                            <select id="selNoticeStudent" class="form-select">
                                <option value="">선택</option>
                                <?php
                                if (!empty($selStudentList)) {
                                    foreach ($selStudentList as $key => $val) {
                                        echo "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <label for="selNoticeStudent">알림대상</label>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <label for="txtWriteContents">내용</label>
                </div>
                <div class="input-group">
                    <textarea id="txtWriteContents" class="form-control" rows="15"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                <button type="button" id="btnNoticeSave" class="btn btn-primary"><i class="fas fa-save me-1"></i>저장</button>
            </div>
        </div>
    </div>
</div>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

$day = date('Y-m-d');
$weekday = array("일", "월", "화", "수", "목", "금", "토");

$infoClassCmp = new infoClassCmp();
$teacherList = $infoClassCmp->teacherList($_SESSION['center_idx']);

$codeInfoCmp = new codeInfoCmp();
$scoreList = $codeInfoCmp->getCodeInfo('54');
rsort($scoreList);
?>
<script>
    var userInfo = {
        user_no: '<?= $_SESSION['logged_no'] ?>',
        user_id: '<?= $_SESSION['logged_id'] ?>',
        user_name: '<?= $_SESSION['logged_name'] ?>',
        user_phone: '<?= phoneFormat($_SESSION['logged_phone']) ?>',
        user_email: '<?= $_SESSION['logged_email'] ?>',
        user_director: '<?= $_SESSION['is_admin'] ?>'
    }
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
</script>
<script src="/center/js/lesson_list.js?v=<?= date('YmdHis') ?>"></script>
<div class="card border-left-primary shadow mt-2">
    <div class="card-header">
        <h6>수업리스트</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-5">
                <div class="input-group mb-2">
                    <?php
                    if ($_SESSION['user_id'] == 'admin' || $_SESSION['is_admin'] == 'Y') {
                    ?>
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select id="sellessonListTeacher" class="form-select" onchange="loadLessonList()">
                                    <?php
                                    if (!empty($teacherList)) {
                                        foreach ($teacherList as $key => $val) {
                                            echo "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=\"" . $_SESSION['logged_no'] . "\">" . $_SESSION['logged_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                                <label for="sellessonListTeacher">담당</label>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <input type="date" id="lessonDate" class="form-control" value="<?= date('Y-m-d') ?>" placeholder="수업일" onchange="loadLessonList()">
                            <label for="lessonDate">수업일</label>
                        </div>
                    </div>
                    <p class="align-self-center text-muted"> 선택한 일자 이후의 수업만 노출됩니다. </p>
                </div>
                <table class="table table-sm table-bordered table-hover" id="lessonListTable">
                    <thead class="align-middle text-center">
                        <th>수업종류</th>
                        <th>일자</th>
                        <th>수업시간</th>
                        <th>담당</th>
                        <th>학생수</th>
                        <th>수업도서</th>
                    </thead>
                    <tbody id="lessonListData">
                    </tbody>
                </table>
            </div>
            <div class="col-2">
                <div class="input-group mb-2" id="divAttendList" style="display: none;">
                    <div class="form-inline me-1">
                        <button type="button" id="btnAllAttend" class="btn btn-sm btn-primary">일괄출석</button>
                    </div>
                    <div class="form-inline me-2">
                        <button type="button" id="btnAllAbsent" class="btn btn-sm btn-danger">일괄결석</button>
                    </div>
                </div>
                <table class="table table-sm table-bordered table-hover">
                    <thead class="align-middle text-center">
                        <th>번호</th>
                        <th>이름</th>
                        <th>학년</th>
                    </thead>
                    <tbody id="lessonStudentList">
                    </tbody>
                </table>
            </div>
            <div class="col-5">
                <input type="hidden" id="hiScheduleNo">
                <input type="hidden" id="hiLessonStudentNo">
                <div class="input-group mb-3">
                    <div class="form-check form-check-inline form-switch align-self-center me-2">
                        <input type="checkbox" id="chkAttendance" class="form-check-input" checked />
                        <label id="lblAttendance" class="form-check-label" for="">출석</label>
                    </div>
                    <div class="form-inline align-self-center">
                        <button type="button" id="btnLessonAttendSave" class="btn btn-sm btn-primary"><i class="fas fa-save"></i>출결저장</button>
                    </div>
                </div>
                <div class="input-group mb-2" id="activityPaperList">
                </div>
                <hr>
                <div>
                    <div class="input-group">
                        <div class="form-inline align-self-center me-2">
                            <label>일괄 점수</label>
                        </div>
                        <?php
                        if (!empty($scoreList)) {
                            foreach ($scoreList as $key => $val) {
                                echo "<div class=\"form-check form-inline align-self-center me-2\">
                                      <input type=\"radio\" id=\"chkAllScore" . $val['code_name'] . "\" class=\"form-check-input \" name=\"chkAllScore\" value=\"" . sprintf("%d", $val['code_num2']) . "\" />
                                      <label class=\"form-check-label\" for=\"chkAllScore" . $val['code_name'] . "\">" . $val['code_name'] . "</label>
                                  </div>";
                            }
                        }
                        ?>
                        <div class="form-inline">
                            <button type="button" id="btnLessonScoreSave" class="btn btn-sm btn-primary"><i class="fas fa-save"></i>저장</button>
                        </div>
                    </div>
                    <label>독서</label>
                    <div class="input-group">
                        <div class="form-inline">
                            <div class="form-floating">
                                <select id="selRead" class="form-select sel">
                                    <option value="">선택</option>
                                    <?php
                                    if (!empty($scoreList)) {
                                        foreach ($scoreList as $key => $val) {
                                            echo "<option value=\"" . sprintf("%d", $val['code_num2']) . "\">" . $val['code_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selRead">독서</label>
                            </div>
                        </div>
                    </div>
                    <label>토론</label>
                    <div class="input-group">
                        <div class="form-inline me-1">
                            <div class="form-floating">
                                <select id="selDebate1" class="form-select sel">
                                    <option value="">선택</option>
                                    <?php
                                    if (!empty($scoreList)) {
                                        foreach ($scoreList as $key => $val) {
                                            echo "<option value=\"" . sprintf("%d", $val['code_num2']) . "\">" . $val['code_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selDebate1">발언</label>
                            </div>
                        </div>
                        <div class="form-inline me-1">
                            <div class="form-floating">
                                <select id="selDebate2" class="form-select sel">
                                    <option value="">선택</option>
                                    <?php
                                    if (!empty($scoreList)) {
                                        foreach ($scoreList as $key => $val) {
                                            echo "<option value=\"" . sprintf("%d", $val['code_num2']) . "\">" . $val['code_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selDebate2">듣기</label>
                            </div>
                        </div>
                        <div class="form-inline me-1">
                            <div class="form-floating">
                                <select id="selDebate3" class="form-select sel">
                                    <option value="">선택</option>
                                    <?php
                                    if (!empty($scoreList)) {
                                        foreach ($scoreList as $key => $val) {
                                            echo "<option value=\"" . sprintf("%d", $val['code_num2']) . "\">" . $val['code_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selDebate3">참여</label>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-floating">
                                <select id="selDebate4" class="form-select sel">
                                    <option value="">선택</option>
                                    <?php
                                    if (!empty($scoreList)) {
                                        foreach ($scoreList as $key => $val) {
                                            echo "<option value=\"" . sprintf("%d", $val['code_num2']) . "\">" . $val['code_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selDebate4">태도</label>
                            </div>
                        </div>
                    </div>
                    <label>글쓰기</label>
                    <div class="input-group">
                        <div class="form-inline me-1">
                            <div class="form-floating">
                                <select id="selWrite1" class="form-select sel">
                                    <option value="">선택&nbsp;&nbsp;</option>
                                    <?php
                                    if (!empty($scoreList)) {
                                        foreach ($scoreList as $key => $val) {
                                            echo "<option value=\"" . sprintf("%d", $val['code_num2']) . "\">" . $val['code_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selWrite1">어휘어법</label>
                            </div>
                        </div>
                        <div class="form-inline me-1">
                            <div class="form-floating">
                                <select id="selWrite2" class="form-select sel">
                                    <option value="">선택&nbsp;&nbsp;</option>
                                    <?php
                                    if (!empty($scoreList)) {
                                        foreach ($scoreList as $key => $val) {
                                            echo "<option value=\"" . sprintf("%d", $val['code_num2']) . "\">" . $val['code_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selWrite2">문장문단</label>
                            </div>
                        </div>
                        <div class="form-inline me-1">
                            <div class="form-floating">
                                <select id="selWrite3" class="form-select sel">
                                    <option value="">선택&nbsp;&nbsp;</option>
                                    <?php
                                    if (!empty($scoreList)) {
                                        foreach ($scoreList as $key => $val) {
                                            echo "<option value=\"" . sprintf("%d", $val['code_num2']) . "\">" . $val['code_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selWrite3">논리사고</label>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-floating">
                                <select id="selWrite4" class="form-select sel">
                                    <option value="">선택&nbsp;&nbsp;</option>
                                    <?php
                                    if (!empty($scoreList)) {
                                        foreach ($scoreList as $key => $val) {
                                            echo "<option value=\"" . sprintf("%d", $val['code_num2']) . "\">" . $val['code_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selWrite4">창의사고</label>
                            </div>
                        </div>
                    </div>
                    <label>주도&#47;인성</label>
                    <div class="input-group">
                        <div class="form-inline">
                            <div class="form-floating">
                                <select id="selLead" class="form-select sel">
                                    <option value="">선택&nbsp;&nbsp;&nbsp;</option>
                                    <?php
                                    if (!empty($scoreList)) {
                                        foreach ($scoreList as $key => $val) {
                                            echo "<option value=\"" . sprintf("%d", $val['code_num2']) . "\">" . $val['code_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selLead">주도&#47;인성</label>
                            </div>
                        </div>
                    </div>

                    <label for="txtMemo">메모</label>
                    <div class="input-group">
                        <textarea id="txtMemo" class="form-control" rows="2" maxlength="100"></textarea>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
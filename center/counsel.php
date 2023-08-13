<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

$codeInfoCmp = new codeInfoCmp();
$infoClassCmp = new infoClassCmp();

$discardList = $codeInfoCmp->getCodeInfo('23');
$classCategoryList = $codeInfoCmp->getCodeInfo('04');
$gradeList = $codeInfoCmp->getCodeInfo('02');

$teacherSelectList = $infoClassCmp->teacherList($_SESSION['center_idx']);
?>
<script src="/center/js/counsel.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var center_idx = "<?= $_SESSION['center_idx'] ?>";
    var userInfo = {
        user_no: '<?= $_SESSION['logged_no'] ?>',
        user_id: '<?= $_SESSION['logged_id'] ?>',
        user_name: '<?= $_SESSION['logged_name'] ?>',
        user_phone: '<?= phoneFormat($_SESSION['logged_phone']) ?>',
        user_email: '<?= $_SESSION['logged_email'] ?>'
    }
</script>
<div class="row mt-2 size-14">
    <div class="col-4">
        <div class="card card-sm border-left-primary shadow">
            <div class="card-header">
                <div class="row">
                    <div class="col align-self-center">
                        <h6>상담목록</h6>
                    </div>
                    <div class="col-auto align-items-end">
                        <div class="form-floating">
                            <input type="month" id="dtCounselMonths" class="form-control" value="<?= date("Y-m") ?>" onchange="counselLoad()" />
                            <label for="dtMonths">년월</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="input-group input-group-sm">
                    <div class="form-check form-switch form-check-inline">
                        <input type="checkbox" id="chkCounsel" class="form-check-input" checked>
                        <label id="lblCounsel" class="form-check-label" for="chkCounsel">정규상담</label>
                    </div>
                </div>
                <table class="table table-bordered table-hover" id="counselTable">
                    <thead class="text-center align-middle">
                        <th>번호</th>
                        <th>이름</th>
                        <th>작성자</th>
                        <th>상담일자</th>
                        <th>작성일자</th>
                    </thead>
                    <tbody id="counselList">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-8">
        <div class="card card-sm border-left-primary shadow">
            <div class="card-header">
                <h6>상담정보</h6>
            </div>
            <div class="card-body">
                <!-- 정규상담 -->
                <div id="div_regular">
                    <div class="input-group input-group-sm mb-2">
                        <div class="form-floating">
                            <input type="hidden" id="student_no">
                            <input type="text" id="txtCounselTitle" class="form-control bg-white" placeholder="원생이름" readonly>
                            <label for="txtCounselTitle">원생이름</label>
                        </div>
                        <div class="input-group-append align-self-center ms-2">
                            <button type="button" id="btnStudentSearch" class="btn btn-sm btn-outline-secondary">원생찾기</button>
                        </div>
                        <div class="input-group-append align-self-center ms-2">
                            <label id="lblGrade" class="form-label"></label>
                        </div>
                        <div class="input-group-append align-self-center ms-2">
                            <label id="lblSchool" class="form-label"></label>
                        </div>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="date" id="dtCounselDate" class="form-control" placeholder="상담일자">
                                <label for="dtCounselDate">상담일자</label>
                            </div>
                        </div>
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select id="selCounselKind" class="form-select">
                                    <option value="1">정기상담</option>
                                    <option value="2">사안상담</option>
                                    <option value="9">퇴원상담</option>
                                </select>
                                <label for="selCounselKind">상담분류</label>
                            </div>
                        </div>
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select id="selCounselMethod" class="form-select">
                                    <option value="1">방문상담</option>
                                    <option value="2">전화상담</option>
                                </select>
                                <label for="selCounselMethod">상담방법</label>
                            </div>
                        </div>
                        <div class="form-inline align-self-center">
                            <div class="input-group input-group-sm">
                                <div class="form-check form-switch form-check-inline">
                                    <input type="checkbox" id="chkCounselOpen" class="form-check-input" checked>
                                    <label id="lblCounselOpen" class="form-check-label" for="chkCounselOpen">상담내용공개</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-2">
                        <div class="form-inline">
                            <div class="form-floating">
                                <select id="selDischargeKind" class="form-select" style="display: none;">
                                    <?php
                                    if (!empty($discardList)) {
                                        foreach ($discardList as $key => $val) {
                                    ?>
                                            <option value="<?= $val['code_num2'] ?>"><?= $val['code_name'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <label id="lblDischargeKind" for="selDischargeKind" style="display: none;">퇴원사유</label>
                            </div>
                        </div>
                    </div>
                    <div id="div-Discharge2" style="display: none;">
                        <div class="input-group input-group-sm">
                            <label for="txtDischarge">퇴원사유</label>
                        </div>
                        <div class="input-group input-group-sm mb-2">
                            <textarea id="txtDischarge" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="input-group input-group-sm">
                        <label for="txtCounselContents">상담내용</label>
                    </div>
                    <div class="input-group input-group-sm">
                        <textarea id="txtCounselContents" class="form-control" rows="7"></textarea>
                    </div>
                    <div class="input-group input-group-sm">
                        <label for="txtCounselRequest">요청사항</label>
                    </div>
                    <div class="input-group input-group-sm">
                        <textarea id="txtCounselRequest" class="form-control bg-white" rows="5" readonly></textarea>
                    </div>
                    <div class="input-group input-group-sm">
                        <label for="txtFollowup">후속조치</label>
                    </div>
                    <div class="input-group mb-2">
                        <textarea id="txtFollowup" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="text-end mt-2">
                        <input type="hidden" id="counsel_idx">
                        <button type="button" id="btnRegularCounselUpdate" class="btn btn-outline-info" style="display: none;">수정</button>
                        <button type="button" id="btnRegularCounselDelete" class="btn btn-outline-danger" style="display: none;">삭제</button>
                        <button type="button" id="btnRegularCounselSave" class="btn btn-outline-success">저장</button>
                    </div>
                </div>

                <!-- 신규상담 -->
                <div id="div_new" style="display: none;">
                    <div class="input-group input-group-sm mt-2">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="date" id="dtNewCounselDate" class="form-control">
                                <label for="dtNewCounselDate">상담일자</label>
                            </div>
                        </div>
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="text" id="txtNewName" class="form-control" placeholder="이름" maxlength="20">
                                <label for="txtNewName">이름</label>
                            </div>
                        </div>
                        <div class="form-check-inline align-self-center">
                            <input type="radio" id="rdoGender1" name="rdoGender" class="form-check-input" value="1" checked>
                            <label class="form-check-label" for="rdoGender1">남자</label>
                        </div>
                        <div class="form-check-inline align-self-center me-2">
                            <input type="radio" id="rdoGender2" name="rdoGender" class="form-check-input" value="2">
                            <label class="form-check-label" for="rdoGender2">여자</label>
                        </div>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select id="selGrade" class="form-select">
                                    <option value="">선택</option>
                                    <?php
                                    if (!empty($gradeList)) {
                                        foreach ($gradeList as $key => $val) {
                                    ?>
                                            <option value="<?= $val['code_num2'] ?>"><?= $val['code_name'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selGrade">학년</label>
                            </div>
                        </div>
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="text" id="txtSchool" class="form-control" placeholder="학교" maxlength="50">
                                <label for="txtSchool">학교</label>
                            </div>
                        </div>
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select id="selCounselTeacher" class="form-select">
                                    <option value="0">선택</option>
                                    <?php
                                    if (!empty($teacherSelectList)) {
                                        foreach ($teacherSelectList as $key => $val) {
                                    ?>
                                            <option value="<?= $val['user_no'] ?>"><?= $val['user_name'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selCounselTeacher">담당선생님</label>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-floating">
                                <input type="text" id="txtParentTel" class="form-control" placeholder="학부모님연락처" maxlength="11">
                                <label for="txtParentTel">학부모님연락처</label>
                            </div>
                        </div>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="text" id="txtCounselResult" class="form-control" placeholder="상담결과" maxlength="30">
                                <label for="txtCounselResult">상담결과</label>
                            </div>
                        </div>
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select id="selRegisterRate" class="form-select">
                                    <option value="1">미정</option>
                                    <option value="2">등록완료</option>
                                    <option value="3">등록가능성 있음</option>
                                    <option value="4">등록가능성 없음</option>
                                </select>
                                <label for="selRegisterRate">등록가능성</label>
                            </div>
                        </div>
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select id="selHopeClass" class="form-select">
                                    <option value="">선택</option>
                                    <?php
                                    if (!empty($classCategoryList)) {
                                        foreach ($classCategoryList as $key => $val) {
                                    ?>
                                            <option value="<?= $val['code_num2'] ?>"><?= $val['code_name'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selHopeClass">희망 수업</label>
                            </div>
                        </div>
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select id="selKnownPath" class="form-select">
                                    <option value="">선택</option>
                                    <option value="1">생활정보지 광고</option>
                                    <option value="2">전단지 광고</option>
                                    <option value="3">인터넷 검색</option>
                                    <option value="4">지인의 소개</option>
                                    <option value="5">학원건물 내 광고</option>
                                    <option value="6">형제 자매</option>
                                </select>
                                <label for="selKnownPath">알게 된 경로</label>
                            </div>
                        </div>
                        <div class="form-inline align-self-center">
                            <button type="button" id="btnReadingTest" class="btn btn-sm btn-outline-warning">독서이력진단</button>
                        </div>
                    </div>
                    <div class="input-group input-group-sm mt-2">
                        <label for="txtNewCounselContents">상담내용</label>
                    </div>
                    <div class="input-group input-group-sm">
                        <textarea id="txtNewCounselContents" class="form-control" rows="10"></textarea>
                    </div>
                    <div class="text-end mt-2">
                        <input type="hidden" id="counsel_new_idx">
                        <button type="button" id="btnNewCounselUpdate" class="btn btn-outline-info" style="display: none;">수정</button>
                        <button type="button" id="btnNewCounselDelete" class="btn btn-outline-danger" style="display: none;">삭제</button>
                        <button type="button" id="btnNewCounselSave" class="btn btn-outline-success">저장</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="SearchStudentModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">학생검색</h5>
                    <button type="button" id="btnClose" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-2">
                        <input type="text" id="txtSearchStudentName" class="form-control">
                        <div class="input-group-append">
                            <button type="button" id="studentSearch" class="btn btn-outline-success"><i class="fas fa-search-location me-1"></i>검색</button>
                        </div>
                    </div>
                    <form>
                        <table class="table table-bordered table-hover" id="studentSearchTable">
                            <thead class="text-center align-middle">
                                <th>이름</th>
                                <th>학년</th>
                                <th>학교</th>
                            </thead>
                            <tbody id="studentSearchList">
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                </div>
            </div>
        </div>
    </div>
</div>
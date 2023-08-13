<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";

$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";

$infoClassCmp = new infoClassCmp();
$codeInfoCmp = new codeInfoCmp();
$teacherList = $infoClassCmp->teacherList($_SESSION['center_idx']);
$colorCodeList = $infoClassCmp->colorCodeList($_SESSION['center_idx']);
$stateSelect   = $codeInfoCmp->getCodeInfo('10'); //회원상태 불러오기
?>
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script src="/center/js/student_list.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
</script>
<div class="mt-2 size-14">
    <div class="row">

        <div class="col-4">
            <div class="card border-left-primary shadow mt-2">
                <div class="card-header">
                    <h6>원생목록</h6>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <div class="form-floating mb-2">
                            <select id="selType" class="form-select" onchange="loadStudent(this.value)">
                                <?php
                                if (!empty($stateSelect)) {
                                    foreach ($stateSelect as $key => $val) {
                                        echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <label for="selType">상태</label>
                        </div>
                    </div>
                    <table id="studentTable" class="table table-sm table-bordered table-hover">
                        <thead class="text-center">
                            <th>번호</th>
                            <th>학년</th>
                            <th>이름</th>
                            <th>연락처</th>
                            <th>담당</th>
                        </thead>
                        <tbody id="studentList" class="text-center">
                        </tbody>
                    </table>
                    <div class="mb-2">
                        <small class="text-muted">퇴원한 원생정보는 일정 기간 이후 삭제 처리되어 확인할 수 없습니다.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-8">
            <div class="card border-left-primary shadow mt-2">
                <div class="card-header">
                    <h6>원생정보</h6>
                </div>
                <div class="card-body">
                    <div class="text-end">
                        <button type="button" id="btnSaveStudentInfo" class="btn btn-sm btn-primary" style="display: none;"><i class="fas fa-save me-1"></i>저장</button>
                    </div>
                    <form id="studentForm">
                        <input type="hidden" id="txtUserNo" value="" />
                        <div class="input-group mb-2">
                            <div class="form-inline me-1">
                                <div class="form-floating">
                                    <input type="text" id="txtName" class="form-control" maxlength="10" placeholder="이름">
                                    <label for="txtName">이름</label>
                                </div>
                            </div>
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <input type="date" id="dtBrithday" class="form-control" />
                                    <label for="dtBrithday">생년월일</label>
                                </div>
                            </div>
                            <div class="form-inline align-self-center me-2">
                                <label id="lblGender" class="form-label"></label>
                            </div>
                            <div class="form-inline align-self-center">
                                <label id="lblGrade" class="form-label"></label>
                            </div>
                        </div>

                        <div class="input-group mb-2">
                            <div class="form-inline me-1">
                                <div class="form-floating">
                                    <input type="text" id="txtHp" class="form-control" maxlength="11" placeholder="연락처">
                                    <label for="txtHp">연락처</label>
                                </div>
                            </div>
                            <div class="form-inline me-1">
                                <div class="form-floating">
                                    <input type="text" id="txtEmail" class="form-control" maxlength="50" placeholder="이메일">
                                    <label for="txtEmail">이메일</label>
                                </div>
                            </div>
                            <div class="form-inline me-1">
                                <div class="form-floating">
                                    <input type="text" id="txtSchool" class="form-control" maxlength="20" placeholder="학교명">
                                    <label for="txtSchool">학교명</label>
                                </div>
                            </div>
                            <div class="form-inline me-1">
                                <div class="form-floating">
                                    <input type="date" id="dtRegdate" class="form-control bg-white" readonly />
                                    <label for="dtRegdate">최초등록일</label>
                                </div>
                            </div>

                        </div>

                        <div class="input-group mb-2">
                            <div class="form-inline me-1">
                                <div class="form-floating">
                                    <select class="form-select" id="selState">
                                        <?php
                                        if (!empty($stateSelect)) {
                                            foreach ($stateSelect as $key => $val) {
                                                echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="selState">상태</label>
                                </div>
                            </div>
                            <div class="form-inline me-1" id="divRestOutReason" style="display: none;">
                                <div class="form-floating">
                                    <select id="selRestOutReason" class="form-select">
                                        <option value="">선택</option>
                                        <option value="1">수업불만족</option>
                                        <option value="2">수업어려움</option>
                                        <option value="3">원비부족</option>
                                        <option value="4">시간부족</option>
                                        <option value="5">이사</option>
                                        <option value="6">유학</option>
                                        <option value="9">기타</option>
                                    </select>
                                    <label for="selRestOutReason">휴퇴원사유</label>
                                </div>
                            </div>
                            <div id="divEtc" class="form-floating" style="display: none;">
                                <input type="text" id="txtEtcReason" class="form-control" maxlength="100" placeholder="기타 사유" />
                                <label for="txtEtcReason">기타 사유</label>
                            </div>
                        </div>

                        <div class="input-group mb-2">
                            <div class="form-inline me-1">
                                <div class="form-floating">
                                    <input type="text" id="txtId" class="form-control bg-white" maxlength="20" placeholder="아이디">
                                    <label for="txtId">아이디</label>
                                </div>
                            </div>
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <select id="selTeacher" class="form-select">
                                        <option value="">선택</option>
                                        <?php
                                        if (!empty($teacherList)) {
                                            foreach ($teacherList as $key => $val) {
                                                echo "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="selTeacher">담당</label>
                                </div>
                            </div>
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <select id="selColor" class="form-select" onchange="changeLableColor(this)">
                                        <option value="">선택</option>
                                        <?php
                                        if (!empty($colorCodeList)) {
                                            foreach ($colorCodeList as $key => $val) {
                                        ?>
                                                <option value="<?= $val['color_idx'] ?>" data-color-code="<?= $val['color_code'] ?>"><?= $val['color_detail'] ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="selColor">색깔태그</label>
                                </div>
                            </div>
                            <div class="form-inline align-self-center me-2">
                                <span id="lblColor" style="display:none;"><i class="fa-solid fa-circle"></i></span>
                            </div>
                            <div class="form-inline align-self-center">
                                <button id="btnColorCode" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#ColorCodeModal" type="button"><i class="fa-solid fa-plus me-1"></i>색깔태그 관리</button>
                            </div>
                        </div>

                        <div class="input-group mb-2">
                            <input type="text" id="txtAddr" class="form-control me-1" placeholder="주소" maxlength="100">
                            <div class="form-inline me-1">
                                <input type="text" id="txtZipCode" class="form-control bg-white" placeholder="우편번호" maxlength="5" readonly>
                            </div>
                            <div class="input-group-append">
                                <button id="btnaddr" class="btn btn-outline-success" type="button"><i class="fas fa-search-location me-1"></i>주소찾기</button>
                            </div>
                        </div>

                        <div class="form-floating">
                            <textarea id="txtMemo" class="form-control" style="min-height: 80px;" maxlength="100"></textarea>
                            <label for="txtMemo">메모</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="ColorCodeModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">색깔태그 목록</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-hover" id="colorCodeTable">
                        <thead class="align-middle text-center">
                            <th>번호</th>
                            <th>색상</th>
                            <th>설명</th>
                        </thead>
                        <tbody class="align-middle text-center" id="colorCodeList">
                        </tbody>
                    </table>
                    <input type="hidden" id="colorIdx">
                    <div class="form-floating">
                        <input type="text" id="txtDetail" class="form-control" placeholder="설명" maxlength="20">
                        <label for="txtDetail">설명</label>
                    </div>
                    <div class="input-group mt-2">
                        <div class="form-inline align-self-center me-2">
                            <label for="selColorCode">색상</label>
                        </div>
                        <div class="form-inline">
                            <input type="color" id="selColorCode" value="#ffffff">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" id="btnColorDelete" class="btn btn-sm btn-danger" style="display: none;"><i class="fa-solid fa-trash me-1"></i>삭제</button>
                        <button type="button" id="btnColorSave" class="btn btn-sm btn-primary"><i class="fas fa-save me-1"></i>저장</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>
</div>
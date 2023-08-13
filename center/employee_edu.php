<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";

include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$center_idx = $_SESSION['center_idx'];
?>
<script src="/center/js/employee_edu.js?v=<?= date("YmdHis") ?>"></script>
<div class="row mt-3 size-14">
    <div class="col-4 mb-2">
        <div class="card border-left-primary shadow h-100">
            <div class="card-header">
                <h6>계획목록</h6>
            </div>
            <div class="card-body">
                <div class="container">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="align-middle text-center small">
                            <th>교육명</th>
                            <th>교육분류</th>
                        </thead>
                        <tbody class="align-middle text-center small" id="eduList">
                            <?php
                            $infoClassCmp = new infoClassCmp();
                            $eduInfoarr = $infoClassCmp->searchEduInfo();
                            if (!empty($eduInfoarr)) {
                                foreach ($eduInfoarr as $key => $val) {
                                    echo "<tr class=\"educ\" data-edu-idx=\"" . $val['edu_idx'] . "\">
                                                                <td>" . $val['edu_name'] . "</td>
                                                                <td>" . $val['edu_type'] . "</td>
                                                            </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <h6>교육계획</h6>
                    <form id="eduInfoForm" class="mb-2">
                        <div class="form-floating mb-1">
                            <input type="text" class="form-control bg-white" id="txtEduName" disabled />
                            <label class="form-label">교육명</label>
                        </div>
                        <div class="form-floating mb-1">
                            <select class="form-select bg-white" id="selEduType" disabled>
                                <option value="1">법정의무교육</option>
                                <option value="2">사내직무교육</option>
                            </select>
                            <label class="form-label">교육분류</label>
                        </div>
                        <div class="form-floating mb-1">
                            <input type="text" class="form-control bg-white" id="txtEduTarget" disabled />
                            <label class="form-label">대상</label>
                        </div>
                        <div class="input-group mb-1">
                            <textarea class="form-control bg-white" id="txtEduWay" style="min-height: 200px;" disabled></textarea>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="col-5 mb-2">
        <div class="card border-left-primary shadow h-100">
            <div class="card-header">
                <h6>교육설정</h6>
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="input-group justify-content-end mb-2">
                        <button type="button" id="btnClearEduInfo" class="btn btn-sm btn-secondary mr-1"><span class="icon mr-1"><i class="fas fa-redo"></i></span>초기화</button>
                        <button type="button" id="btnDeleteEduInfo" class="btn btn-sm btn-danger mr-1 d-none"><span class="icon mr-1"><i class="fas fa-trash"></i></span>삭제</button>
                        <button type="button" id="btnSaveEduInfo" class="btn btn-sm btn-primary"><span class="icon mr-1"><i class="fas fa-save"></i></span>저장</button>
                    </div>
                    <form id="eduScheduleForm">
                        <input type="hidden" id="eduscheduleIdx">
                        <div class="form-floating">
                            <select id="selCenter2" class="form-select bg-white" disabled>
                                <?php
                                $center_info = $infoClassCmp->getFranchiseeDetail($center_idx);
                                ?>
                                <option value="<?= $center_idx ?>" selected><?= $center_info['center_name'] ?></option>
                            </select>
                            <label for="selCenter">센터</label>
                        </div>
                        <div class="input-group mb-2">
                            <label class="form-label">교육명</label>
                        </div>
                        <div class="input-group mb-2">
                            <select class="form-select" id="selEduInfo">
                                <option value="">선택</option>
                                <?php
                                if (!empty($eduInfoarr)) {
                                    foreach ($eduInfoarr as $key => $val) {
                                        echo "<option value=\"" . $val['edu_idx'] . "\">" . $val['edu_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="input-group mb-2">
                            <div class="form-floating me-2">
                                <input type="date" class="form-control" id="dtEdufromDate" placeholder="실시기간(시작)" />
                                <label for="dtEdufromDate">실시기간(시작)</label>
                            </div>
                            <div class="form-floating">
                                <input type="date" class="form-control" id="dtEdutoDate" placeholder="실시기간(끝)" disabled />
                                <label for="dtEdutoDate">실시기간(끝)</label>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead class="align-middle text-center">
                                <th><input type="checkbox" id="chkAllCheck" class="form-check-input" /></th>
                                <th>이름</th>
                            </thead>
                            <tbody id="eduEmployeeMember">
                            </tbody>
                        </table>
                    </form>
                    <h6>교육목록</h6>

                    <table class="table table-sm table-bordered table-hover">
                        <thead class="align-middle text-center small">
                            <th>교육명</th>
                            <th>교육종류</th>
                            <th>실시기간</th>
                            <th>대상인원</th>
                            <th>이수인원</th>
                        </thead>
                        <tbody class="align-middle text-center small" id="eduScheduleList">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- 교육 세부 -->
    <div class="col-3">
        <div class="card border-left-primary shadow h-100">
            <div class="card-header">
                <h6>교육세부</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered table-hover">
                    <thead class="align-middle text-center small">
                        <th>번호</th>
                        <th>이름</th>
                        <th>이수일자</th>
                        <th>수료증</th>
                    </thead>
                    <tbody class="align-middle text-center small" id="eduEmployeeList">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
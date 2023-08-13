<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

$codeInfoCmp = new codeInfoCmp();
$infoClassCmp = new infoClassCmp();

$classCategoryList = $codeInfoCmp->getCodeInfo('04');
$gradeList = $codeInfoCmp->getCodeInfo('02');

$teacherSelectList = $infoClassCmp->teacherList($_SESSION['center_idx']);
?>
<script>
    var center_idx = "<?= $_SESSION['center_idx'] ?>";
    var teacher_idx = "<?= $_SESSION['logged_no'] ?>";
</script>
<script type="text/javascript" src="js/sales_teacher_info.js?v=<?= date('YmdHis') ?>"></script>
<div class="row mt-2 size-14">
    <div class="col-12 mb-2">
        <div class="card border-left-primary shadow h-100 mb-3">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-sm align-items-start align-self-center"><i class="fa-solid fa-sack-dollar me-1"></i>교사 매출</div>
                    <div class="col-sm align-items-end">
                        <div class="row">
                            <div class="col">
                                <div class="form-floating">
                                    <input type="month" id="dtSalesYearMonth" class="form-control" value="<?= date('Y-m') ?>">
                                    <label for="dtSalesYearMonth">기간</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating">
                                    <?php
                                    $admchk = $_SESSION["is_admin"] !== 'Y' ? 'disabled' : '';
                                    ?>
                                    <select id="selSalesTeacher" class="form-select" <?= $admchk ?>>
                                        <?php
                                        if ($_SESSION["is_admin"] == 'Y') {
                                            echo "<option value=\"All\">전체</option>";
                                            if (!empty($teacherSelectList)) {
                                                foreach ($teacherSelectList as $key => $val) {
                                                    echo "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                                                }
                                            }
                                        } else {
                                            if (!empty($teacherSelectList)) {
                                                foreach ($teacherSelectList as $key => $val) {
                                                    if ($_SESSION['logged_no'] == $val['user_no']) {
                                                        echo "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="selSalesTeacher">담당구분</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating">
                                    <select id="selLessonClassType" class="form-select">
                                        <option value="All">전체</option>
                                        <?php
                                        if (!empty($classCategoryList)) {
                                            foreach ($classCategoryList as $key => $val) {
                                                echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="selLessonClassType">수업종류</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm align-items-end text-end">
                        <button type="button" id="btnSaleTeacherInfo" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#SaleTeacherinfoModal"><i class="fa-regular fa-circle-question me-1"></i>도움말</button>
                        <button type="button" id="btnSaleTeacherInfoSearch" class="btn btn-sm btn-secondary"><i class="fa-solid fa-magnifying-glass me-1"></i>검색</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered table-hover">
                    <thead class="align-middle text-center">
                        <th>이름</th>
                        <th>년월</th>
                        <th>수업종류</th>
                        <th>담당학생수</th>
                        <th>교육비</th>
                        <th>납부교육비</th>
                        <th>납부율</th>
                    </thead>
                    <tbody id="TeacherSaleList"></tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="SaleTeacherinfoModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">교사매출현황 도움말</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body size-14">
                <p>1&#46; 담당학생수는 원생목록에서 담당으로 지정한 학생이 수업에 등록되어 원비가 수납된 수가 집계되어 표시됩니다&#46;<br>
                    2&#46; 교육비는 원비수납에 결제대기 또는 결제완료로 입력하신 데이터를 집계하여 표시됩니다&#46;<br>
                    3&#46; 납부교육비는 원비수납에 결제완료로 입력하신 데이터를 집계하여 표시됩니다&#46;
                    <br>&nbsp;&nbsp;&nbsp;&#40;교육비는 원비수납에서 결제대기, 결제완료 항목이 합산되어 표시됨 단&#44; 부분환불 또는 전체 환불한 금액은 차감됨&#41;
                    <br>&nbsp;&nbsp;&nbsp;&#40;납부교육비는 원비수납에서 결제완료 항목이 합산되어 표시됨 단&#44; 부분환불 또는 전체 환불한 금액은 차감됨&#41;
                </p>
            </div>
        </div>
    </div>
</div>
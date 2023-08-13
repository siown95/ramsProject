<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";

$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$permissionCls = new permissionCmp();

include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/ReportController.php";
$reportController = new ReportController();

if (!empty($_SESSION['logged_no'])) {
    $report_setting = $reportController->reportSettingLoad($_SESSION['logged_no']);
}
?>
<script src="/center/js/report.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var userInfo = {
        user_no: '<?= $_SESSION['logged_no'] ?>',
        user_id: '<?= $_SESSION['logged_id'] ?>',
        user_name: '<?= $_SESSION['logged_name'] ?>',
        user_phone: '<?= phoneFormat($_SESSION['logged_phone']) ?>',
        user_email: '<?= $_SESSION['logged_email'] ?>',
        is_admin: '<?= $_SESSION['is_admin'] ?>'
    }
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
</script>
<div class="row size-14">
    <div class="col-12 mt-3">
        <div class="card border-left-primary shadow h-100">
            <div class="card-header">
                <h6>업무보고 목록</h6>
            </div>
            <div class="card-body">
                <div class="input-group input-group-sm mb-2">
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <input type="month" id="dtMonths" class="form-control" value="<?= date("Y-m") ?>" placeholder="작성월">
                            <label for="dtMonths">작성월</label>
                        </div>
                    </div>
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <?php
                            //관리자 계정으로만 가능
                            if ($_SESSION['logged_id'] == 'admin' || $_SESSION['is_admin'] == 'Y') {
                            ?>
                                <select id="selEmployee" class="form-select" onchange="reportLoad()">
                                    <?php
                                    $employee_list = $permissionCls->selectEmployee('center', $_SESSION['center_idx']);
                                    if (!empty($employee_list)) {
                                        foreach ($employee_list as $key => $val) {
                                            echo "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="dtMonths">작성자</label>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <table id="dataTable" class="table table-bordered table-hover">
                    <thead class="text-center">
                        <th class="dt-head-center">번호</th>
                        <th class="dt-head-center">작성자</th>
                        <th class="dt-head-center">업무보고일</th>
                    </thead>
                    <tbody id="reportList">

                    </tbody>
                </table>
                <div class="text-end mt-2">
                    <button type="button" id="btnSetting" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#ReportSettingModal"><i class="fa-solid fa-clipboard-list"></i> 양식설정</button>
                    <button type="button" id="btnAddReport" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-clipboard-list"></i> 작성</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ReportModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="ModalLabel" class="modal-title">업무보고</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <p>이전 작성 내용</p>
                        <?php
                        $pre_report_data = $reportController->reportContentsLoad($_SESSION['logged_no']);

                        if (!empty($pre_report_data)) {
                            for ($i = 1; $i <= count($pre_report_data); $i++) {
                        ?>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $pre_report_data[$i]["title" . $i . ""] ?>" disabled>
                                </div>
                                <div class="input-group mb-2">
                                    <textarea id="txtLastContents<?= $i ?>" class="form-control bg-white" rows="4" disabled><?= $pre_report_data[$i]["content" . $i . ""] ?></textarea>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="col-6">
                        <p>작성 내용</p>
                        <?php
                        if (!empty($report_setting)) {
                            $title_array = array();
                            foreach ($report_setting as $key => $val) {
                                if ($val != '') {
                        ?>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="title_val" value="<?= $val ?>" disabled>
                                    </div>
                                    <div class="input-group mb-2">
                                        <textarea class="form-control" rows="4" name="contents_val" placeholder=""></textarea>
                                    </div>
                        <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                <button type="button" id="btnSaveReport" class="btn btn-primary"><i class="fa-solid fa-clipboard-check"></i> 작성</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ReportViewModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <form id="printFrom">
        <input type="hidden" id="report_idx" name="report_idx">
        <input type="hidden" name="division" value="center">
        <input type="hidden" id="user_no" name="user_no">
        <input type="hidden" id="franchise_idx" name="franchise_idx" value="<?= $_SESSION['center_idx'] ?>">
    </form>
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="ModalLabel" class="modal-title">업무보고</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <p>이전 작성 내용</p>
                        <div id="pre_data"></div>
                    </div>
                    <div class="col-6">
                        <p>작성 내용</p>
                        <div id="report_data"></div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                <button type="button" id="btnDeleteReport" class="btn btn-danger"><i class="fa-solid fa-trash"></i> 삭제</button>
                <button type="button" id="btnUpdateReport" class="btn btn-success"><i class="fa-solid fa-clipboard-check"></i> 수정</button>
                <button type="button" id="btnPrintReport" class="btn btn btn-outline-primary"><i class="fa-solid fa-print"></i> 인쇄</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ReportSettingModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="ModalLabel" class="modal-title">업무보고 양식설정</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ReportSettingForm">
                    <h6>보고내용 입력 칸 제목 설정</h6>
                    <p class="text-muted">제목을 지정한 보고내용만 화면에 표시됩니다&#46;<br>제목 입력은 위에서 부터 순서대로 사용해주세요<br>&#42; 입력 칸은 최대 10개까지 가능합니다&#46;</p>
                    <hr>
                    <div class="input-group input-group-sm mb-2">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="text" id="txtTitle1" value="<?= $report_setting['title1'] ?>" class="form-control" placeholder="보고내용 입력 제목1">
                                <label for="noCount">보고내용 입력 제목1</label>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-floating">
                                <input type="text" id="txtTitle2" value="<?= $report_setting['title2'] ?>" class="form-control" placeholder="보고내용 입력 제목2">
                                <label for="noCount">보고내용 입력 제목2</label>
                            </div>
                        </div>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="text" id="txtTitle3" value="<?= $report_setting['title3'] ?>" class="form-control" placeholder="보고내용 입력 제목3">
                                <label for="noCount">보고내용 입력 제목3</label>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-floating">
                                <input type="text" id="txtTitle4" value="<?= $report_setting['title4'] ?>" class="form-control" placeholder="보고내용 입력 제목4">
                                <label for="noCount">보고내용 입력 제목4</label>
                            </div>
                        </div>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="text" id="txtTitle5" value="<?= $report_setting['title5'] ?>" class="form-control" placeholder="보고내용 입력 제목5">
                                <label for="noCount">보고내용 입력 제목5</label>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-floating">
                                <input type="text" id="txtTitle6" value="<?= $report_setting['title6'] ?>" class="form-control" placeholder="보고내용 입력 제목6">
                                <label for="noCount">보고내용 입력 제목6</label>
                            </div>
                        </div>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="text" id="txtTitle7" value="<?= $report_setting['title7'] ?>" class="form-control" placeholder="보고내용 입력 제목7">
                                <label for="noCount">보고내용 입력 제목7</label>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-floating">
                                <input type="text" id="txtTitle8" value="<?= $report_setting['title8'] ?>" class="form-control" placeholder="보고내용 입력 제목8">
                                <label for="noCount">보고내용 입력 제목8</label>
                            </div>
                        </div>
                    </div>
                    <div class="input-group input-group-sm">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="text" id="txtTitle9" value="<?= $report_setting['title9'] ?>" class="form-control" placeholder="보고내용 입력 제목9">
                                <label for="noCount">보고내용 입력 제목9</label>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-floating">
                                <input type="text" id="txtTitle10" value="<?= $report_setting['title10'] ?>" class="form-control" placeholder="보고내용 입력 제목10">
                                <label for="noCount">보고내용 입력 제목10</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                <button type="button" id="btnSettingSave" class="btn btn-primary"><i class="fa-solid fa-gear"></i> 설정</button>
            </div>
        </div>
    </div>
</div>
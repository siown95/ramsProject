<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$codeInfoCmp = new codeInfoCmp();

$grade_data = $codeInfoCmp->getCodeInfo('02'); //학년
$grade_option = '';

if (!empty($grade_data)) {
    $grade_option .= "<option value='00'>전체</option>";
    foreach ($grade_data as $key => $val) {
        $grade_option .= "<option value='" . $val['code_num2'] . "'>" . $val['code_name'] . "</option>";
    }
}

$month_array = array(
    "00" => "월 전체",
    "01" => "1월",
    "02" => "2월",
    "03" => "3월",
    "04" => "4월",
    "05" => "5월",
    "06" => "6월",
    "07" => "7월",
    "08" => "8월",
    "09" => "9월",
    "10" => "10월",
    "11" => "11월",
    "12" => "12월"
);
$now_month = date("m");
$month_option = '';
foreach ($month_array as $key => $val) {
    $option_select = '';
    if ($key == $now_month) {
        $option_select = 'selected';
    }
    $month_option .= "<option value='" . $key . "' " . $option_select . " >" . $val . "</option>";
}
?>
<script src="/center/js/curriculum.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var franchise_idx = '<?= $_SESSION['center_idx'] ?>'
</script>
<div class="row mt-2 size-14">
    <!-- 커리큘럼 설정 -->
    <div class="col-4">
        <div class="card border-left-primary shadow h-100">
            <div class="card-header">
                <h6>커리큘럼설정</h6>
            </div>
            <div class="card-body">
                <div class="container">
                    <form id="setting-form">
                        <input type="hidden" id="SearchBookNo">
                        <input type="hidden" id="curriculum_idx">
                        <div class="input-group justify-content-end mb-3">
                            <button type="button" id="btnClearCurriculum" class="btn btn-sm btn-secondary me-1"><i class="fas fa-redo"></i>초기화</button>
                            <button type="button" id="btnDeleteCurriculum" class="btn btn-sm btn-danger me-1" style="display: none;"><i class="fas fa-trash"></i>삭제</button>
                            <button type="button" id="btnSaveCurriculum" class="btn btn-sm btn-primary me-1"><i class="fas fa-save"></i>저장</button>
                            <button type="button" id="btnBatchCurriculum" class="btn btn-sm btn-success"><i class="fa-solid fa-download"></i>내려받기</button>
                        </div>
                        <div class="input-group mb-3">
                            <div class="form-inline me-3">
                                <div class="form-floating">
                                    <select class="form-select" id="sel_month">
                                        <?= $month_option ?>
                                    </select>
                                    <label for="sel_month" class="form-label">월</label>
                                </div>
                            </div>
                            <div class="form-inline me-3">
                                <div class="form-floating">
                                    <select class="form-select" id="sel_grade">
                                        <?= $grade_option ?>
                                    </select>
                                    <label for="sel_grade" class="form-label">학년</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" id="txtBookName" class="form-control">
                            <label class="form-label" for="txtBookName">책제목</label>
                        </div>
                        <div class="input-group">
                            <div class="form-inline me-3">
                                <div class="form-floating">
                                    <input type="text" id="txtOrder" class="form-control">
                                    <label class="form-label" for="txtOrder">순서</label>
                                </div>
                            </div>
                            <div class="input-group-append align-self-center me-3">
                                <button type="button" id="btnBookSearch" class="btn btn-outline-primary"><span class="icon me-1"><i class="fa-solid fa-magnifying-glass"></i></span>검색</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 커리큘럼 목록 -->
    <div class="col-8">
        <div class="card border-left-primary shadow h-100">
            <div class="card-header">
                <h6>커리큘럼목록</h6>
            </div>
            <div class="card-body">
                <div class="container">
                    <table id="curriculumInfoTable" class="table table-sm table-bordered table-hover">
                        <thead class="text-center">
                            <th>월</th>
                            <th>학년</th>
                            <th>순서</th>
                            <th>도서명</th>
                            <th>저자명</th>
                            <th>출판사</th>
                        </thead>
                        <tbody class="text-center" id="curriculumInfoList">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="BookModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">도서 정보</h5>
                    <button type="button" id="btnClose" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-2">
                        <input type="text" id="txtSearchBookName" class="form-control">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-success" onclick="book_search()"><i class="fas fa-search-location me-1"></i>검색</button>
                        </div>
                    </div>
                    <form>
                        <table class="table table-bordered table-hover" id="bookSearchTable">
                            <thead>
                                <th>번호</th>
                                <th>ISBN</th>
                                <th>도서명</th>
                                <th>저자</th>
                                <th>출판사</th>
                                <th>카테고리</th>
                            </thead>
                            <tbody id="bookSearchList">
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
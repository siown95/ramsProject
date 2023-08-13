<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

$codeInfoCmp = new codeInfoCmp();
$classCategoryList = $codeInfoCmp->getCodeInfo('04');
$gradeList = $codeInfoCmp->getCodeInfo('02');
?>
<script src="/center/js/receipt_item.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
</script>
<!-- 콘텐츠 -->
<div class="row mt-2 mb-2 size-14">

    <!-- 커리큘럼 설정 -->
    <div class="col-4">
        <div class="card border-left-primary shadow h-100">
            <div class="card-header">
                <h6>수납항목 설정</h6>
            </div>
            <div class="card-body">
                <div class="container">
                    <form id="setting-form">
                    <input type="hidden" id="targetReceipt">
                        <div class="input-group justify-content-end mb-3">
                            <button type="button" id="btnReceiptSave" class="btn btn-sm btn-primary"><i class="fas fa-save me-1"></i>저장</button>
                            <button type="button" id="btnReceiptUpdate" class="btn btn-sm btn-secondary" style="display: none;"><i class="fas fa-redo me-1"></i>수정</button>
                            <button type="button" id="btnReceiptBatch" class="btn btn-sm btn-success"><i class="fa-solid fa-download me-1"></i>내려받기</button>
                        </div>
                        <div class="input-group mb-2">
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <select id="selItem1" class="form-select">
                                        <?php
                                        $receiptTypeList = $codeInfoCmp->getCodeInfo('40');
                                        if (!empty($receiptTypeList)) {
                                            foreach ($receiptTypeList as $key => $val) {
                                                echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="selItem1">수납종류</label>
                                </div>
                            </div>
                            <div class="form-inline div_ct me-2">
                                <div class="form-floating">
                                    <select id="selClassType" class="form-select">
                                        <option value="">전체</option>
                                        <?php
                                        if (!empty($classCategoryList)) {
                                            foreach ($classCategoryList as $key => $val) {
                                                echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="selClassType">수업종류</label>
                                </div>
                            </div>
                            <div class="form-inline div_ct me-2">
                                <div class="form-floating">
                                    <select id="selReceiptGrade" class="form-select">
                                        <option value="">전체</option>
                                        <?php
                                        if (!empty($gradeList)) {
                                            foreach ($gradeList as $key => $val) {
                                                echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="selReceiptGrade">학년</label>
                                </div>
                            </div>
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <select id="selReceiptUse" class="form-select" disabled>
                                        <option value="Y" selected>사용</option>
                                        <option value="N">미사용</option>
                                    </select>
                                    <label for="selReceiptUse">사용여부</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="text" id="txtReceiptItemName" class="form-control" placeholder="수납항목명" maxlength="20" />
                            <label for="txtReceiptItemName">수납항목명</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" id="txtReceiptItemAmount" class="form-control" placeholder="금액" maxlength="10" />
                            <label for="txtReceiptItemAmount">금액</label>
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
                <h6>수납항목 목록</h6>
            </div>
            <div class="card-body">
                <div class="container">
                    <table id="receiptItemTable" class="table table-sm table-bordered table-hover">
                        <thead class="align-middle text-center">
                            <th>수납종류</th>
                            <th>수업종류</th>
                            <th>학년</th>
                            <th>수납항목명</th>
                            <th>금액</th>
                        </thead>
                        <tbody class="align-middle text-center">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
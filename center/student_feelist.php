<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/session_start.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER["DOCUMENT_ROOT"] . "/common/commonClass.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/common/function.php";

$codeInfoCmp = new codeInfoCmp();
$PaymentList = $codeInfoCmp->getCodeInfo('41');
$gradeList = $codeInfoCmp->getCodeInfo('02');
$PayStateList =  $codeInfoCmp->getCodeInfo('45');
?>
<script>
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
    var teacher_idx = '<?= $_SESSION['logged_no'] ?>';
    var pay_arr = [];
    var ori_paystate = "";
</script>
<script type="text/javascript" src="js/student_fee.js?v=<?= date('YmdHis') ?>"></script>

<div class="mt-2 mb-2">
    <div class="row">
        <div class="col-3">
            <div class="card border-left-primary shadow mt-2">
                <div class="card-header">
                    <h6>원생목록</h6>
                </div>
                <div class="card-body">
                    <div class="input-group justify-content-end mb-2">
                        <div class="form-inline">
                            <div class="form-floating">
                                <select id="selPayStudentGrade" class="form-select">
                                    <option value="All">전체</option>
                                    <?php
                                    foreach ($gradeList as $key => $val) {
                                        echo "<option value=\"{$val['code_num2']}\">{$val['code_name']}</option>";
                                    }
                                    ?>
                                </select>
                                <label for="selPayStudentGrade">학년</label>
                            </div>
                        </div>
                    </div>
                    <table id="StudentFeeTable" class="table table-sm table-bordered table-hover">
                        <thead class="text-center align-middle">
                            <th>번호</th>
                            <th>이름</th>
                            <th>연락처</th>
                            <th>담당</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div class="text-end mt-2">
                        <button type="button" id="btnMonthFeeList" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#MonthStudentFeeModal"><i class="fa-solid fa-receipt me-1"></i>월 원비수납생성</button>
                    </div>
                </div>

                <div class="modal fade" id="MonthStudentFeeModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLabel">월 원비수납 생성</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted">지정한 월의 수업이 있는 학생 데이터를 기반으로 수납항목을 생성합니다&#46;<br>
                                    생성된 데이터의 결제상태는 모두 결제대기로 생성됩니다&#46;<br>
                                    이미 생성된 원비수납은 유지됩니다&#46; &#40;이미 해당 월에 수납항목이 있는 경우 중복 생성됨&#41;<br>
                                    수업을 등록하신 후 수업정보를 확인하시고 이용하시기 바랍니다&#46;</p>
                                <div class="input-group mb-2">
                                    <div class="form-inline">
                                        <div class="form-floating">
                                            <input type="month" id="dtReceiptMonth" class="form-control" value="<?= date('Y-m') ?>" />
                                            <label for="dtReceiptMonth">원비수납 생성 월</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                                <button type="button" id="btnMonthFeeListCreate" class="btn btn-primary"><i class="fa-solid fa-receipt me-1"></i>원비수납생성</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="col-5">
            <div class="card border-left-primary shadow mt-2">
                <div class="card-header">
                    <h6>원비수납 목록</h6>
                </div>
                <div class="card-body">
                    <table id="InvoiceTable" class="table table-sm table-bordered table-hover">
                        <thead class="text-center align-middle">
                            <th>결제년월</th>
                            <th>상태</th>
                            <th>결제수단</th>
                            <th>결제금액</th>
                            <th>결제일</th>
                            <th>등록일</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-left-primary shadow mt-2">
                <div class="card-header">
                    <h6>원비수납처리</h6>
                </div>
                <div class="card-body">
                    <form id="addFeeItemForm" class="mb-2">
                        <div class="input-group mb-2">
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <select id="selMoreFeeItem" class="form-select">
                                        <option value="">추가항목선택</option>
                                        <?php
                                        $sql = "SELECT receipt_item_idx, receipt_name, receipt_amount FROM receiptT WHERE franchise_idx = '2' AND receipt_type = '99' AND useYn = 'Y' ";
                                        $receiptItemList = $db->sqlRowArr($sql);
                                        foreach ($receiptItemList as $key => $val) {
                                            echo "<option value=\"{$val['receipt_amount']}\" data-receipt-idx=\"{$val['receipt_item_idx']}\">{$val['receipt_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="selMoreFeeItem">추가결제항목</label>
                                </div>
                            </div>
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <input type="text" id="txtMoreFeeItem" class="form-control" placeholder="추가결제항목" maxlength="10" disabled />
                                    <label for="txtMoreFeeItem">추가결제항목</label>
                                </div>
                            </div>
                            <div class="form-inline">
                                <div class="form-floating">
                                    <input type="text" id="txtMoreFeeAmount" class="form-control" placeholder="추가결제금액" maxlength="7" numberOnly disabled />
                                    <label for="txtMoreFeeAmount">추가결제금액</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2 text-end">
                            <button type="button" id="btnMoreFeeAdd" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-plus me-1"></i>추가</button>
                        </div>
                    </form>
                    <table id="FeeTable" class="table table-sm table-bordered table-hover">
                        <thead class="align-middle text-center">
                            <th>결제항목</th>
                            <th>단가</th>
                            <th width="20%">수량</th>
                            <th width="20%">결제금액</th>
                            <th width="10%"></th>
                        </thead>
                        <tbody id="FeeList" class="align-middle text-center"></tbody>
                    </table>
                    <form id="payForm">
                        <div class="input-group mb-2">
                            <input type="hidden" id="pay_order_num" />
                            <input type="hidden" id="pay_student_idx" />
                            <div class="form-inline me-1">
                                <div class="form-floating">
                                    <input type="month" id="dtPayMonth" class="form-control" value="<?= date('Y-m') ?>" />
                                    <label for="dtPayMonth">결제년월</label>
                                </div>
                            </div>
                            <div class="form-inline me-1">
                                <div class="form-floating">
                                    <input type="date" id="dtPayDate" class="form-control" />
                                    <label for="dtPayDate">결제일</label>
                                </div>
                            </div>
                            <div class="form-inline me-1">
                                <div class="form-floating">
                                    <select id="selPayState" class="form-select">
                                        <option value="">선택</option>
                                        <?php
                                        foreach ($PayStateList as $key => $val) {
                                            if (!($val['code_num2'] == '03' || $val['code_num2'] == '04')) {
                                                echo "<option value=\"{$val['code_num2']}\">{$val['code_name']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="selPayState">결제상태</label>
                                </div>
                            </div>
                            <div class="form-inline">
                                <div class="form-floating">
                                    <select id="selPaymentType" class="form-select">
                                        <option value="">선택</option>
                                        <?php
                                        foreach ($PaymentList as $key => $val) {
                                            echo "<option value=\"{$val['code_num2']}\">{$val['code_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="selPaymentType">결제종류</label>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <textarea id="txtPaymentEtc" class="form-control" placeholder="특이사항 &#47; 비고" rows="2" maxlength="200"></textarea>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="text" id="txtFeeAmount" class="form-control" placeholder="결제금액" numberOnly readonly />
                            <label for="txtFeeAmount">결제금액</label>
                        </div>
                    </form>
                    <div class="mb-2">
                        <span class="text-muted">수업이 등록되어 출석한 수업만 계산되어 표시됩니다&#46;</span>
                    </div>
                    <div class="text-end">
                        <button type="button" id="btnFeeSave" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i>저장</button>
                        <button type="button" id="btnFeeUpdate" class="btn btn-success" style="display: none;"><i class="fa-solid fa-floppy-disk me-1"></i>수정</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
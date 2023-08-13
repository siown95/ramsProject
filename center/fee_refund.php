<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/session_start.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER["DOCUMENT_ROOT"] . "/common/commonClass.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/common/function.php";

$codeInfoCmp = new codeInfoCmp();
$PaymentList = $codeInfoCmp->getCodeInfo('41');
$PayStateList =  $codeInfoCmp->getCodeInfo('45');
$gradeList = $codeInfoCmp->getCodeInfo('02');
?>
<script>
    var pay_arr = [];
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
    var teacher_idx = '<?= $_SESSION['logged_no'] ?>';
    var g_payamt = 0;
</script>
<script type="text/javascript" src="js/student_fee_refund.js?v=<?= date('YmdHis') ?>"></script>
<div class="mt-2 mb-2">
    <div class="row">
        <div class="col-3">
            <div class="card border-left-primary shadow mt-2">
                <div class="card-header">
                    <h6>원생목록</h6>
                </div>
                <div class="card-body">
                    <div class="input-group justify-content-end mb-2">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="month" id="dtPayMonth" class="form-control" value="<?= date('Y-m') ?>" />
                                <label for="dtPayMonth">년월</label>
                            </div>
                        </div>
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
                    <table id="StudentFeeListTable" class="table table-sm table-bordered table-hover">
                        <thead class="text-center align-middle">
                            <th>번호</th>
                            <th>이름</th>
                            <th>연락처</th>
                            <th>담당</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <p class="text-muted">원비가 수납된 학생만 조회됩니다.</p>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-left-primary shadow mt-2">
                <div class="card-header">
                    <h6>원비수납 목록</h6>
                </div>
                <div class="card-body">
                    <table id="InvoiceRefundListTable" class="table table-sm table-bordered table-hover size-12">
                        <thead class="text-center align-middle">
                            <th>결제&#47;환불년월</th>
                            <th>결제&#47;환불수단</th>
                            <th>결제&#47;환불금액</th>
                            <th>결제&#47;환불일</th>
                            <th>등록일</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card border-left-primary shadow mt-2">
                <div class="card-header">
                    <h6>원비환불처리</h6>
                </div>
                <div class="card-body">
                    <form id="FeeRefundForm">
                        <input type="hidden" id="hdPayedInvoice_idx" />
                        <input type="hidden" id="hdPayedStudentNo" />
                        <input type="hidden" id="hdPayedStudentName" />
                        <input type="hidden" id="hdPayedMonth" />
                        <input type="hidden" id="hdPayedDate" />
                        <input type="hidden" id="hdPayedType" />
                        <input type="hidden" id="hdPayedTypeCode" />
                        <input type="hidden" id="hdPayedAmount" />
                        <table class="table table-sm table-bordered">
                            <thead class="align-middle text-center">
                                <th>원생이름</th>
                                <th>결제년월</th>
                                <th>결제수단</th>
                                <th>결제된금액</th>
                            </thead>
                            <tbody id="RefundInfoList" class="align-middle text-center">
                            </tbody>
                        </table>
                        <table id="RefundItemTable" class="table table-sm table-bordered">
                            <thead class="align-middle text-center">
                                <th width="20%">결제항목</th>
                                <th width="14%">결제수량</th>
                                <th width="14%">결제금액</th>
                                <th width="12%">단가</th>
                                <th width="20%">환불상태</th>
                                <th width="20%">환불금액</th>
                            </thead>
                            <tbody id="RefundItemList" class="align-middle text-center">
                            </tbody>
                        </table>
                        <form id="invoiceRefundForm" method="post" action="/TossPayment/index.php" target="_blank">
                            <input type="hidden" id="hd_payment_key" name="paymentKey" value="" />
                            <input type="hidden" name="cancelFlag" value="s" />
                            <div class="input-group mb-2">
                                <div class="form-inline me-2">
                                    <div class="form-floating">
                                        <input type="date" id="dtRefundDate" class="form-control" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" />
                                        <label for="dtRefundDate">환불일자</label>
                                    </div>
                                </div>
                                <div class="form-inline">
                                    <div class="form-floating">
                                        <input type="text" id="txtRefundAmount" class="form-control" name="cancelAmount" value="0" numberOnly readonly />
                                        <label for="txtRefundAmount">환불금액</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-2">
                                <textarea id="txtRefundEtc" class="form-control" name="cancelReason" placeholder="환불 메모" rows="2" maxlength="200"></textarea>
                            </div>
                        </form>
                    </form>
                    <div class="text-end">
                        <button type="button" id="btnFeeRefundSave" class="btn btn-primary" style="display: none;"><i class="fa-solid fa-money-bill me-1"></i>환불</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

?>
<script>
    function getRefundPayment() {
        if ($("#txtRefundEtc").val() || $("#txtRefundEtc").val() == '') {
            $("#txtRefundEtc").val('환불요청에 의한 환불처리');
        }
        var form = document.getElementById("invoiceRefundForm");
        window.open("", "popupName3", "width=700, height=800, left=" + Math.ceil((window.screen.width - 700) / 2) + ", top=" + Math.ceil((window.screen.height - 800) / 2));
        form.target = 'popupName3';
        form.submit();
    }
</script>
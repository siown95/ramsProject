<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

$infoClassCmp = new infoClassCmp();
$franchiseInfo =  $infoClassCmp->getFranchiseeDetail($_SESSION['center_idx']);
?>
<script>
    var centerInfo = {
        center_idx: '<?= $_SESSION['center_idx'] ?>',
        center_phone: '<?= $franchiseInfo['tel_num'] ?>',
        center_addr: '<?= $franchiseInfo['address'] ?>',
        center_zipcode: '<?= $franchiseInfo['zipcode'] ?>'
    };
</script>
<script type="text/javascript" src="js/sales_confirm.js?v=<?= date('YmdHis') ?>"></script>
<div class="row mt-2 mb-2">
    <div class="col-3">
        <div class="card border-left-primary shadow">
            <div class="card-header">
                <div class="input-group justify-content-between">
                    <div class="form-inline align-self-center">
                        <h6>결제목록</h6>
                    </div>
                    <div class="form-inline">
                        <div class="form-floating">
                            <input id="txtSalesYear" class="form-control" value="<?= date('Y') ?>" maxlength="4" numberOnly />
                            <label for="txtSalesYear">기준년월</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="PayInfoTable" class="table table-bordered table-hover">
                    <thead class="text-center align-middle">
                        <th>년월</th>
                        <th>결제금액</th>
                        <th>결제일자</th>
                    </thead>
                    <tbody id="PayInfoList">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-5">
        <div class="card border-left-primary shadow">
            <div class="card-header">
                <div class="row">
                    <div class="col align-self-center">
                        <h6>매출정보</h6>
                    </div>
                    <div class="col-auto align-self-center"><button type="button" id="btnInfo" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#infoModal"><i class="fa-regular fa-circle-question me-1"></i>도움말</button></div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr class="align-middle text-center">
                            <th width="15%">교육센터</th>
                            <td id="tdCenterName" class="text-start" width="35%"><?= $franchiseInfo['center_name'] ?></td>
                            <th width="10%">상태</th>
                            <td id="tdPayState" width="15%"></td>
                            <th width="10%">결제일자</th>
                            <td id="tdPayDate" width="15%"></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-sm table-bordered table-hover mb-2">
                    <thead class="text-center align-middle">
                        <th>학년</th>
                        <th>프로그램명</th>
                        <th>학생수</th>
                        <th>매출액</th>
                        <th>로열티</th>
                    </thead>
                    <tbody id="InvoiceDetailList">
                        <tr class="align-middle">
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-end"></td>
                            <td class="text-end"></td>
                        </tr>
                        <tr class="align-middle">
                            <td class="text-center">합계</td>
                            <td></td>
                            <td class="text-center"></td>
                            <td class="text-end"></td>
                            <td class="text-end"></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr class="align-middle text-end">
                            <th colspan="2">
                                <div class="form-floating">
                                    <input type="text" id="txtRefundNo" class="form-control bg-white" placeholder="환불인원수" readonly value="0" />
                                    <label for="txtRefundNo">환불인원수</label>
                                </div>
                            </th>
                            <th colspan="2">
                                <div class="form-floating">
                                    <input type="text" id="txtRefundAmount" class="form-control bg-white" placeholder="환불금액" readonly value="0" />
                                    <label for="txtRefundAmount">환불금액</label>
                                </div>
                            </th>
                            <th colspan="2">
                                <div class="form-floating">
                                    <input type="text" id="txtRefundRoyalty" class="form-control bg-white" placeholder="조정로열티" readonly value="0" />
                                    <label for="txtRefundRoyalty">조정로열티</label>
                                </div>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card border-left-primary shadow">
            <div class="card-header">
                <h6>결제정보</h6>
            </div>
            <div class="card-body">
                <section class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-row align-items-center">
                            <h5 class="mt-1">계속 가맹금 결제</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="rounded p-3" style="background-color: #ddd;">
                                <h4 class="fw-bold mb-2"><i class="fa-solid fa-file-invoice-dollar text-success me-2"></i>결제 항목</h4>
                                <hr>
                                <div class="d-flex justify-content-between mt-2">
                                    <h6><b>이용요금</b></h6>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <h6 class="text-primary">로열티 ①</h6>
                                    <h6><span id="lblTotal1">0</span> 원</h6>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <h6 class="text-primary">RAMS 임대료 ②</h6>
                                    <h6><span id="lblTotal2">0</span> 원</h6>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <h6 class="text-primary">조정 로열티 ③</h6>
                                    <h6><span id="lblTotal_r">0</span> 원</h6>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mt-2">
                                    <h6><b>이용요금 합계 &#40;① &#43; ② &#45; ③&#41;</b></h6>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <h6>&nbsp;</h6>
                                    <h6><span id="lblTotal1_2">0</span> 원</h6>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mt-2">
                                    <h6><b>세금 ④</b></h6>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <h6 class="text-primary">부가가치세 &#40;10&#37;&#41;</h6>
                                    <h6><span id="lblTotal3">0</span> 원</h6>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mt-2">
                                    <h6><b>총 결제 금액 &#40;① &#43; ② &#45; ③ &#43; ④&#41;</b></h6>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <h6>&nbsp;</h6>
                                    <h6><span id="lblTotalAmount">0</span> 원</h6>
                                </div>
                            </div>
                            <div class="mt-2">
                                <form id="franchiseFeeForm" method="post" action="/TossPayment/index.php" target="_blank">
                                    <input type="hidden" name="center_idx" value="<?= $_SESSION['center_idx'] ?>" />
                                    <input type="hidden" name="user_idx" value="<?= $_SESSION['logged_no'] ?>" />
                                    <input type="hidden" name="order_type" value="f" />
                                    <input type="hidden" id="hdorder_num" name="order_num" value="" />
                                    <input type="hidden" id="hdorder_ym" name="order_ym" value="" />
                                    <input type="hidden" name="order_name" value="<?= $franchiseInfo['center_name'] ?> 계속가맹금 결제" />
                                    <input type="hidden" id="hdtotAmount" name="pay_amount" value="" />
                                    <div class="input-group justify-content-end">
                                        <div id="PaymentMethod_div" class="form-inline me-2" style="display: none;">
                                            <div class="form-floating">
                                                <select id="selfeePaymentMethod" class="form-select" name="pay_method">
                                                    <option value="CARD">카드</option>
                                                    <option value="TRANSFER">계좌이체</option>
                                                </select>
                                                <label for="selfeePaymentMethod">결제방법</label>
                                            </div>
                                        </div>
                                        <div class="form-inline align-self-center">
                                            <button type="button" id="btnFranchiseFeeRefundRequest" class="btn btn-danger" style="display: none;" data-bs-toggle="modal" data-bs-target="#refundRequestModal"><i class="fa-solid fa-hand-holding-dollar me-1"></i>환불신청</button>
                                        </div>
                                        <div class="form-inline align-self-center">
                                            <button type="button" id="btnFranchiseFeePay" class="btn btn-success" style="display: none;"><i class="fa-regular fa-credit-card me-1"></i>결제</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">매출정보 도움말</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body size-14">
                <p>1&#46; 입력하신 수업 내역을 토대로 매출이 계산되어 표시됩니다&#46;</p>
                <p>2&#46; 계속 가맹금 결제 전 발생한 환불&#40;원비 결제&#41;의 경우 계산되어 <mark><strong>자동으로 반영</strong></mark>됩니다&#46;</p>
                <p>3&#46; 계속 가맹금 결제 후 발생한 환불의 경우 본사 확인 후 <mark><strong>결제하신 방법으로 환불 처리</strong></mark>됩니다&#46;</p>
                <p>4&#46; 정산 후&#40;결제일로 부터 4일 이후&#41; 발생한 환불의 경우 본사 확인 후 <mark><strong>결제 수수료를 제한 금액을 환불 처리</strong></mark>합니다&#46;</p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="refundRequestModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">계속가맹금 환불신청</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body size-14">
                <div class="input-group mb-2">
                    <div class="form-floating">
                        <input type="text" id="txtRefundableAmount" class="form-control" maxlength="10" placeholder="환불신청 가능금액&#40;결제 로열티&#41;" readonly />
                        <label for="txtRefundableAmount">환불신청 가능금액&#40;결제 로열티&#41;</label>
                    </div>
                </div>
                <div class="input-group mb-1">
                    <div class="form-floating">
                        <input type="text" id="txtRefundRequestReason" class="form-control" maxlength="100" placeholder="환불신청 사유" />
                        <label for="txtRefundRequestReason">환불신청 사유</label>
                    </div>
                </div>
                <div class="input-group">
                    <div class="form-floating">
                        <input type="text" id="txtRefundRequestAmount" class="form-control" maxlength="10" placeholder="환불신청 금액" numberOnly />
                        <label for="txtRefundRequestAmount">환불신청 금액</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                <button type="button" class="btn btn-primary" onclick="refundRequest();"><i class="fa-solid fa-floppy-disk me-1"></i>환불신청 저장</button>
            </div>
        </div>
    </div>
</div>

<script>
    $("#btnFranchiseFeePay").click(function() {
        var order_num = $("#hdorder_num").val();
        var point_amount2 = $("#hdtotAmount").val();
        var point_method2 = $("#selChargeMethod").val();
        if (point_method2 == 'CARD') {
            if (point_amount2 < 100) {
                alert("카드결제는 결제금액이 100원 이상부터 결제가 가능합니다.");
                return false;
            }
        } else if (point_method2 == 'TRANSFER') {
            if (point_amount2 < 200) {
                alert("계좌이체는 결제금액이 200원 이상부터 결제가 가능합니다.");
                return false;
            }
        }
        if (!order_num) {
            if (invoiceInsert() == false) {
                return false;
            }
        }
        var form = document.getElementById("franchiseFeeForm");
        window.open("", "popupName", "width=700, height=800, left=" + Math.ceil((window.screen.width - 700) / 2) + ", top=" + Math.ceil((window.screen.height - 800) / 2));
        form.target = 'popupName';
        form.submit();
    });
</script>
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
    var userInfo = {
        user_no: '<?= $_SESSION['logged_no'] ?>',
        user_id: '<?= $_SESSION['logged_id'] ?>',
        user_name: '<?= $_SESSION['logged_name'] ?>',
        user_phone: '<?= phoneFormat($_SESSION['logged_phone']) ?>',
        user_email: '<?= $_SESSION['logged_email'] ?>'
    }
</script>
<!-- 콘텐츠 -->
<script src="/js/number_format.js"></script>
<script src="/center/js/goods_order.js?v=<?= date("YmdHis") ?>"></script>
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<div class="row mt-2 size-14">
    <div class="col-12 mb-3">
        <div class="card border-left-primary shadow h-100">
            <div class="card-header">
                <h6>주문목록</h6>
            </div>
            <div class="card-body">
                <div class="input-group justify-content-end mb-2">
                    <div class="form-inline">
                        <div class="form-floating">
                            <input type="month" id="dtOrderMonth" class="form-control" value="<?= date('Y-m') ?>" />
                            <label for="dtOrderMonth">주문년월</label>
                        </div>
                    </div>
                </div>
                <table class="table table-sm table-bordered table-hover" id="orderListTable">
                    <thead class="align-middle text-center">
                        <th>주문번호</th>
                        <th>상태</th>
                        <th>주문금액</th>
                        <th>결제수단</th>
                        <th>결제금액</th>
                        <th>주문일자</th>
                        <th>결제일자</th>
                    </thead>
                    <tbody class="align-middle text-center">
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="col-12">
        <div class="card border-left-primary shadow h-100">
            <div class="card-body">
                <div class="input-group justify-content-end mb-1">
                    <div class="form-inline me-1">
                        <form id="goodsPaymentForm" method="post" action="/TossPayment/index.php" target="_blank">
                            <input type="hidden" name="center_idx" value="<?= $_SESSION['center_idx'] ?>" />
                            <input type="hidden" name="user_idx" value="<?= $_SESSION['logged_no'] ?>" />
                            <input type="hidden" name="order_type" value="o" />
                            <input type="hidden" id="hdgoods_order_num" name="order_num" value="" />
                            <input type="hidden" name="order_name" value="<?= $franchiseInfo['center_name'] ?> 물품주문" />
                            <input type="hidden" id="hdgoods_totamount" name="pay_amount" value="" />
                            <select id="selGoodsPaymentMethod" class="form-select" name="pay_method">
                                <option value="CARD">카드</option>
                                <option value="TRANSFER">계좌이체</option>
                            </select>
                        </form>
                    </div>
                    <div class="form-inline align-self-center me-1">
                        <button type="button" id="btnOrderCancel" class="btn btn-sm btn-danger" style="display: none;"><i class="fa-regular fa-credit-card me-1"></i>결제취소</button>
                    </div>
                    <div class="form-inline align-self-center">
                        <button type="button" id="btnOrderPayment" class="btn btn-sm btn-primary"><i class="fa-regular fa-credit-card me-1"></i>결제하기</button>
                    </div>
                </div>
                <div class="input-group mb-2">
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <input type="text" id="txtOrderDate" class="form-control" placeholder="접수일시" value="" readonly />
                            <label for="txtOrderDate">접수일시</label>
                        </div>
                    </div>
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <input type="text" id="txtSendDate" class="form-control" placeholder="발송일시" value="" readonly />
                            <label for="txtSendDate">발송일시</label>
                        </div>
                    </div>
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <input type="text" id="txtDeliveryCompany" class="form-control" placeholder="택배사" maxlength="10" readonly />
                            <label for="txtDeliveryCompany">택배사</label>
                        </div>
                    </div>
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <input type="text" id="txtDeliveryNo" class="form-control" placeholder="송장번호" maxlength="20" readonly />
                            <label for="txtDeliveryNo">송장번호</label>
                        </div>
                    </div>
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <input type="text" id="txtCancelDate" class="form-control" placeholder="취소일시" value="" readonly />
                            <label for="txtCancelDate">취소일시</label>
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="form-floating">
                            <input type="text" id="txtOrderState" class="form-control" placeholder="상태" value="" readonly />
                            <label for="txtOrderState">상태</label>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-2">
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <input type="text" id="txtOrderAmount" class="form-control" placeholder="결제금액" readonly />
                            <label for="txtOrderAmount">결제금액</label>
                        </div>
                    </div>
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <input type="text" id="txtDeliveryFee" class="form-control" placeholder="배송비" maxlength="10" readonly />
                            <label for="txtDeliveryFee">배송비</label>
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="form-floating">
                            <input type="text" id="txtOrderHp" class="form-control" placeholder="연락처" maxlength="12" value="<?= $franchiseInfo['tel_num'] ?>" />
                            <label for="txtOrderHp">연락처</label>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-2">
                    <div class="form-inline w-75 me-2">
                        <div class="form-floating">
                            <input type="text" id="txtOrderAddr" class="form-control" placeholder="주소" maxlength="100" value="<?= $franchiseInfo['address'] ?>" />
                            <label for="txtOrderAddr">주소</label>
                        </div>
                    </div>
                    <div class="form-inline w-5 me-2">
                        <div class="form-floating">
                            <input type="text" id="txtOrderZipCode" class="form-control" placeholder="우편번호" maxlength="5" value="<?= $franchiseInfo['zipcode'] ?>" />
                            <label for="txtOrderZipCode">우편번호</label>
                        </div>
                    </div>
                    <div class="form-inline align-self-center">
                        <button id="btnOrderAddr" class="btn btn-outline-success" type="button"><i class="fas fa-search-location me-1"></i>주소찾기</button>
                    </div>
                </div>

                <div class="input-group mb-2">
                    <textarea id="txtOrderEtc" class="form-control" rows="2" placeholder="특이사항 &#47; 비고" maxlength="100"></textarea>
                </div>
                <div class="text-end mb-2">
                    <button type="button" id="btnGoodsAdd" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#GoodsModal"><i class="fa-solid fa-box-open me-1"></i>상품추가</button>
                </div>
                <table id="goodsOrderTable" class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr class="align-middle text-center">
                            <th width="5%">번호</th>
                            <th width="37%">품목명</th>
                            <th width="10%">최소수량</th>
                            <th width="10%">주문수량</th>
                            <th width="10%">단위</th>
                            <th width="10%">단가</th>
                            <th width="10%">금액</th>
                            <th width="8%"><button type="button" class="btn btn-sm btn-outline-danger btnOrderItemAllCancel"><i class="fa-solid fa-xmark me-1"></i>전체취소</button></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<div class="modal fade size-14" id="GoodsModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">물품주문</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-hover table-bordered" id="goodsListTable">
                    <thead class="align-middle text-center">
                        <th width="5%"><input type="checkbox" id="chkAllCheck" class="form-check-input" /></th>
                        <th width="35%">품목명</th>
                        <th width="10%">분류</th>
                        <th width="10%">단가</th>
                        <th width="15%">단위</th>
                        <th width="15%">최소주문수량</th>
                        <th width="10%">미리보기</th>
                        <th class="d-none"></th>
                    </thead>
                    <tbody class="align-middle text-center" id="goodsList">
                    </tbody>
                </table>
                <span class="text-muted">추가 버튼을 클릭하지 않고 창을 닫을 경우 선택한 항목이 초기화됩니다&#46;</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                <button type="button" id="btnOrderItemAdd" class="btn btn-success"><i class="fa-solid fa-plus me-1"></i>추가</button>
            </div>
        </div>
    </div>
</div>
<script>
    function loadPayment(order_num) {
        $("#hdgoods_order_num").val(order_num);
        $("#hdgoods_totamount").val($("#txtOrderAmount").val().replace(",", ""));
        var form = document.getElementById("goodsPaymentForm");
        window.open("", "popupName", "width=700, height=800, left=" + Math.ceil((window.screen.width - 700) / 2) + ", top=" + Math.ceil((window.screen.height - 800) / 2));
        form.target = 'popupName';
        form.submit();
    }

    function loadRefundPayment(paymentKey) {
        var refundPaymentForm = document.createElement("form");
        refundPaymentForm.method = "POST";
        refundPaymentForm.action = "/TossPayment/refund.php";
        refundPaymentForm.target = "_blank";

        var paymentkey = document.createElement("input");
        paymentkey.setAttribute("type", "hidden");
        paymentkey.setAttribute("name", "paymentKey");
        paymentkey.setAttribute("value", paymentKey);
        refundPaymentForm.appendChild(paymentkey);

        var cancelFlag = document.createElement("input");
        cancelFlag.setAttribute("type", "hidden");
        cancelFlag.setAttribute("name", "cancelFlag");
        cancelFlag.setAttribute("value", "s");
        refundPaymentForm.appendChild(cancelFlag);

        var cancelReason = document.createElement("input");
        cancelReason.setAttribute("type", "hidden");
        cancelReason.setAttribute("name", "cancelReason");
        cancelReason.setAttribute("value", "고객이 취소");
        refundPaymentForm.appendChild(cancelReason);
        document.body.appendChild(refundPaymentForm);
        window.open("", "popupName", "width=700, height=800, left=" + Math.ceil((window.screen.width - 700) / 2) + ", top=" + Math.ceil((window.screen.height - 800) / 2));
        refundPaymentForm.target = 'popupName';
        refundPaymentForm.submit();
        document.body.removeChild(refundPaymentForm);
    }
</script>
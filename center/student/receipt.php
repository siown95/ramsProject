<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />

    <?php
    $stat = 'student';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
    <script>
        var center_idx = '<?= $_SESSION['center_idx'] ?>';
        var student_idx = '<?= $_SESSION['logged_no'] ?>';
    </script>
    <script type="text/javascript" src="js/receipt.js?v=<?= date('YmdHis') ?>"></script>
</head>

<body class="size-14">
    <!-- 헤더 -->
    <?php include "navbar.php"; ?>

    <!-- 컨텐츠 -->
    <main style="min-height: 79vh;">
        <div class="container-fluid">
            <div class="card border-left-primary shadow mt-2 mb-2">
                <div class="card-header justify-content-between">
                    <div class="row">
                        <div class="col align-self-center">
                            <h6 class="card-title">원비결제</h6>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <div class="form-floating">
                                    <input type="month" id="dtPaymentMonth" class="form-control" value="<?= date('Y-m') ?>" />
                                    <label for="dtPaymentMonth">결제년월</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <table id="PaymentTable" class="table table-sm table-hover table-bordered mb-2">
                                <thead class="align-middle text-center">
                                    <th width="20%">년월</th>
                                    <th width="20%">결제상태</th>
                                    <th width="20%">결제종류</th>
                                    <th width="20%">결제일자</th>
                                    <th width="20%">결제&#47;미결제&#40;예정&#41;금액</th>
                                </thead>
                                <tbody class="align-middle text-center"></tbody>
                            </table>
                            <p class="text-muted mt-2 mb-2">&nbsp;&#42; 결제 또는 결제항목의 세부정보를 확인하시려면 위 목록에서 선택해주세요&#46;</p>
                            <table id="PaymentTable2" class="table table-sm table-hover table-bordered mb-2">
                                <thead class="align-middle text-center">
                                    <tr>
                                        <th colspan="5">결제세부정보</th>
                                    </tr>
                                    <tr>
                                        <th width="20%">번호</th>
                                        <th width="20%">항목이름</th>
                                        <th width="20%">단가</th>
                                        <th width="20%">횟수&#47;수량</th>
                                        <th width="20%">합계금액</th>
                                    </tr>
                                </thead>
                                <tbody class="align-middle text-center"></tbody>
                            </table>
                        </div>
                        <div class="col-3">
                            <form id="receiptForm" method="post" action="/TossPayment/index.php" target="_blank">
                                <input type="hidden" name="center_idx" value="<?= $_SESSION['center_idx'] ?>" />
                                <input type="hidden" name="user_idx" value="<?= $_SESSION['logged_no'] ?>" />
                                <input type="hidden" id="hd_order_num" name="order_num" value="" />
                                <input type="hidden" name="order_type" value="i" />
                                <input type="hidden" name="order_name" value="리딩엠 교육비 결제" />

                                <div class="form-floating mb-2">
                                    <input type="text" id="txtPrice" class="form-control bg-white text-center" readonly />
                                    <input type="hidden" id="txtPrice2" name="pay_amount" value="0" />
                                    <input type="hidden" id="hdPrice" name="pay_tax_free_amount" value="0" />
                                    <label for="txtPrice">총 결제금액</label>
                                </div>
                                <div id="payment_div" class="input-group justify-content-end mb-2" style="display: none;">
                                    <div class="form-inline me-2">
                                        <div class="form-floating">
                                            <select id="selChargeMethod" class="form-select" name="pay_method">
                                                <option value="CARD">카드</option>
                                                <option value="TRANSFER">계좌이체</option>
                                            </select>
                                            <label for="selChargeMethod">결제방법</label>
                                        </div>
                                    </div>
                                    <div class="form-inline align-self-center">
                                        <button type="button" id="btnPayment" class="btn btn-outline-primary">결제하기</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
    <script>
        $("#btnPayment").click(function() {
            var form = document.getElementById("receiptForm");
            window.open("", "popupName", "width=700, height=800, left=" + Math.ceil((window.screen.width - 700) / 2) + ", top=" + Math.ceil((window.screen.height - 800) / 2));
            form.target = 'popupName';
            form.submit();
        });
    </script>
    <!-- 푸터 -->
    <?php include "footer.php"; ?>
</body>

</html>
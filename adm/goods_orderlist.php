<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$codeInfoCmp = new codeInfoCmp();
$orderStateList = $codeInfoCmp->getCodeInfo('44'); //결제 상태 불러오기
?>
<!DOCTYPE html>
<html>

<head>
    <!-- css / script -->
    <?php
    $stat = 'adm';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
    <script type="text/javascript" src="/adm/js/goods_order.js?v=<?= date('YmdHis') ?>"></script>
    <script>
        var state_chk = false;
    </script>
</head>

<body class="sb-nav-fixed size-14">
    <!-- header navbar -->
    <?php include_once('navbar.html'); ?>
    <div id="layoutSidenav">
        <!-- sidebar -->
        <?php include_once('sidebar.html'); ?>

        <div id="Maincontent">
            <main>
                <div class="container-fluid px-4 mt-3">
                    <!-- 콘텐츠 -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="card border-left-primary shadow h-100">
                                <div class="card-header">
                                    <h6>주문목록</h6>
                                </div>
                                <div class="card-body">
                                    <div class="input-group justify-content-end mb-2">
                                        <div class="form-inline me-2">
                                            <div class="form-floating">
                                                <input type="month" id="order_month" class="form-control" value="<?= date('Y-m') ?>" />
                                                <label for="order_month">주문년월</label>
                                            </div>
                                        </div>
                                        <div class="form-inline">
                                            <div class="form-floating">
                                                <select id="selOrderState" class="form-select">
                                                    <option value="All">전체</option>
                                                    <?php
                                                    if (!empty($orderStateList)) {
                                                        foreach ($orderStateList as $key => $val) {
                                                            echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <label for="selOrderState">주문상태</label>
                                            </div>
                                        </div>
                                    </div>
                                    <table id="dataTable" class="table table-sm table-bordered table-hover">
                                        <thead class="align-middle text-center">
                                            <th>번호</th>
                                            <th>주문번호</th>
                                            <th>교육센터</th>
                                            <th>상태</th>
                                            <th>결제수단</th>
                                            <th>주문금액</th>
                                            <th>결제금액</th>
                                            <th>주문일자</th>
                                        </thead>
                                        <tbody class="align-middle text-center"></tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card border-left-primary shadow h-100">
                                <div class="card-body">
                                    <form id="orderinfo">
                                        <div class="text-end mb-1">
                                            <button type="button" id="btnSave" class="btn btn-sm btn-primary"><i class="fas fa-save me-1"></i>저장</button>
                                        </div>
                                        <div class="input-group mb-2">
                                            <div class="form-inline me-2">
                                                <div class="form-floating">
                                                    <input type="text" id="txtOrderNo" class="form-control bg-white" placeholder="주문번호" value="" readonly />
                                                    <label for="txtOrderNo">주문번호</label>
                                                </div>
                                            </div>
                                            <div class="form-inline me-2">
                                                <div class="form-floating">
                                                    <input type="text" id="txtOrderDate" class="form-control bg-white" placeholder="주문일자" value="" readonly />
                                                    <label for="txtOrderDate">주문일자</label>
                                                </div>
                                            </div>
                                            <div class="form-inline me-2">
                                                <div class="form-floating">
                                                    <input type="text" id="txtOrderCenterName" class="form-control bg-white" placeholder="교육센터" value="" readonly />
                                                    <label for="txtOrderCenterName">교육센터</label>
                                                </div>
                                            </div>
                                            <div class="form-inline me-2">
                                                <div class="form-floating">
                                                    <input type="text" id="txtSendDate" class="form-control bg-white" placeholder="발송일자" value="" readonly />
                                                    <label for="txtSendDate">발송일자</label>
                                                </div>
                                            </div>
                                            <div class="form-inline me-3">
                                                <div class="form-floating">
                                                    <input type="text" id="txtCancelDate" class="form-control bg-white" placeholder="취소일자" value="" readonly />
                                                    <label for="txtCancelDate">취소일자</label>
                                                </div>
                                            </div>
                                            <div class="form-inline me-3">
                                                <div class="form-floating">
                                                    <select id="selState" class="form-select">
                                                        <option value="">선택</option>
                                                        <?php
                                                        if (!empty($orderStateList)) {
                                                            foreach ($orderStateList as $key => $val) {
                                                                echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <label for="selState">주문상태</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-group mb-2">
                                            <div class="form-inline me-2">
                                                <div class="form-floating">
                                                    <input type="text" id="txtOrderAmount" class="form-control bg-white" placeholder="결제금액" readonly />
                                                    <label for="txtOrderAmount">결제금액</label>
                                                </div>
                                            </div>
                                            <div class="form-inline me-2">
                                                <div class="form-floating">
                                                    <input type="text" id="txtDeliveryFee" class="form-control" placeholder="배송비" maxlength="10" />
                                                    <label for="txtDeliveryFee">배송비</label>
                                                </div>
                                            </div>
                                            <div class="form-inline me-2">
                                                <div class="form-floating">
                                                    <input type="text" id="txtHp" class="form-control" placeholder="연락처" maxlength="12" />
                                                    <label for="txtHp">연락처</label>
                                                </div>
                                            </div>
                                            <div class="form-inline me-2">
                                                <div class="form-floating">
                                                    <input type="text" id="txtDeliveryCompany" class="form-control" placeholder="택배사" maxlength="10" />
                                                    <label for="txtDeliveryCompany">택배사</label>
                                                </div>
                                            </div>
                                            <div class="form-inline">
                                                <div class="form-floating">
                                                    <input type="text" id="txtDeliveryNo" class="form-control" placeholder="송장번호" maxlength="20" />
                                                    <label for="txtDeliveryNo">송장번호</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-2">
                                            <input type="text" id="txtAddr" class="form-control" placeholder="주소" />
                                            <label for="txtAddr">주소</label>
                                        </div>
                                        <div class="form-floating mb-2">
                                            <textarea id="txtEtc" class="form-control" style="min-height: 100px;"></textarea>
                                            <label for="txtEtc">비고</label>
                                        </div>
                                    </form>
                                    <table id="dataTable2" class="table table-sm table-bordered table-hover">
                                        <thead>
                                            <tr class="align-middle text-center">
                                                <th>번호</th>
                                                <th>품목명</th>
                                                <th>단위</th>
                                                <th>단가</th>
                                                <th>주문수량</th>
                                                <th>금액</th>
                                                <th>처리</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
            <!-- footer -->
            <?php
            include_once('footer.html');
            ?>
        </div>
    </div>
</body>

</html>
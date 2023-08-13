<?php
if (!isset($db)) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
    $db = new DBCmp();
}
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$permissionCls = new permissionCmp();
if (!empty($_SESSION['logged_no'])) {
    $state = $permissionCls->selectCommuteLog($_SESSION['logged_no'], $_SESSION['center_idx'], 'center');
    $state = !empty($state) ? $state : '퇴근';
}
$state = !empty($state) ? $state : '퇴근';
?>
<nav class="navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="index.php"><img class="img rounded-circle" src="/img/logo.png" /><span class="align-middle ms-1">리딩엠</span></a>
    <!-- Navbar Grid-->
    <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <button id="btnSMS" class="btn btn-sm btn-outline-light" type="button"><i class="fa-regular fa-message me-1"></i>문자</button>
        <button id="btnCommute" class="btn btn-sm <?= ($state == "퇴근") ? "btn-outline-warning" : "btn-outline-light" ?>" type="button" onclick="commute_check()">
            <i class="fa-solid fa-clipboard-list me-1"></i>상태 : <?= $state ?>
        </button>
        <?php
        if ($_SESSION['is_admin'] == 'Y') {
            $qry = "SELECT F.point FROM franchiseM F WHERE F.franchise_idx = {$_SESSION['center_idx']}";
            $pointInfo = $db->sqlRowOne($qry);
        ?>
            <button id="btnPoint" class="btn btn-sm btn-success" type="button" data-bs-toggle="modal" data-bs-target="#PointModal"><i class="fa-solid fa-coins me-1"></i><span id="lblPoint" class="me-1"><?= number_format($pointInfo) ?></span>P</button>
        <?php } ?>
    </div>
    <!-- 네비바 메뉴-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user me-1"></i><?= $_SESSION['logged_name'] ?>님<i class="fas fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item menu" href="#usersetting">설정</a></li>
                <li><a class="dropdown-item menu" href="#msggroupsetting">그룹관리</a></li>
                <li><a class="dropdown-item menu" href="#businessreport">업무보고</a></li>
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" href="/center/logout.php">로그아웃</a></li>
            </ul>
        </li>
    </ul>
</nav>
<div class="modal fade" id="PointModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">포인트 충전</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <ul id="pointTab" class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button type="button" id="pointChargePage-tab" class="nav-link active" data-bs-toggle="tab" data-bs-target="#pointChargePage" aria-controls="pointChargePage" aria-selected="true">RAMS 포인트 충전</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button type="button" id="pointChargeList-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#pointChargeList" aria-controls="pointChargeList" aria-selected="false">RAMS 포인트 충전내역</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="pointChargePage" class="tab-pane fade show active" role="tabpanel" aria-labelledby="pointChargePage-tab" tabindex="0">
                            <form id="pointChargeFrom" method="post" action="/TossPayment/index.php" target="_blank">
                                <div class="input-group mt-2">
                                    <input type="hidden" name="center_idx" value="<?= $_SESSION['center_idx'] ?>" />
                                    <input type="hidden" name="user_idx" value="<?= $_SESSION['logged_no'] ?>" />
                                    <input type="hidden" name="order_type" value="p" />
                                    <input type="hidden" name="order_name" value="RAMS 포인트" />
                                    <div class="form-inline me-2">
                                        <div class="form-floating">
                                            <select id="selChargeMethod" class="form-select" name="pay_method">
                                                <option value="CARD">카드</option>
                                                <option value="TRANSFER">계좌이체</option>
                                            </select>
                                            <label for="selChargeMethod">결제방법</label>
                                        </div>
                                    </div>
                                    <div class="form-inline me-3">
                                        <div class="form-floating">
                                            <select id="selCharge" class="form-select">
                                                <option value="10000">10,000원</option>
                                                <option value="20000">20,000원</option>
                                                <option value="30000">30,000원</option>
                                                <option value="50000">50,000원</option>
                                                <option value="100000">100,000원</option>
                                                <option value="0">직접 입력</option>
                                            </select>
                                            <label for="selCharge">충전 금액</label>
                                        </div>
                                    </div>
                                    <div class="form-inline me-2">
                                        <div class="form-floating">
                                            <input type="text" id="txtCharge" class="form-control" name="pay_amount" placeholder="충전 금액" value="10000" maxlength="7" readonly>
                                            <label for="selCharge">충전 금액</label>
                                        </div>
                                    </div>
                                    <div class="form-inline align-self-center">
                                        <button type="button" id="btnPointCharge" class="btn btn-primary"><i class="fa-solid fa-receipt me-1"></i>충전</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="pointChargeList" class="tab-pane fade" role="tabpanel" aria-labelledby="pointChargeList-tab" tabindex="0">
                            <p class="text-muted mt-1">결제내역은 최근 30개 항목을 조회하여 표시합니다&#46;<br>결제취소는 결제일로부터 7일 이전&#40;6일&#41;까지만 가능합니다&#46;</p>
                            <table id="pointChargeListTable" class="table table-sm table-hover table-bordered">
                                <thead class="align-middle text-center">
                                    <th width="25%">번호</th>
                                    <th width="25%">결제일자</th>
                                    <th width="25%">충전금액</th>
                                    <th width="25%"></th>
                                </thead>
                                <tbody class="align-middle text-center"></tbody>
                            </table>
                            <form id="pointRefundForm" method="post" action="/TossPayment/index.php" target="_blank">
                                <input type="hidden" id="hd_payment_key" name="paymentKey" value="" />
                                <input type="hidden" name="cancelReason" value="고객이 취소" />
                                <input type="hidden" name="cancelFlag" value="s" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#btnCommute2').click(function() {
            $('#btnCommute').trigger('click');
        });

        $("#btnPointCharge").click(function() {
            var point_amount = $("#txtCharge").val();
            var point_method = $("#selChargeMethod").val();

            if (!point_amount || point_amount <= 0) {
                alert("충전 금액이 0 원이거나 0원보다 작을 수 없습니다.");
                return false;
            }
            if (point_method == 'CARD') {
                if (point_amount < 100) {
                    alert("카드결제는 결제금액이 100원 이상부터 결제가 가능합니다.");
                    return false;
                }
            } else if (point_method == 'TRANSFER') {
                if (point_amount < 200) {
                    alert("계좌이체는 결제금액이 200원 이상부터 결제가 가능합니다.");
                    return false;
                }
            }
            var form = document.getElementById("pointChargeFrom");
            window.open("", "popupName", "width=700, height=800, left=" + Math.ceil((window.screen.width - 700) / 2) + ", top=" + Math.ceil((window.screen.height - 800) / 2));
            form.target = 'popupName';
            form.submit();
        });

        const triggerTabList = document.querySelectorAll('#pointTab button')
        triggerTabList.forEach(triggerEl => {
            const tabTrigger = new bootstrap.Tab(triggerEl);

            triggerEl.addEventListener('click', event => {
                event.preventDefault();
                tabTrigger.show();
            });
        });

        getPointChargeList();

        $("#pointChargeListTable > tbody").on("click", ".pointcancel", function() {
            var order_num = $(this).parents("tr").data("order_num");
            var payment_key = $(this).parents("tr").data("payment_key");
            var charge_amount = $(this).parents("tr").find("td:eq(2)").text();
            if (!payment_key) {
                alert("잘못된 접근입니다.");
                return false;
            }
            if (confirm("결제를 취소하실 경우 충전하신 금액만큼 차감되며, 충전 금액을 사용하여 포인트 잔액이 충전금액보다 작을 경우 환불할 수 없습니다.\n결제를 취소하시겠습니까?")) {
                if (getPointBalance(charge_amount) == true) {
                    $("#hd_payment_key").val(payment_key);
                    var form = document.getElementById("pointRefundForm");
                    window.open("", "popupName2", "width=700, height=800, left=" + Math.ceil((window.screen.width - 700) / 2) + ", top=" + Math.ceil((window.screen.height - 800) / 2));
                    form.target = 'popupName2';
                    form.submit();
                }
            }
            return false;
        });
    });


    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.dropdown').forEach(function(ei) {
            ei.addEventListener("mouseover", function(e) {
                let el = this.querySelector("a[data-bs-toggle]");
                if (el != null) {
                    let nel = el.nextElementSibling;
                    el.classList.add("show");
                    nel.classList.add("show");
                }
            });
            ei.addEventListener("mouseleave", function(e) {
                let el = this.querySelector("a[data-bs-toggle]");
                if (el != null) {
                    let nel = el.nextElementSibling;
                    el.classList.remove("show");
                    nel.classList.remove("show");
                }
            });
        });
    });

    function commute_check() {
        var user_no = '<?= $_SESSION['logged_no'] ?>';
        var franchise_idx = '<?= $_SESSION['center_idx'] ?>';
        var state = '<?= ($state == '퇴근') ? "출근" : "퇴근" ?>';

        if (confirm(state + " 체크를 진행하겠습니까?") == true) {
            $.ajax({
                url: '/center/ajax/commuteControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'insertCommuteLog',
                    user_no: user_no,
                    franchise_idx: franchise_idx,
                    state: state
                },
                success: function(result) {
                    if (result.success) {
                        alert(result.msg);
                        location.reload();
                        return false;
                    } else {
                        alert(result.msg);
                        return false;
                    }
                },
                error: function(request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }
    }

    function getPointChargeList() {
        var center_idx = '<?= $_SESSION['center_idx'] ?>';
        $.ajax({
            url: '/center/ajax/franchiseControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'getPointChargeList',
                center_idx: center_idx
            },
            success: function(result) {
                if (result.success && result.data) {
                    $("#pointChargeListTable > tbody").html(result.data.tbl);
                    return false;
                } else {
                    alert(result.msg);
                    $("#pointChargeListTable > tbody").empty();
                    return false;
                }
            },
            error: function(request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    }

    function getPointBalance(charge_amount) {
        var center_idx = '<?= $_SESSION['center_idx'] ?>';
        $.ajax({
            url: '/center/ajax/franchiseControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'getPointBalance',
                center_idx: center_idx,
                charge_amount: charge_amount
            },
            success: function(result) {
                if (result.success) {
                    return true;
                } else {
                    alert(result.msg);
                    return false;
                }
            },
            error: function(request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                return false;
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        $("#lblPoint").text($("#lblPoint").text().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
    });

    const amt = document.getElementById("txtCharge");
    amt.addEventListener("focusout", function() {
        amt.value = amt.value.replace(/[^0-9]/g, '');
    });

    $("#selCharge").change(function() {
        if ($(this).val() == '0') {
            $("#txtCharge").removeAttr('readonly');
        } else {
            $("#txtCharge").attr('readonly', true);
        }
        $("#txtCharge").val($(this).val());
    });

    $('#btnSMS').click(function() {
        $('a.menu[href="#send_sms"]').trigger('click');
    });
</script>
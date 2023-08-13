$(document).ready(function () {
    $("#txtSalesYear").datepicker({
        language: 'ko-Kr',
        format: 'yyyy'
    });

    getRoyaltyData();
    $('input:text[numberOnly]').on('keyup', function () {
        $(this).val($(this).val().replace(/[^0-9]/g, ""));
    });

    $("#txtSalesYear").change(function () {
        getRoyaltyData();
    });

    $("#PayInfoList").on("click", ".tc", function (e) {
        if ($(e.target).parents('.bg-light').length) {
            $(e.target).parent('.tc').removeClass('bg-light');
            $("#tdPayState").empty();
            $("#tdPayDate").empty();
            $("#InvoiceDetailList").empty();
            $("#txtRefundNo").val(0);
            $("#txtRefundAmount").val(0);
            $("#txtRefundRoyalty").val(0);
            $("#hdorder_num").val('');
            $("#hdorder_ym").val('');
            $("#PaymentMethod_div").hide();
            $("#btnFranchiseFeePay").hide();
            $("#btnFranchiseFeeRefundRequest").hide();
            setPayForm();
        } else {
            $('.tc').removeClass('bg-light');
            $(e.target).parent('.tc').addClass('bg-light');

            var order_ym = $(e.target).parent('tr').find("td:eq(0)").text();
            var order_num = $(e.target).parent('tr').data('order_num');
            $("#hdorder_num").val(order_num);
            $("#hdorder_ym").val(order_ym);
            var state = $(e.target).parent('tr').data('fee-state');
            if (state == '00' || state == '01') {
                $("#btnFranchiseFeePay").show();
                $("#btnFranchiseFeeRefundRequest").hide();
                $("#PaymentMethod_div").show();
            }else {
                $("#btnFranchiseFeePay").hide();
                $("#btnFranchiseFeeRefundRequest").show();
                $("#PaymentMethod_div").hide();
                setPayForm();
            }
            getInvoiceData(order_ym);
            getRoyaltyPaymentData(order_ym);
        }
    });
});

function invoiceInsert() {
    var order_num = $("#hdorder_num").val();
    var order_ym = $("#hdorder_ym").val();
    var amount = $("#hdtotAmount").val();
    if (!order_ym) {
        alert("잘못된 접근입니다.");
        return false;
    }
    if (!amount || amount == 0) {
        alert("잘못된 접근입니다.");
        return false;
    }
    if (!order_num || order_num == '') {
        $.ajax({
            url: '/center/ajax/saleConfirmControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'invoiceInsert',
                center_idx: centerInfo.center_idx,
                amount: amount,
                order_ym: order_ym
            },
            success: function (result) {
                if (result.success) {
                    return true;
                } else {
                    return false;
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                return false;
            }
        });
    }
}

function getInvoiceData(payYm) {
    $.ajax({
        url: '/center/ajax/saleConfirmControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getInvoiceData',
            center_idx: centerInfo.center_idx,
            payym: payYm
        },
        success: function (result) {
            if (result.success) {
                if (result.data && (result.data).length != 0) {
                    $("#InvoiceDetailList").html(result.data.tbl);
                    return false;
                } else {
                    $("#InvoiceDetailList").empty();
                    return false;
                }
            } else {
                $("#InvoiceDetailList").empty();
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });

    $.ajax({
        url: '/center/ajax/saleConfirmControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getInvoiceRefundData',
            center_idx: centerInfo.center_idx,
            payym: payYm
        },
        success: function (result) {
            if (result.success) {
                if (result.data) {
                    $("#txtRefundNo").val(result.data.refund_cnt);
                    $("#txtRefundAmount").val(result.data.refund_amount);
                    $("#txtRefundRoyalty").val(result.data.adjust_royalty);
                    return false;
                } else {
                    $("#txtRefundNo").val(0);
                    $("#txtRefundAmount").val(0);
                    $("#txtRefundRoyalty").val(0);
                    return false;
                }
            } else {
                $("#txtRefundNo").val(0);
                $("#txtRefundAmount").val(0);
                $("#txtRefundRoyalty").val(0);
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });

    $.ajax({
        url: '/center/ajax/saleConfirmControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getFranchiseFeeInfo',
            center_idx: centerInfo.center_idx,
            payym: payYm
        },
        success: function (result) {
            if (result.success) {
                if (result.data) {
                    $("#tdPayState").html(result.data.fee_state);
                    $("#tdPayDate").html(result.data.franchise_fee_date);
                    return false;
                } else {
                    $("#tdPayState").empty();
                    $("#tdPayDate").empty();
                }
            } else {
                $("#tdPayState").empty();
                $("#tdPayDate").empty();
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function getRoyaltyData() {
    var SalesYear = $("#txtSalesYear").val();
    $.ajax({
        url: '/center/ajax/saleConfirmControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getRoyaltyData',
            center_idx: centerInfo.center_idx,
            year: SalesYear
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#PayInfoTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [
                        {
                            data: 'order_ym'
                        },
                        {
                            data: 'tot_money'
                        },
                        {
                            data: 'fee_state'
                        }
                    ],
                    createdRow: function (row, data) {
                        $("th").addClass('text-center align-middle');
                        $(row).addClass('tc text-center align-middle');
                        $(row).attr('data-fee-state', data.franchise_fee_state);
                        $(row).attr('data-order_num', data.order_num);
                    },
                    order: [[0, 'desc']],
                    lengthChange: false,
                    info: false,
                    searching: false,
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
            } else {
                $('#PayInfoTable').DataTable().destroy();
                $("#PayInfoTable > tbody").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function getRoyaltyPaymentData(payYm) {
    $.ajax({
        url: '/center/ajax/saleConfirmControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getRoyaltyPaymentData',
            center_idx: centerInfo.center_idx,
            payym: payYm
        },
        success: function (result) {
            if (result.success) {
                if (result.data) {
                    $("#lblTotal1").text(result.data.royalty); // 로열티
                    $("#lblTotal2").text(result.data.rams_fee); // RAMS임대료
                    $("#lblTotal_r").text(result.data.adjust_royalty); // 조정로열티
                    $("#lblTotal1_2").text(result.data.usage_fee); // 이용요금 합계 
                    $("#lblTotal3").text(result.data.tax); // 세금
                    $("#lblTotalAmount").text(result.data.totamount); // 총 결제 금액
                    $("#hdtotAmount").val((result.data.totamount).replace(",", ""));
                    $("#txtRefundableAmount").val(result.data.refundableAmt);
                    return false;
                } else {
                    $("#lblTotal1").text('0'); // 로열티
                    $("#lblTotal2").text('0'); // RAMS임대료
                    $("#lblTotal_r").text('0'); // 조정로열티
                    $("#lblTotal1_2").text('0'); // 이용요금 합계 
                    $("#lblTotal3").text('0'); // 세금
                    $("#lblTotalAmount").text('0'); // 총 결제 금액
                    $("#txtRefundableAmount").val('0'); // 환불신청 가능금액
                }
            } else {
                $("#lblTotal1").text('0'); // 로열티
                $("#lblTotal2").text('0'); // RAMS임대료
                $("#lblTotal_r").text('0'); // 조정로열티
                $("#lblTotal1_2").text('0'); // 이용요금 합계 
                $("#lblTotal3").text('0'); // 세금
                $("#lblTotalAmount").text('0'); // 총 결제 금액
                $("#txtRefundableAmount").val('0'); // 환불신청 가능금액
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function refundRequest() {
    var refundable_amount = $("#txtRefundableAmount").val();
    var req_reason = $("#txtRefundRequestReason").val();
    var req_amount = $("#txtRefundRequestAmount").val();
    var order_num = $("#hdorder_num").val();
    if (!order_num) {
        alert("잘못된 접근입니다.");
        return false;
    }
    if (!req_reason || req_reason.length < 2) {
        alert("환불 사유는 2자 이상 입력해주셔야 합니다.");
        $("#txtRefundRequestReason").focus();
        return false;
    }
    if (!req_amount || (Number)(req_amount) > (Number)(refundable_amount)) {
        alert("환불신청 가능금액을 확인한 후 환불 신청금액을 입력해주세요.");
        $("#txtRefundRequestAmount").focus();
        return false;
    }
    $.ajax({
        url: '/center/ajax/saleConfirmControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'refundRequestInsert',
            center_idx: centerInfo.center_idx,
            order_num: order_num,
            req_reason: req_reason,
            req_amount: req_amount
        },
        success: function (result) {
            if (result.success) {
                alert(result.msg);
                $("#refundRequestModal").modal("hide");
                return false;
            } else {
                alert(result.msg);
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function setPayForm() {
    $("#lblTotal1").text('0'); // 로열티
    $("#lblTotal2").text('0'); // RAMS임대료
    $("#lblTotal_r").text('0'); // 조정로열티
    $("#lblTotal1_2").text('0'); // 이용요금 합계 
    $("#lblTotal3").text('0'); // 세금
    $("#lblTotalAmount").text('0'); // 총 결제 금액
}

function comma2Money(money) {
    return money.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
}
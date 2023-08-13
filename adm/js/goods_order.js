$(document).ready(function () {
    loadOrderGoodsList();

    $("#order_month, #selOrderState").change(function () {
        loadOrderGoodsList();
    });

    $('#txtDeliveryFee').on('propertychange change keyup paste', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#btnSave").click(function () {
        var order_num = $("#txtOrderNo").val();
        var state = $("#selState").val();
        if (!state) {
            alert("주문상태를 선택해주세요.");
            return false;
        } else if (state == '98') {
            alert("결제취소는 사용자 취소입니다. 본사취소로 선택해주세요.");
            return false;
        }
        checkGoodsOrderState();
        console.log(state_chk);
        if (state_chk == true) {
            orderUpdate(order_num, state);
            return false;
        } else {
            console.log(state_chk);
            return false;
        }
    });

    $("#dataTable").on('page.dt', function () {
        $('.tc').removeClass('bg-light');
    });

    $("#dataTable > tbody").on("click", ".tc", function (e) {
        var targetClass = $(e.target).parents("tr");

        if ($(e.target).parent('.bg-light').length) {
            targetClass.removeClass('bg-light');

            $('#orderinfo')[0].reset();
            $("#dataTable2 > tbody").empty();
        } else {
            $('.tc').removeClass('bg-light');
            targetClass.addClass('bg-light');

            var order_num = targetClass.data("order-num");

            $.ajax({
                url: '/adm/ajax/goodsControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'selectOrderGoodsList',
                    order_num: order_num
                },
                success: function (result) {
                    if (result.success && result.data) {
                        setFormData(result.data.shipping_data);
                        $("#dataTable2 > tbody").html(result.data.order_list);
                    } else {
                        return false;
                    }
                },
                error: function (request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }
    });
});

function loadOrderGoodsList() {
    var month = $("#order_month").val();
    var state = $("#selOrderState").val();
    if (!month) {
        alert("주문년월을 선택해주세요.");
        return false;
    }
    if (!state) {
        alert("주문상태를 선택해주세요.");
        return false;
    }

    $.ajax({
        url: '/adm/ajax/goodsControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'loadOrderGoodsList',
            order_month: month,
            order_state: state
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#dataTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'order_num'
                    },
                    {
                        data: 'center_name'
                    },
                    {
                        data: 'order_state'
                    },
                    {
                        data: 'order_method'
                    },
                    {
                        data: 'order_money'
                    },
                    {
                        data: 'pay_money'
                    },
                    {
                        data: 'order_date'
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    order: [[0, 'desc']],
                    createdRow: function (row, data) {
                        $(row).addClass('tc text-center align-middle');
                        $(row).attr('data-order-num', data.order_num);
                        $("th").addClass('text-center align-middle');
                    },
                    displayLength: 15,
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });
            } else {
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function checkGoodsOrderState() {
    var order_num = $("#txtOrderNo").val();
    var order_state = $("#selState").val();

    $.ajax({
        url: '/adm/ajax/goodsControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'checkGoodsOrderState',
            order_num: order_num
        },
        success: function (result) {
            if (result.success && result.data) {
                var state = result.data.state;
                if (state >= '98') {
                    alert("이미 취소된 주문의 주문상태는 변경할 수 없습니다.");
                    return false;
                } else if (state == order_state) {
                    alert("주문상태를 확인해주세요.");
                    return false;
                } else if (state >= '02' && order_state == '01') {
                    alert("이미 결제된 주문의 주문상태를 결제대기로 변경할 수 없습니다.");
                    return false;
                } else {
                    state_chk = true;
                    return true;
                }
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

function orderUpdate(order_num, state) {
    $.ajax({
        url: '/adm/ajax/goodsControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'orderUpdate',
            order_num: order_num,
            state: state
        },
        success: function (result) {
            if (result.success) {
                if (result.data) {
                    if (state == '99') {
                        loadRefundPayment(result.data.paymentKey);
                        alert(result.msg);
                    }
                    return false;
                } else {
                    alert(result.msg);
                    location.reload();
                    return false;
                }
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

function setFormData(data) {
    $("#txtOrderNo").val(data.order_num);
    $("#txtOrderDate").val(data.reg_date);
    $("#txtOrderCenterName").val(data.center_name);
    $("#txtSendDate").val(data.shipping_date);
    $("#txtCancelDate").val(data.cancel_date);
    $("#selState option[value=" + data.order_state + "]").prop("selected", true);
    $("#txtOrderAmount").val(data.order_money);
    $("#txtDeliveryFee").val(data.shipping_money);
    $("#txtHp").val(data.teacher_phone);
    $("#txtDeliveryCompany").val(data.shipping_company);
    $("#txtDeliveryNo").val(data.invoice_number);
    $("#txtAddr").val(data.shipping_address);
    $("#txtEtc").val(data.shipping_memo);
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
    cancelFlag.setAttribute("value", "h");
    refundPaymentForm.appendChild(cancelFlag);

    var cancelReason = document.createElement("input");
    cancelReason.setAttribute("type", "hidden");
    cancelReason.setAttribute("name", "cancelReason");
    cancelReason.setAttribute("value", "본사 취소");
    refundPaymentForm.appendChild(cancelReason);
    document.body.appendChild(refundPaymentForm);
    window.open("", "popupName", "width=700, height=800, left=" + Math.ceil((window.screen.width - 700) / 2) + ", top=" + Math.ceil((window.screen.height - 800) / 2));
    refundPaymentForm.target = 'popupName';
    refundPaymentForm.submit();
    document.body.removeChild(refundPaymentForm);
}
$(document).ready(function () {
    getReceiptData();
    $("#dtPaymentMonth").change(function () {
        getReceiptData();
    });

    $("#PaymentTable > tbody").on("click", ".rtc", function () {

        if ($(this).hasClass("bg-light") === true) {
            $(this).removeClass("bg-light");
            $("#payment_div").hide();
            $("#txtPrice").val('0');
            $("#hdPrice").val('0');
            $("#hd_order_num").val('');
            $("#PaymentTable2 > tbody").empty();
        } else {
            $(this).addClass("bg-light");
            var amount = $(this).data("amount");
            var order_amount = $(this).data("order_amount");
            var order_num = $(this).data("order_num");
            var dtpaymonth = $(this).data("order_ym");
            getReceiptDataDetail(order_num, dtpaymonth);
            if (amount == 0) {
                $("#txtPrice").val('0');
                $("#hdPrice").val('0');
                $("#hd_order_num").val('');
                $("#payment_div").hide();
            } else {
                $("#txtPrice").val(amount);
                $("#txtPrice2").val(amount.replace(",", ""));
                $("#hdPrice").val(amount.replace(",", ""));
                $("#hd_order_num").val(order_num);
                $("#payment_div").show();
            }
        }

    });
});

function getReceiptData() {
    var dtpaymonth = $("#dtPaymentMonth").val();

    $.ajax({
        url: '/center/student/ajax/receiptControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getReceiptData',
            center_idx: center_idx,
            paymonth: dtpaymonth,
            student_no: student_idx
        },
        success: function (result) {
            if (result.success) {
                if (result.data) {
                    $("#PaymentTable > tbody").empty();
                    $("#PaymentTable > tbody").html(result.data.tbl);
                } else {
                    $("#PaymentTable > tbody").empty();
                    $("#PaymentTable2 > tbody").empty();
                }
                return false;
            } else {
                $("#PaymentTable > tbody").empty();
                $("#PaymentTable2 > tbody").empty();
                return false;
            }
        }
    });
}

function getReceiptDataDetail(order_num, dtpaymonth) {
    $.ajax({
        url: '/center/student/ajax/receiptControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getReceiptDataDetail',
            center_idx: center_idx,
            student_no: student_idx,
            order_num: order_num,
            paymonth: dtpaymonth
        },
        success: function (result) {
            if (result.success) {
                if (result.data) {
                    $("#PaymentTable2 > tbody").empty();
                    $("#PaymentTable2 > tbody").html(result.data.tbl2);
                } else {
                    $("#PaymentTable2 > tbody").empty();
                }
                return false;
            } else {
                $("#PaymentTable2 > tbody").empty();
                return false;
            }
        }
    });
}
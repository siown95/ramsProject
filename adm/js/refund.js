$(document).ready(function () {
    getStudentRefundData($("#refundMonth").val());
    $("#refundMonth").change(function () {
        if (!($(this).val())) {
            alert("환불년월을 선택해주세요.");
            return false;
        }
        var type = $("#selSearchType").val();
        tableReDraw(type);
        if (type == 'i') {
            getStudentRefundData($(this).val());
        } else if (type == 'f') {
            getFranchiseFeeRefundData($(this).val());
        } else {
            alert("잘못된 접근입니다.");
            return false;
        }
    });

    $('#RefundTable > tbody').on("click", ".tc", function () {
        $('#RefundModal').modal('show');
        var order_num = $(this).data("order-num");
        var type = $("#selSearchType").val();
        getRequestRefundData(type, order_num);
    });

    $("#selSearchType").change(function () {
        var type = $("#selSearchType").val();
        tableReDraw(type);
        if (!($("#refundMonth").val())) {
            alert("환불년월을 선택해주세요.");
            return false;
        }
        var type = $("#selSearchType").val();
        tableReDraw(type);
        if (type == 'i') {
            getStudentRefundData($("#refundMonth").val());
        } else if (type == 'f') {
            getFranchiseFeeRefundData($("#refundMonth").val());
        } else {
            alert("잘못된 접근입니다.");
            return false;
        }
    })
});

function getRequestRefundData(type, order_num) {
    $.ajax({
        url: '/adm/ajax/refundControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'getRequestRefundData',
            order_num: order_num,
            type: type
        },
        success: function (result) {
            if (result.success) {
                if (result.data) {
                    if (type == 'i') {
                        $("#txtStudentName").val(result.data.user_name);
                        $("#txtCenterName").val(result.data.center_name);
                        $("#txtHp").val(result.data.user_phone);
                        $("#txtPayAmount").val(result.data.pay_amount);
                        $("#txtRefundRequestAmount").val(result.data.refund_amount);
                        $("#txtRefundAmount").val(result.data.refund_amount);
                        $("#txtRefundEtc").val(result.data.refund_etc);
                    } else if (type == 'f') {
                        $("#txtStudentName").val(result.data.user_name);
                        $("#txtCenterName").val(result.data.center_name);
                        $("#txtHp").val(result.data.user_phone);
                        $("#txtPayAmount").val(result.data.pay_amount);
                        $("#txtRefundRequestAmount").val(result.data.refund_request_amount);
                        $("#txtRefundAmount").val(result.data.refund_amount);
                        $("#txtRefundEtc").val(result.data.refund_request_reason);
                    }
                    return false;
                } else {
                    $("#txtStudentName").val('');
                    $("#txtCenterName").val('');
                    $("#txtHp").val('');
                    $("#txtPayAmount").val('');
                    $("#txtRefundRequestAmount").val('');
                    $("#txtRefundAmount").val('');
                    $("#txtRefundEtc").val('');
                    return false;
                }
            } else {
                alert(result.msg);
                $("#txtStudentName").val('');
                $("#txtCenterName").val('');
                $("#txtHp").val('');
                $("#txtPayAmount").val('');
                $("#txtRefundRequestAmount").val('');
                $("#txtRefundAmount").val('');
                $("#txtRefundEtc").val('');
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function tableReDraw(type) {
    $("#RefundTable > thead").empty();
    $("#RefundTable > tbody").empty();
    if (type == 'i') {
        $("#RefundTable > thead").append(`<tr>
        <th>번호</th>
        <th>센터명</th>
        <th>이름</th>
        <th>전화번호</th>
        <th>결제수단</th>
        <th>결제금액</th>
        <th>환불일자</th>
        <th>환불금액</th>
        <th>환불신청일시</th>
        </tr>`);
    } else if (type == 'f') {
        $("#RefundTable > thead").append(`
        <tr>
        <th>번호</th>
        <th>센터명</th>
        <th>이름</th>
        <th>전화번호</th>
        <th>결제수단</th>
        <th>상태</th>
        <th>결제일자</th>
        <th>결제금액</th>
        <th>환불일자</th>
        <th>환불신청금액</th>
        <th>환불금액</th>
        </tr>`);
    } else {
        alert("잘못된 접근입니다.");
        return false;
    }
}

function getStudentRefundData(refund_month) {
    $.ajax({
        url: '/adm/ajax/refundControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'getStudentRefundData',
            refund_month: refund_month
        },
        success: function (result) {
            if (result.success) {
                if (result.data) {
                    $("#RefundTable > tbody").html(result.data.tbl);
                    return false;
                } else {
                    $("#RefundTable > tbody").empty();
                    return false;
                }
            } else {
                alert(result.msg);
                $("#RefundTable > tbody").empty();
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function getFranchiseFeeRefundData(refund_month) {
    $.ajax({
        url: '/adm/ajax/refundControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'getFranchiseFeeRefundData',
            refund_month: refund_month
        },
        success: function (result) {
            if (result.success) {
                if (result.data) {
                    $("#RefundTable > tbody").html(result.data.tbl);
                    return false;
                } else {
                    $("#RefundTable > tbody").empty();
                    return false;
                }
            } else {
                alert(result.msg);
                $("#RefundTable > tbody").empty();
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}
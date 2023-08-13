$(document).ready(function () {
    getStudentFeeList();
    $("#dtPayMonth, #selPayStudentGrade").change(function () {
        getStudentFeeList();
    });

    $('input:text[numberOnly]').on('propertychange change keyup paste', function () {
        $(this).val($(this).val().replace(/[^0-9]/g, ""));
    });

    $("#btnFeeRefundSave").click(function () {
        g_payamt = (Number)($("#hdPayedAmount").val());
        var order_num = $("#hdPayedInvoice_idx").val();
        var studentNo = $("#hdPayedStudentNo").val();
        var studentName = $("#hdPayedStudentName").val();
        var PayedMonth = $("#hdPayedMonth").val();
        var payedDate = $("#hdPayedDate").val();
        var refundMethod = $("#hdPayedTypeCode").val();
        var refundDate = $("#dtRefundDate").val();
        var refundAmount = $("#txtRefundAmount").val();
        var refundetc = $("#txtRefundEtc").val();

        if (g_payamt == 0) {
            alert("환불할 수 있는 잔액이 없습니다.");
            return false;
        }

        if (refundAmount > g_payamt) {
            alert("환불 금액이 결제 금액보다 클 수 없습니다.");
            $("#txtRefundAmount").val('0');
            return false;
        }

        if (refundDate < payedDate) {
            alert("환불일자가 결제일자보다 이전일 수 없습니다.");
            return false;
        }


        pay_arr.forEach(element => {
            pay_arr = pay_arr.filter((element) => element[2] !== "");
            pay_arr = pay_arr.filter((element) => element[3] !== "0");
        });

        if (pay_arr.length === 0) {
            alert("환불 상태를 변경하세요");
            return false;
        }

        if (confirm(`환불 신청 후 수정이 불가능합니다. \n원생이름 : ${studentName} \n결제년월 : ${PayedMonth} \n결제금액 : ${g_payamt} 원\n환불일자 : ${refundDate} \n환불금액 : ${refundAmount} 원\n환불 처리하시겠습니까?`)) {
            $.ajax({
                url: '/center/ajax/studentRefundControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'studentRefundInsert',
                    order_num: order_num,
                    center_idx: center_idx,
                    teacher_idx: teacher_idx,
                    student_no: studentNo,
                    PayedMonth: PayedMonth,
                    refunddate: refundDate,
                    refundMethod: refundMethod,
                    refundamount: refundAmount,
                    refundetc: refundetc,
                    pay_arr: pay_arr
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        getStudentFeeRefundList(studentNo, PayedMonth);
                        if (getPaymentKey(order_num)) {
                            if (confirm("온라인 결제로 진행된 결제정보입니다. 온라인 환불을 진행할 경우 확인을 눌러주시고, 오프라인 환불을 진행하시려는 경우 취소를 눌러주세요.\n온라인 환불의 경우 결제일로부터 7일 경과 시 직접 환불처리해야 합니다.")) {
                                getRefundPayment();
                            }
                        }
                        return false;
                    } else {
                        alert(result.msg);
                        return false;
                    }
                }
            });
            return false;
        }
    });

    $("#StudentFeeListTable > tbody").on("click", ".tc", function (e) {
        var student_no = $(e.target).parent('.tc').data("user-idx");
        var paymonth = $("#dtPayMonth").val();
        $("#hdPayedStudentNo").val($(e.target).parent('.tc').data("user-idx"));
        $("#hdPayedStudentName").val($(e.target).parents("tr").find("td:eq(1)").text());
        getStudentFeeRefundList(student_no, paymonth);
    });

    $("#InvoiceRefundListTable > tbody").on("click", "tr", function (e) {
        var Target = $(e.target).parents("tr").data("order-num");
        if (!Target) return false;
        var amt = (Number)($(e.target).parents("tr").find("td:eq(2)").text().split("/")[0].replace(",", ""));
        $("#hdPayedInvoice_idx").val(Target);
        $("#hdPayedMonth").val($(e.target).parents("tr").find("td:eq(0)").text());
        $("#hdPayedType").val($(e.target).parents("tr").find("td:eq(1)").text());
        $("#hdPayedTypeCode").val($(e.target).parents("tr").find("td:eq(1)").data('pay-method'));
        $("#hdPayedAmount").val(amt);
        $("#hdPayedDate").val($(e.target).parents("tr").find("td:eq(4)").text());
        $("#RefundInfoList").html(`<tr><td>${$("#hdPayedStudentName").val()}</td><td>${$("#hdPayedMonth").val()}</td><td>${$("#hdPayedType").val()}</td><td>${$("#hdPayedAmount").val()}</td></tr>`);
        getInvoiceItemData(Target);
    });


    $("#RefundItemList").on("change", ".selState", function () {
        if ($(this).val() == '03') { //전체환불
            $(this).parents('tr').find('td:eq(5) > input').val($(this).parents('tr').find('td:eq(2)').text().replace(",", ""));
            $(this).parents('tr').find('td:eq(5) > input').attr("readonly", true);
        } else if ($(this).val() == '04') { //부분환불
            $(this).parents('tr').find('td:eq(5) > input').val($(this).parents('tr').find('td:eq(3)').text().replace(",", ""));
            $(this).parents('tr').find('td:eq(5) > input').removeAttr("readonly");
        } else {
            $(this).parents('tr').find('td:eq(5) > input').val(0);
            $(this).parents('tr').find('td:eq(5) > input').attr("readonly", true);
        }

        $("#txtRefundAmount").val(getPayItemAmount());
    });

    $("#RefundItemList").on("propertychange change keyup paste", "input", function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));

        if ($(this).parents('tr').find('td:eq(4) > select').val() == '04') {
            if ($(this).val() > $(this).parents('tr').find('td:eq(2)').text().replace(",", "")) {
                alert("부분환불 금액은 결제 금액보다 적은액수여야 합니다.");
                $(this).val($(this).parents('tr').find('td:eq(3)').text().replace(",", ""));
            }
        }

        $("#txtRefundAmount").val(getPayItemAmount());
    });
});

function getPayItemAmount() {
    var payamt = 0;
    pay_arr = [];
    $("#RefundItemList tr").each(function () {
        if (!this.rowIndex) return false;
        pay_arr.push([$(this).data("item-idx"), $(this).find("td:eq(0)").text(), $(this).find("select").val(), $(this).find("td:eq(5)").find('input').val()]);
    });
    pay_arr.forEach(element => {
        payamt += (Number)(element[3].replace(",", ""));
    });

    return payamt;
}

function getStudentFeeList() {
    var paymonth = $("#dtPayMonth").val();
    var grade = $("#selPayStudentGrade").val();

    if (!paymonth || !grade) {
        alert("검색 조건을 다시 확인해주세요.");
        return false;
    }

    $.ajax({
        url: '/center/ajax/studentRefundControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'loadFeeStudent',
            center_idx: center_idx,
            paymonth: paymonth,
            grade: grade
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#StudentFeeListTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: '<"col-sm-12"f>t<"col-sm-12"p>',
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'user_name'
                    },
                    {
                        data: 'user_phone'
                    },
                    {
                        data: 'teacher_name'
                    }
                    ],
                    createdRow: function (row, data) {
                        $(row).addClass('tc text-center align-middle');
                        $(row).attr('data-user-idx', data.user_no);
                        $("th").addClass('text-center align-middle')
                    },
                    columnDefs: [{
                        targets: [0, 1, 2, 3],
                        className: 'dt-head-center'
                    }],
                    lengthChange: false,
                    info: false,
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
                return false;
            } else {
                $('#StudentFeeListTable').DataTable().destroy();
                $("#StudentFeeListTable > tbody").empty();
                return false;
            }
        }
    });
}

function getStudentFeeRefundList(student_no, paymonth) {
    $.ajax({
        url: '/center/ajax/studentRefundControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'getPayedAmount',
            center_idx: center_idx,
            student_no: student_no,
            paymonth: paymonth
        },
        success: function (result) {
            if (result.success && result.data) {
                g_payamt = result.data;
                if (result.msg) {
                    alert(result.msg);
                }
                $.ajax({
                    url: '/center/ajax/studentRefundControll.ajax.php',
                    dataType: 'JSON',
                    type: 'POST',
                    data: {
                        action: 'loadStudentFeeList',
                        center_idx: center_idx,
                        student_no: student_no,
                        paymonth: paymonth
                    },
                    success: function (result) {
                        if (result.success && result.data) {
                            $('#InvoiceRefundListTable').DataTable({
                                autoWidth: false,
                                destroy: true,
                                data: result.data,
                                stripeClasses: [],
                                dom: '<"col-sm-12"f>t<"col-sm-12"p>',
                                columns: [{
                                    data: 'order_ym'
                                },
                                {
                                    data: 'pay_method'
                                },
                                {
                                    data: 'pay_amount'
                                },
                                {
                                    data: 'pay_date'
                                },
                                {
                                    data: 'order_date'
                                }],
                                createdRow: function (row, data) {
                                    $(row).addClass('text-center align-middle');
                                    $(row).attr('data-order-num', data.order_num);
                                    $("td:eq(1)", row).attr("data-pay-method", data.order_method);
                                    $("th").addClass('text-center align-middle')
                                },
                                lengthChange: false,
                                order: [[0, "desc"], [4, "desc"]],
                                displayLength: 12,
                                info: false,
                                language: {
                                    url: '/json/ko_kr.json'
                                }
                            });

                            return false;
                        } else {
                            $("#btnFeeRefundSave").hide();
                            $('#InvoiceRefundListTable').DataTable().destroy();
                            $("#InvoiceRefundListTable > tbody").empty();
                            return false;
                        }
                    }
                });
                return false;
            } else {
                alert(result.msg);
                g_payamt = 0;
                return false;
            }
        }
    });
}

function getInvoiceItemData(order_num) {
    $.ajax({
        url: '/center/ajax/studentRefundControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getInvoiceItemData',
            order_num: order_num,
            center_idx: center_idx
        },
        success: function (result) {
            if (result.success) {
                if (result.data) {
                    $("#RefundItemList").html(result.data.tbl);
                    $("#txtRefundEtc").val(result.data.refund_etc);
                    $("#btnFeeRefundSave").show();
                    $("#txtRefundAmount").val(getPayItemAmount());
                } else {
                    $("#txtRefundEtc").val('');
                    $("#btnFeeRefundSave").hide();
                    $("#RefundItemList").empty();
                }
                return false;
            } else {
                alert(result.msg);
                $("#btnFeeRefundSave").hide();
                $("#RefundItemList").empty();
                return false;
            }
        }
    });
}

function getPaymentKey(order_num) {
    if (!order_num || order_num == '') {
        alert("잘못된 접근입니다.");
        return false;
    }
    $.ajax({
        url: '/center/ajax/studentRefundControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'getPaymentKey',
            order_num: order_num,
            center_idx: center_idx
        },
        success: function (result) {
            if (result.success) {
                if (result.data) {
                    $("#hd_payment_key").val(result.data.paymentKey);
                } else {
                    $("#hd_payment_key").val('');
                }
                return true;
            } else {
                alert(result.msg);
                $("#hd_payment_key").val('');
                return false;
            }
        }
    });
}
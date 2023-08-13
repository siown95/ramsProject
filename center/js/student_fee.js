$(document).ready(function () {
    getStudentList();
    $("#FeeList").on("click", ".btnFeeCancel", function () {
        $(this).closest("tr").remove();
        $("#txtFeeAmount").val(getPayItemAmount());
    });

    $('input:text[numberOnly]').on('propertychange change keyup paste input keydown keypress', function () {
        $(this).val($(this).val().replace(/[^0-9]/g, ""));
    });

    $("#btnMonthFeeListCreate").click(function () {
        var targetFeeMonth = $("#dtReceiptMonth").val();
        if (confirm("지정하신 " + targetFeeMonth + "의 수업정보로 원비 수납정보를 생성하시겠습니까?\n기존에 존재하는 수납정보는 그대로 존재하는 경우 중복으로 생성되므로 확인하시기 바랍니다.")) {
            monthInvoiceListInsert();
        }
        return false;
    });

    $("#selMoreFeeItem").change(function () {
        $("#txtMoreFeeItem").val("");
        $("#txtMoreFeeAmount").val("");

        if ($("#selMoreFeeItem option:selected").text() == '도서분실') {
            $("#txtMoreFeeAmount").removeAttr("disabled");
        } else {
            $("#txtMoreFeeAmount").attr("disabled", "true");
        }

        $("#txtMoreFeeItem").val($("#selMoreFeeItem option:selected").text());
        $("#txtMoreFeeAmount").val($("#selMoreFeeItem option:selected").val());
    });

    $("#btnMoreFeeAdd").click(function () {
        if (!$("#txtMoreFeeItem").val() || !$("#txtMoreFeeAmount").val()) {
            alert("추가 결제할 항목을 선택하세요");
            return false;
        }
        let receipt_name = $("#txtMoreFeeItem").val();
        let receipt_amount = $("#txtMoreFeeAmount").val();
        receipt_amount = receipt_amount.replace(/[^0-9]/g, "");
        if ($("#selMoreFeeItem option:selected").text() == '도서분실') {
            if ($("#txtMoreFeeAmount").val() == 0) {
                alert('금액을 입력하세요');
                return false;
            } else {
                $("#txtMoreFeeAmount").val('0');
            }
        }

        let receipt_idx = $("#selMoreFeeItem option:selected").data('receipt-idx');
        $("#FeeList:last").append(`<tr data-receipt-idx="${receipt_idx}">
        <td>${receipt_name}</td>
        <td>${receipt_amount}</td>
        <td><input class="form-control tct" type="text" maxlength="2" value="1"/></td>
        <td><input class="form-control tamt" type="text" maxlength="7" value="${receipt_amount}"/></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger btnFeeCancel">취소</button></td>
        </tr>`);
        $("#txtFeeAmount").val(getPayItemAmount());
        return false;
    });

    $("#dtPayMonth").change(function () {
        getLessonFeeInfo();
    });

    $("#selPayStudentGrade").change(function () {
        getStudentList();
    });

    $("#FeeList").on("propertychange change keyup paste", "input", function(){
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#FeeList").on("change", ".tct", function(){
        var amount = Number($(this).parents('tr').find("td:eq(1)").text() * $(this).val());
        $(this).parents('tr').find("td:eq(3)").find('input').val(amount);
        $("#txtFeeAmount").val(getPayItemAmount());
    });

    $("#FeeList").on("change", ".tamt", function(){
        $("#txtFeeAmount").val(getPayItemAmount());
    });

    $("#StudentFeeTable > tbody").on("click", ".tc", function (e) {
        var targetClass = $(e.target).parents(".tc");
        if ($(e.target).parents('.bg-light').length) {
            targetClass.removeClass('bg-light');
            $("#pay_student_idx").val('');
            $("#InvoiceTable > tbody").empty();
            $("#payForm")[0].reset();
            $("#FeeList").empty();
        } else {
            $('.tc').removeClass('bg-light');
            targetClass.addClass('bg-light');
            var student_no = $(e.target).parent('.tc').data("user-idx");
            $("#pay_student_idx").val(student_no);
            loadStudentFeeList(student_no);
        }
    });

    $("#InvoiceTable > tbody").on("click", ".ptc", function (e) {
        var targetClass = $(e.target).parents(".ptc");
        var order_num = $(e.target).parents('tr').data("order-num");
        if (order_num == "0" || order_num == '') {
            return false;
        }
        if ($(e.target).parents('.bg-light').length) {
            targetClass.removeClass('bg-light');
            $("#FeeList").empty();
            $("#pay_order_num").val('');
            $("#btnFeeSave").show();
            $("#btnFeeUpdate").hide();
            $("#payForm")[0].reset();
            ori_paystate = "";
        } else {
            $('.ptc').removeClass('bg-light');
            targetClass.addClass('bg-light');
            $("#btnFeeSave").hide();
            $("#btnFeeUpdate").show();

            $.ajax({
                url: '/center/ajax/studentFeeControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'loadInvoiceDetail',
                    center_idx: center_idx,
                    order_num: order_num
                },
                success: function (result) {
                    if (result.success) {
                        if (result.data) {
                            $("#FeeList").empty();
                            $("#FeeTable > #FeeList").html(result.data.tbl);
                            $("#pay_order_num").val(order_num);
                            $("#dtPayMonth").val(result.data[0].order_ym);
                            $("#dtPayDate").val(result.data[0].pay_date);
                            $("#selPayState").val(result.data[0].order_state).prop("selected", true);
                            $("#selPaymentType").val(result.data[0].order_method).prop("selected", true);
                            $("#txtPaymentEtc").val(result.data[0].pay_memo);
                            $("#txtFeeAmount").val(result.data.pay_amount);
                            ori_paystate = result.data[0].order_state;
                        }
                        return false;
                    } else {
                        $("#FeeList").empty();
                        return false;
                    }
                }
            });
        }
    });

    $("#btnFeeSave").click(function () {
        var order_num = $("#pay_order_num").val();
        var student_idx = $("#pay_student_idx").val();
        var paymonth = $("#dtPayMonth").val();
        var paydate = $("#dtPayDate").val();
        var paystate = $("#selPayState").val();
        var paytype = $("#selPaymentType").val();
        var payetc = $("#txtPaymentEtc").val();
        var payamount = 0;

        if (order_num) {
            alert("이미 처리된 원비수납은 수정할 수 없습니다.");
            return false;
        }

        if (!paymonth) {
            alert("결제년월을 선택해주세요.");
            return false;
        }

        if (!paystate) {
            alert("결제상태를 선택해주세요.");
            return false;
        } else if (!(paystate == '01' || paystate == '02')) {
            alert("결제상태가 결제대기 또는 결제완료로만 선택 가능합니다.");
            return false;
        }

        if (paystate == '02') {
            if (!paydate) {
                alert("결제상태가 결제완료일 경우 결제일을 선택해주세요.");
                return false;
            }
            if (!paytype) {
                alert("결제상태가 결제완료일 경우 결제종류를 선택해주세요.");
                return false;
            }
        }
        
        payamount = getPayItemAmount();
        if (payamount == 0) {
            alert("결제금액이 없습니다.");
            return false;
        }

        if (pay_arr.length < 1) {
            alert("결제항목이 없습니다.");
            return false;
        }

        $.ajax({
            url: '/center/ajax/studentFeeControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'payInvoiceInsert',
                center_idx: center_idx,
                teacher_idx: teacher_idx,
                student_idx: student_idx,
                paymonth: paymonth,
                paydate: paydate,
                paystate: paystate,
                paytype: paytype,
                payetc: payetc,
                payamount: payamount,
                lists: pay_arr
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#payForm")[0].reset();
                    loadStudentFeeList(student_idx);
                    return false;
                } else {
                    alert(result.msg);
                    return false;
                }
            }
        });
    });

    $("#btnFeeUpdate").click(function () {
        var order_num = $("#pay_order_num").val();
        var student_idx = $("#pay_student_idx").val();
        var paystate = $("#selPayState").val();
        if (!order_num || !student_idx) {
            alert("잘못된 접근입니다.");
            return false;
        }
        if (!paystate) {
            alert("결제상태를 선택해주세요.");
            return false;
        }
        if (paystate == '99') {
            if (payInvoiceCheck(order_num) === false) {
                return false;
            }
        }

        if( (ori_paystate == '02' && paystate == '01') || (ori_paystate == '99' && (paystate == '01' || paystate == '02') ) ){
            alert("결제상태가 결제대기인 원비수납 항목을 결제완료로 수정하거나, 결제완료인 원비수납 항목을 결제취소로 변경가능합니다.\n결제상태를 확인해주세요.");
            return false;
        }

        if (confirm("결제대기에서 결제완료로 변경 후 다시 결제대기로 변경이 불가능합니다.\n결제완료된 항목은 등록일로부터 7일 경과 시 변경이 불가능합니다.\n결제취소된 항목은 변경이 불가능합니다.")) {
            $.ajax({
                url: '/center/ajax/studentFeeControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'payInvoiceUpdate',
                    order_num: order_num,
                    center_idx: center_idx,
                    teacher_idx: teacher_idx,
                    student_idx: student_idx,
                    paystate: paystate
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#payForm")[0].reset();
                        loadStudentFeeList(student_idx);
                        return false;
                    } else {
                        alert(result.msg);
                        return false;
                    }
                }
            });
        }
    });
});

function getPayItemAmount() {
    var payamt = 0;
    pay_arr = [];
    $("#FeeList tr").each(function () {
        if (!this.rowIndex) return false;
        if (!$(this).find("td").eq(2).find('input').val() || $(this).find("td").eq(2).find('input').val() == 0){
            $(this).find("td").eq(2).find('input').val(1);
            return false;
        } 
        pay_arr.push([$(this).data("receipt-idx"), $(this).find("td:eq(0)").text(), $(this).find("td:eq(2)").find('input').val(), $(this).find("td:eq(3)").find('input').val()]);
    });
    pay_arr.forEach(element => {
        payamt += (Number)(element[3]);
    });

    return payamt;
}

function getStudentList() {
    var studentgrade = $("#selPayStudentGrade").val();
    $.ajax({
        url: '/center/ajax/studentFeeControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'loadStudent',
            center_idx: center_idx,
            grade: studentgrade
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#StudentFeeTable').DataTable({
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
                $('#StudentFeeTable').DataTable().destroy();
                $("#StudentFeeTable > tbody").empty();
                return false;
            }
        }
    });
}
function loadStudentFeeList(student_no) {
    $.ajax({
        url: '/center/ajax/studentFeeControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'loadStudentFeeList',
            center_idx: center_idx,
            student_no: student_no
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#InvoiceTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: '<"col-sm-12"f>t<"col-sm-12"p>',
                    columns: [{
                        data: 'order_ym'
                    },
                    {
                        data: 'pay_state'
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
                        $(row).addClass('text-center align-middle ptc');
                        $(row).attr('data-order-num', data.order_num);
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
                getLessonFeeInfo();
                return false;
            } else {
                $('#InvoiceTable').DataTable().destroy();
                $("#InvoiceTable > tbody").empty();
                return false;
            }
        }
    });
}
function getLessonFeeInfo() {
    var student_idx = $("#pay_student_idx").val();
    var paymonth = $("#dtPayMonth").val();
    if (!student_idx) {
        alert("학생을 선택해주세요.");
        return false;
    }
    if (!paymonth) {
        alert("결제년월을 선택해주세요.");
        return false;
    }
    $.ajax({
        url: '/center/ajax/studentFeeControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'getLessonFeeInfo',
            center_idx: center_idx,
            student_idx: student_idx,
            paymonth: paymonth
        },
        success: function (result) {
            if (result.success && result.data) {
                $("#FeeList").empty();
                $("#FeeList").html(result.data.tbl);
                $("#txtFeeAmount").val(result.data.amt);
                return false;
            } else {
                $("#FeeList").empty();
                return false;
            }
        }
    });
}

function payInvoiceCheck(order_num) {
    var msg = '';
    var return_data = true; 
    $.ajax({
        url: '/center/ajax/studentFeeControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'payInvoiceCheck',
            order_num: order_num,
            center_idx: center_idx
        },
        success: function (result) {
            if (result.success && result.msg) {
                msg = result.msg;
                return_data = false;
                alert(msg);
            } else {
                return_data = true;
            }
        }
    });
    
    return return_data;
}

function monthInvoiceListInsert() {
    var targetFeeMonth = $("#dtReceiptMonth").val();
    if (!targetFeeMonth) {
        alert("원비수납 항목을 생성할 년월을 선택해주세요.");
        return false;
    }

    $.ajax({
        url: '/center/ajax/studentFeeControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'monthInvoiceListInsert',
            center_idx: center_idx,
            teacher_idx: teacher_idx,
            month: targetFeeMonth
        },
        success: function (result) {
            if (result.success) {
                alert(result.msg);
                return true;
            } else {
                alert(result.msg);
                return false;
            }
        }
    });
}
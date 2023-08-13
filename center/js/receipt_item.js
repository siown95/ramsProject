$(document).ready(function () {
    receiptItemLoad();
    $("#selItem1").change(function () {
        if ($("#selItem1").val() != '99') {
            $(".div_ct").show();
        } else {
            $(".div_ct").hide();
        }
    });

    $('#txtReceiptItemAmount').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#receiptItemTable").on('page.dt', function () {
        clearData();
    });

    $("#receiptItemTable > tbody").on("click", ".tc", function (e) {
        var targetClass = $(e.target).parent('tr');

        if ($(e.target).parent('.bg-light').length) {
            targetClass.removeClass('bg-light');
            $("#targetReceipt").val('');
            $("#selItem1 option:first").prop("selected", true);
            $("#selClassType option:first").prop("selected", true);
            $("#selGrade option:first").prop("selected", true);
            $("#selReceiptUse").attr("disabled", true);
            $("#selReceiptUse option:first").prop("selected", true);
            $("#txtReceiptItemName").val('');
            $("#txtReceiptItemAmount").val('');
            $(".div_ct").show();
            $("#btnReceiptSave").show();
            $("#btnReceiptUpdate").hide();
        } else {
            $('.tc').removeClass('bg-light');
            targetClass.addClass('bg-light');

            var targetReceipt = targetClass.data('receipt-idx');
            $("#targetReceipt").val(targetReceipt);

            $.ajax({
                url: '/center/ajax/receiptItemControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'receiptItemSelect',
                    targetReceipt: targetReceipt
                },
                success: function (result) {
                    if (result.success && result.data) {

                        if (result.data.receipt_type == '99') {
                            $(".div_ct").hide();
                            $("#selItem1 option[value=" + result.data.receipt_type + "]").prop("selected", true);
                        } else {
                            $(".div_ct").show();

                            $("#selItem1 option[value=" + result.data.receipt_type + "]").prop("selected", true);
                            $("#selClassType option[value=" + result.data.receipt_lesson_type + "]").prop("selected", true);
                            $("#selReceiptGrade option[value=" + result.data.receipt_grade + "]").prop("selected", true);
                        }

                        $("#selReceiptUse").removeAttr('disabled');
                        $("#selReceiptUse option[value=" + result.data.useYn + "]").prop("selected", true);

                        $("#txtReceiptItemName").val(result.data.receipt_name);
                        $("#txtReceiptItemAmount").val(result.data.receipt_amount);

                        $("#btnReceiptSave").hide();

                        if (result.data.headYn == 'Y') {
                            $("#btnReceiptUpdate").hide();
                        } else {
                            $("#btnReceiptUpdate").show();
                        }
                    }
                },
                error: function (request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }
    });

    $("#btnReceiptSave").click(function () {
        var selItem1 = $("#selItem1").val(); //수납종류
        var txtReceiptItemName = $("#txtReceiptItemName").val(); //수납항목명
        var txtReceiptItemAmount = $("#txtReceiptItemAmount").val(); //금액

        if (selItem1 != '99') {
            var selClassType = $("#selClassType").val(); //수업종류
            var selGrade = $("#selReceiptGrade").val(); //학년

            if (!selClassType) {
                alert('수업종류를 선택하세요');
                return false;
            }

            if (!selGrade) {
                alert('학년을 선택하세요');
                return false;
            }
        }

        if (!txtReceiptItemName || !txtReceiptItemName.trim()) {
            alert('수납항목명을 입력하세요');
            return false;
        }

        if (!txtReceiptItemAmount) {
            alert('금액을 입력하세요');
            return false;
        }

        $.ajax({
            url: '/center/ajax/receiptItemControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'receiptItemInsert',
                sel_center: center_idx,
                selItem1: selItem1,
                selClassType: selClassType,
                selGrade: selGrade,
                txtReceiptItemName: txtReceiptItemName,
                txtReceiptItemAmount: txtReceiptItemAmount
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    receiptItemLoad();
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
    });

    $("#btnReceiptUpdate").click(function () {
        var targetReceipt = $("#targetReceipt").val();
        var selItem1 = $("#selItem1").val(); //수납종류
        var selReceiptUse = $("#selReceiptUse").val(); //사용여부
        var txtReceiptItemName = $("#txtReceiptItemName").val(); //수납항목명
        var txtReceiptItemAmount = $("#txtReceiptItemAmount").val(); //금액

        if (selItem1 != '99') {
            var selClassType = $("#selClassType").val(); //수업종류
            var selGrade = $("#selReceiptGrade").val(); //학년

            if (!selClassType) {
                alert('수업종류를 선택하세요');
                return false;
            }

            if (!selGrade) {
                alert('학년을 선택하세요');
                return false;
            }
        }

        if (!txtReceiptItemName || !txtReceiptItemName.trim()) {
            alert('수납항목명을 입력하세요');
            return false;
        }

        if (!txtReceiptItemAmount) {
            alert('금액을 입력하세요');
            return false;
        }

        $.ajax({
            url: '/center/ajax/receiptItemControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'receiptItemUpdate',
                targetReceipt: targetReceipt,
                selItem1: selItem1,
                selReceiptUse: selReceiptUse,
                selClassType: selClassType,
                selGrade: selGrade,
                txtReceiptItemName: txtReceiptItemName,
                txtReceiptItemAmount: txtReceiptItemAmount
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    receiptItemLoad();
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
    });

    $("#btnReceiptBatch").click(function () {
        if (confirm("본사 수납항목을 내려받으시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/receiptItemControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'receiptItemBatch',
                    sel_center: center_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        receiptItemLoad();
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
    });
});

function receiptItemLoad() {
    clearData();

    $.ajax({
        url: '/center/ajax/receiptItemControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'receiptItemLoad',
            sel_center: center_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#receiptItemTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'receipt_type'
                    },
                    {
                        data: 'lesson_type'
                    },
                    {
                        data: 'grade'
                    },
                    {
                        data: 'receipt_name'
                    },
                    {
                        data: 'receipt_amount'
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    createdRow: function (row, data) {
                        $("th").addClass('text-center align-middle');
                        $(row).addClass('tc text-center align-middle');
                        $(row).attr('data-receipt-idx', data.receipt_item_idx);
                    },
                    language: {
                        url: "/json/ko_kr.json"
                    }
                });
            } else {
                $('#receiptItemTable').DataTable().destroy();
                $('#receiptItemTable > tbody').empty();
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function clearData() {
    $("#receiptItemTable > tbody > tr").removeClass('bg-light');
    $("#targetReceipt").val('');
    $("#selItem1 option:first").prop("selected", true);
    $("#selClassType option:first").prop("selected", true);
    $("#selReceiptGrade option:first").prop("selected", true);
    $("#selReceiptUse").attr("disabled", true);
    $("#selReceiptUse option:first").prop("selected", true);
    $("#txtReceiptItemName").val('');
    $("#txtReceiptItemAmount").val('');

    $(".div_ct").show();

    $("#btnReceiptSave").show();
    $("#btnReceiptUpdate").hide();
}
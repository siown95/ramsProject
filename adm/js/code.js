$(document).ready(function () {
    codeLoad(1);

    $('#Codeform1').on('propertychange change keyup paste input', '#txtCode1', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $('#Codeform2').on('propertychange change keyup paste input', '#txtCode2', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $('#Codeform2').on('propertychange change keyup paste input', '#txtCode3', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#degree1").on("click", ".emt1", function (e) {
        degreeClick(e, 1);
    });

    $("#degree2").on("click", ".emt2", function (e) {
        degreeClick(e, 2);
    });

    $("#degree3").on("click", ".emt3", function (e) {
        degreeClick(e, 3);
    });

    $("#chkCode3").click(function () {
        if ($("#chkCode3").is(':checked')) {
            $("#code3Div").show();
        } else {
            $("#code3Div").hide();
        }
    });

    $("#btnClear1").click(function () {
        if ($("#txtCode1:disabled").length) {
            $("#txtCodeName1").val('');
        } else {
            $("#Codeform1")[0].reset();
        }
    });

    $("#btnClear2").click(function () {
        if ($("#chkCode3").is(':checked')) {
            if ($("#txtCode3:disabled").length) {
                $("#txtCodeName3").val('');
            } else {
                $("#txtCode3").val('');
                $("#txtCodeName3").val('');
            }
        } else if ($("#txtCode2:disabled").length) {
            $("#txtCodeName2").val('');
        } else {
            $("#txtCode2").val('');
            $("#txtCodeName2").val('');
        }
    });
});

//코드목록 선택
function degreeClick(e, degree) {
    var targetClass = $(e.target).parents('.emt' + degree);

    if ($(e.target).parent('.bg-light').length) {
        targetClass.removeClass('bg-light');

        $("#txtCode" + degree).val('');
        $("#txtCode" + degree).attr('disabled', false);
        $("#txtCodeName" + degree).val('');
        $("#editCode" + degree).val('0');
        $("#btnDelete" + degree).hide();

        if (degree == '1') {
            clearTalble('2');
            clearTalble('3');
        } else if (degree == '2') {
            $("#txtCode3").removeAttr('disabled');
            $("#chkCode3").prop('checked', false);
            $("#txtCode3").val('');
            $("#txtCodeName3").val('');
            $("#code3Div").hide();
            $("#txtCodeName2").removeAttr('disabled');
            $("#editCode2").val('0');
            $("#editCode3").val('0');
            clearTalble('3');
        } else {
            $("#chkCode3").prop('checked', false);
            $("#code3Div").hide();
            $("#txtCode3").val('');
            $("#txtCodeName3").val('');
            $("#txtCodeName2").removeAttr('disabled');
            $("#editCode3").val('0');
        }
    } else {
        $("#editCode" + degree).val('1');

        $('.emt' + degree).removeClass('bg-light');
        targetClass.addClass('bg-light');

        $("#txtCode" + degree).val(targetClass.find('td:eq(0)').text());
        $("#txtCode" + degree).attr('disabled', true);
        $("#txtCodeName" + degree).val(targetClass.find('td:eq(1)').text());

        if (degree == '1') {
            clearTalble('2');
            clearTalble('3');
            $("#editCode2").val('0');
            $("#editCode3").val('0');
            $("#txtCode1_2").val(targetClass.find('td:eq(0)').text());
            $("#txtCodeName1_2").val(targetClass.find('td:eq(1)').text());
            $("#btnSave2").show();
            codeLoad('2', targetClass.find('td:eq(0)').text());
        } else if (degree == '2') {
            $("#chkCode3").prop('checked', false);
            $("#code3Div").hide();
            $("#editCode3").val('0');
            $("#txtCodeName2").removeAttr('disabled');
            codeLoad('3', $("#txtCode1").val(), targetClass.find('td:eq(0)').text());
        } else {
            $("#chkCode3").prop('checked', true);
            $("#code3Div").show();
            $("#txtCodeName2").attr('disabled', true);
        }

        if (targetClass.data('necessary') == 'Y') {
            $("#btnDelete" + degree).hide();
        } else {
            $("#btnDelete" + degree).show();
        }
    }
}

//코드 불러오기
function codeLoad(degree, code_num1, code_num2) {
    $.ajax({
        url: '/adm/ajax/codeControll.ajax.php',
        dataType: 'json',
        type: 'POST',
        
        data: {
            action: 'codeLoad',
            degree: degree,
            code_num1: code_num1,
            code_num2: code_num2
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#dataTable' + degree).DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    displayLength: 5,
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    stripeClasses: [],
                    columns: [{
                        data: 'code_num'
                    },
                    {
                        data: 'code_name'
                    }
                    ],
                    createdRow: function (row, data) {
                        $(row).addClass('emt' + degree);
                        $(row).attr('data-necessary', data.necessary);
                        $(row).attr('data-code-num', data.code_num);
                        $("th").addClass('text-center align-middle');
                    },
                    lengthChange: false,
                    info: false,
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });

            } else {
                $('#dataTable' + degree).DataTable().destroy();
                $('#degree' + degree).empty();

                if (degree == '2') {
                    $("#btnDelete2").hide();
                    $("#txtCode2").val('');
                    $("#txtCode2").attr('disabled', false);
                    $("#txtCodeName2").val('');
                }
            }
        }
    });
}

//코드 입력 + 수정
function codeAction(degree) {
    var code_num = $("#txtCode" + degree).val(); //차수에 따른 기본 입력 코드
    var code_name = $("#txtCodeName" + degree).val(); //차수에 따른 기본 입력 코드명

    //ajax 핸들링시 보내는 데이터셋
    var code_num1 = '';
    var code_num2 = '';
    var code_num3 = '';
    var detail = '';
    var action = 'codeInsert';

    if (!code_num || !code_num.trim()) {
        alert('코드번호를 입력하세요.');
        $("#txtCode" + degree).focus();
        return false;
    }

    if (!code_name || !code_name.trim()) {
        alert('코드명을 입력하세요.');
        $("#txtCodeName" + degree).focus();
        return false;
    }

    if (degree == '1') {
        code_num1 = $("#txtCode1").val();
    } else if (degree == '2') {
        if (!$("#txtCode1").val()) {
            alert('1차 코드를 선택하세요.');
            return false;
        }

        if ($("#chkCode3").is(':checked')) {
            code_num3 = $("#txtCode3").val();
            detail = $("#txtCodeName3").val();

            if ($("#txtCode2").is(":disabled") == true) {
                if (!code_num3 || !code_num3.trim()) {
                    alert('3차 코드를 입력하세요.');
                    return false;
                }

                if (!detail || !detail.trim()) {
                    alert('3차 코드명을 입력하세요.');
                    return false;
                }

                degree = 3;
            } else {
                alert('2차 코드 생성 후 3차 코드를 작성해주세요.');
                return false;
            }
        }

        code_num1 = $("#txtCode1").val();
        code_num2 = $("#txtCode2").val();
    }

    if ($("#editCode" + degree).val() == '1') {
        action = 'codeUpdate';
    }

    if ($("#editCode3").val() == '1') {
        action = 'codeUpdate';
        degree = 3;
    }

    $.ajax({
        url: '/adm/ajax/codeControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        
        data: {
            action: action,
            degree: degree,
            code_name: code_name,
            detail: detail,
            code_num1: code_num1,
            code_num2: code_num2,
            code_num3: code_num3,
        },
        success: function (result) {
            if (result.success) {
                alert(result.msg);
                if (degree == 1) {
                    location.reload();
                } else if (degree == '2') {
                    codeLoad(degree, code_num1);
                    $("#txtCode2").removeAttr('disabled')
                    $("#txtCode2").val('');
                    $("#txtCodeName2").val('');
                    $("#editCode2").val('0');
                    $("#btnDelete2").hide();
                    clearTalble('3');
                } else {
                    codeLoad(degree, code_num1, code_num2);
                    $("#txtCode3").removeAttr('disabled')
                    $("#txtCode3").val('');
                    $("#txtCodeName3").val('');
                    $("#editCode3").val('0');
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

function codeDelete(degree) {
    var code_num1 = '';
    var code_num2 = '';
    var code_num3 = '';

    if (degree == '1') {
        code_num1 = $("#txtCode1").val();
    } else if (degree == '2') {
        if (!$("#txtCode1").val()) {
            alert('1차 코드를 선택하세요.');
            return false;
        }

        if ($("#chkCode3").is(':checked')) {
            code_num3 = $("#txtCode3").val();
            degree = 3;
        }

        code_num1 = $("#txtCode1").val();
        code_num2 = $("#txtCode2").val();
    }

    $.ajax({
        url: '/adm/ajax/codeControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        
        data: {
            action: 'codeDelete',
            degree: degree,
            code_num1: code_num1,
            code_num2: code_num2,
            code_num3: code_num3,
        },
        success: function (result) {
            if (result.success) {
                alert(result.msg);
                if (degree == 1) {
                    location.reload();
                } else if (degree == '2') {
                    codeLoad(degree, code_num1);
                    $("#txtCode2").removeAttr('disabled')
                    $("#txtCode2").val('');
                    $("#txtCodeName2").val('');
                    $("#editCode2").val('0');
                    clearTalble('3');
                } else {
                    codeLoad(degree, code_num1, code_num2);
                    $("#txtCode3").removeAttr('disabled')
                    $("#txtCode3").val('');
                    $("#txtCodeName3").val('');
                    $("#editCode3").val('0');
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

function clearTalble(idx) {
    $("#txtCode1_" + idx).val('');
    $("#txtCodeName1_" + idx).val('');
    $("#txtCode" + idx).attr('disabled', false);
    $("#txtCode" + idx).val('');
    $("#txtCodeName" + idx).val('');
    $("#dataTable" + idx).DataTable().destroy();
    $("#degree" + idx).empty();
    $("#btnSave" + idx).hide();
    $("#btnDelete" + idx).hide();
    $("#chkCode3").prop('checked', false);
    $("#code3Div").hide();
    $("#txtCode3").removeAttr('disabled');
    $("#chkCode3").prop('checked', false);
    $("#txtCode3").val('');
    $("#txtCodeName3").val('');
    $("#code3Div").hide();
    $("#txtCodeName2").removeAttr('disabled');
}
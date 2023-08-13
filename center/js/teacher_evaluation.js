$(document).ready(function () {
    teacherEvalLoad();

    $("#btnTEvalSave").click(function () {
        var user_no = $("#selTeacher").val();
        var txtTEval1 = $("#txtTEval1").val();
        var selTEval1 = $("#selTEval1").val();
        var txtTEval2 = $("#txtTEval2").val();
        var selTEval2 = $("#selTEval2").val();
        var txtTEval3 = $("#txtTEval3").val();
        var selTEval3 = $("#selTEval3").val();
        var txtTEval4 = $("#txtTEval4").val();
        var selTEval4 = $("#selTEval4").val();
        var txtTEval5 = $("#txtTEval5").val();
        var selTEval5 = $("#selTEval5").val();
        var txtTEval6 = $("#txtTEval6").val();
        var selTEval6 = $("#selTEval6").val();
        var txtTEval7 = $("#txtTEval7").val();
        var selTEval7 = $("#selTEval7").val();
        var txtTEval8 = $("#txtTEval8").val();
        var selTEval8 = $("#selTEval8").val();
        var txtTEval9 = $("#txtTEval9").val();
        var selTEval9 = $("#selTEval9").val();
        var txtTEval10 = $("#txtTEval10").val();
        var selTEval10 = $("#selTEval10").val();

        if (!user_no) {
            alert('선생님을 선택하세요.');
            return false;
        }

        if (!txtTEval1 || !txtTEval1.trim()) {
            alert('평가 근거를 입력하세요');
            return false;
        }

        if (!txtTEval2 || !txtTEval2.trim()) {
            alert('평가 근거를 입력하세요');
            return false;
        }

        if (!txtTEval3 || !txtTEval3.trim()) {
            alert('평가 근거를 입력하세요');
            return false;
        }

        if (!txtTEval4 || !txtTEval4.trim()) {
            alert('평가 근거를 입력하세요');
            return false;
        }

        if (!txtTEval5 || !txtTEval5.trim()) {
            alert('평가 근거를 입력하세요');
            return false;
        }

        if (!txtTEval6 || !txtTEval6.trim()) {
            alert('평가 근거를 입력하세요');
            return false;
        }

        if (!txtTEval7 || !txtTEval7.trim()) {
            alert('평가 근거를 입력하세요');
            return false;
        }

        if (!txtTEval8 || !txtTEval8.trim()) {
            alert('평가 근거를 입력하세요');
            return false;
        }

        if (!txtTEval9 || !txtTEval9.trim()) {
            alert('평가 근거를 입력하세요');
            return false;
        }

        if (!txtTEval10 || !txtTEval10.trim()) {
            alert('평가 근거를 입력하세요');
            return false;
        }

        if (confirm("저장 후 수정 및 삭제가 불가능합니다.\n등록하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/teacherEvaluationControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'teacherEvaluationInsert',
                    center_idx: center_idx,
                    user_no: user_no,
                    txtTEval1: txtTEval1,
                    selTEval1: selTEval1,
                    txtTEval2: txtTEval2,
                    selTEval2: selTEval2,
                    txtTEval3: txtTEval3,
                    selTEval3: selTEval3,
                    txtTEval4: txtTEval4,
                    selTEval4: selTEval4,
                    txtTEval5: txtTEval5,
                    selTEval5: selTEval5,
                    txtTEval6: txtTEval6,
                    selTEval6: selTEval6,
                    txtTEval7: txtTEval7,
                    selTEval7: selTEval7,
                    txtTEval8: txtTEval8,
                    selTEval8: selTEval8,
                    txtTEval9: txtTEval9,
                    selTEval9: selTEval9,
                    txtTEval10: txtTEval10,
                    selTEval10: selTEval10,
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#contents").load('teacher_evaluation.php');
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

    $("#TevaluationList").on("click", ".tc", function (e) {
        teacherEvalSelect(e);
    });
});

function teacherEvalLoad() {
    var months = $("#dtMonths").val();
    var teacher_no = $("#selTeacher").val();

    $("#txtTEval1").removeAttr("readonly");
    $("#txtTEval2").removeAttr("readonly");
    $("#txtTEval3").removeAttr("readonly");
    $("#txtTEval4").removeAttr("readonly");
    $("#txtTEval5").removeAttr("readonly");
    $("#txtTEval6").removeAttr("readonly");
    $("#txtTEval7").removeAttr("readonly");
    $("#txtTEval8").removeAttr("readonly");
    $("#txtTEval9").removeAttr("readonly");
    $("#txtTEval10").removeAttr("readonly");

    $("#selTEval1").removeAttr("disabled");
    $("#selTEval2").removeAttr("disabled");
    $("#selTEval3").removeAttr("disabled");
    $("#selTEval4").removeAttr("disabled");
    $("#selTEval5").removeAttr("disabled");
    $("#selTEval6").removeAttr("disabled");
    $("#selTEval7").removeAttr("disabled");
    $("#selTEval8").removeAttr("disabled");
    $("#selTEval9").removeAttr("disabled");
    $("#selTEval10").removeAttr("disabled");

    $("#txtTEval1").val('');
    $("#txtTEval2").val('');
    $("#txtTEval3").val('');
    $("#txtTEval4").val('');
    $("#txtTEval5").val('');
    $("#txtTEval6").val('');
    $("#txtTEval7").val('');
    $("#txtTEval8").val('');
    $("#txtTEval9").val('');
    $("#txtTEval10").val('');

    $("#selTEval1 option[value=01]").prop("selected", true);
    $("#selTEval2 option[value=01]").prop("selected", true);
    $("#selTEval3 option[value=01]").prop("selected", true);
    $("#selTEval4 option[value=01]").prop("selected", true);
    $("#selTEval5 option[value=01]").prop("selected", true);
    $("#selTEval6 option[value=01]").prop("selected", true);
    $("#selTEval7 option[value=01]").prop("selected", true);
    $("#selTEval8 option[value=01]").prop("selected", true);
    $("#selTEval9 option[value=01]").prop("selected", true);
    $("#selTEval10 option[value=01]").prop("selected", true);

    $("#btnTEvalSave").show();

    $.ajax({
        url: '/center/ajax/teacherEvaluationControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'teacherEvalLoad',
            center_idx: center_idx,
            months: months,
            teacher_no: teacher_no
        },
        success: function (result) {
            if (result.success) {
                $("#TevaluationList").html(result.data.tbl);
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function teacherEvalSelect(e) {
    var targetClass = $(e.target).parents('.tc');

    if ($(e.target).parents('.bg-light').length) {
        targetClass.removeClass('bg-light');
        $("#txtTEval1").removeAttr("readonly");
        $("#txtTEval2").removeAttr("readonly");
        $("#txtTEval3").removeAttr("readonly");
        $("#txtTEval4").removeAttr("readonly");
        $("#txtTEval5").removeAttr("readonly");
        $("#txtTEval6").removeAttr("readonly");
        $("#txtTEval7").removeAttr("readonly");
        $("#txtTEval8").removeAttr("readonly");
        $("#txtTEval9").removeAttr("readonly");
        $("#txtTEval10").removeAttr("readonly");

        $("#selTEval1").removeAttr("disabled");
        $("#selTEval2").removeAttr("disabled");
        $("#selTEval3").removeAttr("disabled");
        $("#selTEval4").removeAttr("disabled");
        $("#selTEval5").removeAttr("disabled");
        $("#selTEval6").removeAttr("disabled");
        $("#selTEval7").removeAttr("disabled");
        $("#selTEval8").removeAttr("disabled");
        $("#selTEval9").removeAttr("disabled");
        $("#selTEval10").removeAttr("disabled");

        $("#txtTEval1").val('');
        $("#txtTEval2").val('');
        $("#txtTEval3").val('');
        $("#txtTEval4").val('');
        $("#txtTEval5").val('');
        $("#txtTEval6").val('');
        $("#txtTEval7").val('');
        $("#txtTEval8").val('');
        $("#txtTEval9").val('');
        $("#txtTEval10").val('');

        $("#selTEval1 option[value=01]").prop("selected", true);
        $("#selTEval2 option[value=01]").prop("selected", true);
        $("#selTEval3 option[value=01]").prop("selected", true);
        $("#selTEval4 option[value=01]").prop("selected", true);
        $("#selTEval5 option[value=01]").prop("selected", true);
        $("#selTEval6 option[value=01]").prop("selected", true);
        $("#selTEval7 option[value=01]").prop("selected", true);
        $("#selTEval8 option[value=01]").prop("selected", true);
        $("#selTEval9 option[value=01]").prop("selected", true);
        $("#selTEval10 option[value=01]").prop("selected", true);

        $("#btnTEvalSave").show();
    } else {
        $('.tc').removeClass('bg-light');
        targetClass.addClass('bg-light');

        var teval_idx = targetClass.data('teval-idx');

        $.ajax({
            url: '/center/ajax/teacherEvaluationControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'teacherEvalSelect',
                teval_idx: teval_idx
            },
            success: function (result) {
                if (result.success) {
                    setFormdata(result.data);
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

}

function setFormdata(data) {
    $("#txtTEval1").attr("readonly", true);
    $("#txtTEval2").attr("readonly", true);
    $("#txtTEval3").attr("readonly", true);
    $("#txtTEval4").attr("readonly", true);
    $("#txtTEval5").attr("readonly", true);
    $("#txtTEval6").attr("readonly", true);
    $("#txtTEval7").attr("readonly", true);
    $("#txtTEval8").attr("readonly", true);
    $("#txtTEval9").attr("readonly", true);
    $("#txtTEval10").attr("readonly", true);

    $("#selTEval1").attr("disabled", true);
    $("#selTEval2").attr("disabled", true);
    $("#selTEval3").attr("disabled", true);
    $("#selTEval4").attr("disabled", true);
    $("#selTEval5").attr("disabled", true);
    $("#selTEval6").attr("disabled", true);
    $("#selTEval7").attr("disabled", true);
    $("#selTEval8").attr("disabled", true);
    $("#selTEval9").attr("disabled", true);
    $("#selTEval10").attr("disabled", true);

    $("#txtTEval1").val(data.teval_sub1);
    $("#txtTEval2").val(data.teval_sub2);
    $("#txtTEval3").val(data.teval_sub3);
    $("#txtTEval4").val(data.teval_sub4);
    $("#txtTEval5").val(data.teval_sub5);
    $("#txtTEval6").val(data.teval_sub6);
    $("#txtTEval7").val(data.teval_sub7);
    $("#txtTEval8").val(data.teval_sub8);
    $("#txtTEval9").val(data.teval_sub9);
    $("#txtTEval10").val(data.teval_sub10);

    $("#selTEval1 option[value=" + data.teval_score1 + "]").prop("selected", true);
    $("#selTEval2 option[value=" + data.teval_score2 + "]").prop("selected", true);
    $("#selTEval3 option[value=" + data.teval_score3 + "]").prop("selected", true);
    $("#selTEval4 option[value=" + data.teval_score4 + "]").prop("selected", true);
    $("#selTEval5 option[value=" + data.teval_score5 + "]").prop("selected", true);
    $("#selTEval6 option[value=" + data.teval_score6 + "]").prop("selected", true);
    $("#selTEval7 option[value=" + data.teval_score7 + "]").prop("selected", true);
    $("#selTEval8 option[value=" + data.teval_score8 + "]").prop("selected", true);
    $("#selTEval9 option[value=" + data.teval_score9 + "]").prop("selected", true);
    $("#selTEval10 option[value=" + data.teval_score10 + "]").prop("selected", true);

    $("#btnTEvalSave").hide();
}
$(document).ready(function () {
    getWeekEvalData();
    getSemiAnnualEvalData();
    $("#dtMonths1").change(function () {
        getWeekEvalData();
    });

    $("#evalDatepicker, #selDivide").change(function () {
        getSemiAnnualEvalData();
    });

    $("#evalDatepicker").datepicker({
        language: 'ko-Kr',
        format: 'yyyy'
    });

    $("#btnRequestSave").click(function () {
        var eval_idx = $("#eval_idx").val();
        var contents = $("#eval_c7_contents").val();
        if (!contents || !contents.trim()) {
            alert("요청 사항을 입력해주세요.");
            return false;
        }
        if (confirm("전달 후에는 요청 사항 수정이 불가능합니다.\n요청 사항을 전달하시겠습니까?")) {
            $.ajax({
                url: '/center/student/ajax/lessonEvaluationControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'parentRequestSave',
                    eval_idx: eval_idx,
                    franchise_idx: center_idx,
                    student_idx: student_idx,
                    contents: contents
                },
                success: function (result) {
                    if (result.success) {
                        alert("요청사항이 정상적으로 등록되었습니다.");
                        getSemiAnnualEvalData();
                        return false;
                    }
                },
                error: function (request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                    return false;
                }
            });
        }
    });
});

function getWeekEvalData() {
    var eval_month = $("#dtMonths1").val();

    if (!eval_month) {
        alert("날짜를 정확하게 선택해주세요.");
        return false;
    }

    $.ajax({
        url: '/center/student/ajax/lessonEvaluationControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'weekEvaluationSelect',
            franchise_idx: center_idx,
            student_idx: student_idx,
            eval_month: eval_month
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#WeekEvalTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [
                        {
                            data: 'lesson_date'
                        },
                        {
                            data: 'score_read'
                        },
                        {
                            data: 'score_debate1'
                        },
                        {
                            data: 'score_debate2'
                        },
                        {
                            data: 'score_debate3'
                        },
                        {
                            data: 'score_debate4'
                        },
                        {
                            data: 'score_write1'
                        },
                        {
                            data: 'score_write2'
                        },
                        {
                            data: 'score_write3'
                        },
                        {
                            data: 'score_write4'
                        }
                    ],
                    lengthChange: false,
                    info: false,
                    searching: false,
                    paging: false,
                    ordering: false,
                    displayLength: 10,
                    createdRow: function (row, data) {
                        $("td:eq(0)", row).addClass('text-center align-middle');
                        $("td:eq(1)", row).addClass('text-center align-middle');
                        $("td:eq(2)", row).addClass('text-center align-middle');
                        $("td:eq(3)", row).addClass('text-center align-middle');
                        $("td:eq(4)", row).addClass('text-center align-middle');
                        $("td:eq(5)", row).addClass('text-center align-middle');
                        $("th").addClass('text-center align-middle');
                    },
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
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

function getSemiAnnualEvalData() {
    var eval_year = $("#evalDatepicker").val();
    var eval_semiannual = $("#selDivide").val();

    $.ajax({
        url: '/center/student/ajax/lessonEvaluationControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'semiAnnualEvaluationSelect',
            franchise_idx: center_idx,
            student_idx: student_idx,
            eval_year: eval_year,
            eval_semiannual: eval_semiannual
        },
        success: function (result) {
            table2Clear();
            if (result.success) {
                if (result.data.length != 0) {
                    $("#eval_idx").val(result.data[0].eval_idx);
                    $("#eval_c1").text(result.data[0].eval_content);
                    $("#eval_c2").text(result.data[0].read_content);
                    $("#eval_c3").text(result.data[0].debate_content);
                    $("#eval_c4").text(result.data[0].write_content);
                    $("#eval_c5").text(result.data[0].lead_content);
                    $("#eval_c6").text(result.data[0].guide_content);
                    if (result.data[0].parent_request) {
                        $(".div_rs").hide();
                        $("#btnRequestSave").attr("disabled", true);
                        $("#eval_c7_contents").val(result.data[0].parent_request);
                    } else {
                        $(".div_rs").show();
                        $("#btnRequestSave").removeAttr("disabled");
                    }
                    $("#eval_c8").text(result.data[0].next_lead_content);
                    return false;
                }
            } else {
                $(".div_rs").hide();
                $("#btnRequestSave").attr("disabled", true);
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function table2Clear() {
    $("#eval_idx").val('');
    $("#eval_c1").text('');
    $("#eval_c2").text('');
    $("#eval_c3").text('');
    $("#eval_c4").text('');
    $("#eval_c5").text('');
    $("#eval_c6").text('');
    $("#eval_c7_contents").val('');
    $("#eval_c8").text('');
}
$(document).ready(function () {
    loadLessonList();

    $("#chkAttendance").click(function () {
        if ($("#chkAttendance").is(':checked') == true) {
            $("#lblAttendance").text("출석");
        } else {
            $("#lblAttendance").text("결석");
        }
    });

    $("input[name='chkAllScore']").change(function () {
        if ($("input[name='chkAllScore']:checked").val() == "5") {
            $(".sel").val('5').prop("selected", true);
            return false;
        } else if ($("input[name='chkAllScore']:checked").val() == "4") {
            $(".sel").val('4').prop("selected", true);
            return false;
        } else if ($("input[name='chkAllScore']:checked").val() == "3") {
            $(".sel").val('3').prop("selected", true);

            return false;
        } else if ($("input[name='chkAllScore']:checked").val() == "2") {
            $(".sel").val('2').prop("selected", true);

            return false;
        } else if ($("input[name='chkAllScore']:checked").val() == "1") {
            $(".sel").val('1').prop("selected", true);
            return false;
        } else {
            $(".sel").val('0').prop("selected", true);
            return false;
        }
    });

    $("#lessonListTable").on('page.dt', function () {
        $("#divAttendList").hide();
        $("#lessonStudentList").empty();

        $("#hiLessonStudentNo").val('');
        $("#btnLessonAttendSave").hide();
        $("#btnLessonScoreSave").hide();

        $("input:radio[name='chkAllScore']").prop('checked', false);
        $("#selRead option:first").prop('selected', true);
        $("#selDebate1 option:first").prop('selected', true);
        $("#selDebate2 option:first").prop('selected', true);
        $("#selDebate3 option:first").prop('selected', true);
        $("#selDebate4 option:first").prop('selected', true);
        $("#selWrite1 option:first").prop('selected', true);
        $("#selWrite2 option:first").prop('selected', true);
        $("#selWrite3 option:first").prop('selected', true);
        $("#selWrite4 option:first").prop('selected', true);
        $("#selLead option:first").prop('selected', true);

        $("#txtMemo").val('');
    });

    $("#lessonListData").on("click", ".tc", function (e) {
        var schedule_idx = $(e.target).parent('tr').data("schedule-idx");
        $("#hiLessonStudentNo").val('');
        $("#btnLessonAttendSave").hide();
        $("#btnLessonScoreSave").hide();

        $("input:radio[name='chkAllScore']").prop('checked', false);
        $("#selRead option:first").prop('selected', true);
        $("#selDebate1 option:first").prop('selected', true);
        $("#selDebate2 option:first").prop('selected', true);
        $("#selDebate3 option:first").prop('selected', true);
        $("#selDebate4 option:first").prop('selected', true);
        $("#selWrite1 option:first").prop('selected', true);
        $("#selWrite2 option:first").prop('selected', true);
        $("#selWrite3 option:first").prop('selected', true);
        $("#selWrite4 option:first").prop('selected', true);
        $("#selLead option:first").prop('selected', true);

        $("#txtMemo").val('');
        loadLessonStudentList(schedule_idx);
    });

    $("#lessonStudentList").on("click", ".sl", function (e) {
        var targetClass = $(e.target).parents('.sl');

        if ($(e.target).parent('.bg-light').length) {
            targetClass.removeClass('bg-light');
            $("#hiLessonStudentNo").val('');
            $("#btnLessonAttendSave").hide();
            $("#btnLessonScoreSave").hide();

            $("input:radio[name='chkAllScore']").prop('checked', false);
            $("#selRead option:first").prop('selected', true);
            $("#selDebate1 option:first").prop('selected', true);
            $("#selDebate2 option:first").prop('selected', true);
            $("#selDebate3 option:first").prop('selected', true);
            $("#selDebate4 option:first").prop('selected', true);
            $("#selWrite1 option:first").prop('selected', true);
            $("#selWrite2 option:first").prop('selected', true);
            $("#selWrite3 option:first").prop('selected', true);
            $("#selWrite4 option:first").prop('selected', true);
            $("#selLead option:first").prop('selected', true);

            $("#txtMemo").val('');
        } else {
            $('.sl').removeClass('bg-light');
            targetClass.addClass('bg-light');

            var student_idx = targetClass.data('student-idx');
            var score_chk = targetClass.data('score-check');
            var attend_chk = targetClass.data('attend-check');

            $("#hiLessonStudentNo").val(student_idx);
            $("#btnLessonAttendSave").show();

            if (attend_chk == 'N') {
                $("#chkAttendance").prop("checked", false);
                $("#lblAttendance").text("결석");
            } else {
                $("#chkAttendance").prop("checked", true);
                $("#lblAttendance").text("출석");
            }

            if (score_chk == 'Y') {
                $("#btnLessonScoreSave").hide();
            } else {
                $("#btnLessonScoreSave").show();
            }

            loadLessonScore();
        }
    });

    //일괄 출석
    $("#btnAllAttend").click(function () {
        var schedule_idx = $("#hiScheduleNo").val();

        if (confirm("기존 등록된 출결데이터가 삭제된 후 등록됩니다.\n진행하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/lessonListControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',

                data: {
                    action: 'lessonAllAttend',
                    schedule_idx: schedule_idx,
                    attend_chk: 'Y',
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $('.sl').removeClass('bg-light');
                        $("#hiLessonStudentNo").val('');
                        $("#btnLessonAttendSave").hide();
                        $("#btnLessonScoreSave").hide();
                        loadLessonStudentList(schedule_idx);
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

    //일괄 결석
    $("#btnAllAbsent").click(function () {
        var schedule_idx = $("#hiScheduleNo").val();

        if (confirm("기존 등록된 출결데이터가 삭제된 후 등록됩니다.\n진행하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/lessonListControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',

                data: {
                    action: 'lessonAllAttend',
                    schedule_idx: schedule_idx,
                    attend_chk: 'N',
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $('.sl').removeClass('bg-light');
                        $("#hiLessonStudentNo").val('');
                        $("#btnLessonAttendSave").hide();
                        $("#btnLessonScoreSave").hide();
                        loadLessonStudentList(schedule_idx);
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

    //출결 저장
    $("#btnLessonAttendSave").click(function () {
        var schedule_idx = $("#hiScheduleNo").val();
        var student_idx = $("#hiLessonStudentNo").val();

        var attend_chk = $("#chkAttendance").is(":checked") ? 'Y' : 'N';

        if (!schedule_idx) {
            alert('수업을 선택하세요');
            return false;
        }

        if (!student_idx) {
            alert('학생을 선택하세요');
            return false;
        }

        $.ajax({
            url: '/center/ajax/lessonListControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',

            data: {
                action: 'lessonAttendCheck',
                schedule_idx: schedule_idx,
                student_idx: student_idx,
                attend_chk: attend_chk,
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $('.sl').removeClass('bg-light');
                    $("#hiLessonStudentNo").val('');
                    $("#btnLessonAttendSave").hide();
                    $("#btnLessonScoreSave").hide();
                    loadLessonStudentList(schedule_idx);
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

    //점수 저장
    $("#btnLessonScoreSave").click(function () {
        var schedule_idx = $("#hiScheduleNo").val();
        var student_idx = $("#hiLessonStudentNo").val();

        var selRead = $("#selRead").val();
        var selDebate1 = $("#selDebate1").val();
        var selDebate2 = $("#selDebate2").val();
        var selDebate3 = $("#selDebate3").val();
        var selDebate4 = $("#selDebate4").val();
        var selWrite1 = $("#selWrite1").val();
        var selWrite2 = $("#selWrite2").val();
        var selWrite3 = $("#selWrite3").val();
        var selWrite4 = $("#selWrite4").val();
        var selLead = $("#selLead").val();
        var txtMemo = $("#txtMemo").val();

        if (!schedule_idx) {
            alert('수업을 선택하세요');
            return false;
        }

        if (!student_idx) {
            alert('학생을 선택하세요');
            return false;
        }

        if (!selRead || !selDebate1 || !selDebate2 || !selDebate3 || !selDebate4 || !selWrite1 || !selWrite2 || !selWrite3 || !selWrite4 || !selLead) {
            alert('점수를 선택하세요');
            return false;
        }

        if (confirm("정보 등록시 수정이 불가능합니다.\n등록하사겠습니까?")) {
            $.ajax({
                url: '/center/ajax/lessonListControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',

                data: {
                    action: 'scoreInsert',
                    center_idx: center_idx,
                    schedule_idx: schedule_idx,
                    student_idx: student_idx,
                    selRead: selRead,
                    selDebate1: selDebate1,
                    selDebate2: selDebate2,
                    selDebate3: selDebate3,
                    selDebate4: selDebate4,
                    selWrite1: selWrite1,
                    selWrite2: selWrite2,
                    selWrite3: selWrite3,
                    selWrite4: selWrite4,
                    selLead: selLead,
                    txtMemo: txtMemo,
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $('.sl').removeClass('bg-light');
                        $("#hiLessonStudentNo").val('');
                        $("#btnLessonAttendSave").hide();
                        $("#btnLessonScoreSave").hide();
                        loadLessonStudentList(schedule_idx);
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

function loadLessonList() {
    var now_date = $("#lessonDate").val();
    var teacher_no = '';

    $("#divAttendList").hide();
    $("#lessonStudentList").empty();
    $("#activityPaperList").empty();

    $("#hiScheduleNo").val('');
    $("#hiLessonStudentNo").val('');
    $("#btnLessonScoreSave").hide();
    $("#btnLessonAttendSave").hide();

    if (userInfo.user_id == 'admin' || userInfo.user_director == 'Y') {
        teacher_no = $("#sellessonListTeacher").val();
    } else {
        teacher_no = userInfo.user_no;
    }

    $.ajax({
        url: '/center/ajax/lessonListControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',

        data: {
            action: 'loadLessonList',
            center_idx: center_idx,
            teacher_no: teacher_no,
            now_date: now_date
        },
        success: function (result) {
            if (result.success && result.data) {
                $("#lessonListTable").DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [
                        {
                            data: 'lesson_type'
                        },
                        {
                            data: 'lesson_date'
                        },
                        {
                            data: 'lesson_time'
                        },
                        {
                            data: 'teacher_name'
                        },
                        {
                            data: 'student_count'
                        },
                        {
                            data: 'lesson_book_name'
                        }
                    ],
                    lengthChange: false,
                    info: false,
                    displayLength: 10,
                    createdRow: function (row, data) {
                        $(row).addClass('tc text-center align-middle');
                        $("th").addClass('text-center align-middle');
                        $(row).attr('data-schedule-idx', data.schedule_idx);

                        if (data.now_flag == 'Y') {
                            $(row).addClass('bg-warning');
                        }
                    },
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
            } else {
                $("#lessonListTable").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function loadLessonStudentList(schedule_idx) {
    $.ajax({
        url: '/center/ajax/lessonListControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',

        data: {
            action: 'loadLessonStudentList',
            center_idx: center_idx,
            schedule_idx: schedule_idx
        },
        success: function (result) {
            if (result.success) {
                if (result.data.divShow == 'Y') {
                    $("#divAttendList").show();
                } else {
                    $("#divAttendList").hide();
                }
                $("#lessonStudentList").html(result.data.tbl);
                $("#activityPaperList").html(result.data.activityList);
                $("#hiScheduleNo").val(schedule_idx);
            } else {
                $("#divAttendList").hide();
                $("#lessonStudentList").empty();
                $("#activityPaperList").empty();
                $("#hiScheduleNo").val('');
                $("#hiLessonStudentNo").val('');
                $("#btnLessonScoreSave").hide();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function loadLessonScore() {
    var schedule_idx = $("#hiScheduleNo").val();
    var student_idx = $("#hiLessonStudentNo").val();

    $.ajax({
        url: '/center/ajax/lessonListControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',

        data: {
            action: 'loadLessonScore',
            center_idx: center_idx,
            schedule_idx: schedule_idx,
            student_idx: student_idx,
        },
        success: function (result) {
            if (result.success && result.data) {
                $("input:radio[name='chkAllScore']").prop('checked', false);
                $("#selRead option[value=" + result.data.score_read + "]").prop("selected", true);
                $("#selDebate1 option[value=" + result.data.score_debate1 + "]").prop("selected", true);
                $("#selDebate2 option[value=" + result.data.score_debate2 + "]").prop("selected", true);
                $("#selDebate3 option[value=" + result.data.score_debate3 + "]").prop("selected", true);
                $("#selDebate4 option[value=" + result.data.score_debate4 + "]").prop("selected", true);
                $("#selWrite1 option[value=" + result.data.score_write1 + "]").prop("selected", true);
                $("#selWrite2 option[value=" + result.data.score_write2 + "]").prop("selected", true);
                $("#selWrite3 option[value=" + result.data.score_write3 + "]").prop("selected", true);
                $("#selWrite4 option[value=" + result.data.score_write4 + "]").prop("selected", true);
                $("#selLead option[value=" + result.data.score_lead + "]").prop("selected", true);

                $("#txtMemo").val(result.data.score_memo);
                return false;
            } else {
                $("input:radio[name='chkAllScore']").prop('checked', false);
                $("#selRead option:first").prop('selected', true);
                $("#selDebate1 option:first").prop('selected', true);
                $("#selDebate2 option:first").prop('selected', true);
                $("#selDebate3 option:first").prop('selected', true);
                $("#selDebate4 option:first").prop('selected', true);
                $("#selWrite1 option:first").prop('selected', true);
                $("#selWrite2 option:first").prop('selected', true);
                $("#selWrite3 option:first").prop('selected', true);
                $("#selWrite4 option:first").prop('selected', true);
                $("#selLead option:first").prop('selected', true);

                $("#txtMemo").val('');
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}
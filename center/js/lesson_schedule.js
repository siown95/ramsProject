var hiViewType = 'month';
var calendar;
var standardMonth;
var range = $("#calendar_range");
var options = {
    defaultView: 'month',
    usageStatistics: false,
    useFormPopup: false,
    useCreationPopup: false,
    useDetailPopup: true,
    isReadOnly: true,
    month: {
        startDayOfWeek: 1,
        //dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
        dayNames: ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'],
        narrowWeekend: false,
        taskView: false,
        isAlways6Weeks: false,
    },
    week: {
        startDayOfWeek: 1,
        //dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
        dayNames: ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'],
        narrowWeekend: false,
        taskView: false
    },
    gridSelection: {
        enableDblClick: false,
        enableClick: true,
    },
    theme: {
        week: {
            today: {
                color: 'rgba(35, 199, 71, 1)',
            },
        },
        common: {
            saturday: {
                color: 'blue',
            },
            today: {
                color: 'white',
            },
        },
    },
    template: {
        monthDayName(model) {
            return `<span class="fw-bold">${model.label}</span>`;
        },
    },
};

$(document).ready(function () {
    let date = new Date();
    let offset = date.getTimezoneOffset() * 60000; //ms단위라 60000곱해줌
    let dateOffset = new Date(date.getTime() - offset);
    var now_date = dateOffset.toISOString().slice(0, 10);

    standardMonth = dateOffset.toISOString().slice(0, 7);
    loadCurriculumnBook();
    loadLessonSchedule();
    loadStudentList();
    getHolidayData();

    calendar = new tui.Calendar('#calendars', options);
    calendar.setOptions({
        month: {
            startDayOfWeek: 1,
            dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
            narrowWeekend: false,
            taskView: false,
            isAlways6Weeks: false,
        },
        week: {
            startDayOfWeek: 1,
            dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
            narrowWeekend: false,
            taskView: false
        },
    });
    displayRenderRange(hiViewType);
    getCalendarData();

    //달
    $("#btnCVm").click(function () {
        calendar.changeView('month');
        hiViewType = 'month';
        displayRenderRange(hiViewType);
        loadCurriculumnBook();
        loadLessonSchedule();
        getCalendarData();
    });

    //주
    $("#btnCVw").click(function () {
        calendar.changeView('week');
        hiViewType = 'week';
        displayRenderRange(hiViewType);
        loadCurriculumnBook();
        loadLessonSchedule();
        getCalendarData();
    });

    //일
    $("#btnCVd").click(function () {
        calendar.changeView('day');
        hiViewType = 'day';
        displayRenderRange(hiViewType);
        loadCurriculumnBook();
        loadLessonSchedule();
        getCalendarData();
    });

    //이전
    $("#btnCPrev").click(function () {
        calendar.prev();
        $("#dtsd").val(moment(calendar.getDate().toDate()).format('YYYY-MM-DD'));
        displayRenderRange(hiViewType);
        loadCurriculumnBook();
        loadLessonSchedule();
        getCalendarData();
    });

    //오늘
    $("#btnCToday").click(function () {
        calendar.today();
        $('#dtsd').val(now_date);
        displayRenderRange(hiViewType);
        loadCurriculumnBook();
        loadLessonSchedule();
        getCalendarData();
    });

    //다음
    $("#btnCNext").click(function () {
        calendar.next();
        $("#dtsd").val(moment(calendar.getDate().toDate()).format('YYYY-MM-DD'));
        displayRenderRange(hiViewType);
        loadCurriculumnBook();
        loadLessonSchedule();
        getCalendarData();
    });

    $("#btnAddSchedule").click(function () {
        var onoff_yn = ($('#chkLOnOff').is(':checked')) ? 'Y' : ''; //온오프라인
        var freehand_yn = ($('#chkLFree').is(':checked')) ? 'Y' : ''; //재량수업
        var supple_yn = ($('#chkLmChk').is(':checked')) ? 'Y' : ''; //보강여부
        var supple_type = ''; //보강종류
        var selLessonType = $("#selLessonType").val(); //수업종류
        var selLessonTeacher = $("#selLessonTeacher").val(); //담당선생님
        var selLessonRoom = $("#selLessonRoom").val(); //강의실
        var selLessonGrade = $("#selLessonGrade").val(); //학년
        var selReceiptItem = $("#selReceiptItem").val(); //특강종류
        var dtsd = $("#dtsd").val(); //수업일자
        var dtsft = $("#dtsft").val(); //수업시작시간
        var dtstt = $("#dtstt").val(); //수업종료시간
        var selLessonBook = $("#selLessonBook").val(); //수업도서
        var chkRepeat = ($('#chkRepeat').is(':checked')) ? 'Y' : ''; //월말반복

        if (!selLessonType) {
            alert('수업 종류를 선택하세요.');
            return false;
        }

        if (!selLessonTeacher) {
            alert('담당 선생님을 선택하세요');
            return false;
        }

        if (!selLessonRoom) {
            alert('강의실을 선택하세요');
            return false;
        }

        if (selLessonType == '01' && !selLessonGrade) {
            alert("학년을 지정하세요");
            return false;
        }

        if (selLessonType == '02' && !selReceiptItem) {
            alert("특강종류를 지정하세요");
            return false;
        }

        if (!dtsd) {
            alert('일자를 지정하세요');
            return false;
        }

        if (dtsft > dtstt) {
            alert('수업 시간을 확인하세요');
            return false;
        }

        if ($('#chkLmChk').is(':checked')) {
            supple_type = ($('#chkLm').is(':checked')) ? '1' : '2';
        }

        $.ajax({
            url: '/center/ajax/lessonScheduleControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'lessonScheduleInsert',
                franchise_idx: center_idx,
                onoff_yn: onoff_yn,
                freehand_yn: freehand_yn,
                supple_yn: supple_yn,
                supple_type: supple_type,
                selLessonType: selLessonType,
                selLessonTeacher: selLessonTeacher,
                selLessonRoom: selLessonRoom,
                selLessonGrade: selLessonGrade,
                selReceiptItem: selReceiptItem,
                dtsd: dtsd,
                dtsft: dtsft,
                dtstt: dtstt,
                selLessonBook: selLessonBook,
                chkRepeat: chkRepeat
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    var nd = new Date();
                    $("#selLessonType option:first").prop("selected", true);
                    $("#selLessonTeacher option:first").prop("selected", true);
                    $("#selLessonRoom option:first").prop("selected", true);
                    $("#selLessonBook option:first").prop('selected', true);
                    $("#chkLmChk").prop("checked", false);
                    $("#div_Lm").hide();
                    $('#chkLm').prop("checked", false);
                    $('#lblLm').text('개인보강');
                    $("#lessonScheduleList").find('tr').removeClass('bg-light');
                    $("#targetScheduleIdx").val('');
                    $("#btnAddSchedule").show();
                    $("#btnUpdateSchedule").hide();
                    $("#btnLStudentAdd").hide();
                    $("#LessonStudentList").empty();
                    $("#dtsd").val(now_date);
                    $("#dtsft").val(nd.getHours() + ':00');
                    $("#dtstt").val((nd.getHours() + 1) + ':00');
                    calendar.clear();
                    getCalendarData();
                    loadCurriculumnBook();
                    loadLessonSchedule();
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

    $("#btnUpdateSchedule").click(function () {
        var onoff_yn = ($('#chkLOnOff').is(':checked')) ? 'Y' : ''; //온오프라인
        var freehand_yn = ($('#chkLFree').is(':checked')) ? 'Y' : ''; //재량수업
        var supple_yn = ($('#chkLmChk').is(':checked')) ? 'Y' : ''; //보강여부
        var supple_type = ''; //보강종류
        var selLessonType = $("#selLessonType").val(); //수업종류
        var selLessonTeacher = $("#selLessonTeacher").val(); //담당선생님
        var selLessonRoom = $("#selLessonRoom").val(); //강의실
        var selLessonGrade = $("#selLessonGrade").val(); //학년
        var selReceiptItem = $("#selReceiptItem").val(); //특강종류
        var dtsd = $("#dtsd").val(); //수업일자
        var dtsft = $("#dtsft").val(); //수업시작시간
        var dtstt = $("#dtstt").val(); //수업종료시간
        var selLessonBook = $("#selLessonBook").val(); //수업도서
        var schedule_idx = $("#targetScheduleIdx").val();

        if (!selLessonType) {
            alert('수업 종류를 선택하세요.');
            return false;
        }

        if (!selLessonTeacher) {
            alert('담당 선생님을 선택하세요');
            return false;
        }

        if (!selLessonRoom) {
            alert('강의실을 선택하세요');
            return false;
        }

        if (selLessonType == '01' && !selLessonGrade) {
            alert("학년을 지정하세요");
            return false;
        }

        if (selLessonType == '02' && !selReceiptItem) {
            alert("특강종류를 지정하세요");
            return false;
        }

        if (!dtsd) {
            alert('일자를 지정하세요');
            return false;
        }

        if (dtsft > dtstt) {
            alert('수업 시간을 확인하세요');
            return false;
        }

        if ($('#chkLmChk').is(':checked')) {
            supple_type = ($('#chkLm').is(':checked')) ? '1' : '2';
        }

        $.ajax({
            url: '/center/ajax/lessonScheduleControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'lessonScheduleUpdate',
                schedule_idx: schedule_idx,
                onoff_yn: onoff_yn,
                freehand_yn: freehand_yn,
                supple_yn: supple_yn,
                supple_type: supple_type,
                selLessonType: selLessonType,
                selLessonTeacher: selLessonTeacher,
                selLessonRoom: selLessonRoom,
                selLessonGrade: selLessonGrade,
                selReceiptItem: selReceiptItem,
                dtsd: dtsd,
                dtsft: dtsft,
                dtstt: dtstt,
                selLessonBook: selLessonBook
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    var nd = new Date();
                    $("#selLessonType option:first").prop("selected", true);
                    $("#selLessonTeacher option:first").prop("selected", true);
                    $("#selLessonRoom option:first").prop("selected", true);
                    $("#selLessonBook option:first").prop('selected', true);
                    $("#selLessonGrade option:first").prop('selected', true);
                    $("#chkLmChk").prop("checked", false);
                    $("#div_Lm").hide();
                    $('#chkLm').prop("checked", false);
                    $('#lblLm').text('개인보강');
                    $("#lessonScheduleList").find('tr').removeClass('bg-light');
                    $("#targetScheduleIdx").val('');
                    $("#btnAddSchedule").show();
                    $("#btnUpdateSchedule").hide();
                    $("#btnLStudentAdd").hide();
                    $("#LessonStudentList").empty();
                    $("#dtsd").val(now_date);
                    $("#dtsft").val(nd.getHours() + ':00');
                    $("#dtstt").val((nd.getHours() + 1) + ':00');
                    calendar.clear();
                    getCalendarData();
                    loadCurriculumnBook();
                    loadLessonSchedule();
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

    $("#lessonScheduleList").on("click", '.schedule_del', function (e) {
        var schedule_idx = $(e.target).parents('tr').data('schedule-idx');

        if (confirm("수업정보를 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/lessonScheduleControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'lessonScheduleDelete',
                    schedule_idx: schedule_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        calendar.clear();
                        loadCurriculumnBook();
                        loadLessonSchedule();
                        loadStudentList();
                        getCalendarData();
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

    $("#lessonScheduleTable").on('page.dt', function () {
        $("#selLessonType option:first").prop("selected", true);
        $("#selLessonTeacher option:first").prop("selected", true);
        $("#selLessonRoom option:first").prop("selected", true);
        $("#selLessonBook option:first").prop('selected', true);
        loadCurriculumnBook();
        $("#chkLmChk").prop("checked", false);
        $("#div_Lm").hide();
        $('#chkLm').prop("checked", false);
        $('#lblLm').text('개인보강');
        $("#lessonScheduleList").find('tr').removeClass('bg-light');
        $("#targetScheduleIdx").val('');
        $("#btnAddSchedule").show();
        $("#btnUpdateSchedule").hide();
        $("#btnLStudentAdd").hide();
        $("#LessonStudentList").empty();
    });

    $("#lessonScheduleList").on("click", '.tc', function (e) {
        if ($(e.target).parents('.bg-light').length) {
            $(e.target).parents('tr').removeClass('bg-light');
            $("#selLessonType option:first").prop("selected", true);
            $("#selLessonTeacher option:first").prop("selected", true);
            $("#selLessonRoom option:first").prop("selected", true);
            $("#selLessonBook option:first").prop('selected', true);
            $("#selLessonGrade option:first").prop("selected", true);
            loadCurriculumnBook();
            $("#chkLmChk").prop("checked", false);
            $("#div_Lm").hide();
            $('#chkLm').prop("checked", false);
            $('#lblLm').text('개인보강');
            $("#targetScheduleIdx").val('');

            $("#btnAddSchedule").show();
            $("#btnUpdateSchedule").hide();
            $("#btnLStudentAdd").hide();
            $("#receipt_div").hide();
            $("#LessonStudentList").empty();
        } else {
            $('tr').removeClass('bg-light');
            $(e.target).parents('tr').addClass('bg-light');

            var schedule_idx = $(e.target).parents('tr').data('schedule-idx');
            $("#btnAddSchedule").hide();
            $("#btnUpdateSchedule").show();
            $("#targetScheduleIdx").val(schedule_idx);
            $("#btnLStudentAdd").show();

            lessonScheduleSelect(schedule_idx);
            loadScheduleStudent(schedule_idx);
        }
    });

    $("#btnLStudentAdd").click(function () {
        var schedule_idx = $("#targetScheduleIdx").val();
        var student_no = $("#selScheduleStudent").val();

        if (!student_no) {
            alert("학생을 선택해주세요");
            return false;
        }

        $.ajax({
            url: '/center/ajax/lessonScheduleControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'scheduleStudentInsert',
                schedule_idx: schedule_idx,
                center_idx: center_idx,
                student_no: student_no
            },
            success: function (result) {
                if (result.success) {
                    calendar.clear();
                    loadStudentList();
                    getCalendarData();
                    loadScheduleStudent(schedule_idx);
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

    $("#LessonStudentList").on("click", ".student_del", function (e) {
        var schedule_idx = $("#targetScheduleIdx").val();
        var class_idx = $(e.target).parents('tr').data('class-idx');

        if (confirm("수강 학생을 제외하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/lessonScheduleControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'scheduleStudentDelete',
                    class_idx: class_idx
                },
                success: function (result) {
                    if (result.success) {
                        calendar.clear();
                        loadScheduleStudent(schedule_idx);
                        getCalendarData();
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

    $("#standardLessonMonth, #selLessonWeek").change(function () {
        var standardMonth = $("#standardLessonMonth").val();
        var selLessonWeek = $("#selLessonWeek").val();

        var targetLextMonth = moment(standardMonth).add(Number(selLessonWeek * 7), 'd').format('YYYY-MM-DD');
        $("#targetLessonMonth").val(targetLextMonth);
    });


    $("#btnLessonMove").click(function () {
        var standardMonth = $("#standardLessonMonth").val();
        var targetMonth = $("#targetLessonMonth").val();
        var selLessonWeek = $("#selLessonWeek").val();

        if (!standardMonth) {
            alert('시작일을 선택하세요');
            return false;
        }

        if (!targetMonth) {
            alert('종료일을 선택하세요');
            return false;
        }

        if (standardMonth >= targetMonth) {
            alert('날짜를 정확히 지정하세요');
            return false;
        }

        if (confirm(standardMonth + "로부터 " + targetMonth + "까지 \n" + selLessonWeek + " 주차의 데이터를 이월하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/lessonScheduleControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'lessonScheduleMove',
                    center_idx: center_idx,
                    standardMonth: standardMonth,
                    targetMonth: targetMonth,
                    selLessonWeek: selLessonWeek
                },
                success: function (result) {
                    if (result.success) {
                        $("#lessonMoveModal").modal('hide');
                        calendar.clear();
                        getCalendarData();
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

    $("#startLessonWeek1").change(function () {
        var startweek1 = $("#startLessonWeek1").val();
        var startweek2 = $("#startLessonWeek2").val();
        var endweek2 = $("#endLessonWeek2").val();

        if (!startweek1) {
            alert("날짜를 지정해주세요.");
            $("#startLessonWeek1").focus();
            return false;
        }
        $("#endLessonWeek1").val(moment(startweek1).add(Number(6), 'd').format('YYYY-MM-DD'));
        var endweek1 = $("#endLessonWeek1").val();

        if ((startweek1 >= startweek2 && startweek1 <= endweek2) || (endweek1 >= startweek2 && endweek1 <= endweek2)) {
            alert("동일한 주의 수업정보를 이월할 수 없습니다.");
            $("#startLessonWeek1").focus();
            return false;
        }
    });

    $("#startLessonWeek2").change(function () {
        var startweek1 = $("#startLessonWeek1").val();
        var endweek1 = $("#endLessonWeek1").val();
        var startweek2 = $("#startLessonWeek2").val();

        if (!startweek2) {
            alert("날짜를 지정해주세요.");
            $("#startLessonWeek2").focus();
            return false;
        }
        $("#endLessonWeek2").val(moment(startweek2).add(Number(6), 'd').format('YYYY-MM-DD'));
        var endweek2 = $("#endLessonWeek2").val();

        if ((startweek2 >= startweek1 && startweek2 <= endweek1) || (endweek2 >= startweek1 && endweek2 <= endweek1)) {
            alert("동일한 주의 수업정보를 이월할 수 없습니다.");
            $("#startLessonWeek2").focus();
            return false;
        }
    });

    $("#btnLessonMoveWeek").click(function () {
        var startweek1 = $("#startLessonWeek1").val();
        var endweek1 = $("#endLessonWeek1").val();
        var startweek2 = $("#startLessonWeek2").val();
        var endweek2 = $("#endLessonWeek2").val();
        if (!startweek1 || !startweek2 || !endweek1 || !endweek2) {
            alert("날짜를 지정해주세요.");
            return false;
        }
        if ((startweek1 >= startweek2 && startweek1 <= endweek2) || (endweek1 >= startweek2 && endweek1 <= endweek2)) {
            alert("동일한 주의 수업정보를 이월할 수 없습니다.");
            $("#startLessonWeek1").focus();
            return false;
        }
        if ((startweek2 >= startweek1 && startweek2 <= endweek1) || (endweek2 >= startweek1 && endweek2 <= endweek1)) {
            alert("동일한 주의 수업정보를 이월할 수 없습니다.");
            $("#startLessonWeek2").focus();
            return false;
        }
        if (confirm(startweek1 + "~" + endweek1 + "로부터 " + startweek2 + "~" + endweek2 + "까지 \n특정주 주차의 데이터를 이월하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/lessonScheduleControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'lessonScheduleMoveWeek',
                    center_idx: center_idx,
                    startweek1: startweek1,
                    endweek1: endweek1,
                    startweek2: startweek2,
                    endweek2: endweek2
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#lessonMoveWeekModal").modal('hide');
                        calendar.clear();
                        getCalendarData();
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

    $("#chkLOnOff").click(function () {
        if ($('#chkLOnOff').is(':checked') == true) {
            $('#lblLOnOff').text('온라인');
        } else {
            $('#lblLOnOff').text('오프라인');
        }
    });

    $("#chkLmChk").click(function () {
        if ($('#chkLmChk').is(':checked') == true) {
            $("#div_Lm").show();
        } else {
            $("#div_Lm").hide();
        }
    });

    $("#chkLm").click(function () {
        if ($('#chkLm').is(':checked') == true) {
            $('#lblLm').text('정규보강');
        } else {
            $('#lblLm').text('개인보강');
        }
    });

    $(".chktv").click(function () {
        var ci = $(this).val();

        if ($(this).is(":checked") == true) {
            calendar.setCalendarVisibility(ci, true);
        } else {
            calendar.setCalendarVisibility(ci, false);
        }

    });

    $("#btnHolidaySave").click(function () {
        var holiday = $("#dtHolidayDate").val();
        var memo = $("#txtHolidayMemo").val();

        if (!holiday || !holiday.trim()) {
            alert("휴일의 날짜를 지정해주세요.");
            return false;
        }

        $.ajax({
            url: '/center/ajax/holidayControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'holidayInsert',
                center_idx: center_idx,
                user_idx: user_idx,
                holiday_date: holiday,
                memo: memo
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    getHolidayData();
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

    $("#HolidayInfoTable > tbody").on("click", ".btnholidaydel", function () {
        var holiday_idx = $(this).parents("tr").data("holiday-idx");
        if (!holiday_idx) {
            alert("잘못된 접근입니다.");
            return false;
        }
        $.ajax({
            url: '/center/ajax/holidayControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'holidayDelete',
                holiday_idx: holiday_idx,
                center_idx: center_idx,
                user_idx: user_idx
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    getHolidayData();
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

    $("#selLessonType, #selLessonGrade").change(function () {
        var lesson_type = $("#selLessonType").val();
        var lesson_grade = $("#selLessonGrade").val();

        getReceiptItem(lesson_type, lesson_grade);
    });
});

function getHolidayData() {
    var years = $("#dtHolidayYear").val();

    $.ajax({
        url: '/center/ajax/holidayControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'holidayLoad',
            center_idx: center_idx,
            years: years
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#HolidayInfoTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'holiday_date'
                    },
                    {
                        data: 'holiday_memo'
                    },
                    {
                        data: 'btnDelete',
                        orderable: false
                    }],
                    order: [[0, 'desc']],
                    lengthChange: false,
                    info: false,
                    displayLength: 5,
                    createdRow: function (row, data) {
                        $("td:eq(0)", row).addClass('text-center align-middle');
                        $("td:eq(1)", row).addClass('text-start align-middle');
                        $("td:eq(2)", row).addClass('text-center align-middle');
                        $("th").addClass('text-center align-middle');
                        $(row).attr('data-holiday-idx', data.holiday_idx);
                    },
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
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

function getCalendarData() {
    $("#selLessonType option:first").prop("selected", true);
    $("#selLessonTeacher option:first").prop("selected", true);
    $("#selLessonRoom option:first").prop("selected", true);
    $("#selLessonBook option:first").prop('selected', true);
    $("#selLessonGrade option:first").prop("selected", true);
    $("#chkLmChk").prop("checked", false);
    $("#div_Lm").hide();
    $('#chkLm').prop("checked", false);
    $('#lblLm').text('개인보강');
    $("#targetScheduleIdx").val('');
    $("#btnAddSchedule").show();
    $("#btnUpdateSchedule").hide();
    $("#targetScheduleIdx").val('');
    $("#btnLStudentAdd").hide();
    $("#LessonStudentList").empty();
    $("#receipt_div").hide();
    calendar.clear();

    $.ajax({
        url: '/center/ajax/lessonScheduleControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'getCalendarData',
            franchise_idx: center_idx,
            standardMonth: standardMonth
        },
        success: function (result) {
            if (result.success) {
                for (let i = 0; i < result.data.data.length; i++) {
                    drawCalendar(result.data.data[i]);
                }
                return false;
            } else {
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function drawCalendar(calendarData) {
    displayRenderRange(hiViewType);
    calendar.createEvents([calendarData]);
}

function loadLessonSchedule() {
    $.ajax({
        url: '/center/ajax/lessonScheduleControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        cache: false,
        data: {
            action: 'loadLessonSchedule',
            center_idx: center_idx,
            standardMonth: standardMonth
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#lessonScheduleTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'lesson_type'
                    },
                    {
                        data: 'lesson_grade'
                    },
                    {
                        data: 'lesson_date'
                    },
                    {
                        data: 'teacher_name'
                    },
                    {
                        data: 'lesson_room'
                    },
                    {
                        data: 'book_name'
                    },
                    {
                        data: 'btnDelete'
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    displayLength: 5,
                    createdRow: function (row, data) {
                        $("td:eq(0)", row).addClass('tc text-center align-middle');
                        $("td:eq(1)", row).addClass('tc text-center align-middle');
                        $("td:eq(2)", row).addClass('tc text-center align-middle');
                        $("td:eq(3)", row).addClass('tc text-center align-middle');
                        $("td:eq(4)", row).addClass('tc text-center align-middle');
                        $("td:eq(5)", row).addClass('tc text-center align-middle');
                        $("td:eq(6)", row).addClass('tc text-start align-middle');
                        $("td:eq(7)", row).addClass('text-center align-middle');
                        $("th").addClass('text-center align-middle');
                        $(row).attr('data-schedule-idx', data.schedule_idx);
                    },
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function loadStudentList() {
    var selScheduleGrade = $("#selScheduleGrade").val();

    $.ajax({
        url: '/center/ajax/lessonScheduleControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'loadStudentList',
            franchise_idx: center_idx,
            selScheduleGrade: selScheduleGrade
        },
        success: function (result) {
            if (result.success) {
                $("#selScheduleStudent").html(result.data.data);
                return false;
            } else {
                $("#selScheduleStudent").html('<option value="">선택</option>');
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function displayRenderRange(type) {
    range.text(getNavbarRange(calendar.getDateRangeStart(), calendar.getDateRangeEnd(), type));
}

function getNavbarRange(tzStart, tzEnd, viewType) {
    var start = tzStart.toDate();
    var end = tzEnd.toDate();
    var middle;
    if (viewType === 'month') {
        middle = new Date(start.getTime() + (end.getTime() - start.getTime()) / 2);
        standardMonth = moment(middle).format('YYYY-MM');
        return moment(middle).format('YYYY-MM');
    }
    if (viewType === 'day') {
        standardMonth = moment(start).format('YYYY-MM');
        return moment(start).format('YYYY-MM-DD');
    }
    if (viewType === 'week') {
        standardMonth = moment(start).format('YYYY-MM');
        return moment(start).format('YYYY-MM-DD') + ' ~ ' + moment(end).format('YYYY-MM-DD');
    }

    throw new Error('no view type');
}

function loadCurriculumnBook() {
    var selLessonGrade = $("#selLessonGrade").val();
    var optionDate = $("#dtsd").val();

    $.ajax({
        url: '/center/ajax/lessonScheduleControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'loadCurriculumnBook',
            franchise_idx: center_idx,
            selLessonGrade: selLessonGrade,
            optionDate: optionDate
        },
        success: function (result) {
            if (result.success) {
                $("#selLessonBook").html(result.data.data);
                return false;
            } else {
                $("#selLessonBook").html('<option value="">선택</option>');
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function lessonScheduleSelect(schedule_idx) {
    $.ajax({
        url: '/center/ajax/lessonScheduleControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'lessonScheduleSelect',
            center_idx: center_idx,
            schedule_idx: schedule_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $("#selLessonType option[value=" + result.data.lesson_type + "]").prop("selected", true);
                $("#selLessonTeacher option[value=" + result.data.teacher_idx + "]").prop("selected", true);
                $("#selLessonRoom option[value=" + result.data.lesson_room + "]").prop("selected", true);
                $("#dtsd").val(result.data.lesson_date);
                $("#dtsft").val(result.data.lesson_fromtime);
                $("#dtstt").val(result.data.lesson_totime);

                if (result.data.lesson_grade) {
                    $("#selLessonGrade option[value=" + result.data.lesson_grade + "]").prop("selected", true);
                } else {
                    $("#selLessonGrade option:first").prop("selected", true);
                }

                if (result.data.lesson_book_idx == '0' || !result.data.lesson_book_idx) {
                    $("#selLessonBook option:first").prop('selected', true);
                } else {
                    $("#selLessonBook option[value=" + result.data.lesson_book_idx + "]").prop("selected", true);
                }

                if (result.data.onoff_yn == 'Y') {
                    $("#chkLOnOff").prop("checked", true);
                    $("#lblLOnOff").text("온라인");
                } else {
                    $("#chkLOnOff").prop("checked", false);
                    $("#lblLOnOff").text("오프라인");
                }

                if (result.data.freehand_yn == 'Y') {
                    $("#chkLFree").prop("checked", true);
                } else {
                    $("#chkLFree").prop("checked", false);
                }

                if (result.data.supple_yn == 'Y') {
                    $("#chkLmChk").prop("checked", true);
                    $("#div_Lm").show();
                    if (result.data.supple_type == '1') {
                        $('#chkLm').prop("checked", true);
                        $('#lblLm').text('정규보강');
                    } else {
                        $('#chkLm').prop("checked", false);
                        $('#lblLm').text('개인보강');
                    }
                } else {
                    $("#chkLmChk").prop("checked", false);
                    $("#div_Lm").hide();
                    $('#chkLm').prop("checked", false);
                    $('#lblLm').text('개인보강');
                }

                if (result.data.lesson_type == '02' && result.data.lesson_grade != '') {
                    $("#receipt_div").show();
                    getReceiptItem(result.data.lesson_type, result.data.lesson_grade);
                    $("#selReceiptItem option[value=" + result.data.receipt_idx + "]").prop("selected", true);
                }
                return false;
            } else {
                $("#selLessonType option:first").prop("selected", true);
                $("#selLessonTeacher option:first").prop("selected", true);
                $("#selLessonRoom option:first").prop("selected", true);
                $("#selLessonBook option:first").prop('selected', true);
                $("#selLessonGrade option:first").prop("selected", true);
                $("#receipt_div").hide();
                $("#chkLmChk").prop("checked", false);
                $("#div_Lm").hide();
                $('#chkLm').prop("checked", false);
                $('#lblLm').text('개인보강');
                return false;
            }

        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function loadScheduleStudent(schedule_idx) {
    $.ajax({
        url: '/center/ajax/lessonScheduleControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'loadScheduleStudent',
            center_idx: center_idx,
            schedule_idx: schedule_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $("#LessonStudentList").html(result.data.tbl);
                return false;
            } else {
                $("#LessonStudentList").empty();
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function getReceiptItem(lesson_type, lesson_grade) {
    if (lesson_type !== '02') {
        $("#receipt_div").hide();
        return false;
    } else {
        $("#receipt_div").show();
        if (lesson_grade !== '') {
            $.ajax({
                url: '/center/ajax/receiptItemControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'getReceiptList',
                    center_idx: center_idx,
                    grade: lesson_grade
                },
                success: function (result) {
                    if (result.success && result.data) {
                        $("#selReceiptItem").html(result.data.selOption);
                    } else {
                        $("#selReceiptItem").html(result.data.selOption);
                    }
                },
                error: function (request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }
    }
}
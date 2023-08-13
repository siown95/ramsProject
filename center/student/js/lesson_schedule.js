$(document).ready(function () {
    let date = new Date();
    let offset = date.getTimezoneOffset() * 60000; //ms단위라 60000곱해줌
    let dateOffset = new Date(date.getTime() - offset);
    var now_date = dateOffset.toISOString().slice(0, 10);

    standardMonth = dateOffset.toISOString().slice(0, 7);
    loadLessonSchedule();
    calendar = new tui.Calendar('#calendars', options);
    displayRenderRange(hiViewType);
    getCalendarData();

    //달
    $("#btnCVm").click(function () {
        calendar.changeView('month');
        hiViewType = 'month';
        displayRenderRange(hiViewType);
        loadLessonSchedule();
        getCalendarData();
    });

    //주
    $("#btnCVw").click(function () {
        calendar.changeView('week');
        hiViewType = 'week';
        displayRenderRange(hiViewType);
        loadLessonSchedule();
        getCalendarData();
    });

    //일
    $("#btnCVd").click(function () {
        calendar.changeView('day');
        hiViewType = 'day';
        displayRenderRange(hiViewType);
        loadLessonSchedule();
        getCalendarData();
    });

    //이전
    $("#btnCPrev").click(function () {
        calendar.prev();
        displayRenderRange(hiViewType);
        loadLessonSchedule();
        getCalendarData();
    });

    //오늘
    $("#btnCToday").click(function () {
        calendar.today();
        displayRenderRange(hiViewType);
        loadLessonSchedule();
        getCalendarData();
    });

    //다음
    $("#btnCNext").click(function () {
        calendar.next();
        displayRenderRange(hiViewType);
        loadLessonSchedule();
        getCalendarData();
    });
});

function getCalendarData() {
    $("#LessonStudentList").empty();
    calendar.clear();

    $.ajax({
        url: '/center/student/ajax/lessonScheduleControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'getCalendarData',
            franchise_idx: center_idx,
            student_idx: student_idx,
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
        url: '/center/student/ajax/lessonScheduleControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        cache: false,
        data: {
            action: 'loadLessonSchedule',
            center_idx: center_idx,
            student_idx: student_idx,
            standardMonth: standardMonth
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#LessonInfoTable').DataTable({
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
                        data: 'book_name'
                    },
                    {
                        data: 'teacher_name'
                    },
                    {
                        data: 'lesson_room'
                    },
                    {
                        data: 'Attend_Yn'
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    displayLength: 5,
                    createdRow: function (row, data) {
                        $("td:eq(0)", row).addClass('text-center align-middle');
                        $("td:eq(1)", row).addClass('text-center align-middle');
                        $("td:eq(2)", row).addClass('text-start align-middle');
                        $("td:eq(3)", row).addClass('text-center align-middle');
                        $("td:eq(4)", row).addClass('text-center align-middle');
                        $("td:eq(5)", row).addClass('text-center align-middle');
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
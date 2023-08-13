$(document).ready(function () {
    console.log(Mid('11', 2, 2));
    loadLessonBookList();
    loadLessonScheduleList();

    $("#dtYearMonth").change(function () {
        loadLessonBookList();
        loadLessonScheduleList();
    });

    $("#selGrade").change(function () {
        loadLessonBookList();
        loadLessonScheduleList();
    });

    $("#btnBatchLessonBook").click(function () {
        var now_date = $("#dtYearMonth").val();
        var grade = $("#selGrade").val();

        $.ajax({
            url: '/center/ajax/lessonScheduleBatchControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'lessonBookBatch',
                center_idx: center_idx,
                grade: grade,
                now_date: now_date
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    loadLessonScheduleList();
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
});
function Mid(str, s, cnt)
{
    s = s - 1;
    return (str.substring(s, s + cnt));
}
function loadLessonBookList() {
    var now_date = $("#dtYearMonth").val();
    var grade = $("#selGrade").val();

    $.ajax({
        url: '/center/ajax/lessonScheduleBatchControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',

        data: {
            action: 'loadLessonBookList',
            center_idx: center_idx,
            grade: grade,
            now_date: now_date
        },
        success: function (result) {
            if (result.success && result.data) {
                $("#lessonBookTable").DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [
                        {
                            data: 'no'
                        },
                        {
                            data: 'lesson_book_name',
                            className: 'text-start'
                        },
                        {
                            data: 'lesson_book_cnt'
                        },
                        {
                            data: 'book_rent_cnt'
                        },
                        {
                            data: 'lack_cnt'
                        },
                        {
                            data: 'pre_cnt'
                        }
                    ],
                    lengthChange: false,
                    info: false,
                    displayLength: 10,
                    createdRow: function (row) {
                        $(row).addClass('text-center align-middle');
                        $("th").addClass('text-center align-middle');
                    },
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
            } else {
                $("#lessonBookTable").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function loadLessonScheduleList() {
    var now_date = $("#dtYearMonth").val();
    var grade = $("#selGrade").val();

    $.ajax({
        url: '/center/ajax/lessonScheduleBatchControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',

        data: {
            action: 'loadLessonScheduleList',
            center_idx: center_idx,
            grade: grade,
            now_date: now_date
        },
        success: function (result) {
            if (result.success && result.data) {
                $("#lessonBatchTable").DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [
                        {
                            data: 'lesson_grade'
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
                            data: 'book_name',
                            className: 'text-start'
                        },
                        {
                            data: 'student_cnt'
                        },
                        {
                            data: 'freehand_yn'
                        },
                        {
                            data: 'onoff_yn'
                        }
                    ],
                    lengthChange: false,
                    info: false,
                    displayLength: 10,
                    createdRow: function (row) {
                        $(row).addClass('text-center align-middle');
                        $("th").addClass('text-center align-middle');
                    },
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
            } else {
                $("#lessonBookTable").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}
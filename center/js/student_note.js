$(document).ready(function () {
    bookReportLoad();

    $("#bookReportList").on("click", ".tc", function (e) {
        var book_report_idx = $(e.target).parent("tr").data('bookreport-idx');

        $.ajax({
            url: '/center/ajax/bookReportContorll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'bookReportSelect',
                book_report_idx: book_report_idx
            },
            success: function (result) {
                if (result.success) {
                    $("#txtViewTitle").val(result.data.book_report_title);
                    $("#txtBookReportWriter").val(result.data.student_name);
                    $("#txtBookReportDate").val(result.data.reg_date);
                    $("#txtViewBookName").val(result.data.book_name);
                    $("#txtViewContents").val(result.data.book_report_contents);
                    $("#book_report_idx").val(book_report_idx);

                    $("#txtViewTitle").attr("disabled", true);
                    $("#txtViewContents").attr("disabled", true);

                    $("#btnBookReportEdit").show();
                    $("#btnBookReportEditSave").hide();
                    $("#NoteViewModal").modal('show');
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

    //학생 검색 모달
    $("#btnBookReportStudentSearch").click(function () {
        $("#BookReportStudentModal").modal('show');
    });

    //학생 검색
    $("#studentBookReport").click(function () {
        var student_name = $("#txtBookReportStudentName").val();

        if (!student_name || !student_name.trim()) {
            alert("학생이름을 입력하세요");
            return false;
        }

        if (student_name.length < 2) {
            alert("2글자 이상 입력하세요");
            return false;
        }

        $.ajax({
            url: '/center/ajax/bookReportContorll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'bookReportStudentSearch',
                center_idx: center_idx,
                student_name: student_name
            },
            success: function (result) {
                if (result.success && result.data.table) {
                    $("#studentBookReportList").html(result.data.table);
                    return false;
                } else {
                    alert(result.msg);
                    $("#studentBookReportList").empty();
                    return false;
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    });

    $("#txtBookReportStudentName").keypress(function (e) {
        if (e.keyCode === 13) {
            $("#studentBookReport").trigger('click');
        }
    });

    $("#txtStudentName").click(function () {
        $("#btnBookReportStudentSearch").trigger('click');
    });

    //학생 검색 후 리스트 클릭
    $("#studentBookReportList").on("click", ".tc", function (e) {
        var student_no = $(e.target).parent('.tc').data("student-no");
        var student_name = $(e.target).parent('.tc').find("td:eq(0)").text();
        var student_grade = $(e.target).parent('.tc').find("td:eq(1)").text();
        var student_school = $(e.target).parent('.tc').find("td:eq(2)").text();

        $("#writer_no").val(student_no);
        $("#txtStudentName").val(student_name);
        $("#lblGrade").text(student_grade);
        $("#lblSchool").text(student_school);
        $("#studentBookReportList").empty();
        $("#BookReportStudentModal").modal('hide');
        $("#txtBookReportStudentName").val('');

        readBookListLoad();
    });

    //등록
    $("#btnBookRentSave").click(function () {
        var student_no = $("#writer_no").val();
        var txtWriteTitle = $("#txtBookReportTitle").val();
        var selLessonBook = $("#selLessonBook").val();
        var txtWriteContents = $("#txtBookReportContents").val();

        if (!txtWriteTitle || !txtWriteTitle.trim()) {
            alert('제목을 입력하세요');
            return false;
        }

        if (!student_no) {
            alert('학생을 지정하세요');
            return false;
        }

        if (!selLessonBook) {
            alert('도서를 선택하세요');
            return false;
        }

        if (!txtWriteContents || !txtWriteContents.trim()) {
            alert('내용을 입력하세요');
            return false;
        }

        $.ajax({
            url: '/center/ajax/bookReportContorll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'bookReportInsert',
                center_idx: center_idx,
                student_no: student_no,
                txtWriteTitle: txtWriteTitle,
                selLessonBook: selLessonBook,
                txtWriteContents: txtWriteContents
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#NoteWriteModal").modal('hide');
                    formClear();
                    bookReportLoad();
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

    $("#btnBookReportEdit").click(function () {
        $("#btnBookReportEdit").hide();
        $("#btnBookReportEditSave").show();

        $("#txtViewTitle").removeAttr("disabled");
        $("#txtViewContents").removeAttr("disabled");
    });

    //수정
    $("#btnBookReportEditSave").click(function () {
        var book_report_idx = $("#book_report_idx").val();
        var txtViewTitle = $("#txtViewTitle").val();
        var txtViewContents = $("#txtViewContents").val();

        $.ajax({
            url: '/center/ajax/bookReportContorll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'bookReportUpdate',
                book_report_idx: book_report_idx,
                txtViewTitle: txtViewTitle,
                txtViewContents: txtViewContents
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#NoteViewModal").modal('hide');
                    bookReportLoad();
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

    //삭제
    $("#btnBookReportDelete").click(function () {
        var book_report_idx = $("#book_report_idx").val();

        if (confirm("게시글을 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/bookReportContorll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'bookReportDelete',
                    book_report_idx: book_report_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#NoteViewModal").modal('hide');
                        bookReportLoad();
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

function readBookListLoad() {
    var student_no = $("#writer_no").val();

    $.ajax({
        url: '/center/ajax/bookReportContorll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'readBookListLoad',
            center_idx: center_idx,
            student_no: student_no
        },
        success: function (result) {
            $("#div_selBook").show();
            $("#selLessonBook").html(result.data.selectOption);
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function bookReportLoad() {
    $.ajax({
        url: '/center/ajax/bookReportContorll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'bookReportLoad',
            center_idx: center_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#bookReportTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'student_name'
                    },
                    {
                        data: 'school_name'
                    },
                    {
                        data: 'age'
                    },
                    {
                        data: 'book_name',
                        className: 'text-start'
                    },
                    {
                        data: 'book_report_title',
                        className: 'text-start'
                    },
                    {
                        data: 'reg_date'
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    order: [[0, 'desc']],
                    displayLength: 20, 
                    createdRow: function (row, data) {
                        $(row).addClass('tc text-center align-middle');
                        $(row).attr('data-bookreport-idx', data.book_report_idx);
                        $("th").addClass('text-center align-middle');
                    },
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
            } else {
                $('#bookReportTable').DataTable().destroy();
                $("#bookReportList").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function formClear() {
    $("#div_selBook").hide();
    $("#writer_no").val('');
    $("#txtBookReportTitle").val('');
    $("#selLessonBook").empty();
    $("#txtBookReportContents").val('');
    $("#lblGrade").text('');
    $("#lblSchool").text('');
    $("#txtStudentName").val('');
}
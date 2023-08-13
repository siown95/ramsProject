$(document).ready(function () {
    readBookListLoad();
    bookReportLoad();

    $("#bookReportList").on("click", ".tc", function (e) {
        var book_report_idx = $(e.target).parent("tr").data('bookreport-idx');

        $.ajax({
            url: '/center/student/ajax/bookReportContorll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'bookReportSelect',
                book_report_idx: book_report_idx,
                student_no: userInfo.user_no
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

                    if (result.data.mod_flag == 'Y') {
                        $("#btnBookReportDelete").show();
                        $("#btnBookReportEdit").show();
                    } else {
                        $("#btnBookReportDelete").hide();
                        $("#btnBookReportEdit").hide();
                    }
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

    $("#btnBookReportEdit").click(function () {
        $("#btnBookReportEdit").hide();
        $("#btnBookReportEditSave").show();

        $("#txtViewTitle").removeAttr("disabled");
        $("#txtViewContents").removeAttr("disabled");
    });

    //등록
    $("#btnWriteSave").click(function () {
        var txtWriteTitle = $("#txtWriteTitle").val();
        var selLessonBook = $("#selLessonBook").val();
        var txtWriteContents = $("#txtWriteContents").val();

        if (!txtWriteTitle || !txtWriteTitle.trim()) {
            alert('제목을 입력하세요');
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
            url: '/center/student/ajax/bookReportContorll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'bookReportInsert',
                center_idx: center_idx,
                student_no: userInfo.user_no,
                txtWriteTitle: txtWriteTitle,
                selLessonBook: selLessonBook,
                txtWriteContents: txtWriteContents
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#NoteWriteModal").modal('hide');
                    location.reload();
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

    //수정
    $("#btnBookReportEditSave").click(function(){
        var book_report_idx = $("#book_report_idx").val();
        var txtViewTitle = $("#txtViewTitle").val();
        var txtViewContents = $("#txtViewContents").val();

        $.ajax({
            url: '/center/student/ajax/bookReportContorll.ajax.php',
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
                    location.reload();
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
    $("#btnBookReportDelete").click(function(){
        var book_report_idx = $("#book_report_idx").val();

        if(confirm("게시글을 삭제하시겠습니까?")){
            $.ajax({
                url: '/center/student/ajax/bookReportContorll.ajax.php',
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
                        location.reload();
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
    $.ajax({
        url: '/center/student/ajax/bookReportContorll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'readBookListLoad',
            center_idx: center_idx,
            student_no: userInfo.user_no
        },
        success: function (result) {
            $("#selLessonBook").html(result.data.selectOption);
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function bookReportLoad() {
    $.ajax({
        url: '/center/student/ajax/bookReportContorll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'bookReportLoad',
            center_idx: center_idx,
            student_no: userInfo.user_no
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
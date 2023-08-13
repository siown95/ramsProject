$(document).ready(function () {
    loadActivityErrorList();

    $("#btnAddErrorReport").click(function () {
        $("#txtTitle").val('');
        $("#txtContents").val('');
        $("#txtReportFileName").val('');
        $('#reportFileAttach').val('');
        $("#errorReportOriginFile").addClass("d-none");
        $("#errorReportOriginFile").attr("href", "");
        $("#errorReportOriginFileName").val('');
        $("#errorReportFileDel").addClass("d-none");
        $("#mdErrorIdx").val('');
    });

    $("#errorList").on("click", ".tc", function (e) {
        activityErrorSelect(e);
    });

    $('#btnReportFileUpload').click(function () {
        $('#reportFileAttach').trigger('click');
    });

    $('#txtReportFileName').click(function () {
        $('#reportFileAttach').trigger('click');
    });

    $('#reportFileAttach').change(function () {
        var fileNm = $('#reportFileAttach')[0].files[0];

        if (fileNm) {
            $("#txtReportFileName").val(fileNm.name);
        } else {
            $("#txtReportFileName").val('');
            $('#reportFileAttach').val('');
        }
    });

    $("#btnSaveErrorReport").click(function () {
        var title = $("#txtTitle").val();
        var contents = $("#txtContents").val();
        var error_idx = $("#mdErrorIdx").val();
        var file_name = $("#errorReportOriginFileName").val();
        var action = '';

        if (!title || !title.trim()) {
            alert('제목을 입력하세요.');
            $("#txtTitle").focus();
            return false;
        }

        if (!contents || !contents.trim()) {
            alert('내용을 입력하세요.');
            $("#txtTitle").focus();
            return false;
        }

        datas = new FormData();
        datas.append('files', $('#reportFileAttach')[0].files[0]);
        datas.append('title', title);
        datas.append('contents', contents);
        datas.append('writer_no', userInfo.user_no);
        datas.append('file_name', file_name);

        if(error_idx){
            action = 'activityErrorUpdate';
            datas.append('error_idx', error_idx);
        }else{
            action = 'activityErrorInsert';
        }

        datas.append('action', action);

        $.ajax({
            url: '/center/ajax/activityErrorReport.ajax.php',
            contentType: 'multipart/form-data',
            dataType: 'JSON',
            type: 'POST',
            mimeType: 'multipart/form-data',
            data: datas,
            cache: false,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#writeErrorReportModal").modal('hide');
                    loadActivityErrorList();
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

    $("#btnUpdateErrorReport").click(function () {
        activityErrorUpdateLoad();
    });

    $("#btnDeleteErrorReport").click(function () {
        var error_idx = $("#mdErrorIdx").val();
        var file_name = $("#errorReportFileName").val();

        if (confirm("오류신고 내역을 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/activityErrorReport.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'activityErrorDelete',
                    error_idx: error_idx,
                    file_name: file_name
                },
                success: function (result) {
                    if (result.success && result.data) {
                        alert(result.msg);
                        loadActivityErrorList();
                        $("#replyViewModal").modal('hide');
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

    $("#errorReportFileDel").click(function () {
        var error_idx = $("#mdErrorIdx").val();
        var file_name = $("#errorReportOriginFileName").val();

        if (confirm("첨부 파일을 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/activityErrorReport.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'activityErrorFileDelete',
                    error_idx: error_idx,
                    file_name: file_name
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#errorReportOriginFile").addClass("d-none");
                        $("#errorReportOriginFile").attr("href", "");
                        $("#errorReportOriginFileName").val('');
                        $("#errorReportFileDel").addClass("d-none");
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

function loadActivityErrorList() {
    $.ajax({
        url: '/center/ajax/activityErrorReport.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'activityErrorLoad'
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#dataTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'title',
                        className:'text-start'
                    },
                    {
                        data: 'reg_date'
                    },
                    {
                        data: 'writer'
                    },
                    {
                        data: 'state'
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    order: [[0, 'desc']],
                    createdRow: function (row, data) {
                        $(row).addClass('tc');
                        $(row).attr('data-error-idx', data.error_report_idx);
                        $("th").addClass('text-center align-middle');
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

function activityErrorSelect(e) {
    var error_idx = $(e.target).parents('tr').data('error-idx');

    $.ajax({
        url: '/center/ajax/activityErrorReport.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'activityErrorSelect',
            error_idx: error_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $("#mdErrorIdx").val(error_idx);
                setForm(result.data);
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

function activityErrorUpdateLoad() {
    var error_idx = $("#mdErrorIdx").val();

    $.ajax({
        url: '/center/ajax/activityErrorReport.ajax.php',
        dataType: 'JSON',
        type: 'POST',

        data: {
            action: 'activityErrorSelect',
            error_idx: error_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                setUpdateForm(result.data);
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

function setForm(data) {
    $("#txtErrorRepotTitle").text(data.title);
    $("#txtErrorContents").val(data.contents);
    $("#txtErrorReportWriter").text(data.writer);
    $("#txtErrorReportDate").text(data.reg_date);

    if (data.file_name) {
        $("#errorReportFileLink").removeClass("d-none");
        $("#errorReportFileLink").attr("href", "/files/activity_error/" + data.file_name);
        $("#errorReportFileLink").html("<i class=\"fa-solid fa-file-arrow-down\"></i>" + data.origin_name);
        $("#errorReportFileName").val(data.file_name);
    } else {
        $("#errorReportFileLink").addClass("d-none");
        $("#errorReportFileLink").attr("href", "");
        $("#errorReportFileName").val('');
    }

    $("#errorReportState").text(data.state);
    $("#txtErrorComments").text(data.comments);

    if ((data.writer_no == userInfo.user_no) && data.state == '접수중') {
        $("#btnUpdateErrorReport").show();
        $("#btnDeleteErrorReport").show();
    } else {
        $("#btnUpdateErrorReport").hide();
        $("#btnDeleteErrorReport").hide();
    }

    $("#replyViewModal").modal('show');
}

function setUpdateForm(data) {
    $("#replyViewModal").modal('hide');
    $("#txtTitle").val(data.title);
    $("#txtContents").val(data.contents);

    if (data.file_name) {
        $("#errorReportOriginFile").removeClass("d-none");
        $("#errorReportOriginFile").attr("href", "/files/activity_error/" + data.file_name);
        $("#errorReportOriginFile").html("<i class=\"fa-solid fa-file-arrow-down\"></i>" + data.origin_name);
        $("#errorReportOriginFileName").val(data.file_name);
        $("#errorReportFileDel").removeClass("d-none");
    } else {
        $("#errorReportOriginFile").addClass("d-none");
        $("#errorReportOriginFile").attr("href", "");
        $("#errorReportOriginFileName").val('');
        $("#errorReportFileDel").addClass("d-none");
    }

    $("#writeErrorReportModal").modal('show');
}
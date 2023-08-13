$(document).ready(function () {
    inquiryLoad();

    $("#inquiryList").on("click", ".tc", function (e) {
        inquirySelect(e);
    });

    $("#txtFileName").click(function () {
        $("#fileAttach").trigger("click");
    });
    $("#btnFileUpload").click(function () {
        $("#fileAttach").trigger("click");
    });

    $('#fileAttach').change(function () {
        var fileNm = $('#fileAttach')[0].files[0];

        if (fileNm) {
            $("#txtFileName").val(fileNm.name);
        } else {
            $("#txtFileName").val('');
            $('#fileAttach').val('');
        }
    });

    //문의사항 답변 저장
    $("#btnAnswerSave").click(function () {
        var inquiry_idx = $("#inquiry_idx").val();
        var txtAnswer = $("#txtAnswer").val();
        var fileInquiry = $('#fileAttach')[0].files[0];

        if (!txtAnswer || !txtAnswer.trim()) {
            alert('답변을 입력하세요');
            return false;
        }

        var datas = new FormData();
        datas.append('action', 'inquiryCmtInsert');
        datas.append('inquiry_idx', inquiry_idx);
        datas.append('txtAnswer', txtAnswer);

        if (fileInquiry) {
            datas.append('fileInquiryCmt', fileInquiry);
        }

        $.ajax({
            url: '/adm/ajax/inquiryControll.ajax.php',
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

    //문의사항 답변 수정
    $("#btnAnswerUpdate").click(function () {
        var comment_idx = $("#cmtIdx").val();
        var txtAnswer = $("#txtAnswer").val();
        var fileInquiry = $('#fileAttach')[0].files[0];
        var cmtFileName = $("#updateFileName").val();

        if (!txtAnswer || !txtAnswer.trim()) {
            alert('답변을 입력하세요');
            return false;
        }

        var datas = new FormData();
        datas.append('action', 'inquiryCmtUpdate');
        datas.append('comment_idx', comment_idx);
        datas.append('txtAnswer', txtAnswer);
        datas.append('cmtFileName', cmtFileName);

        if (fileInquiry) {
            datas.append('fileInquiryCmt', fileInquiry);
        }

        $.ajax({
            url: '/adm/ajax/inquiryControll.ajax.php',
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

    $("#answerList").on("click", "#btnCommentUpdate", function (e) {
        var origin_answer = $(e.target).parents("tr").find("td:eq(1)").text();

        $("#txtAnswer").val(origin_answer);
        $("#btnAnswerUpdate").show();
        $("#answerInsertForm").show();
    });

    //문의 답변 삭제
    $("#answerList").on("click", "#btnCommentDelete", function () {
        var comment_idx = $("#cmtIdx").val();
        var cmtFileName = $("#updateFileName").val();

        if (confirm("답변을 삭제하시겠습니까?")) {
            $.ajax({
                url: '/adm/ajax/inquiryControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                
                data: {
                    action: 'inquiryCmtDelete',
                    comment_idx: comment_idx,
                    cmtFileName: cmtFileName
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
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

    //댓글 파일 삭제
    $("#answerInsertForm").on("click", "#cmtFileDelete", function () {
        var comment_idx = $("#cmtIdx").val();
        var cmtFileName = $("#updateFileName").val();

        if (confirm("파일을 삭제하시겠습니까?")) {
            $.ajax({
                url: '/adm/ajax/inquiryControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                
                data: {
                    action: 'inquiryCmtFileDelete',
                    comment_idx: comment_idx,
                    cmtFileName: cmtFileName
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#updateFileDown").attr("href", "");
                        $("#updateFileDown").html("");
                        $("#updateFileName").val('');
                        $("#updateFileDiv").hide();
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

function inquiryLoad() {
    $.ajax({
        url: '/adm/ajax/inquiryControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        
        data: {
            action: 'inquiryLoad'
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#inquiryTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'num'
                    },
                    {
                        data: 'inquiry_title',
                        className: 'text-start'
                    },
                    {
                        data: 'reg_date'
                    },
                    {
                        data: 'inquiry_writer'
                    },
                    {
                        data: 'cmt_exist'
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    order: [[0, 'desc']],
                    createdRow: function (row, data) {
                        $(row).addClass('tc text-center align-middle');
                        $(row).attr('data-inquiry-idx', data.inquiry_idx);
                        $("th").addClass('text-center align-middle');
                    },
                    language: {
                        emptyTable: '데이터가 없습니다.',
                        zeroRecords: '데이터가 없습니다.',
                        search: '검색 ',
                        paginate: {
                            "next": ">", // 다음 페이지
                            "previous": "<" // 이전 페이지
                        }
                    }
                });
            } else {
                $('#inquiryTable').DataTable().destroy();
                $("#inquiryList").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function inquirySelect(e) {
    var inquiry_idx = $(e.target).parent("tr").data("inquiry-idx");

    $.ajax({
        url: '/adm/ajax/inquiryControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        
        data: {
            action: 'inquirySelect',
            inquiry_idx: inquiry_idx
        },
        success: function (result) {
            if (result.success) {
                setFormdata(result.data);
                $("#inquiry_idx").val(inquiry_idx);
                $("#replyModal").modal('show');
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

function setFormdata(data) {
    $("#txtAnswer").val('');
    $("#btnAnswerUpdate").hide();

    $("#txtTitle").val(data.inquiry_title);
    $("#txtWriter").val(data.inquiry_writer);
    $("#txtDate").val(data.reg_date);
    $("#txtKind").val(data.inquiry_kind);
    $("#txtContents").val(data.inquiry_contents);

    if (data.file_name) {
        $("#inquiryFileDown").show();
        $("#inquiryFileDown").attr("href", "/files/inquiry_file/" + data.file_name);
        $("#inquiryFileDown").html("<i class=\"fa-solid fa-file-arrow-down\"></i> " + data.origin_file_name);

        $("#updateFileDown").attr("href", "/files/inquiry_file/" + data.file_name);
        $("#updateFileDown").html("<i class=\"fa-solid fa-file-arrow-down\"></i> " + data.origin_file_name);
    } else {
        $("#inquiryFileDown").hide();
        $("#inquiryFileDown").attr("href", '');
        $("#inquiryFileDown").html('');
    }

    if (data.inquiry_comment) {
        $("#btnAnswerSave").hide();
        $("#answerInsertForm").hide();
        $("#answerListDiv").show();

        $("#cmtIdx").val(data.inquiry_comment_idx);
        $("#answerList").html(data.inquiry_comment);

        if (data.comment_file) {
            $("#updateFileDown").attr("href", "/files/inquiry_file/" + data.comment_file);
            $("#updateFileDown").html("<i class=\"fa-solid fa-file-arrow-down\"></i> " + data.comment_file);
            $("#updateFileName").val(data.comment_file);
            $("#updateFileDiv").show();
        } else {
            $("#updateFileDown").attr("href", "");
            $("#updateFileDown").html("");
            $("#updateFileName").val('');
            $("#updateFileDiv").hide();
        }
    } else {
        $("#btnAnswerSave").show();
        $("#answerInsertForm").show();
        $("#answerListDiv").hide();
        $("#cmtIdx").val('');
        $("#answerList").html('');
        $("#updateFileDown").attr("href", "");
        $("#updateFileDown").html("");
        $("#updateFileName").val('');
        $("#updateFileDiv").hide();
    }
}

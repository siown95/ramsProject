$(document).ready(function () {
    inquiryLoad();

    $("#btnAddInquiry").click(function () {
        $("#txtTitle").val('');
        $("#txtContents").val('');
        $("#selInquiryKind option:first").prop("selected", true);
        $("#btnInquiryUpdate").show();
        $("#btnInquirySave").hide();
        $("#updateFileDiv").hide();
        $("#updateFileDown").attr("href", '');
        $("#updateFileDown").html('');
        $("#updateFileName").val('');
        $("#fileInquiryName").val('');
        $('#fileInquiry').val('');
        $("#btnInquiryUpdate").hide();
        $("#btnInquirySave").show();
        $("#inquiry_idx").val('');
    });

    $("#inquiryList").on("click", ".tc", function (e) {
        inquirySelect(e);
    });

    $('#btnInquiryUpload').click(function () {
        $('#fileInquiry').trigger('click');
    });

    $('#fileInquiry').change(function () {
        var fileNm = $('#fileInquiry')[0].files[0];

        if (fileNm) {
            $("#fileInquiryName").val(fileNm.name);
        } else {
            $("#fileInquiryName").val('');
            $('#fileInquiry').val('');
        }
    });

    //문의사항 저장
    $("#btnInquirySave").click(function () {
        var txtTitle = $("#txtTitle").val();
        var selInquiryKind = $("#selInquiryKind").val();
        var txtContents = $("#txtContents").val();
        var fileInquiry = $('#fileInquiry')[0].files[0];

        if (!txtTitle || !txtTitle.trim()) {
            alert('제목을 입력하세요');
            return false;
        }

        if (!selInquiryKind) {
            alert('문의 구분을 선택하세요');
            return false;
        }

        if (!txtContents || !txtContents.trim()) {
            alert('문의 내용을 입력하세요');
            return false;
        }

        var datas = new FormData();
        datas.append('action', 'inquiryInsert');
        datas.append('writer_no', userInfo.user_no);
        datas.append('txtTitle', txtTitle);
        datas.append('selInquiryKind', selInquiryKind);
        datas.append('txtContents', txtContents);

        if (fileInquiry) {
            datas.append('fileInquiry', fileInquiry);
        }

        $.ajax({
            url: '/center/ajax/inquiryControll.ajax.php',
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
                    $("#writeInquiryModal").modal('hide');
                    inquiryLoad();
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

    //문의사항 수정
    $("#btnInquiryUpdate").click(function () {
        var inquiry_idx = $("#inquiry_idx").val();
        var txtTitle = $("#txtTitle").val();
        var selInquiryKind = $("#selInquiryKind").val();
        var txtContents = $("#txtContents").val();
        var updateFileName = $("#updateFileName").val();
        var fileInquiry = $('#fileInquiry')[0].files[0];

        if (!txtTitle || !txtTitle.trim()) {
            alert('제목을 입력하세요');
            return false;
        }

        if (!selInquiryKind) {
            alert('문의 구분을 선택하세요');
            return false;
        }

        if (!txtContents || !txtContents.trim()) {
            alert('문의 내용을 입력하세요');
            return false;
        }

        var datas = new FormData();
        datas.append('action', 'inquiryUpdate');
        datas.append('inquiry_idx', inquiry_idx);
        datas.append('txtTitle', txtTitle);
        datas.append('selInquiryKind', selInquiryKind);
        datas.append('txtContents', txtContents);
        datas.append('updateFileName', updateFileName);

        if (fileInquiry) {
            datas.append('fileInquiry', fileInquiry);
        }

        $.ajax({
            url: '/center/ajax/inquiryControll.ajax.php',
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
                    $("#writeInquiryModal").modal('hide');
                    $("#replyViewModal").modal('hide');
                    inquiryLoad();
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

    //문의사항 삭제
    $("#btnInquiryDelete").click(function () {
        var inquiry_idx = $("#inquiry_idx").val();
        var inquiryFileName = $("#inquiryFileName").val();

        if (confirm("문의 및 요청사항을 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/inquiryControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'inquiryDelete',
                    inquiry_idx: inquiry_idx,
                    inquiryFileName: inquiryFileName
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#writeInquiryModal").modal('hide');
                        $("#replyViewModal").modal('hide');
                        inquiryLoad();
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

    //파일만 삭제
    $("#inquiryFileDelete").click(function () {
        var inquiry_idx = $("#inquiry_idx").val();
        var updateFileName = $("#updateFileName").val();

        if (confirm("첨부파일을 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/inquiryControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'inquiryFileDelete',
                    inquiry_idx: inquiry_idx,
                    updateFileName: updateFileName
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#updateFileDiv").hide();
                        $("#updateFileDown").attr("href", '');
                        $("#updateFileDown").html('');
                        $("#updateFileName").val('');
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

    $("#btnInquiryUpdateView").click(function () {
        inquiryUpdateLoad();
    });
});

function inquiryLoad() {
    $.ajax({
        url: '/center/ajax/inquiryControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
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
        url: '/center/ajax/inquiryControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'inquirySelect',
            inquiry_idx: inquiry_idx,
            user_no: userInfo.user_no
        },
        success: function (result) {
            if (result.success) {
                setFormdata(result.data);
                $("#inquiry_idx").val(inquiry_idx);
                $("#replyViewModal").modal('show');
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

function inquiryUpdateLoad() {
    var inquiry_idx = $("#inquiry_idx").val();

    $.ajax({
        url: '/center/ajax/inquiryControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'inquiryUpdateLoad',
            inquiry_idx: inquiry_idx
        },
        success: function (result) {
            if (result.success) {
                setUpdatedata(result.data);
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
    $("#txtViewTitle").val(data.inquiry_title);
    $("#txtViewWriter").val(data.inquiry_writer);
    $("#txtViewDate").val(data.reg_date);
    $("#txtInquiryKind").val(data.inquiry_kind);
    $("#txtViewContents").val(data.inquiry_contents);

    if (data.file_name) {
        $("#inquiryFileDown").attr("href", "/files/inquiry_file/" + data.file_name);
        $("#inquiryFileDown").html("<i class=\"fa-solid fa-file-arrow-down\"></i> " + data.origin_file_name);
        $("#inquiryFileName").val(data.file_name);
    } else {
        $("#inquiryFileDown").attr("href", '');
        $("#inquiryFileDown").html('');
        $("#inquiryFileName").val('');
    }

    if (data.inquiry_comment) {
        $("#commentSection").show();
        $("#answerList").html(data.inquiry_comment);
    } else {
        $("#commentSection").hide();
        $("#answerList").html('');
    }

    if (data.mod_flag == 'Y') {
        $("#btnInquiryDelete").show();
        $("#btnInquiryUpdateView").show();
    } else {
        $("#btnInquiryDelete").hide();
        $("#btnInquiryUpdateView").hide();
    }
}

function setUpdatedata(data) {
    $("#txtTitle").val(data.inquiry_title);
    $("#txtContents").val(data.inquiry_contents);
    $("#selInquiryKind option[value=" + data.inquiry_type + "]").prop("selected", true);
    $("#btnInquiryUpdate").show();
    $("#btnInquirySave").hide();

    if (data.file_name) {
        $("#updateFileDiv").show();
        $("#updateFileDown").attr("href", data.file_name);
        $("#updateFileDown").html("<i class=\"fa-solid fa-file-arrow-down\"></i> " + data.origin_file_name);
        $("#updateFileName").val(data.file_name);
    } else {
        $("#updateFileDiv").hide();
        $("#updateFileDown").attr("href", '');
        $("#updateFileDown").html('');
        $("#updateFileName").val('');
    }

    $("#replyViewModal").modal('hide');
    $("#writeInquiryModal").modal('show');
}
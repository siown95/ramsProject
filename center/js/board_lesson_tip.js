$(document).ready(function () {
    boardTipLoad();

    $("#boardTipList").on("click", ".tc", function (e) {
        boardTipSelect(e);
        $('#viewContents').find('img').addClass('img-fluid');
    });

    $('#btnUploadTipFile').click(function () {
        $('#lessonTipFileAttach').trigger('click');
    });

    $('#txtLessonTipFileName').click(function () {
        $('#lessonTipFileAttach').trigger('click');
    });

    $('#lessonTipFileAttach').change(function () {
        var fileNm = $('#lessonTipFileAttach')[0].files[0];
        var fileExt = '';

        if (fileNm) {
            $("#txtLessonTipFileName").val(fileNm.name);
        } else {
            $("#txtLessonTipFileName").val('');
            $('#lessonTipFileAttach').val('');
        }
    });

    $('#summernote').summernote({
        lang: 'ko-KR',
        height: 500, // 에디터 높이
        minHeight: 500, // 최소 높이
        maxHeight: null,
        toolbar: [
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['style', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['color', ['forecolor', 'color']],
            ['table', ['table']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['picture', 'link', 'video']],
            ['view', ['codeview', 'help']]
        ],
        fontNames: ['맑은 고딕', '궁서', '굴림체', '굴림', '돋움체', '바탕체', 'sans-serif', 'Arial'],
        fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '28', '30', '36', '50', '72'],
        callbacks: { //여기 부분이 이미지를 첨부하는 부분
            onImageUpload: function (files) {
                setFiles(files, this);
            },
            onPaste: function (e) {
                var clipboardData = e.originalEvent.clipboardData;

                if (clipboardData && clipboardData.items && clipboardData.items.length) {
                    var item = clipboardData.items[0];
                    // 붙여넣는게 파일이고, 이미지면
                    if (item.kind === 'file' && item.type.indexOf('image/') !== -1) {
                        // 이벤트 막음
                        e.preventDefault();
                    }
                }
            }
        }
    });
    $('#summernote').summernote('fontName', '맑은 고딕');

    $("#btnAddLessonTip").click(function () {
        $("#board_idx").val('');
        $("#file_name").val('');
        $("#file_path").val('');
        $("#writer_no").val('');
        $("#txtTitle").val('');
        $("#exfile").addClass('d-none');
        $("#exfile").attr("href", "");
        $("#imgdel").addClass("d-none");
        $('#BoardModal').modal('show');
    });

    $("#btnSaveLessonTip").click(function () {
        var board_idx = $("#board_idx").val();
        var title = $("#txtTitle").val();
        var board_kind = $("#selKind").val();
        var contents = $('#summernote').summernote('code');
        var file_name = $("#file_name").val();
        var file_path = $("#file_path").val();
        var notice = '';

        if (!title || !title.trim()) {
            alert('제목을 입력하세요.');
            $("#txtTitle").focus();
            return false;
        }

        if (!contents || !contents.trim()) {
            alert('내용을 입력하세요.');
            return false;
        }

        if ($("#chkNotice").is(":checked") == true) {
            notice = 'Y';
        }

        var file_board_arr = new Array();

        var sommernoteWriteArea = document.getElementsByClassName("note-editable")[0];
        var imgsTags = Array.from(sommernoteWriteArea.getElementsByTagName('img'));

        imgsTags.forEach(img => {
            file_board_arr.push(img.src);
        });

        datas = new FormData();
        datas.append('attachFiles', $('#lessonTipFileAttach')[0].files[0]);
        datas.append('user_no', userInfo.user_no);
        datas.append('title', title);
        datas.append('board_kind', board_kind);
        datas.append('contents', contents);
        datas.append('notice_yn', notice);

        if (!board_idx) {
            action = 'boardTipInsert';
        } else {
            action = 'boardTipUpdate';
            datas.append('board_idx', board_idx);

            if (file_name != '') {
                datas.append('file_name', file_name);
            }

            if (file_path != '') {
                datas.append('file_path', file_path);
            }
        }

        if (file_board_arr.length > 0) {
            for (var i = 0; i < file_board_arr.length; i++) {
                datas.append('files[]', file_board_arr[i]);
            }
        }

        datas.append('action', action);

        $.ajax({
            url: '/center/ajax/boardTipControll.ajax.php',
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
                    $('#BoardModal').modal('hide');
                    boardTipLoad();
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

    $("#btnDeleteLessonTip").click(function () {
        var board_idx = $("#board_idx").val();
        var file_name = $("#file_name").val();
        var file_path = $("#file_path").val();

        if (confirm("게시글을 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/boardTipControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'boardTipDelete',
                    board_idx: board_idx,
                    file_name: file_name,
                    file_path: file_path
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $('#BoardViewModal').modal('hide');
                        boardTipLoad();
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

    $("#btnLikes").click(function () {
        var board_idx = $("#board_idx").val();

        $.ajax({
            url: '/center/ajax/boardTipControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'cmtLikeChange',
                board_idx: board_idx,
                user_no: userInfo.user_no,
                franchise_idx: center_idx
            },
            success: function (result) {
                if (result.success) {
                    boardTipCmtLoad(board_idx);
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

    $("#btnUpdateLessonTip").click(function () {
        $("#BoardViewModal").modal('hide');
        boardTipUpateLoad();
    });

    $("#imgdel").click(function () {
        var board_idx = $("#board_idx").val();
        var file_name = $("#file_name").val();

        if (confirm('첨부파일을 삭제하시겠습니까?')) {
            $.ajax({
                url: '/center/ajax/boardTipControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'deleteImage',
                    board_idx: board_idx,
                    file_name: file_name,
                },
                success: function (result) {
                    alert(result.msg);
                    $('#BoardModal').modal('hide');
                    boardTipLoad();
                },
                error: function (request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }
    });

    $("#btnCommentSave").click(function () {
        var comment = $("#txtComment").val();
        var board_idx = $("#board_idx").val();

        if (!comment || !comment.trim()) {
            alert('댓글을 입력하세요.');
            $("#txtComment").focus();
            return false;
        }

        $.ajax({
            url: '/center/ajax/boardTipControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'boardTipCmtInsert',
                comment: comment,
                board_idx: board_idx,
                user_no: userInfo.user_no
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    boardTipCmtLoad(board_idx);
                    $("#txtComment").val('');
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

    $("#cmtList").on("click", ".cmt_del", function (e) {
        var comment_idx = $(e.target).data('cmt-idx');
        var board_idx = $("#board_idx").val();

        $.ajax({
            url: '/center/ajax/boardTipControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'boardTipCmtDelete',
                comment_idx: comment_idx
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    boardTipCmtLoad(board_idx);
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

function setFiles(files, editor) {
    var maxSize = 2 * 1024 * 1024; // 2MB
    var filesLengh = files.length;

    for (var i = 0; i < filesLengh; i++) {
        var file = files[i];
        if (file.size > maxSize) {
            alert("이미지는 최대 1MB 까지 등록 가능합니다.");
            return false;
        }

        if (file.type.match('image.*')) {
            var reader = new FileReader();
            reader.onload = function () {
                var result = reader.result;
                $(editor).summernote('pasteHTML', "<img src='" + result + "' />");
            };
            reader.readAsDataURL(file);
        }
    }
}

function boardTipLoad() {
    $.ajax({
        url: '/center/ajax/boardTipControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'boardTipLoad'
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#boardTipTable').DataTable().destroy();
                $('#boardTipList').html(result.data.notice + result.data.board);
        
                $('#boardTipTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    lengthChange: false,
                    displayLength: 20,
                    info: false,
                    ordering: false,
                    createdRow: function () {
                        $("th").addClass('text-center align-middle');
                    },
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });

            } else {
                $('#boardTipTable').DataTable().destroy();
                $("#boardTipList").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function boardTipSelect(e) {
    var board_idx = $(e.target).parents('tr').data('board-idx');
    $("#writer_no").val('');
    $("#file_name").val('');
    $("#file_path").val('');

    $.ajax({
        url: '/center/ajax/boardTipControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'boardTipSelect',
            board_idx: board_idx
        },
        success: function (result) {
            if (result.success) {
                $("#board_idx").val(board_idx);
                $("#writer_no").val(result.data.writer_no);
                $("#file_name").val(result.data.file_name);
                $("#file_path").val(result.data.file_path);
                $("#txtViewTitle").val(result.data.title);
                $("#viewContents").html(result.data.contents);
                $("#file_link").html(result.data.file_link);

                if(userInfo.user_no == result.data.writer_no){
                    $("#btnUpdateLessonTip").show();
                    $("#btnDeleteLessonTip").show();
                }else{
                    $("#btnUpdateLessonTip").hide();
                    $("#btnDeleteLessonTip").hide();
                }

                boardTipCmtLoad(board_idx);
                $('#BoardViewModal').modal('show');
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

function boardTipUpateLoad() {
    var board_idx = $("#board_idx").val();

    $.ajax({
        url: '/center/ajax/boardTipControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'boardTipSelect',
            board_idx: board_idx
        },
        success: function (result) {
            if (result.success) {
                $("#txtTitle").val(result.data.title);
                $('#summernote').summernote('code', result.data.contents);

                if(result.data.board_kind){
                    $("#selKind option[value=" + result.data.board_kind + "]").prop("selected", true);
                }else{
                    $("#selKind option[value='']").prop("selected", true);
                }

                if(result.data.notice_yn){
                    $("#chkNotice").prop("checked", true);
                }else{
                    $("#chkNotice").prop("checked", false);
                }

                if (result.data.file_name) {
                    $("#exfile").removeClass("d-none");
                    $("#exfile").attr("href", "/files/tip_file/" + result.data.file_name);
                    $("#exfile").html("<i class=\"fa-solid fa-file-arrow-down\"></i>" + result.data.origin_name);
                    $("#file_name").val(result.data.file_name);
                    $("#imgdel").removeClass("d-none");
                } else {
                    $("#exfile").addClass("d-none");
                    $("#exfile").attr("href", "");
                    $("#file_name").val('');
                    $("#imgdel").addClass("d-none");
                }

                if (result.data.file_path) {
                    $("#file_path").val(result.data.file_path);
                } else {
                    $("#file_path").val('');
                }
                $('#summernote').summernote('fontName', '맑은 고딕');
                $('#BoardModal').modal('show');
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

function boardTipCmtLoad(board_idx) {
    $.ajax({
        url: '/center/ajax/boardTipControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'boardTipCmtLoad',
            board_idx: board_idx,
            writer_no: $("#writer_no").val()
        },
        success: function (result) {
            if (result.success) {
                $("#cmtList").html(result.data.cmt);
                $("#btnLikes").children('span').text(result.data.cnt);
            } else {
                $("#cmtList").empty();
                $("#btnLikes").children('span').text('0');
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}
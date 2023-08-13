$(document).ready(function () {
    loadBoard();

    $('#summernote').summernote({
        lang: 'ko-KR',
        height: 500, // 에디터 높이
        minHeight: 500, // 최소 높이
        maxHeight: null,
        image: {
            maximumImageFileSize: 2 * 1024 * 1024,
        },
        toolbar: [
            ['font', ['fontname','fontsize','fontsizeunit']],
            ['style', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['color', ['forecolor', 'color']],
            ['table', ['table']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['picture', 'link', 'video']],
            ['view', ['codeview', 'help']]
        ],
        fontNames: ['맑은 고딕', '궁서', '굴림체', '굴림', '돋움체', '바탕체', 'Arial', 'sans-serif'],
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

    $("#dataTable").on("click", ".tc", function (e) {
        boardSelect(e);
    });

    $("#btnAdd").click(function () {
        $("#board_idx").val('');
        $("#file_path").val('');
        $("#btnDelete").addClass("d-none");
        $("#boardForm")[0].reset();
        $('#summernote').summernote('reset');
        
        $('#BoardModal').modal('show');
        $("#exfile").addClass('d-none');
        $("#exfile").attr("href", "");
        $("#imgdel").addClass("d-none");
    });

    $("#imgdel").click(function () {
        deleteImage();
    });

    $("#btnSave").click(function () {
        var title = $("#txtTitle").val();
        var contents = $('#summernote').summernote('code');
        var board_idx = $("#board_idx").val();
        var file_name = $("#file_name").val();
        var file_path = $("#file_path").val();
        var franchiseType = $("#selOpenFranchiseType").val();
        var targetType = $("#selOpenTargetType").val();
        var action = '';

        if (!title || !title.trim()) {
            alert('제목을 입력하세요.');
            $("#txtTitle").focus();
            return false;
        }

        if (!contents || !contents.trim()) {
            alert('내용을 입력하세요.');
            return false;
        }

        var file_board_arr = new Array();

        var sommernoteWriteArea = document.getElementsByClassName("note-editable")[0];
        var imgsTags = Array.from(sommernoteWriteArea.getElementsByTagName('img'));

        imgsTags.forEach(img => {
            file_board_arr.push(img.src);
        });

        datas = new FormData();

        datas.append('attachFiles', $('#fileAttach')[0].files[0]);
        datas.append('title', title);
        datas.append('contents', contents);
        datas.append('franchiseType', franchiseType);
        datas.append('targetType', targetType);

        if (!board_idx) {
            action = 'boardInsert';
        } else {
            action = 'boardUpdate';
            datas.append('board_idx', board_idx);
            datas.append('file_path', file_path);

            if (file_name != '') {
                datas.append('file_name', file_name);
            }
        }

        if (file_board_arr.length > 0) {
            for (var i = 0; i < file_board_arr.length; i++) {
                datas.append('files[]', file_board_arr[i]);
            }
        }

        datas.append('action', action);

        $.ajax({
            url: '/adm/ajax/boardControll.ajax.php',
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
                } else {
                    alert(result.msg);
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    });

    $("#btnDelete").click(function () {
        var board_idx = $("#board_idx").val();
        var file_name = $("#file_name").val();
        var file_path = $("#file_path").val();

        if (confirm('게시글을 삭제하시겠습니까?')) {
            $.ajax({
                url: '/adm/ajax/boardControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'deleteBoard',
                    board_idx: board_idx,
                    file_name: file_name,
                    file_path: file_path,
                    board_type: '1'
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        location.reload();
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

    $('#fileAttach').change(function () {
        var fileNm = document.getElementById('fileAttach');
        $('#txtImageFileName').val(fileNm.files[0].name);
    });

    $('#btnImageUpload').click(function () {
        $('#fileAttach').trigger('click');
    });
});

function setFiles(files, editor) {
    var maxSize = 2 * 1024 * 1024; // 2MB
    var filesLengh = files.length;

    for (var i = 0; i < filesLengh; i++) {
        var file = files[i];

        if (file.size > maxSize) {
            alert("이미지는 최대 2MB 까지 등록 가능합니다.");
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

function loadBoard() {
    $.ajax({
        url: '/adm/ajax/boardControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        
        data: {
            action: 'loadBoard'
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
                        data: 'open_franchise'
                    },
                    {
                        data: 'open_target'
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'reg_date'
                    },
                    {
                        data: 'file_link'
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    order: [[0, 'desc']],
                    createdRow: function (row, data) {
                        $(row).attr('data-board-idx', data.board_idx);
                        $("td:eq(0)", row).addClass('tc text-center align-middle');
                        $("td:eq(1)", row).addClass('tc text-center align-middle');
                        $("td:eq(2)", row).addClass('tc text-center align-middle');
                        $("td:eq(3)", row).addClass('tc text-start align-middle');
                        $("td:eq(4)", row).addClass('tc text-center align-middle');
                        $("td:eq(5)", row).addClass('text-center align-middle');
                        $("th").addClass('text-center align-middle');
                    },
                    language: {
                        url: "/json/ko_kr.json",
                    }
                });
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function boardSelect(e) {
    var board_idx = $(e.target).parents('tr').data('board-idx');

    $.ajax({
        url: '/adm/ajax/boardControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'boardSelect',
            board_idx: board_idx
        },
        success: function (result) {
            setForm(result.data);
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function setForm(data) {
    $("#board_idx").val(data.board_idx);
    $("#txtTitle").val(data.title);
    $("#selOpenFranchiseType option[value='" + data.open_franchise + "']").prop("selected", true);
    $("#selOpenTargetType option[value='" + data.open_target + "']").prop("selected", true);
    
    $('#summernote').summernote('code', data.contents);
    $("#btnDelete").removeClass("d-none");

    if (data.files) {
        $("#exfile").removeClass("d-none");
        $("#exfile").attr("href", "/files/notice_file/" + data.files);
        $("#exfile").html("<i class=\"fa-solid fa-file-arrow-down\"></i>" + data.origin_file);
        $("#imgdel").removeClass("d-none");
        $("#file_name").val(data.files);
    } else {
        $("#exfile").addClass("d-none");
        $("#exfile").attr("href", "");
        $("#imgdel").addClass("d-none");
        $("#file_name").val('');
    }

    if (data.file_path) {
        $("#file_path").val(data.file_path);
    } else {
        $("#file_path").val('');
    }


    $('#summernote').summernote('fontName', '맑은 고딕');
    $('#BoardModal').modal('show');
}

function deleteImage() {
    var board_idx = $("#board_idx").val();
    var file_name = $("#file_name").val();

    if (confirm('첨부파일을 삭제하시겠습니까?')) {
        $.ajax({
            url: '/adm/ajax/boardControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'deleteImage',
                board_idx: board_idx,
                file_name: file_name,
                board_type: '1'
            },
            success: function (result) {
                alert(result.msg);
                loadBoard();
                $("#exfile").addClass("d-none");
                $("#exfile").attr("href", "");
                $("#imgdel").addClass("d-none");
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    }
}
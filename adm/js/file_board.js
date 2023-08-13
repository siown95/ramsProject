$(document).ready(function () {
    loadFileBoard();

    $("#dataTable").on("click", ".tc", function (e) {
        boardSelect(e);
    });

    $("#btnAdd").click(function () {
        $("#board_idx").val('');
        $("#btnDelete").addClass("d-none");
        $("#boardForm")[0].reset();
        $('#BoardModal').modal('show');
        $("#exfile").addClass('d-none');
        $("#exfile").attr("href", "");
        $("#imgdel").addClass("d-none");
        $("#selKind2").html("<option value=\"\">선택</option>");
    });

    $("#imgdel").click(function () {
        deleteImage();
    });

    $("#btnSave").click(function () {
        var title = $("#txtTitle").val();
        var category1 = $("#selKind").val();
        var category2 = $("#selKind2").val();
        var contents = $("#txtContents").val();
        var board_idx = $("#board_idx").val();
        var file_name = $("#file_name").val();
        var action = '';

        if(!title || !title.trim()){
            alert('제목을 입력하세요.');
            $("#txtTitle").focus();
            return false;
        }

        if(!category1){
            alert('분류를 지정해주세요.');
            return false;
        }

        datas = new FormData();
        datas.append('files', $('#fileAttach')[0].files[0]);
        datas.append('title', title);
        datas.append('category1', category1);
        datas.append('category2', category2);
        datas.append('contents', contents);

        if (!board_idx) {
            action = 'fileBoardInsert';
        } else {
            action = 'fileBoardUpdate';
            datas.append('board_idx', board_idx);

            if(file_name != ''){
                datas.append('file_name', file_name);
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
                if(result.success){
                    alert(result.msg);
                    location.reload();
                }else{
                    alert(result.msg);
                    return false;
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    });

    $("#btnDelete").click(function(){
        var board_idx = $("#board_idx").val();
        var file_name = $("#file_name").val();

        if(confirm('게시글을 삭제하시겠습니까?')){
            $.ajax({
                url: '/adm/ajax/boardControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action : 'deleteBoard',
                    board_idx : board_idx,
                    file_name : file_name,
                    board_type : '2'
                },
                success: function (result) {
                    alert(result.msg);
                    location.reload();
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

    $("#selKind").on("change", function () {
        var category1 = $(this).val();
        category_select(category1);
    });

});

function category_select(category1) {
    $.ajax({
        url: '/adm/ajax/boardCategory.php',
        dataType: 'JSON',
        type: 'POST',
        
        data: {
            category1: category1
        },
        success: function (result) {
            $("#selKind2").html(result.data);
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function loadFileBoard() {
    $.ajax({
        url: '/adm/ajax/boardControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        
        data: {
            action: 'loadFileBoard'
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
                        data: 'code_name1'
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
                        $("td:eq(2)", row).addClass('tc text-start align-middle');
                        $("td:eq(3)", row).addClass('tc text-center align-middle');
                        $("td:eq(4)", row).addClass('text-center align-middle');
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
    $("#selKind option[value=" + data.category1 + "]").prop("selected", true);
    category_select(data.category1);
    if(data.category2){
        $("#selKind2 option[value=" + data.category2 + "]").prop("selected", true);
    }
    $("#txtContents").val(data.contents);

    $("#btnDelete").removeClass("d-none");

    if (data.files) {
        $("#exfile").removeClass("d-none");
        $("#exfile").attr("href", "/files/board_file/" + data.files);
        $("#exfile").html("<i class=\"fa-solid fa-file-arrow-down\"></i>" + data.origin_file);
        $("#imgdel").removeClass("d-none");
        $("#file_name").val(data.files);
    } else {
        $("#exfile").addClass("d-none");
        $("#exfile").attr("href", "");
        $("#imgdel").addClass("d-none");
        $("#file_name").val('');
    }

    $('#BoardModal').modal('show');
}

function deleteImage() {
    var board_idx = $("#board_idx").val();
    var file_name = $("#file_name").val();

    if(confirm('첨부파일을 삭제하시겠습니까?')){
        $.ajax({
            url: '/adm/ajax/boardControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'deleteImage',
                board_idx: board_idx,
                file_name : file_name,
                board_type : '2'
            },
            success: function (result) {
                alert(result.msg);
                loadFileBoard();
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
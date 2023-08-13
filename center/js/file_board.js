$(document).ready(function () {
    loadFileBoard();

    $("#fileBoardTable").on("click", ".tc", function (e) {
        boardSelect(e);
    });
});

function loadFileBoard() {
    $.ajax({
        url: '/center/ajax/boardControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'loadFileBoard'
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#fileBoardTable').DataTable({
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
                        $("td:eq(0)", row).addClass('tc text-center align-middle');
                        $("td:eq(1)", row).addClass('tc text-center align-middle');
                        $("td:eq(2)", row).addClass('tc text-start align-middle');
                        $("td:eq(3)", row).addClass('tc text-center align-middle');
                        $("td:eq(4)", row).addClass('text-center align-middle');
                        $("th").addClass('text-center align-middle');
                        $(row).attr('data-board-idx', data.board_idx);
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

function boardSelect(e) {
    var board_idx = $(e.target).parents('tr').data('board-idx');

    $.ajax({
        url: '/center/ajax/boardControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'fileBoardSelect',
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
    $("#fileBoardTarget").val(data.board_idx);
    $("#txtFileBoardTitle").val(data.title);
    
    $("#txtFileBoardKind1").val(data.category1);

    if(data.category2){
        $("#divFileBoardKind2").show();
        $("#txtFileBoardKind2").val(data.category2);
    }else{
        $("#divFileBoardKind2").hide();
        $("#txtFileBoardKind2").val('');
    }

    $("#txtFileBoardContents").val(data.contents);

    if (data.files) {
        $("#fileBoardFile").removeClass("d-none");
        $("#fileBoardFile").attr("href", "/files/board_file/" + data.files);
        $("#fileBoardFile").html("<i class=\"fa-solid fa-file-arrow-down\"></i>" + data.origin_file);
    } else {
        $("#fileBoardFile").addClass("d-none");
        $("#fileBoardFile").attr("href", "");
    }

    $('#fileBoardModal').modal('show');
}
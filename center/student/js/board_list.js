$(document).ready(function(){
    loadBoard();

    $("#dataTable").on("click", ".tc", function (e) {
        boardSelect(e);
    });
});

function loadBoard() {
    $.ajax({
        url: '/center/student/ajax/boardControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
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
                        $("td:eq(1)", row).addClass('tc text-start align-middle');
                        $("td:eq(2)", row).addClass('tc text-center align-middle');
                        $("td:eq(3)", row).addClass('text-center align-middle');
                        $(row).attr('data-board-idx', data.board_idx);
                    },
                    columnDefs: [{
                        targets: [0, 1, 2, 3],
                        className: 'dt-head-center'
                    }],
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
        url: '/center/student/ajax/boardControll.ajax.php',
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
    $("#txtContents").html(data.contents);
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

    $('#BoardModal').modal('show');
}
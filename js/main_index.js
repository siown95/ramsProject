$(document).ready(function () {
    $("#mainNoticeTable > tbody").on("click", "tr", function () {
        var target = $(this).data("board_idx");
        if (!target) {
            alert("잘못된 접근입니다.");
            return false;
        }
        getNoticeContent(target);
    });

    $("#mainNoticeTable2 > tbody").on("click", "tr", function () {
        var target = $(this).data("board_idx");
        if (!target) {
            alert("잘못된 접근입니다.");
            return false;
        }
        getNoticeContent(target);
    });
});

function getNoticeContent(board_idx) {
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

    $('#mainNoticeViewModal').modal('show');
}
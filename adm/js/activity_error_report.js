$(document).ready(function () {
    loadActivityErrorList();

    $("#errorList").on("click", ".tc", function (e) {
        activityErrorSelect(e);
    });

    $("#btnSave").click(function () {
        var error_idx =  $("#error_idx").val();
        var state = $("#selState").val();
        var comments = $("#error_comments").val();

        if(!error_idx){
            alert('잘못된 접근입니다.');
            return false;
        }

        $.ajax({
            url: '/adm/ajax/activityErrorReport.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'activityErrorComments',
                error_idx: error_idx,
                state: state,
                comments: comments
            },
            success: function (result) {
                if(result.success && result.data){
                    alert(result.msg);
                    location.reload();
                    return false;
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
        var error_idx =  $("#error_idx").val();
        var file_name = $("#file_name").val();

        if(confirm("활동지 오류신고 내역을 삭제하시겠습니까?")){
            $.ajax({
                url: '/adm/ajax/activityErrorReport.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'activityErrorDelete',
                    error_idx: error_idx,
                    file_name: file_name
                },
                success: function (result) {
                    if(result.success && result.data){
                        alert(result.msg);
                        location.reload();
                        return false;
                    }else{
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
        url: '/adm/ajax/activityErrorReport.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        
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
                        className: 'text-start'
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
                        $(row).addClass('tc text-center align-middle');
                        $(row).attr('data-error-idx', data.error_report_idx);
                        $("th").addClass('text-center align-middle');
                    },
                    displayLength: 15, 
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

function activityErrorSelect(e){
    var error_idx = $(e.target).parents('tr').data('error-idx');

    $.ajax({
        url: '/adm/ajax/activityErrorReport.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'activityErrorSelect',
            error_idx: error_idx
        },
        success: function (result) {
            if(result.success && result.data){
                $("#error_idx").val(error_idx);
                setForm(result.data);
            }else{
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
    $("#error_title").text(data.title);    
    $("#error_contents").val(data.contents);
    $("#writer").text(data.writer);
    $("#write_date").text(data.reg_date);

    if (data.file_name) {
        $("#exfile").removeClass("d-none");
        $("#exfile").attr("href", "/files/activity_error/" + data.file_name);
        $("#exfile").html("<i class=\"fa-solid fa-file-arrow-down\"></i>" + data.origin_name);
        $("#file_name").val(data.file_name);
    } else {
        $("#exfile").addClass("d-none");
        $("#exfile").attr("href", "");
        $("#file_name").val('');

    }
    $("#error_comments").text(data.comments);
    $("#selState option[value=" + data.state + "]").prop("selected", true);
    $("#replyModal").modal('show');
}
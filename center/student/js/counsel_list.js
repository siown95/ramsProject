$(document).ready(function(){
    counselLoad();

    $("#counselList").on("click", ".tc", function (e) {
        counselSelect(e);
    });

    $("#btnCounselRequestSave").click(function(){
        var counsel_idx = $("#counsel_idx").val();
        var CounselRequest = $("#CounselRequest").val();

        if(!CounselRequest || !CounselRequest.trim){
            alert('요청사항을 입력하세요');
            return false;
        }

        $.ajax({
            url: '/center/student/ajax/counselControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'counselUpdate',
                counsel_idx: counsel_idx,
                counsel_request: CounselRequest
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
    });
});

function counselLoad() {
    $.ajax({
        url: '/center/student/ajax/counselControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'counselLoad',
            student_no: userInfo.user_no,
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#counselTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: '<"col-sm-12"f>t<"col-sm-12"p>',
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'teacher_name'
                    },
                    {
                        data: 'counsel_kind'
                    },
                    {
                        data: 'counsel_date'
                    }
                    ],
                    createdRow: function (row, data) {
                        $(row).addClass('text-center align-middle tc');
                        $(row).attr('data-counsel-idx', data.counsel_idx);
                        $("th").addClass('text-center align-middle');
                    },
                    lengthChange: false,
                    info: false,
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
            } else {
                $('#counselTable').DataTable().destroy();
                $("#counselList").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function counselSelect(e) {
    var targetClass = $(e.target).parents('.tc');

    if ($(e.target).parents('.bg-light').length) {
        targetClass.removeClass('bg-light');
        clearCounselForm();
    } else {
        $('.tc').removeClass('bg-light');
        targetClass.addClass('bg-light');

        var counsel_idx = targetClass.data('counsel-idx');

        $.ajax({
            url: '/center/student/ajax/counselControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'counselSelect',
                counsel_idx: counsel_idx
            },
            success: function (result) {
                if (result.success) {
                    setCounselData(result.data);
                    $("#counsel_idx").val(counsel_idx);
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
}

function setCounselData(data) {
    $("#txtWriter").val(data.teacher_name);
    $("#txtCounselType").val(data.counsel_kind);
    $("#txtCounselDate").val(data.counsel_date);
    $("#CounselContents1").val(data.counsel_contents);
    $("#CounselContents2").val(data.counsel_followup);
    $("#CounselRequest").val(data.counsel_request);
    $("#btnCounselRequestSave").show();
}

function clearCounselForm() {
    $("#txtWriter").val('');
    $("#txtCounselType").val('');
    $("#txtCounselDate").val('');
    $("#CounselContents1").val('');
    $("#CounselContents2").val('');
    $("#CounselRequest").val('');

    $("#counsel_idx").val('');
    $("#btnCounselRequestSave").hide();
}
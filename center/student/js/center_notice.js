$(document).ready(function(){
    centerNoticeLoad();

    //알림사항 확인
    $("#centerNoticeList").on("click", ".tc", function(e){        
        var notice_idx = $(e.target).parent('tr').data('notice-idx');

        $.ajax({
            url: '/center/student/ajax/boardCenterNoticeControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'centerNoticeSelect',
                notice_idx: notice_idx,
            },
            success: function (result) {
                if (result.success) {
                    $("#txtTitle").val(result.data.notice_title);
                    $("#txtContents").val(result.data.notice_contents);
                    $("#CenterNoticeModal").modal('show');
                    return false;
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    });
});

function centerNoticeLoad(){
    $.ajax({
        url: '/center/student/ajax/boardCenterNoticeControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'centerNoticeLoad',
            franchise_idx: center_idx,
            student_no: userInfo.user_no
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#centerNoticeTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'no'
                    },
                    {
                        data: 'notice_title',
                        className: 'text-start'
                    },
                    {
                        data: 'reg_date'
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    order: [[0, 'desc']],
                    createdRow: function (row, data) {
                        $("th").addClass("text-center align-middle");
                        $(row).addClass("text-center align-middle tc");
                        $(row).attr('data-notice-idx', data.notice_idx);
                    },
                    language: {
                        url: '/json/ko_kr.json'
                    }
                });
            } else {
                $('#centerNoticeTable').DataTable().destroy();
                $("#centerNoticeList").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}
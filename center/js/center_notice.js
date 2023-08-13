$(document).ready(function () {
    //센터 알림 목록
    centerNoticeLoad();

    $("input:radio[name=chkNoticeTarget]").click(function () {
        if ($('#chkTeacher').is(':checked') == true) {
            $("#div_selTeacher").show();
            $("#div_selStudent").hide();
        } else if ($('#chkStudent').is(':checked') == true) {
            $("#div_selTeacher").hide();
            $("#div_selStudent").show();
        } else {
            $("#div_selTeacher").hide();
            $("#div_selStudent").hide();
        }
    });

    //센터 알림사항 저장
    $("#btnNoticeSave").click(function () {
        var txtWriteTitle = $("#txtWriteTitle").val();
        var txtWriteContents = $("#txtWriteContents").val();
        var selTarget = '';
        var selTargetNo = '';

        if (!txtWriteTitle || !txtWriteTitle.trim()) {
            alert('제목을 입력하세요');
            return false;
        }

        if ($('#chkTeacher').is(':checked')) {
            selTargetNo = $("#selNoticeTeacher").val();
            if(!selTargetNo){
                alert("선생님을 지정하세요");
                return false;
            }else{
                selTarget = 't';
            }
        } else if ($('#chkStudent').is(':checked')) {
            selTargetNo = $("#selNoticeStudent").val();
            if(!selTargetNo){
                alert("학생을 지정하세요");
                return false;
            }else{
                selTarget = 's';
            }
        } else {
            selTarget = 'a';
        }

        if (!txtWriteContents || !txtWriteContents.trim()) {
            alert('내용을 입력하세요');
            return false;
        }

        $.ajax({
            url: '/center/ajax/boardCenterNoticeControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'centerNoticeInsert',
                franchise_idx: center_idx,
                txtWriteTitle: txtWriteTitle,
                txtWriteContents: txtWriteContents,
                selTarget: selTarget,
                selTargetNo: selTargetNo,
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#CenterNoticeWriteModal").modal('hide');
                    $('#contents').load('center_notice_list.php');
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

    //알림사항 확인
    $("#centerNoticeList").on("click", ".tc", function (e) {
        $("#notice_idx").val('');
        $("#btnCenterNoticeEdit").show();
        $("#btnCenterNoticeEditSave").hide();
        $("#txtViewTitle").attr("disabled", true);
        $("#txtViewContents").attr("disabled", true);

        var notice_idx = $(e.target).parent('tr').data('notice-idx');
        var notice_target = $(e.target).parent('tr').data('notice-target');

        $.ajax({
            url: '/center/ajax/boardCenterNoticeControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'centerNoticeSelect',
                notice_idx: notice_idx,
                notice_target: notice_target,
            },
            success: function (result) {
                if (result.success) {
                    $("#notice_idx").val(notice_idx);
                    $("#txtViewTitle").val(result.data.notice_title);
                    $("#txtViewContents").val(result.data.notice_contents);

                    if(notice_target == 'a'){
                        $("#div_Target").hide();
                    }else{
                        $("#div_Target").show();
                        $("#txtTarget").val(result.data.target_name);
                    }

                    $("#CenterNoticeModal").modal('show');
                    return false;
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    });

    $("#btnCenterNoticeEdit").click(function () {
        $("#txtViewTitle").removeAttr("disabled");
        $("#txtViewContents").removeAttr("disabled");
        $("#btnCenterNoticeEdit").hide();
        $("#btnCenterNoticeEditSave").show();
    });

    //알림사항 수정
    $("#btnCenterNoticeEditSave").click(function () {
        var notice_idx = $("#notice_idx").val();
        var txtViewTitle = $("#txtViewTitle").val();
        var txtViewContents = $("#txtViewContents").val();

        if (!txtViewTitle || !txtViewTitle.trim()) {
            alert('제목을 입력하세요');
            return false;
        }

        if (!txtViewContents || !txtViewContents.trim()) {
            alert('내용을 입력하세요');
            return false;
        }

        $.ajax({
            url: '/center/ajax/boardCenterNoticeControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'centerNoticeUpdate',
                notice_idx: notice_idx,
                txtViewTitle: txtViewTitle,
                txtViewContents: txtViewContents
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#CenterNoticeModal").modal('hide');
                    $('#contents').load('center_notice_list.php');
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

    //알림사항 삭제
    $("#btnCenterNoticeDelete").click(function () {
        if (confirm("알림사항을 삭제하시겠습니까?")) {
            var notice_idx = $("#notice_idx").val();

            $.ajax({
                url: '/center/ajax/boardCenterNoticeControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'centerNoticeDelete',
                    notice_idx: notice_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#CenterNoticeModal").modal('hide');
                        $('#contents').load('center_notice_list.php');
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
});

function centerNoticeLoad() {
    $.ajax({
        url: '/center/ajax/boardCenterNoticeControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'centerNoticeLoad',
            franchise_idx: center_idx,
            teacher_no: userInfo.user_no,
            is_admin: userInfo.is_admin,
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#centerNoticeTable').DataTable().destroy();
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
                        data: 'notice_text'
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
                        $(row).attr('data-notice-target', data.notice_target);
                    },
                    language: {
                        url: "/json/ko_kr.json",
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
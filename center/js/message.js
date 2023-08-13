$(document).ready(function () {
    getSaveMsgList();
    $(".searchdiv").hide();

    $('#txtMsg').on('propertychange change keyup paste input', function () {
        var val = $('#txtMsg').val();
        var fileNm = $("#AttachImageFile").val();
        var len = byteCheck(val);
        if (len < 80) {
            $('#lblMsg').val("SMS  |  " + len + " Bytes");
        } else if (fileNm.length > 0) {
            $('#lblMsg').val("MMS  |  " + len + " Bytes");
        } else {
            $('#lblMsg').val("LMS  |  " + len + " Bytes");
        }
    });

    $("#btnMsgListMove").click(function () {
        msg_arr.forEach((element, index) => {
            $(".direct-add").append(`<tr class="align-middle text-center">
            <td>${msg_arr[index][0]}</td>
            <td>${msg_arr[index][1]}</td>
            <td><button type="button" class="btn btn-sm btn-outline-danger del"><i class="fa-solid fa-trash me-1"></i>삭제</button></td>
            </tr>`);
        });
        msg_arr = [];
    });

    $('#btnDirectAdd').click(function () {
        var val1 = $('#txtSendMsgName').val();
        var val2 = $('#txtSendMsgHp').val();
        if (val1 == '' || val1 == null || val1 == 'undefined') {
            alert('이름을 입력해주세요.');
            return false;
        } else if (val2 == '' || val2 == null || val2 == 'undefined') {
            alert('전화번호를 입력해주세요.');
            return false;
        } else {
            var str = '<tr class="align-middle text-center"><td>' + val1 + '</td><td>' + val2 + '</td><td><button type="button" id="btnDelList" class="btn btn-sm btn-outline-danger del"><i class="fa-solid fa-trash me-1"></i>삭제</button></td></tr>';
            $('.direct-add').append(str);
            $('#txtSendMsgName').val('');
            $('#txtSendMsgHp').val('');
        }
    });

    $("#btnMsgSave").click(function () {
        var msgTitle = $("#txtMsgTitle").val();
        var msgContents = $("#txtMsg").val();

        if (!msgTitle) {
            alert("제목을 입력해주세요.");
            return false;
        }
        if (!msgContents) {
            alert("내용을 입력해주세요.");
            return false;
        }

        if (confirm("메시지를 저장하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/saveMessageControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'saveMsgInsert',
                    center_idx: center_idx,
                    user_idx: user_idx,
                    msgtitle: msgTitle,
                    msgcontents: msgContents
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        getSaveMsgList();
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

    $("#btnSaveMsgDelete").click(function () {
        var msg_idx = $("#selSaveMsg").val();

        if (!msg_idx) {
            return false;
        }
        if (confirm("선택하신 저장 메시지를 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/saveMessageControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'saveMsgDelete',
                    msg_idx: msg_idx,
                    center_idx: center_idx,
                    user_idx: user_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#txtMsgTitle").val('');
                        $("#txtMsg").val('');
                        getSaveMsgList();
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

    $("#selKind").change(function () {
        var val = $("#selKind option:selected").val();
        if (val == '1') {
            $(".searchdiv").hide();
            $("#divGrade").show();
            $("#divTeacher").show();
        } else if (val == '2') {
            $(".searchdiv").hide();
        } else if (val == '3') {
            $(".searchdiv").hide();
            $("#divGroup").show();
        } else if (val == '4') {
            $(".searchdiv").hide();
            $("#divGrade").show();
            $("#divTeacher").show();
            $("#divLesson").show();
        } else if (val == '5') {
            $(".searchdiv").hide();
            $("#divRest").show();
        } else if (val == '6') {
            $(".searchdiv").hide();
            $("#divOut").show();
        } else if (val == '7') {
            $(".searchdiv").hide();
            $("#divNewCounsel").show();
        } else if (val == '8') {
            $(".searchdiv").hide();
            $("#divCounsel").show();
        } else {
            $(".searchdiv").hide();
        }
    });

    $("#smsList").on("click", ".del", function (e) {
        $(e.target).parents('tr').remove();
    });

    $("#selSaveMsg").change(function () {
        var msg_idx = $("#selSaveMsg").val();

        if (!msg_idx) {
            return false;
        }
        SaveMsgSelect(msg_idx);
    });

    $("#btnSearchMsgList").click(function () {
        var val = $("#selKind").val();
        var targetUrl = '';
        var datas = new FormData();
        datas.append("action", "msgInfoSelect");
        datas.append("center_idx", center_idx);
        if (val == '1') { // 학생
            targetUrl = '/center/ajax/studentControll.ajax.php';
            datas.append("teacher_idx", $("#selInCharge option:selected").val());
            datas.append("grade", $("#selMsgGrade").val());
            datas.append("state", "00");
        } else if (val == '2') { // 직원
            targetUrl = '/center/ajax/employeeControll.ajax.php';
            datas.append("state", "00");
        } else if (val == '3') { // 그룹
            if (!$("#selGroup").val()) {
                alert("그룹을 선택해주세요.");
                return false;
            }
            targetUrl = '/center/ajax/msgGroupControll.ajax.php';
            datas.append("center_idx", center_idx);
            datas.append("user_idx", user_idx);
            datas.append("group_idx", $("#selGroup").val());
        } else if (val == '4') { // 수업
            targetUrl = '';
        } else if (val == '5') { // 휴원
            targetUrl = '/center/ajax/studentControll.ajax.php';
            datas.append("teacher_idx", $("#selInCharge").val());
            datas.append("grade", $("#selMsgGrade").val());
            datas.append("months", $("#dtRestMonth").val());
            datas.append("state", "01");
        } else if (val == '6') { // 퇴원
            targetUrl = '/center/ajax/studentControll.ajax.php';
            datas.append("teacher_idx", $("#selInCharge").val());
            datas.append("grade", $("#selMsgGrade").val());
            datas.append("months", $("#dtOutMonth").val());
            datas.append("state", "02");
        } else if (val == '7') { // 신규
            targetUrl = '/center/ajax/counselControll.ajax.php';
            datas.append("months", $("#dtNewCounselMonth").val());
            datas.append("flag", 'n');
        } else if (val == '8') { // 정규
            targetUrl = '/center/ajax/counselControll.ajax.php';
            datas.append("months", $("#dtCounselMonth").val());
            datas.append("flag", 'o');
        } else {
            return false;
        }
        $.ajax({
            url: targetUrl,
            dataType: 'JSON',
            type: 'POST',
            data: datas,
            processData: false,
            contentType: false,
            success: function (result) {
                if (result.success && result.data) {
                    $('#MsgInfoTable').DataTable({
                        autoWidth: false,
                        destroy: true,
                        data: result.data,
                        stripeClasses: [],
                        dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                        columns: [{
                            data: 'chkmsg',
                            orderable: false
                        },
                        {
                            data: 'user_name'
                        },
                        {
                            data: 'user_phone'
                        }
                        ],
                        lengthChange: false,
                        info: false,
                        createdRow: function (row, data) {
                            $("th").addClass('text-center align-middle');
                            $("td:eq(0)", row).addClass('text-center align-middle');
                            $("td:eq(1)", row).addClass('text-center align-middle');
                            $("td:eq(2)", row).addClass('text-center align-middle');
                        },
                        displayLength: 15,
                        language: {
                            url: "/json/ko_kr.json",
                        }
                    });
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

    $("#MsgInfoTable").on('page.dt', function () {
        $('#chkAllCheck').prop('checked', false);
    });

    $('#MsgInfoTable').on('change', "#chkAllCheck", function () {
        if ($('#chkAllCheck').is(':checked') == true) {
            $('.chkMsg').prop('checked', true);
            $('.chkMsg').trigger("change");
        } else {
            $('.chkMsg').prop('checked', false);
            $('.chkMsg').trigger("change");
        }
    });

    $("#MsgInfoTable > tbody").on("change", ".chkMsg", function (e) {
        chkMsgArray(e);
    });

    $("#btnGroupAdd").click(function () {
        var grpnm = $("#txtMsgGroupName").val();
        if (!grpnm) {
            alert("저장할 그룹이름을 입력해주세요.");
            return false;
        }
        getMsgList();
        $.ajax({
            url: '/center/ajax/msgGroupControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'saveGrpInsert',
                center_idx: center_idx,
                user_idx: user_idx,
                group_name: grpnm,
                lists: smslist_arr
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
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

function chkMsgArray(e) {
    if ($(e.target).is(":checked") == true) {
        msg_arr.push([$(e.target).parents("tr").find("td:eq(1)").text(), $(e.target).parents("tr").find("td:eq(2)").text()]);
    } else {
        msg_arr = msg_arr.filter((element) => element[0] !== $(e.target).parents("tr").find("td:eq(1)").text() && element[1] !== $(e.target).parents("tr").find("td:eq(2)").text());
    }
}

function getSaveMsgList() {
    $.ajax({
        url: '/center/ajax/saveMessageControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'saveMsgLoad',
            center_idx: center_idx,
            user_idx: user_idx
        },
        success: function (result) {
            if (result.success) {
                $("#selSaveMsg").html(result.data.opt);
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

function getMsgList() {
    smslist_arr = [];
    $("#smsList tr").each(function () {
        if (!this.rowIndex) return false;
        smslist_arr.push([$(this).find("td").eq(0).text(), $(this).find("td").eq(1).text()]);
    });
}

function SaveMsgSelect(msg_idx) {
    $.ajax({
        url: '/center/ajax/saveMessageControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'saveMsgSelect',
            msg_idx: msg_idx,
            center_idx: center_idx,
            user_idx: user_idx
        },
        success: function (result) {
            if (result.success) {
                $("#txtMsgTitle").val(result.data.msg_title);
                $("#txtMsg").val(result.data.msg_contents);
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

function byteCheck(obj) {
    var text_val = obj;
    var text_len = text_val.length;
    var totBtyes = 0;

    for (var i = 0; i < text_len; i++) {
        var each_char = text_val.charAt(i);
        var uni_char = escape(each_char);
        if (uni_char.length > 4) {
            totBtyes += 2;
        } else {
            totBtyes += 1;
        }
    }
    return totBtyes;
}
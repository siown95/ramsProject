$(document).ready(function () {
    $(".searchdiv").hide();
    getGroupList();

    $("#selKind").change(function () {
        var val = $("#selKind option:selected").val();
        if (val == '1') {
            $(".searchdiv").hide();
            $("#divGrade").show();
            $("#divTeacher").show();
        } else if (val == '2') {
            $(".searchdiv").hide();
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

    $("#btnMsgListMove").click(function () {
        if (msg_arr.length < 1) {
            alert("그룹에 추가할 연락처를 선택해주세요.");
            return false;
        }

        var group_idx = $("#selMsgGroup").val();
        if (!group_idx) {
            alert("연락처를 추가할 그룹을 선택해주세요.");
            return false;
        }
        $.ajax({
            url: '/center/ajax/msgGroupControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'msgGroupListInsert',
                group_idx: group_idx,
                lists: msg_arr
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    msg_arr = [];
                    $("#btnSearchMsgList").trigger("click");
                    getGroupDetailList(group_idx);
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

    $("#MsgGroupTable").on('page.dt', function () {
        $('#chkAllMsgGrp').prop('checked', false);
    });

    $('#MsgGroupTable').on('change', "#chkAllMsgGrp", function () {
        if ($('#chkAllMsgGrp').is(':checked') == true) {
            $('.chkgrp').prop('checked', true);
            $('.chkgrp').trigger("change");
        } else {
            $('.chkgrp').prop('checked', false);
            $('.chkgrp').trigger("change");
        }
    });

    $("#MsgGroupTable").on("click", ".mtc", function (e) {
        var group_idx = $(e.target).parents("tr").data("group-idx");
        getGroupDetailList(group_idx);
    });

    $('#MsgGroupTable').on('click', ".btngrpdel", function (e) {
        var group_idx = $(e.target).parents("tr").data("group-idx");
        if (!group_idx) {
            alert("삭제하려는 그룹을 선택해주세요.");
            return false;
        }
        if (confirm("선택하신 그룹을 삭제하시겠습니까?\n그룹을 삭제할 경우 그룹에 등록된 연락처도 같이 삭제됩니다.")) {
            $.ajax({
                url: '/center/ajax/msgGroupControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'msgGroupDeleteOne',
                    center_idx: center_idx,
                    user_idx: user_idx,
                    group_idx: group_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        getGroupList();
                        getGroupDetailList(group_idx);
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

    $("#MsgGroupTable").on("change", ".chkgrp", function (e) {
        chkGrpArray(e);
    });

    $("#MsgGroupDetailTable").on('page.dt', function () {
        $('#chkAllMsgGrpDetail').prop('checked', false);
    });

    $('#MsgGroupDetailTable').on('change', "#chkAllMsgGrpDetail", function () {
        if ($('#chkAllMsgGrpDetail').is(':checked') == true) {
            $('.chkgrpd').prop('checked', true);
            $('.chkgrpd').trigger("change");
        } else {
            $('.chkgrpd').prop('checked', false);
            $('.chkgrpd').trigger("change");
        }
    });

    $('#MsgGroupDetailTable').on('click', ".btngrpddel", function (e) {
        var group_idx = $(e.target).parents("tr").data("group-idx");
        var group_reg_idx = $(e.target).parents("tr").data("group-reg-idx");
        if (!group_reg_idx) {
            alert("삭제하려는 항목을 선택해주세요.");
            return false;
        }
        if (confirm("선택하신 항목을 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/msgGroupControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'msgGroupDetailDeleteOne',
                    center_idx: center_idx,
                    user_idx: user_idx,
                    group_reg_idx: group_reg_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        getGroupDetailList(group_idx);
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

    $("#MsgGroupDetailTable").on("change", ".chkgrpd", function (e) {
        chkGrpDetailArray(e);
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

    $("#MsgInfoTable").on("change", ".chkMsg", function (e) {
        chkMsgArray(e);
    });

    $("#btnMsgGroupSave").click(function () {
        var grpname = $("#txtMgGrpName").val();
        if (!grpname || !grpname.trim()) {
            alert("그룹 이름을 입력해주세요.");
            return false;
        }
        $.ajax({
            url: '/center/ajax/msgGroupControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                action: 'msgGroupInsert',
                center_idx: center_idx,
                user_idx: user_idx,
                group_name: grpname
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    getGroupList();
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
    $("#btnMsgGroupSelDelete").click(function () {
        if (grp_arr.length < 1) {
            alert("삭제할 그룹을 선택해주세요.");
            return false;
        }
        if (confirm("선택하신 그룹을 삭제하시겠습니까?\n그룹을 삭제할 경우 그룹에 등록된 연락처도 모두 삭제됩니다.")) {
            $.ajax({
                url: '/center/ajax/msgGroupControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'msgGroupListDelete',
                    center_idx: center_idx,
                    user_idx: user_idx,
                    lists: grp_arr
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        getGroupList();
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
    $("#btnMsgGroupDSelDelete").click(function () {
        if (grpd_arr.length < 1) {
            alert("삭제할 연락처를 선택해주세요.");
            return false;
        }
        if (confirm("선택하신 연락처를 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/msgGroupControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'msgGroupDetailListDelete',
                    center_idx: center_idx,
                    user_idx: user_idx,
                    lists: grpd_arr
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        getGroupDetailList(grp_idx);
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

    $("#btnMsgGroupShare").click(function () {
        var target_group_idx = $("#selTargetGroup").val();
        var target_teacher_idx = $("#selTargetTeacher").val();
        if (!target_group_idx) {
            alert("공유할 그룹을 선택해주세요.");
            return false;
        }
        if (!target_teacher_idx) {
            alert("그룹을 공유할 대상을 선택해주세요.");
            return false;
        }
        if (confirm("선택하신 그룹 '" + $("#selTargetGroup option:selected").text() + "'을 " + $("#selTargetTeacher option:selected").text() + " 님에게 공유하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/msgGroupControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'msgGroupShare',
                    center_idx: center_idx,
                    user_idx: user_idx,
                    target_group_idx: target_group_idx,
                    target_user_idx: target_teacher_idx
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
        }
    });
});

function chkGrpArray(e) {
    if ($(e.target).is(":checked") == true) {
        grp_arr.push($(e.target).parents("tr").data("group-idx"));
    } else {
        grp_arr = grp_arr.filter((element) => element !== $(e.target).parents("tr").data("group-idx"));
    }
}

function chkGrpDetailArray(e) {
    grp_idx = $(e.target).parents("tr").data("group-idx");
    if ($(e.target).is(":checked") == true) {
        grpd_arr.push($(e.target).parents("tr").data("group-reg-idx"));
    } else {
        grpd_arr = grpd_arr.filter((element) => element !== $(e.target).parents("tr").data("group-reg-idx"));
    }
}

function chkMsgArray(e) {
    if ($(e.target).is(":checked") == true) {
        msg_arr.push([$(e.target).parents("tr").find("td:eq(1)").text(), $(e.target).parents("tr").find("td:eq(2)").text()]);
    } else {
        msg_arr = msg_arr.filter((element) => element[0] !== $(e.target).parents("tr").find("td:eq(1)").text() && element[1] !== $(e.target).parents("tr").find("td:eq(2)").text());
    }
}

function getGroupList() {
    $.ajax({
        url: '/center/ajax/msgGroupControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'msgGroupLoad',
            center_idx: center_idx,
            user_idx: user_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#MsgGroupTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'chk_msggroup',
                        orderable: false
                    },
                    {
                        data: 'group_name'
                    },
                    {
                        data: 'del_msggroup',
                        orderable: false
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    createdRow: function (row, data) {
                        $("th").addClass('text-center align-middle');
                        $(row).attr('data-group-idx', data.group_idx);
                        $("td:eq(0)", row).addClass('text-center align-middle');
                        $("td:eq(1)", row).addClass('text-start align-middle mtc');
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
    $.ajax({
        url: '/center/ajax/msgGroupControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'selMsgGroupLoad',
            center_idx: center_idx,
            user_idx: user_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $("#selMsgGroup").html(result.data.opt);
                $("#selTargetGroup").html(result.data.opt);
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

function getGroupDetailList(group_idx) {
    $.ajax({
        url: '/center/ajax/msgGroupControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        data: {
            action: 'msgGroupSelect',
            center_idx: center_idx,
            user_idx: user_idx,
            group_idx: group_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $('#MsgGroupDetailTable').DataTable({
                    autoWidth: false,
                    destroy: true,
                    data: result.data,
                    stripeClasses: [],
                    dom: "<'col-sm-12'f>t<'col-sm-12'p>",
                    columns: [{
                        data: 'chk_msggrpd',
                        orderable: false
                    },
                    {
                        data: 'group_user_name'
                    },
                    {
                        data: 'group_user_hp'
                    },
                    {
                        data: 'del_msggrpd',
                        orderable: false
                    }
                    ],
                    lengthChange: false,
                    info: false,
                    createdRow: function (row, data) {
                        $("th").addClass('text-center align-middle');
                        $(row).attr('data-group-reg-idx', data.group_reg_idx);
                        $(row).attr('data-group-idx', data.group_idx);
                        $("td:eq(0)", row).addClass('text-center align-middle');
                        $("td:eq(1)", row).addClass('text-center align-middle');
                        $("td:eq(2)", row).addClass('text-center align-middle');
                        $("td:eq(3)", row).addClass('text-center align-middle');
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
}
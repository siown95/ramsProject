$(document).ready(function () {
    fnCheck();
    eduCenterSelect($("#selCenter2").val());
    eduCenterScheduleSelect($("#selCenter2").val());
    $("#eduList").on("click", ".educ", function (e) {
        eduInfoSelect(e);
    });

    $("#eduScheduleList").on("click", '.tc', function (e) {
        eduScheduleSelect(e);
    });

    $("#dtEdufromDate").on("change", function () {
        if ($(this).val()) {
            $('#dtEdutoDate').val($(this).val().substring(0, 4) + '-12-31');
        } else {
            $('#dtEdutoDate').val('');
        }
    });

    $("#btnClearEduInfo").click(function () {
        $("#eduScheduleForm")[0].reset();
        $("#eduEmployeeList").empty();
        $("#eduscheduleIdx").val('');
        $("#btnDeleteEduInfo").addClass("d-none");
        $("#btnSaveEduInfo").removeClass("d-none");
    });

    $("#btnSaveEduInfo").click(function () {
        var franchise_idx = $("#selCenter2").val();
        var edu_idx = $("#selEduInfo").val();
        var from_time = $("#dtEdufromDate").val();
        var to_time = $("#dtEdutoDate").val();

        var user_no_arr = new Array();

        $("input[name=chkNo]:checked").each(function () {
            user_no_arr.push($(this).val());
        });

        if (!franchise_idx) {
            alert('센터를 선택해주세요.');
            return false;
        }

        if (!edu_idx) {
            alert('교육을 선택해주세요.');
            return false;
        }

        if (!from_time) {
            alert('실시기간을 입력해주세요.');
            return false;
        }

        if (!to_time) {
            alert('종료기간을 입력해주세요.');
            return false;
        }

        if (user_no_arr.length < 1) {
            alert("교육 대상을 선택해주세요.");
            return false;
        }

        $.ajax({
            url: '/center/ajax/employeeEduControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'eduScheduleInsert',
                franchise_idx: franchise_idx,
                edu_idx: edu_idx,
                from_time: from_time,
                to_time: to_time,
                user_list: user_no_arr
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    $("#eduScheduleForm")[0].reset();
                    eduCenterSelect($("#selCenter2").val());
                    eduCenterScheduleSelect($("#selCenter2").val());
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

    $("#btnDeleteEduInfo").click(function () {
        var eduschedule_idx = $("#eduscheduleIdx").val();
        if (confirm('교육 일정을 삭제하시겠습니까?')) {
            $.ajax({
                url: '/center/ajax/employeeEduControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'eduScheduleDelete',
                    eduschedule_idx: eduschedule_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#eduScheduleForm")[0].reset();
                        eduCenterSelect($("#selCenter2").val());
                        eduCenterScheduleSelect($("#selCenter2").val());
                        $("#btnDeleteEduInfo").addClass("d-none");
                        $("#btnSaveEduInfo").removeClass("d-none");
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

    $("#eduEmployeeList").on("click", ".file-upload", function () {
        var _idx = $(this).parent("td").parent("tr").index();
        $("input[name=fileAttach]:eq(" + _idx + ")").trigger('click');
    });

    $("#eduEmployeeList").on("change", "[name=fileAttach]", function () {
        var _idx = $(this).parent("td").parent("tr").index();
        var file = $("input[name=fileAttach]")[_idx].files[0];
        var origin_file_name = $(this).parent("td").find(".file-upload").data('fn');

        if (file) {
            if (confirm("파일을 업로드하시겠습니까?")) {
                var employee_edu_idx = $(this).parents("tr").find("td:eq(0)").text();
                var eduschedule_idx = $(this).parent("td").parent("tr").data('eduschedule-idx');
                var franchise_idx = $(this).parent("td").parent("tr").data('franchise-idx');
                var user_no = $(this).parent("td").parent("tr").data('user-no');

                datas = new FormData();

                datas.append('action', 'certificatesUpload');
                datas.append("employee_edu_idx", employee_edu_idx);
                datas.append("eduschedule_idx", eduschedule_idx);
                datas.append("franchise_idx", franchise_idx);
                datas.append("user_no", user_no);
                datas.append("file_name", file);
                datas.append("origin_file_name", origin_file_name);

                $.ajax({
                    url: '/center/ajax/employeeEduControll.ajax.php',
                    contentType: 'multipart/form-data',
                    dataType: 'JSON',
                    type: 'POST',
                    mimeType: 'multipart/form-data',
                    data: datas,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        if (result.success) {
                            alert(result.msg);
                            $("#eduScheduleForm")[0].reset();
                            eduCenterSelect($("#selCenter2").val());
                            eduCenterScheduleSelect($("#selCenter2").val());
                            $("#btnDeleteEduInfo").addClass("d-none");
                            $("#btnSaveEduInfo").removeClass("d-none");
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
        } else {
            $("input[name=fileAttach]")[_idx].val('');
        }
    });
});

function fnCheck() {
    $("#eduEmployeeMember").on("click", "input:checkbox[name='chkNo']", function () {
        if ($('input:checkbox[name="chkNo"]').length == $('input:checkbox[name="chkNo"]:checked').length) {
            $('#chkAllCheck').prop('checked', true);
        } else {
            $('#chkAllCheck').prop('checked', false);
        }
    });

    $('#chkAllCheck').on('click', function () {
        if ($('#chkAllCheck').is(':checked') == true) {
            $('.chk').prop('checked', true);
        } else {
            $('.chk').prop('checked', false);
        }
    });
}

function eduInfoSelect(e) {
    var targetClass = $(e.target).parent('.educ');
    var edu_idx = targetClass.data('edu-idx');

    if ($(e.target).parent('.bg-light').length) {
        targetClass.removeClass('bg-light');

        $("#txtEduName").val('');
        $("#selEduType").prop("selectedIndex", 0);
        $("#txtEduTarget").val('');
        $("#txtEduWay").val('');
        $("#btnDelete1").addClass("d-none");
        $("#eduInfoidx").val('');
    } else {
        $('.educ').removeClass('bg-light');
        targetClass.addClass('bg-light');

        $.ajax({
            url: '/center/ajax/employeeEduControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'eduInfoSelect',
                edu_idx: edu_idx
            },
            success: function (result) {
                if (result.success && result.data) {
                    $("#txtEduName").val(result.data.edu_name);
                    $("#selEduType option[value=" + result.data.edu_type + "]").prop("selected", true);
                    $("#txtEduTarget").val(result.data.edu_target);
                    $("#txtEduWay").val(result.data.edu_way);
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    }
}

function eduCenterSelect(franchise_idx) {
    $.ajax({
        url: '/center/ajax/employeeEduControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'eduCenterSelect',
            franchise_idx: franchise_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $("#eduEmployeeMember").html(result.data.data);
            } else {
                $("#eduEmployeeMember").empty();
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function eduCenterScheduleSelect(franchise_idx) {
    $.ajax({
        url: '/center/ajax/employeeEduControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: 'eduCenterScheduleSelect',
            franchise_idx: franchise_idx
        },
        success: function (result) {
            if (result.success && result.data) {
                $("#eduScheduleList").html(result.data.data);
                $("#eduEmployeeList").empty();
            } else {
                $("#eduScheduleList").empty();
                $("#eduEmployeeList").empty();

            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}

function eduScheduleSelect(e) {
    var targetClass = $(e.target).parent('.tc');
    var eduschedule_idx = targetClass.data('edu-schedule-idx');

    if ($(e.target).parent('.bg-light').length) {
        targetClass.removeClass('bg-light');
        $("#btnSaveEduInfo").removeClass("d-none");
        $("#btnDeleteEduInfo").addClass("d-none");
        $('.chk').prop('checked', false);
        $('#chkAllCheck').prop('checked', false);
        $("#eduscheduleIdx").val('');
        $("#selEduInfo").prop("selectedIndex", 0);
        $("#dtEdufromDate").val('');
        $("#dtEdutoDate").val('');
        $("#eduEmployeeList").empty();
    } else {
        $('.tc').removeClass('bg-light');
        targetClass.addClass('bg-light');

        $.ajax({
            url: '/center/ajax/employeeEduControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'eduScheduleSelect',
                eduschedule_idx: eduschedule_idx
            },
            success: function (result) {
                if (result.success) {
                    $("#eduEmployeeList").html(result.data.table);
                    $("#selCenter2 option[value=" + result.data.data.franchise_idx + "]").prop("selected", true);
                    $("#selEduInfo option[value=" + result.data.data.edu_idx + "]").prop("selected", true);
                    $("#dtEdufromDate").val(result.data.data.edu_from_time);
                    $("#dtEdutoDate").val(result.data.data.edu_to_time);
                    $("#eduscheduleIdx").val(eduschedule_idx);
                    $("#btnSaveEduInfo").addClass("d-none");
                    $("#btnDeleteEduInfo").removeClass("d-none");

                    var user_arr = result.data.data.user_list.split(',');
                    $('.chk').prop('checked', false);
                    for (var i = 0; i < user_arr.length; i++) {
                        $('input:checkbox[name="chkNo"][value=' + user_arr[i] + ']').prop("checked", true);
                    }

                    if ($('input:checkbox[name="chkNo"]').length == $('input:checkbox[name="chkNo"]:checked').length) {
                        $('#chkAllCheck').prop('checked', true);
                    } else {
                        $('#chkAllCheck').prop('checked', false);
                    }
                } else {
                    alert(result.msg);
                    $("#eduEmployeeList").empty();
                    return false;
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    }
}
$(document).ready(function () {
    counselLoad();

    $("#counselTable").on('page.dt', function () {
        if ($("#chkCounsel").is(":checked")) {
            clearCounselForm();
        } else {
            clearCounselNewForm();
        }
        $("#counselTable > tbody > tr").removeClass('bg-light');
    });

    $('#txtParentTel').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $("#chkCounsel").change(function () {
        counselLoad();
    });

    $('#chkCounsel').click(function () {
        if ($('#chkCounsel').is(':checked') == true) {
            $('#lblCounsel').text('정규상담')
            $('#div_regular').show();
            $('#div_new').hide();
        } else {
            $('#lblCounsel').text('신규상담')
            $('#div_regular').hide();
            $('#div_new').show();
        }
    });

    $('#chkCounselOpen').click(function () {
        if ($('#chkCounselOpen').is(':checked') == true) {
            $('#lblCounselOpen').text('상담내용공개');
        } else {
            $('#lblCounselOpen').text('상담내용비공개');
        }
    });

    $('#selCounselKind').change(function () {
        if ($('#selCounselKind').val() == '9') {
            if ($('#selDischargeKind').val() == '06') {
                $('#div-Discharge2').show();
            } else {
                $('#div-Discharge2').hide();
            }
            $('#lblDischargeKind').show();
            $('#selDischargeKind').show();
        } else {
            $('#lblDischargeKind').hide();
            $('#selDischargeKind').hide();
            $('#div-Discharge2').hide();
        }
    });

    $('#selDischargeKind').change(function () {
        if ($('#selDischargeKind').val() == '06') {
            $('#div-Discharge2').show();
        } else {
            $('#div-Discharge2').hide();
        }
    });

    $("#btnStudentSearch").click(function () {
        $("#SearchStudentModal").modal('show');
    });

    $("#txtSearchStudentName").keypress(function (e) {
        if (e.keyCode === 13) {
            $("#studentSearch").trigger('click');
        }
    });

    $("#studentSearch").click(function () {
        var student_name = $("#txtSearchStudentName").val();

        if (!student_name || !student_name.trim()) {
            alert("학생이름을 입력하세요");
            return false;
        }

        if (student_name.length < 2) {
            alert("2글자 이상 입력하세요");
            return false;
        }

        $.ajax({
            url: '/center/ajax/counselControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'studentSearch',
                student_name: student_name,
                franchise_idx: center_idx
            },
            success: function (result) {
                if (result.success && result.data.table) {
                    $("#studentSearchList").html(result.data.table);
                    return false;
                } else {
                    alert(result.msg);
                    $("#studentSearchList").empty();
                    return false;
                }
            },
            error: function (request, status, error) {
                alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    });

    $("#studentSearchList").on("click", ".tc", function (e) {
        var student_no = $(e.target).parent('.tc').data("student-no");
        var student_name = $(e.target).parent('.tc').find("td:eq(0)").text();
        var student_grade = $(e.target).parent('.tc').find("td:eq(1)").text();
        var student_school = $(e.target).parent('.tc').find("td:eq(2)").text();

        $("#student_no").val(student_no);
        $("#txtCounselTitle").val(student_name);
        $("#lblGrade").text(student_grade);
        $("#lblSchool").text(student_school);
        $("#studentSearchList").empty();
        $("#SearchStudentModal").modal('hide');
    });

    $("#counselList").on("click", ".tc", function (e) {
        counselSelect(e);
    });

    //정규생 상담 입력
    $("#btnRegularCounselSave").click(function () {
        var student_no = $("#student_no").val();
        var dtCounselDate = $("#dtCounselDate").val();
        var selCounselKind = $("#selCounselKind").val();
        var selCounselMethod = $("#selCounselMethod").val();
        var txtFollowup = $("#txtFollowup").val();
        var selDischargeKind = $("#selDischargeKind").val();
        var txtDischarge = $("#txtDischarge").val();
        var txtCounselContents = $("#txtCounselContents").val();
        var chkCounselOpen = '';

        if (!student_no) {
            alert("학생을 선택해주세요");
            return false;
        }

        if (!dtCounselDate) {
            alert("상담일자를 선택하세요");
            return false;
        }

        if (selCounselKind == '9' && selDischargeKind == '06') {
            if (!txtDischarge) {
                alert("퇴원 사유를 입력하세요");
                return false;
            }
        } else {
            txtDischarge = '';
        }

        if (!txtCounselContents || !txtCounselContents.trim()) {
            alert("상담내용을 입력하세요");
            return false;
        }

        if ($('#chkCounselOpen').is(':checked')) {
            chkCounselOpen = 'Y';
        } else {
            chkCounselOpen = 'N';
        }

        $.ajax({
            url: '/center/ajax/counselControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'counselInsert',
                writer_no: userInfo.user_no,
                student_no: student_no,
                franchise_idx: center_idx,
                dtCounselDate: dtCounselDate,
                selCounselKind: selCounselKind,
                selCounselMethod: selCounselMethod,
                txtFollowup: txtFollowup,
                selDischargeKind: selDischargeKind,
                txtDischarge: txtDischarge,
                txtCounselContents: txtCounselContents,
                chkCounselOpen: chkCounselOpen,
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    clearCounselForm();
                    counselLoad();
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

    //정규생 상담 수정
    $("#btnRegularCounselUpdate").click(function () {
        var counsel_idx = $("#counsel_idx").val();
        var dtCounselDate = $("#dtCounselDate").val();
        var selCounselKind = $("#selCounselKind").val();
        var selCounselMethod = $("#selCounselMethod").val();
        var txtFollowup = $("#txtFollowup").val();
        var selDischargeKind = $("#selDischargeKind").val();
        var txtDischarge = $("#txtDischarge").val();
        var txtCounselContents = $("#txtCounselContents").val();
        var chkCounselOpen = '';

        if (!dtCounselDate) {
            alert("상담일자를 선택하세요");
            return false;
        }

        if (selCounselKind == '9' && selDischargeKind == '06') {
            if (!txtDischarge) {
                alert("퇴원 사유를 입력하세요");
                return false;
            }
        } else {
            txtDischarge = '';
        }

        if (!txtCounselContents || !txtCounselContents.trim()) {
            alert("상담내용을 입력하세요");
            return false;
        }

        if ($('#chkCounselOpen').is(':checked')) {
            chkCounselOpen = 'Y';
        } else {
            chkCounselOpen = 'N';
        }

        $.ajax({
            url: '/center/ajax/counselControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'counselUpdate',
                counsel_idx: counsel_idx,
                dtCounselDate: dtCounselDate,
                selCounselKind: selCounselKind,
                selCounselMethod: selCounselMethod,
                txtFollowup: txtFollowup,
                selDischargeKind: selDischargeKind,
                txtDischarge: txtDischarge,
                txtCounselContents: txtCounselContents,
                chkCounselOpen: chkCounselOpen,
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    clearCounselForm();
                    counselLoad();
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

    //정규생 상담 삭제
    $("#btnRegularCounselDelete").click(function () {
        var counsel_idx = $("#counsel_idx").val();

        if (confirm("상담내역을 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/counselControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'counselDelete',
                    counsel_idx: counsel_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        clearCounselForm();
                        counselLoad();
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

    //신규상담 입력
    $("#btnNewCounselSave").click(function () {
        var dtNewCounselDate = $("#dtNewCounselDate").val();
        var txtNewName = $("#txtNewName").val();
        var rdoGender = $("input[name='rdoGender']:checked").val();
        var selGrade = $("#selGrade").val();
        var txtSchool = $("#txtSchool").val();
        var selCounselTeacher = $("#selCounselTeacher").val();
        var txtParentTel = $("#txtParentTel").val();
        var txtCounselResult = $("#txtCounselResult").val();
        var selRegisterRate = $("#selRegisterRate").val();
        var selHopeClass = $("#selHopeClass").val();
        var selKnownPath = $("#selKnownPath").val();
        var txtNewCounselContents = $("#txtNewCounselContents").val();

        if (!dtNewCounselDate) {
            alert("상담일자를 지정해주세요");
            return false;
        }

        if (!txtNewName || !txtNewName.trim()) {
            alert("이름을 입력하세요");
            return false;
        }

        if (!selGrade) {
            alert("학년을 지정해주세요");
            return false;
        }

        if (!txtParentTel || !txtParentTel.trim()) {
            alert("전화번호를 입력하세요");
            return false;
        }

        if (!validatePhone(txtParentTel)) {
            alert("정확한 전화번호를 입력하세요");
            return false;
        }

        if (!txtNewCounselContents || !txtNewCounselContents.trim()) {
            alert("상담내용을 입력하세요");
            return false;
        }

        $.ajax({
            url: '/center/ajax/counselControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'counselNewInsert',
                writer_no: userInfo.user_no,
                franchise_idx: center_idx,
                dtNewCounselDate: dtNewCounselDate,
                txtNewName: txtNewName,
                rdoGender: rdoGender,
                selGrade: selGrade,
                txtSchool: txtSchool,
                selCounselTeacher: selCounselTeacher,
                txtParentTel: txtParentTel,
                txtCounselResult: txtCounselResult,
                selRegisterRate: selRegisterRate,
                selHopeClass: selHopeClass,
                selKnownPath: selKnownPath,
                txtNewCounselContents: txtNewCounselContents
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    clearCounselNewForm();
                    counselLoad();
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

    //신규상담 수정
    $("#btnNewCounselUpdate").click(function () {
        var counsel_idx = $("#counsel_new_idx").val();
        var dtNewCounselDate = $("#dtNewCounselDate").val();
        var txtNewName = $("#txtNewName").val();
        var rdoGender = $("input[name='rdoGender']:checked").val();
        var selGrade = $("#selGrade").val();
        var txtSchool = $("#txtSchool").val();
        var selCounselTeacher = $("#selCounselTeacher").val();
        var txtParentTel = $("#txtParentTel").val();
        var txtCounselResult = $("#txtCounselResult").val();
        var selRegisterRate = $("#selRegisterRate").val();
        var selHopeClass = $("#selHopeClass").val();
        var selKnownPath = $("#selKnownPath").val();
        var txtNewCounselContents = $("#txtNewCounselContents").val();

        if (!dtNewCounselDate) {
            alert("상담일자를 지정해주세요");
            return false;
        }

        if (!txtNewName || !txtNewName.trim()) {
            alert("이름을 입력하세요");
            return false;
        }

        if (!selGrade) {
            alert("학년을 지정해주세요");
            return false;
        }

        if (!txtParentTel || !txtParentTel.trim()) {
            alert("전화번호를 입력하세요");
            return false;
        }

        if (!validatePhone(txtParentTel)) {
            alert("정확한 전화번호를 입력하세요");
            return false;
        }

        if (!txtNewCounselContents || !txtNewCounselContents.trim()) {
            alert("상담내용을 입력하세요");
            return false;
        }

        $.ajax({
            url: '/center/ajax/counselControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: 'counselNewUpdate',
                counsel_idx: counsel_idx,
                dtNewCounselDate: dtNewCounselDate,
                txtNewName: txtNewName,
                rdoGender: rdoGender,
                selGrade: selGrade,
                txtSchool: txtSchool,
                selCounselTeacher: selCounselTeacher,
                txtParentTel: txtParentTel,
                txtCounselResult: txtCounselResult,
                selRegisterRate: selRegisterRate,
                selHopeClass: selHopeClass,
                selKnownPath: selKnownPath,
                txtNewCounselContents: txtNewCounselContents
            },
            success: function (result) {
                if (result.success) {
                    alert(result.msg);
                    clearCounselNewForm();
                    counselLoad();
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

    //신규상담 삭제
    $("#btnNewCounselDelete").click(function () {
        var counsel_idx = $("#counsel_new_idx").val();

        if (confirm("상담내역을 삭제하시겠습니까?")) {
            $.ajax({
                url: '/center/ajax/counselControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'counselNewDelete',
                    counsel_idx: counsel_idx
                },
                success: function (result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#contents").load("counsel.php");
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

function counselLoad() {
    var action = '';
    var counsel_month = $("#dtCounselMonths").val();

    if ($("#chkCounsel").is(":checked")) {
        action = 'counselLoad';
        clearCounselForm();
    } else {
        action = 'counselNewLoad';
        clearCounselNewForm();
    }

    $.ajax({
        url: '/center/ajax/counselControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            action: action,
            franchise_idx: center_idx,
            counsel_month: counsel_month
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
                        data: 'student_name'
                    },
                    {
                        data: 'teacher_name'
                    },
                    {
                        data: 'counsel_date'
                    },
                    {
                        data: 'reg_date'
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
                        url: "/json/ko_kr.json",
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
    var action = '';

    if ($(e.target).parents('.bg-light').length) {
        targetClass.removeClass('bg-light');
        clearCounselForm();
        clearCounselNewForm();
    } else {
        $('.tc').removeClass('bg-light');
        targetClass.addClass('bg-light');

        if ($("#chkCounsel").is(":checked")) {
            action = 'counselSelect';
        } else {
            action = 'counselNewSelect';
        }

        var counsel_idx = targetClass.data('counsel-idx');

        $.ajax({
            url: '/center/ajax/counselControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            data: {
                action: action,
                counsel_idx: counsel_idx
            },
            success: function (result) {
                if (result.success) {
                    setCounselData(result.data);

                    if ($("#chkCounsel").is(":checked")) {
                        $("#counsel_idx").val(counsel_idx);
                    } else {
                        $("#counsel_new_idx").val(counsel_idx);
                    }

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
    if ($("#chkCounsel").is(":checked")) {
        $("#txtCounselTitle").val(data.student_name);
        $("#lblGrade").text(data.student_age);
        $("#lblSchool").text(data.school_name);
        $("#dtCounselDate").val(data.counsel_date);
        $("#selCounselMethod option[value=" + data.counsel_method + "]").prop("selected", true);
        $("#txtFollowup").val(data.counsel_followup);

        if (data.counsel_kind == '9') {
            $("#selCounselKind option[value=" + data.counsel_kind + "]").prop("selected", true);
            $('#lblDischargeKind').show();
            $('#selDischargeKind').show();

            if (data.counsel_discharge_reason == '06') {
                $('#div-Discharge2').show();
                $("#txtDischarge").val(data.counsel_discharge_contents);
            } else {
                $('#div-Discharge2').hide();
            }

            $("#selDischargeKind option[value=" + data.counsel_discharge_reason + "]").prop("selected", true);
        } else {
            $("#selCounselKind option[value=" + data.counsel_kind + "]").prop("selected", true);
            $('#lblDischargeKind').hide();
            $('#selDischargeKind').hide();
            $('#div-Discharge2').hide();
        }

        if (data.counsel_open == 'Y') {
            $('#chkCounselOpen').prop('checked', true);
            $('#lblCounselOpen').text('상담내용공개');
        } else {
            $('#chkCounselOpen').prop('checked', false);
            $('#lblCounselOpen').text('상담내용비공개');
        }

        $("#txtCounselContents").val(data.counsel_contents);
        $("#txtCounselRequest").val(data.counsel_request);

        $("#btnRegularCounselDelete").show();
        $("#btnRegularCounselUpdate").show();
        $("#btnRegularCounselSave").hide();
        $("#btnStudentSearch").hide();
    } else {
        $("#dtNewCounselDate").val(data.counsel_date);
        $("#txtNewName").val(data.counselee_name);
        $("#rdoGender" + data.gender).prop("checked", true);
        $("#txtSchool").val(data.school_name);

        $("#txtParentTel").val(data.counsel_phone);
        $("#txtCounselResult").val(data.counsel_result);
        $("#txtNewCounselContents").val(data.counsel_contents);

        $("#selGrade option[value=" + data.counsel_grade + "]").prop("selected", true);
        $("#selRegisterRate option[value=" + data.counsel_regist + "]").prop("selected", true);

        if (data.counsel_class) {
            $("#selHopeClass option[value=" + data.counsel_class + "]").prop("selected", true);
        } else {
            $("#selHopeClass option:first").prop('selected', true);
        }

        if (data.counsel_know) {
            $("#selKnownPath option[value=" + data.counsel_know + "]").prop("selected", true);
        } else {
            $("#selKnownPath option:first").prop('selected', true);
        }

        if (data.counsel_teacher_no) {
            $("#selCounselTeacher option[value=" + data.counsel_teacher_no + "]").prop("selected", true);
        } else {
            $("#selCounselTeacher option:first").prop('selected', true);
        }

        $("#btnNewCounselDelete").show();
        $("#btnNewCounselUpdate").show();
        $("#btnNewCounselSave").hide();
    }
}

function clearCounselForm() {
    $("#txtCounselTitle").val('');
    $("#lblGrade").empty();
    $("#lblSchool").empty();
    $("#dtCounselDate").val('');
    $("#txtFollowup").val('');
    $("#txtDischarge").val('');
    $("#txtCounselContents").val('');
    $("#txtCounselRequest").val('');
    $('#lblDischargeKind').hide();
    $('#selDischargeKind').hide();
    $('#div-Discharge2').hide();

    $("#btnRegularCounselDelete").hide();
    $("#btnRegularCounselUpdate").hide();
    $("#btnRegularCounselSave").show();
    $("#btnStudentSearch").show();

    $("#student_no").val('');
    $("#counsel_idx").val('');

    $("#selCounselKind option:first").prop('selected', true);
    $("#selCounselMethod option:first").prop('selected', true);

    $('#chkCounselOpen').prop('checked', true);
    $('#lblCounselOpen').text('상담내용공개');
}

function clearCounselNewForm() {
    $("#dtNewCounselDate").val('');
    $("#txtNewName").val('');
    $("#rdoGender1").prop("checked", true);
    $("#selGrade option:first").prop('selected', true);
    $("#txtSchool").val('');
    $("#selCounselTeacher option:first").prop('selected', true);
    $("#txtParentTel").val('');
    $("#txtCounselResult").val('');
    $("#selRegisterRate option:first").prop('selected', true);
    $("#selHopeClass option:first").prop('selected', true);
    $("#selKnownPath option:first").prop('selected', true);
    $("#txtNewCounselContents").val('');

    $("#counsel_new_idx").val('');
    $("#btnNewCounselDelete").hide();
    $("#btnNewCounselUpdate").hide();
    $("#btnNewCounselSave").show();
}

//전화번호 체크
function validatePhone(phone) {
    var check = /^[0-9]+$/;
    if (!check.test(phone)) {
        return false;
    } else {
        var regExp = /^01([0|1|6|7|8|9])([0-9]{3,4})([0-9]{4})$/;
        if (!regExp.test(phone)) {
            return false
        } else {
            return true;
        }
    }
}
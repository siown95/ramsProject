$(document).ready(function () {
    fnCheck();
    loadEmployee(); //직원리스트 호출

    $("#user_name").on("propertychange change keyup paste input", function () {
        $(this).val($(this).val().replace(/[0-9.]/g, ''));
    });

    $("#employeeList").on("click", ".tc", function (e) {
        selectEmployee(e);
    });

    $('#txtHp').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });

    $('#txtHp2').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });
});

function fnCheck() {
    $("#menuList").on("click", ".chk", function () {
        if ($('input:checkbox[name="chkNo"]').length == $('input:checkbox[name="chkNo"]:checked').length) {
            $('#chkAll').prop('checked', true);
        } else {
            $('#chkAll').prop('checked', false);
        }
    });

    $('#chkAll').on('click', function () {
        if ($('#chkAll').is(':checked') == true) {
            $('.chk').prop('checked', true);
        } else {
            $('.chk').prop('checked', false);
        }
    });
}

function clearForm() {
    var user_id = $("#user_id").val();
    if (!$("#user_id").is('[readonly]')) {
        $('#employeeForm')[0].reset();
        $('#loadDiv3').load(location.href + ' #loadDiv4');
    } else {
        $('#employeeForm')[0].reset();
        $("#user_id").val(user_id);
        $('#loadDiv3').load(location.href + ' #loadDiv4');
    }
}

function daum_postcode() {
    new daum.Postcode({
        oncomplete: function (data) {
            $('#txtZipCode').val(data.zonecode);
            $('#txtAddr').val(data.roadAddress);
            $('#txtAddr').focus();
        }
    }).open();
}

//회원수정
function employeeUpdate() {
    var center_idx = $("#selCenter").val();
    var user_name = $("#user_name").val(); //이름
    var gender = $("input[name='gender']:checked").val(); //성별
    var birth = $("#dtBirthday").val(); //생년월일
    var state = $("#state").val(); //상태
    var position = $("#selPosition").val(); //직책
    var password = $("#txtPassword").val(); //비밀번호
    var user_phone = $("#txtHp").val(); //연락처
    var emergencyhp = $("#txtHp2").val();
    var email = $("#email").val(); //이메일
    var zipcode = $("#txtZipCode").val(); //우편번호
    var address = $("#txtAddr").val(); //주소
    var hire_date = $("#dtHiringDate").val(); //채용일
    var resign_date = $("#dtResignationDate").val(); //퇴직일
    var school = $("#txtGraduationSchool").val(); //최종학력
    var graduation_months = $("#txtGraduationDate").val(); //졸업년도
    var major = $("#txtMajor").val(); //전공
    var degree_number = $("#txtDegreeNumber").val(); //학위번호
    var career = $("#txtCareer").val(); //경력
    var career_year = $("#txtCareerYear").val(); //연차
    var certificate = $("#txtCertificate").val(); //자격증
    var bank_name = $("#txtBank").val(); //은행명
    var account_number = $("#txtPassbook").val(); //계좌

    var paid_holiday = $("#selHoliday1").val(); //유급휴일
    var unpaid_holiday = $("#selHoliday2").val(); //무급휴일
    var from_time_array = new Array(); //출근시간 배열
    var to_time_array = new Array(); //퇴근시간 배열
    var user_no = $("#employeeList").children('.bg-light').find('td:eq(3)').text();

    var menu_group_arr = new Array();

    $("input[name=chkNo]:checked").each(function () {
        menu_group_arr.push($(this).val());
    });

    $(".from-time").each(function () {
        if ($(this).val() != '') {
            from_time_array.push($(this).val());
        }
    });

    $(".to-time").each(function () {
        if ($(this).val() != '') {
            to_time_array.push($(this).val());
        }
    });

    if (!user_name || !user_name.trim()) {
        alert('이름을 입력하세요.');
        $("#user_name").focus();
        return false;
    }

    if (!validatBlank(user_name)) {
        alert('이름에 공백이 존재합니다.');
        $("#user_name").focus();
        return false;
    }

    if (validateNumber(user_name)) {
        alert('이름에 숫자가 존재합니다.');
        $("#user_name").focus();
        return false;
    }

    if (!birth || !birth.trim()) {
        alert('생년월일을 입력하세요.');
        $("#dtBirthday").focus();
        return false;
    }

    if (!state) {
        alert('상태를 선택 하세요.');
        $("#state").focus();
        return false;
    }

    if (!position) {
        alert('직책을 선택 하세요.');
        $("#selPosition").focus();
        return false;
    }

    if (password) {
        if (!password.trim()) {
            alert('변경할 비밀번호를 입력하세요.');
            $("#txtPassword").focus();
            return false;
        }

        if (!validatBlank(password)) {
            alert('비밀번호에 공백이 존재합니다.');
            $("#txtPassword").focus();
            return false;
        }

        if (password.length < 4) {
            alert('비밀번호를 4자리 이상으로 입력해주세요.');
            $("#txtPassword").focus();
            return false;
        }
    }

    if (!email || !email.trim()) {
        alert('이메일을 입력하세요.');
        $("#email").focus();
        return false;
    }

    if (!validateEmail(email)) {
        alert('정확한 이메일을 입력하세요.');
        $("#email").focus();
        return false;
    }

    if (!validatBlank(email)) {
        alert('이메일에 공백이 존재합니다.');
        $("#email").focus();
        return false;
    }

    if (!user_phone || !user_phone.trim()) {
        alert('전화번호를 입력하세요.');
        $("#txtHp").focus();
        return false;
    }

    if (!validatePhone(user_phone)) {
        alert('정확한 전화번호를 입력하세요.');
        $("#txtHp").focus();
        return false;
    }

    if (!emergencyhp || !emergencyhp.trim()) {
        alert('비상연락처를 입력하세요.');
        $("#txtHp2").focus();
        return false;
    }

    if (!validatePhone(emergencyhp)) {
        alert('정확한 전화번호를 입력하세요.');
        $("#txtHp2").focus();
        return false;
    }

    if (!address || !address.trim()) {
        alert('주소를 입력하세요.');
        $("#txtAddr").focus();
        return false;
    }

    if (!zipcode || !zipcode.trim()) {
        alert('우편번호를 입력하세요.');
        $("#txtZipCode").focus();
        return false;
    }

    if (!paid_holiday) {
        alert('유급휴일을 선택하세요.');
        $("#selHoliday1").focus();
        return false;
    }

    if (!unpaid_holiday) {
        alert('무급휴일을 선택하세요.');
        $("#selHoliday2").focus();
        return false;
    }

    if (from_time_array.length < 5) {
        alert('출근시간을 모두 입력하세요.');
        return false;
    }

    if (to_time_array.length < 5) {
        alert('퇴근시간을 모두 입력하세요.');
        return false;
    }

    $.ajax({
        url: '/adm/ajax/employeeControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        
        data: {
            action: 'employeeUpdate',
            center_idx: center_idx,
            user_no: user_no,
            user_name: user_name,
            gender: gender,
            birth: birth,
            state: state,
            position: position,
            password: password,
            user_phone: user_phone,
            emergencyhp: emergencyhp,
            email: email,
            zipcode: zipcode,
            address: address,
            hire_date: hire_date,
            resign_date: resign_date,
            school: school,
            graduation_months: graduation_months,
            major: major,
            degree_number: degree_number,
            career: career,
            career_year: career_year,
            certificate: certificate,
            bank_name: bank_name,
            account_number: account_number,
            menu_group_arr: menu_group_arr,
            paid_holiday: paid_holiday,
            unpaid_holiday: unpaid_holiday,
            from_time: from_time_array.join(','),
            to_time: to_time_array.join(',')
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
}

function setHoliday(type, day) {
    var _text = '';
    var targetFromTime = '';
    var targetToTime = '';
    var sel1 = $("#selHoliday1").val();
    var sel2 = $("#selHoliday2").val();

    if (type == 'paid') {
        _text = "유급 휴일";
        targetFromTime = $("input[name='from_time']").not("#timefrom" + sel2);
        targetToTime = $("input[name='to_time']").not("#timeto" + sel2);

        if (sel1 != '' && sel1 == sel2) {
            alert('유급휴일과 무급휴일을 동일한 날짜로 설정할 수 없습니다.');
            $("#selHoliday1").val('');
            targetFromTime.removeAttr("readonly");
            targetToTime.removeAttr("readonly");
            targetFromTime.attr("type", "time");
            targetToTime.attr("type", "time");
            return false;
        }
    } else {
        _text = "무급 휴일";
        targetFromTime = $("input[name='from_time']").not("#timefrom" + sel1);
        targetToTime = $("input[name='to_time']").not("#timeto" + sel1);

        if (sel2 != '' && sel2 == sel1) {
            alert('무급휴일과 유급휴일을 동일한 날짜로 설정할 수 없습니다.');
            $("#selHoliday2").val('');
            targetFromTime.removeAttr("readonly");
            targetToTime.removeAttr("readonly");
            targetFromTime.attr("type", "time");
            targetToTime.attr("type", "time");
            return false;
        }
    }

    if (day) {
        targetFromTime.removeAttr("readonly");
        targetToTime.removeAttr("readonly");
        targetFromTime.attr("type", "time");
        targetToTime.attr("type", "time");
        targetFromTime.attr("class", "form-control from-time");
        targetToTime.attr("class", "form-control to-time");
        $("#timeto" + day).attr("readonly", true);
        $("#timefrom" + day).attr("readonly", true);
        $("#timeto" + day).attr("type", "text");
        $("#timefrom" + day).attr("type", "text");
        $("#timeto" + day).attr("class", "form-control small");
        $("#timefrom" + day).attr("class", "form-control small");
        $("#timeto" + day).val(_text);
        $("#timefrom" + day).val(_text);
    } else {
        targetFromTime.removeAttr("readonly");
        targetToTime.removeAttr("readonly");
        targetFromTime.attr("type", "time");
        targetToTime.attr("type", "time");
    }
}

//직원 목록 리스트
function loadEmployee() {
    var center_idx = $("#selCenter").val();
    var state = $("#selState").val();

    $.ajax({
        url: '/adm/ajax/employeeControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        
        data: {
            action: 'loadEmployee',
            center_idx: center_idx,
            state: state
        },
        success: function (result) {
            if (result.success) {
                if (result.data.memberData) {
                    $('#dataTable').DataTable({
                        autoWidth: false,
                        destroy: true,
                        data: result.data.memberData,
                        stripeClasses: [],
                        dom: '<"col-sm-12"f>t<"col-sm-12"p>',
                        columns: [{
                            data: 'user_name'
                        },
                        {
                            data: 'position'
                        },
                        {
                            data: 'user_phone'
                        },
                        {
                            data: 'user_no',
                            className: 'd-none'
                        },
                        {
                            data: 'position_no',
                            className: 'd-none'
                        }
                        ],
                        createdRow: function (row) {
                            $(row).addClass('tc');
                            $("th").addClass('text-center align-middle');
                        },
                        order: [[4, 'asc']],
                        lengthChange: false,
                        info: false,
                        language: {
                            url: "/json/ko_kr.json",
                        }
                    });
                } else {
                    $('#dataTable').DataTable().destroy();
                    $("#employeeList").empty();
                }
                $("#selPosition").html(result.data.selPosition);
            } else {
                $('#dataTable').DataTable().destroy();
                $("#employeeList").empty();
            }
        }
    });
}
//리스트 클릭 액션
function selectEmployee(e) {
    var targetClass = $(e.target).parents('.tc');

    if ($(e.target).parents('.bg-light').length) {
        targetClass.removeClass('bg-light');
        $(".chk").prop("checked", false);
        $("#user_id").removeAttr("readonly");

        $("#employeeForm")[0].reset();
        $('#btnSave').hide();

        $("#commuteForm")[0].reset();
        $("input[name='from_time']").attr("class", "form-control from-time");
        $("input[name='to_time']").attr("class", "form-control to-time");
        $("input[name='from_time']").attr("type", "time");
        $("input[name='to_time']").attr("type", "time");
        $("input[name='from_time']").removeAttr("readonly");
        $("input[name='to_time']").removeAttr("readonly");
    } else {
        $('.tc').removeClass('bg-light');
        targetClass.addClass('bg-light');
        $('#btnSave').show();
        $("input[name='from_time']").attr("class", "form-control from-time");
        $("input[name='to_time']").attr("class", "form-control to-time");
        $("input[name='from_time']").attr("type", "time");
        $("input[name='to_time']").attr("type", "time");
        $("input[name='from_time']").removeAttr("readonly");
        $("input[name='to_time']").removeAttr("readonly");

        var user_no = targetClass.find('td:eq(3)').text();
        var center_idx = $("#selCenter").val();

        $.ajax({
            url: '/adm/ajax/employeeControll.ajax.php',
            dataType: 'JSON',
            type: 'POST',
            
            data: {
                action: 'selectEmployee',
                user_no: user_no,
                center_idx: center_idx
            },
            success: function (result) {
                if (result.success) {
                    setFormdata(result.data);
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

function setFormdata(data) {
    //기본값
    $("#id_check").hide();
    $("#user_id").val(data.user_id);
    $("#user_id").attr('readonly', true);
    $("#user_name").val(data.user_name);

    if (data.gender == 'M') {
        $("#genderM").attr('checked', true);
        $("#genderF").attr('checked', false);
    } else {
        $("#genderM").attr('checked', false);
        $("#genderF").attr('checked', true);
    }

    $("#dtBirthday").val(data.birth);
    $("#txtHp").val(data.user_phone);
    $("#txtHp2").val(data.emergencyhp);
    $("#email").val(data.email);
    $("#txtZipCode").val(data.zipcode);
    $("#txtAddr").val(data.address);
    $("#state option[value=" + data.state + "]").prop("selected", true);
    $("#txtGraduationSchool").val(data.school);
    $("#txtGraduationDate").val(data.graduation_months);
    $("#txtMajor").val(data.major);
    $("#txtDegreeNumber").val(data.degree_number);
    $("#txtCareer").val(data.career);
    $("#txtCareerYear").val(data.career_year);
    $("#txtCertificate").val(data.certificate);
    $("#txtBank").val(data.bank_name);
    $("#txtPassbook").val(data.account_number);
    $("#dtHiringDate").val(data.hire_date);
    $("#dtResignationDate").val(data.resign_date);
    $(".chk").prop("checked", false);


    if (data.position) {
        $("#selPosition option[value=" + data.position + "]").prop("selected", true);
    } else {
        $("#selPosition option[value='']").prop("selected", true);
    }

    if (data.paid_holiday) {
        $("#selHoliday1 option[value=" + data.paid_holiday + "]").prop("selected", true);
        $("#timeto" + data.paid_holiday).attr("readonly", true);
        $("#timeto" + data.paid_holiday).attr("type", "text");
        $("#timefrom" + data.paid_holiday).attr("type", "text");
        $("#timeto" + data.paid_holiday).attr("class", "form-control small");
        $("#timefrom" + data.paid_holiday).attr("class", "form-control small");
        $("#timeto" + data.paid_holiday).val('유급휴일');
        $("#timefrom" + data.paid_holiday).val('유급휴일');
    } else {
        $("#commuteForm")[0].reset();
    }

    if (data.unpaid_holiday) {
        $("#selHoliday2 option[value=" + data.unpaid_holiday + "]").prop("selected", true);
        $("#timefrom" + data.paid_holiday).attr("readonly", true);
        $("#timeto" + data.unpaid_holiday).attr("readonly", true);
        $("#timefrom" + data.unpaid_holiday).attr("readonly", true);
        $("#timeto" + data.unpaid_holiday).attr("type", "text");
        $("#timefrom" + data.unpaid_holiday).attr("type", "text");
        $("#timeto" + data.unpaid_holiday).attr("class", "form-control small");
        $("#timefrom" + data.unpaid_holiday).attr("class", "form-control small");
        $("#timeto" + data.unpaid_holiday).val('무급휴일');
        $("#timefrom" + data.unpaid_holiday).val('무급휴일');
    }

    if (data.from_time_array.length > 0) {
        for (var i = 0; i < data.from_time_array.length; i++) {
            $(".from-time").eq(i).val(data.from_time_array[i]);
        }
    }

    if (data.to_time_array.length > 0) {
        for (var i = 0; i < data.to_time_array.length; i++) {
            $(".to-time").eq(i).val(data.to_time_array[i]);
        }
    }

    if (data.menu_group_array.length > 0) {
        for (var i = 0; i < data.menu_group_array.length; i++) {
            $("input[name=chkNo][value=" + data.menu_group_array[i] + "]").prop("checked", true);
        }

        if ($('input:checkbox[name="chkNo"]').length == $('input:checkbox[name="chkNo"]:checked').length) {
            $('#chkAll').prop('checked', true);
        } else {
            $('#chkAll').prop('checked', false);
        }
    }
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

// Email 유효성 검사
function validateEmail(email) {
    var check = /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/i;
    if (check.test(email)) {
        return true;
    } else {
        return false;
    }
}

//한글 체크
function validateKorean(text) {
    var check = /[\u3131-\u314e|\u314f-\u3163|\uac00-\ud7a3]/g;
    if (check.test(text) == true) {
        return false;
    } else {
        return true;
    }
}

//숫자 체크
function validateNumber(text) {
    var check = /[0-9]/g;
    if (check.test(text)) {
        return true;
    } else {
        return false;
    }
}

//공백 체크
function validatBlank(text) {
    var check = /[\s]/g;
    if (check.test(text) == true) {
        return false;
    } else {
        return true;
    }
}
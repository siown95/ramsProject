$(document).ready(function () {
    $('#btnaddr').click(function () {
        new daum.Postcode({
            oncomplete: function (data) {
                $('#txtZipCode').val(data.zonecode);
                $('#txtAddr').val(data.roadAddress);
                $('#txtAddr').focus();
            }
        }).open();
    });

    $('#txtHp, #txtHp2').on('propertychange change keyup paste input', function () {
        var value = $(this).val();
        $(this).val(value.replace(/[^0-9.]/g, ''));
    });
});

function changeUserInfo() {
    var user_no = $("#user_no").val();
    var user_name = $("#txtName").val();
    var password = $("#txtPassword").val();
    var gender = $("input[name='gender']:checked").val();
    var birth = $("#dtBirthday").val();
    var user_phone = $("#txtHp").val();
    var emergencyhp = $("#txtHp2").val();
    var email = $("#txtEmail").val();
    var school = $("#txtGraduationSchool").val();
    var graduation_months = $("#txtGraduationDate").val();
    var major = $("#txtMajor").val();
    var degree_number = $("#txtDegreeNumber").val();
    var career = $("#txtCareer").val();
    var career_year = $("#txtCareerYear").val();
    var certificate = $("#txtCertificate").val();
    var bank_name = $("#txtBank").val();
    var account_number = $("#txtPassbook").val();
    var zipcode = $("#txtZipCode").val();
    var address = $("#txtAddr").val();

    if (!user_name || !user_name.trim()) {
        alert('이름을 입력하세요.');
        $("#txtName").focus();
        return false;
    }

    if (validateBlank(user_name)) {
        alert('이름에 공백이 존재합니다.');
        $("#txtName").focus();
        return false;
    }

    if (validateNumber(user_name)) {
        alert('이름에 숫자가 존재합니다.');
        $("#txtName").focus();
        return false;
    }

    if (password.length > 0) {
        if (validateBlank(password)) {
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

    if (!email || !email.trim()) {
        alert('이메일을 입력하세요.');
        $("#txtEmail").focus();
        return false;
    }

    if (validateEmail(email)) {
        alert('정확한 이메일을 입력하세요.');
        $("#txtEmail").focus();
        return false;
    }

    if (validateBlank(email)) {
        alert('이메일에 공백이 존재합니다.');
        $("#txtEmail").focus();
        return false;
    }

    if (!birth) {
        alert('생년월일을 입력하세요.');
        $("#dtBirthday").focus();
        return false;
    }

    if (!zipcode || !zipcode.trim()) {
        alert('우편번호를 입력하세요.');
        $("#txtZipCode").focus();
        return false;
    }

    if (!address || !address.trim()) {
        alert('주소를 입력하세요.');
        $("#txtAddr").focus();
        return false;
    }

    $.ajax({
        url: '/adm/ajax/employeeControll.ajax.php',
        dataType: 'JSON',
        type: 'POST',
        
        data: {
            action: 'myInfoUpdate',
            user_no: user_no,
            user_name: user_name,
            password: password,
            gender: gender,
            birth: birth,
            user_phone: user_phone,
            emergencyhp: emergencyhp,
            email: email,
            school: school,
            graduation_months: graduation_months,
            major: major,
            degree_number: degree_number,
            career: career,
            career_year: career_year,
            certificate: certificate,
            bank_name: bank_name,
            account_number: account_number,
            zipcode: zipcode,
            address: address,
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
        return false;
    } else {
        return true;
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
function validateBlank(text) {
    var check = /[\s]/g;
    if (check.test(text) == true) {
        return true;
    } else {
        return false;
    }
}
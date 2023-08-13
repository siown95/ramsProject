$(document).ready(function () {
    $('#inputDiv').hide();
    $("#tooltip").tooltip();
    $("#div_captcha").on("click", "#img_captcha", function () {
        $('#img_captcha').attr('src', '/captcha/img.php');
    });

    $("#loginForm").keypress(function (e) {
        if (e.keyCode === 13) {
            login_check();
        }
    });
});

function login_check() {
    var user_id = $("#inputId").val();
    var password = $("#inputPassword").val();
    var captcha = $("#captcha").val();

    if (!user_id || !user_id.trim()) {
        alert('아이디를 입력하세요.');
        $("#inputId").focus();
        return false;
    }

    if (!password || !password.trim()) {
        alert('비밀번호를 입력하세요.');
        $("#inputPassword").focus();
        return false;
    }

    var hash = CryptoJS.SHA256(password).toString();

    $.ajax({
        url: '/center/student/login.php',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {
            user_id: user_id,
            password: hash,
            center: center_idx,
            captcha: captcha
        },
        success: function (data) {
            if (data.status == "success") {
                location.href = '/' + center_name + '/student/';
            } else if (data.status == 'fail') {
                alert(data.msg);

                if (data.captcha_page) {
                    if ($("#div_captcha").hasClass('d-none')) {
                        $("#div_captcha").html(data.captcha_page);
                        $("#div_captcha").removeClass('d-none');
                    }
                    $('#img_captcha')[0].click();
                    $("#captcha").val('');
                }

                return false;
            } else if (data.status == 'lock') {
                alert(data.msg);
                location.href = "/redirect_protect.html";
                return false;
            } else {
                alert('처리 과정에서 오류가 발생하였습니다. 관리자에게 문의');
                return false;
            }
        },
        error: function (request, status, error) {
            alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
        }
    });
}
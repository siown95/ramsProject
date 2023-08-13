<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$infoClassCmp = new infoClassCmp();
$engName = explode('/', $_SERVER['REQUEST_URI']);

$CenterIdx = $infoClassCmp->getFranchiseeInfo($engName[1]);

if (empty($CenterIdx)) {
?>
    <script>
        alert('잘못된 접근입니다.');
        location.href = "/center/center_list.php";
    </script>
<?php
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>리딩엠 RAMS - 계정 생성</title>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
    <!-- css -->
    <link rel="stylesheet" href="/css/styles.css" />
    <!-- script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js" integrity="sha512-pax4MlgXjHEPfCwcJLQhigY7+N8rt6bVvWLFyUMuxShv170X53TRzGPmPkZmGBhk+jikR8WBM4yl7A9WMHHqvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body class="bg-dark">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4"><img class="img rounded-circle" src="/img/logo.png" /><span id="headTxt" class="align-middle ms-2">계정 생성</span></h3>
                                </div>

                                <div id="checkDiv" class="card-body">
                                    <?php
                                    include('privacy.php');
                                    ?>
                                    <div class="container text-center">
                                        <div class="form-check mt-2 mb-2">
                                            <input type="checkbox" id="chkPrivacy" class="form-check-input" />
                                            <label class="form-check-label" for="chkPrivacy">위 내용을 모두 확인하였고, 이용약관 및 개인정보 수집 및 이용에 대해 동의합니다.</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="inputDiv" class="card-body">
                                    <form>
                                        <div class="row mb-3">
                                            <div class="col-md-5">
                                                <div class="form-floating mb-md-0">
                                                    <input type="hidden" id="Center_Idx" value="<?= $CenterIdx ?>" />
                                                    <input class="form-control" id="txtName" type="text" placeholder="이름" maxlength="10"/>
                                                    <label for="txtName">이름</label>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-floating mb-md-0">
                                                    <input class="form-control" id="txtUserId" type="text" placeholder="아이디" maxlength="20"/>
                                                    <label for="txtUserId">아이디</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2 align-self-center">
                                                <div class="form-floating mb-md-0">
                                                    <button type="button" id="btnIdCheck" class="btn btn-sm btn-outline-success">중복확인</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-md-0">
                                                    <input class="form-control" id="txtPassword" type="password" placeholder="비밀번호" maxlength="20"/>
                                                    <label for="txtPassword">비밀번호</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-md-0">
                                                    <input class="form-control" id="txtPasswordConfirm" type="password" placeholder="비밀번호 확인" maxlength="20"/>
                                                    <label for="txtPasswordConfirm">비밀번호 확인</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="form-floating mb-md-0">
                                                    <input class="form-control" id="txtEmail" type="email" placeholder="이메일" maxlength="50" />
                                                    <label for="txtEmail">이메일</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-floating mb-md-0">
                                                    <input class="form-control" id="txtHp" type="text" placeholder="전화번호" maxlength="11" />
                                                    <label for="txtHp">전화번호</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-floating mb-md-0">
                                                    <input class="form-control" id="dtBirthday" type="date" placeholder="생년월일" value="<?=date("Y-m-d")?>"/>
                                                    <label for="dtBirthday">생년월일</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2 align-self-center">
                                                <div class="form-floating mb-md-0">
                                                    <div class="form-check form-switch">
                                                        <input id="chkSex" class="form-check-input" type="checkbox" role="switch" checked>
                                                        <label id="lblSex" class="form-check-label" for="chkSex">남</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-8">
                                                <div class="form-floating mb-md-0">
                                                    <input class="form-control" id="txtAddr" type="text" placeholder="주소" maxlength="100"/>
                                                    <label for="txtAddr">주소</label>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-floating mb-md-0">
                                                    <input class="form-control" id="txtZipCode" type="text" placeholder="우편번호" readonly />
                                                    <label for="txtZipCode">우편번호</label>
                                                </div>
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="mb-3 mb-md-0">
                                                    <button type="button" id="btnaddr" class="btn btn-sm btn-outline-success">주소찾기</button>
                                                    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
                                                    <script>
                                                        $('#btnaddr').click(function() {
                                                            new daum.Postcode({
                                                                oncomplete: function(data) {
                                                                    $('#txtAddr').val(data.roadAddress);
                                                                    $('#txtZipCode').val(data.zonecode);
                                                                    $('#txtAddr').focus();
                                                                }
                                                            }).open();
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 mb-0">
                                            <div class="d-grid"><a class="btn btn-primary btn-block" id="registerBtn" href="javascript:void(0)">계정 생성 요청</a></div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a href="login.php">이미 계정이 있으신가요? 로그인으로 이동</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <!-- footer -->
        <?php
        include_once('footer_auth.php');
        ?>
    </div>
    <script>
        $(document).ready(function() {
            $('#inputDiv').hide();

            $('#txtHp').on('propertychange change keyup paste input', function() {
                var value = $(this).val();
                $(this).val(value.replace(/[^0-9.]/g, ''));
            });

            $("#txtUserId").on("propertychange change keyup paste input", function() {
                $("#txtUserId").data("id-check", "N");
                $("#btnIdCheck").removeClass("btn-success");
                $("#btnIdCheck").addClass("btn-outline-success");
            });
        });
        
        $('#chkSex').click(function() {
            if ($('#chkSex').is(':checked') == true) {
                $('#lblSex').text('남');
            } else {
                $('#lblSex').text('여');
            }
        });

        $('#chkPrivacy').change(function() {
            if ($('#chkPrivacy').is(':checked') == true) {
                var chk = confirm('이용약관 및 개인정보 이용에 동의하시겠습니까?');
                if (chk == true) {
                    $('#checkDiv').hide();
                    $('#inputDiv').show();
                } else {
                    $('#chkPrivacy').attr('checked', false);
                }
            }
        });

        $("#btnIdCheck").click(function() {
            var user_id = $("#txtUserId").val();

            if (!user_id || !user_id.trim()) {
                alert('아이디를 입력하세요.');
                $("#txtUserId").focus();
                return false;
            }

            if (user_id.length < 5 || user_id.length > 20) {
                alert('아이디는 영문으로 시작하는 5자 이상 20자 이하로 입력하세요.');
                $("#txtUserId").focus();
                return false;
            }

            if (!validateId(user_id)) {
                alert('아이디는 영문으로 시작하는 5자 이상 20자 이하로 입력하세요.');
                $("#txtUserId").focus();
                return false;
            }

            $.ajax({
                url: '/ajax/employeeControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'idCheck',
                    user_id: user_id
                },
                success: function(result) {
                    if (result.success) {
                        alert(result.msg);
                        $("#txtUserId").data("id-check", "Y");
                        $("#btnIdCheck").removeClass("btn-outline-success");
                        $("#btnIdCheck").addClass("btn-success");
                        return false;
                    } else {
                        alert(result.msg);
                        return false;
                    }
                },
                error: function(request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        });

        $("#registerBtn").click(function() {
            var user_name = $("#txtName").val();
            var user_id = $("#txtUserId").val();
            var password = $("#txtPassword").val();
            var password_tmp = $("#txtPasswordConfirm").val();
            var email = $("#txtEmail").val();
            var birth = $("#dtBirthday").val();
            var gender = $("#chkSex").is(':checked') ? 'M' : 'F';
            var user_phone = $("#txtHp").val();
            var address = $("#txtAddr").val();
            var zipcode = $("#txtZipCode").val();
            var franchise_idx = $("#Center_Idx").val();

            if (!user_name || !user_name.trim()) {
                alert('이름을 입력하세요.');
                $("#txtName").focus();
                return false;
            }

            if (!validatBlank(user_name)) {
                alert('이름에 공백이 존재합니다.');
                $("#txtName").focus();
                return false;
            }

            if (validateNumber(user_name)) {
                alert('이름에 숫자가 존재합니다.');
                $("#txtName").focus();
                return false;
            }

            if (!user_id || !user_id.trim()) {
                alert('아이디를 입력하세요.');
                $("#txtUserId").focus();
                return false;
            }

            if (!validatBlank(user_id)) {
                alert('아이디에 공백이 존재합니다.');
                $("#txtUserId").focus();
                return false;
            }

            if (user_id.length < 5 || user_id.length > 20) {
                alert('아이디는 영문으로 시작하는 5자 이상 20자 이하로 입력하세요.');
                $("#txtUserId").focus();
                return false;
            }

            if (!validateKorean(user_id)) {
                alert('아이디에 한글이 존재합니다.');
                $("#txtUserId").focus();
                return false;
            }

            if (!validateId(user_id)) {
                alert('아이디는 영문으로 시작하는 5자 이상 20자 이하로 입력하세요.');
                $("#txtUserId").focus();
                return false;
            }

            if ($("#txtUserId").data("id-check") != 'Y') {
                alert('아이디 중복확인 버튼을 클릭하세요.');
                return false;
            }

            if (!password || !password.trim()) {
                alert('비밀번호를 입력하세요.');
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

            if (password != password_tmp) {
                alert('비밀번호가 일치하지 않습니다.\n다시 확인해주세요');
                $("#txtPasswordConfirm").focus();
                return false;
            }

            if (!email || !email.trim()) {
                alert('이메일을 입력하세요.');
                $("#txtEmail").focus();
                return false;
            }

            if (!validateEmail(email)) {
                alert('정확한 이메일을 입력하세요.');
                $("#txtEmail").focus();
                return false;
            }

            if (!validatBlank(email)) {
                alert('이메일에 공백이 존재합니다.');
                $("#txtEmail").focus();
                return false;
            }

            if (!birth || !birth.trim()) {
                alert('생년월일을 입력하세요.');
                $("#dtBirthday").focus();
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

            $.ajax({
                url: '/ajax/employeeControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    action: 'employeeInsert',
                    user_name: user_name,
                    user_id: user_id,
                    password: password,
                    email: email,
                    birth: birth,
                    gender: gender,
                    user_phone: user_phone,
                    address: address,
                    zipcode: zipcode,
                    franchise_idx: franchise_idx
                },
                success: function(result) {
                    if (result.success) {
                        alert(result.msg);
                        location.href = '/<?=$engName[1]?>/login.php';
                        return false;
                    } else {
                        alert(result.msg);
                        return false;
                    }
                },
                error: function(request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        });

        //아이디 유효성 검사
        function validateId(text) {
            var check = /^[a-z]+[a-z0-9]{4,19}$/g;
            if (!check.test(text) == true) {
                return false;
            } else {
                return true;
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
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>리딩엠 RAMS - 아이디 / 비밀번호 찾기</title>
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
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4"><img class="img rounded-circle" src="/img/logo.png" /><span id="headTxt" class="align-middle ms-2">아이디 / 비밀번호 찾기</span></h3>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="input-group align-self-center mb-3">
                                            <div class="form-check form-switch">
                                                <input id="chkDivision" class="form-check-input" type="checkbox" role="switch" checked value="1">
                                                <label id="lblDivision" class="form-check-label" for="chkDivision">아이디찾기</label>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="txtName" type="text" placeholder="이름" />
                                            <label for="txtName">이름</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="txtEmail" type="email" placeholder="이메일" />
                                            <label for="txtEmail">이메일</label>
                                        </div>
                                        <div id="div_id" class="form-floating mb-3" style="display: none;">
                                            <input class="form-control" id="txtId" type="text" placeholder="아이디" />
                                            <label for="txtId">아이디</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-end mt-4 mb-0">
                                            <button type="button" id="btnIdFind" class="btn btn-primary">아이디 찾기</button>
                                            <button type="button" id="btnPasswordFind" class="btn btn-primary" style="display: none;">비밀번호 찾기</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small"><a class="btn- btn-outline-primary me-3" href="register.php">회원가입</a><a class="btn- btn-outline-primary" href="login.php">로그인</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="FindModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLabel">회원 정보</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="size-12 mb-2">
                                    <span>현재 회원님이 보유하고 계신 계정은 아래와 같습니다&#46;<br>계정의 모든 정보를 확인하시려면 등록하신 이메일을 통해 확인하실 수 있습니다&#46;</span>
                                </div>
                                <div class="input-group mb-1">
                                    <div class="form-check-inline">
                                        <input type="radio" id="rdoMember1" class="form-check-input" name="rdoMember" value="1" checked>
                                        <label class="form-check-label" for="rdoMember1">tes&#42;&#42;<span class="ms-2">&#40;본사&#41;</span></label>
                                    </div>
                                </div>
                                <div class="input-group mb-1">
                                    <div class="form-check-inline">
                                        <input type="radio" id="rdoMember2" class="form-check-input" name="rdoMember" value="0">
                                        <label class="form-check-label" for="rdoMember2">tes&#42;&#42;<span class="ms-2">&#40;목동본원&#41;</span></label>
                                    </div>
                                </div>
                                <div class="input-group mb-1">
                                    <div class="form-check-inline">
                                        <input type="radio" id="rdoMember3" class="form-check-input" name="rdoMember" value="0">
                                        <label class="form-check-label" for="rdoMember3">tes&#42;&#42;<span class="ms-2">&#40;도곡직영&#41;</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                                <button type="button" class="btn btn-primary"><i class="fa-solid fa-envelope-open-text"></i> 이메일발송</button>
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
        $('#chkDivision').click(function() {
            if ($('#chkDivision').is(':checked') == true) {
                $('#chkDivision').val('1');
                $('#lblDivision').text('아이디찾기');
                $('#div_id').hide();
                $('#btnIdFind').show();
                $('#btnPasswordFind').hide();
            } else {
                $('#chkDivision').val('2');
                $('#lblDivision').text('비밀번호찾기');
                $('#div_id').show();
                $('#btnIdFind').hide();
                $('#btnPasswordFind').show();
            }
        });

        $('input[name=rdoMember]').click(function() {
            if($(this).is(':checked') == true) {
                $('input[name=rdoMember]').val('0');
                $(this).val('1');
            }
        });

        $('#btnIdFind').click(function() {
            $('#FindModal').modal('show');
            // $.ajax({
            //     url: 'mail.php',
            //     type: 'post',
            //     data: {
            //         email: $('#txtEmail').val(),
            //         flag: $('chkDivision').val()
            //     },

            //     success: function(data) {
            //         console.log(data.msg);
            //     },
            //     error: function(data) {
            //         console.log(data.msg);
            //     }
            // });
        });

        $('#btnPasswordFind').click(function() {
            $.ajax({
                url: 'mail.php',
                type: 'post',
                data: {
                    email: $('#txtEmail').val(),
                    id: $('#txtId').val(),
                    flag: $('chkDivision').val()
                },

                success: function(data) {
                    console.log(data.msg);
                },
                error: function(data) {
                    console.log(data.msg);
                }
            });
        });
    </script>
</body>

</html>
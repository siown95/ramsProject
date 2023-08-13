<?php
$quizNo = '1';
$question = '나는 수많은 책 중 어떤 책을 골라 읽을지 막막하다.';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="container">
    <div class="card border-left-primary shadow mt-3">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 id="lblTitle" class="text-primary">도서선택과 이력관리 영역</h6>
                </div>
                <div>
                    <h6 id="lblSubTitle" class="text-muted">도서 선택 능력</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="progress mb-3">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
            <div id="questionDiv">
                <div class="form-floating mb-3">
                    <input type="text" id="txtQuestion" class="form-control bg-white" placeholder="문제<?= $quizNo ?>" value="<?= $question ?>" readonly />
                    <label id="lblquiz" for="txtQuestion">문제 <?= $quizNo ?></label>
                </div>
                <div class="input-group">
                    <div class="form-check form-check-inline">
                        <input type="radio" id="rdo1" class="form-check-input" name="rdoQuiz" value="5" />
                        <label id="lbl1" class="form-check-label" for="rdo1">정말그렇다</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="rdo2" class="form-check-input" name="rdoQuiz" value="4" />
                        <label id="lbl2" class="form-check-label" for="rdo2">그런편이다</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="rdo3" class="form-check-input" name="rdoQuiz" value="3" />
                        <label id="lbl3" class="form-check-label" for="rdo3">그저그렇다</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="rdo4" class="form-check-input" name="rdoQuiz" value="2" />
                        <label id="lbl4" class="form-check-label" for="rdo4">아닌편이다</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="rdo5" class="form-check-input" name="rdoQuiz" value="1" />
                        <label id="lbl5" class="form-check-label" for="rdo5">정말아니다</label>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <label id="lblQuizCnt" for="btnNext"></label>
                <button type="button" id="btnNext" class="btn btn-outline-primary">다음 문제</button>
                <button type="button" id="btnSend" class="btn btn-outline-success" style="display: none;">제출</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js" integrity="sha512-i9cEfJwUwViEPFKdC1enz4ZRGBj8YQo6QByFTF92YXHi7waCqyexvRD75S5NVTsSiTv7rKWqG9Y5eFxmRsOn0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var answer = new Array();
    var question = new Array();
    var count = 0;
    $(document).ready(function() {
        $.ajax({
            url: 'reading_question.php',
            dataType: 'JSON',
            type: 'POST',
            async: false,
            success: function(data) {
                for (var i = 0; i < data.question.length; i++) {
                    question.push(data.question[i]);
                }
            },
            error: function(request, status, error) {
                console.log("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
            }
        });
    });

    $("#btnNext").click(function() {
        if ($("input:radio[name=rdoQuiz]").is(":checked") == true) {
            if (count >= 100) {
                $("#btnNext").hide();
                $(".progress-bar").css('width', "100%");
                $(".progress-bar").text("100%");
                $("#lblTitle").text("제출하기");
                $("#lblSubTitle").text("");
                $("#questionDiv").html("<h6>많은 문제를 푸느라 고생 많으셨습니다. <br> 오른쪽 아래에 제출 버튼을 눌러 작성한 답을 제출해주세요. <br> 제출 후 결과를 확인할 수 있습니다.</h6>");
                $("#btnSend").show();
                return;
            }
            answer.push($("input:radio[name=rdoQuiz]:checked").val());
            count++;
            $(".progress-bar").css('width', Math.round(count / 101 * 100) + "%");
            $(".progress-bar").text(Math.round(count / 101 * 100) + "%");
            $("#txtQuestion").val(question[count]);
            $("#lblquiz").text("문제 " + (count + 1));
            if (count < 14) {
                $("#lblTitle").text("도서선택과 이력관리 영역");
                $("#lblSubTitle").text("도서 선택 능력");
            } else if (count > 13 && count < 24) {
                $("#lblTitle").text("도서선택과 이력관리 영역");
                $("#lblSubTitle").text("도서 이력관리 활용 능력");
            } else if (count > 23 && count < 34) {
                $("#lblTitle").text("독서활동영역");
                $("#lblSubTitle").text("독서전 활동");
            } else if (count > 33 && count < 46) {
                $("#lblSubTitle").text("독서중 활동");
            } else if (count > 45 && count < 63) {
                $("#lblSubTitle").text("독서후 활동");
            } else if (count > 62 && count < 82) {
                $("#lblTitle").text("과거 독서이력 영역");
                $("#lblSubTitle").text("분포도 - 편독");
            } else if (count > 81 && count < 101) {
                $("#lblTitle").text("현재 독서분야 및 분량 영역");
            }

            if (count >= 63 && count <= 82) {
                $("#lbl1").text("매우 많이 읽음");
                $("#lbl2").text("많이 읽음");
                $("#lbl3").text("보통");
                $("#lbl4").text("조금 읽음");
                $("#lbl5").text("거의 읽지 않음");
            } else if (count >= 83 && count <= 100) {
                $("#lbl1").text("4권이상");
                $("#lbl2").text("3권");
                $("#lbl3").text("2권");
                $("#lbl4").text("1권");
                $("#lbl5").text("0권");
            } else {
                $("#lbl1").text("정말그렇다");
                $("#lbl2").text("그런편이다");
                $("#lbl3").text("그저그렇다");
                $("#lbl4").text("아닌편이다");
                $("#lbl5").text("정말아니다");
            }

            // $("input:radio[name='rdoQuiz']").prop("checked", false);
        } else {
            alert("문항의 답을 선택해주세요 !");
            return false;
        }
    });
</script>
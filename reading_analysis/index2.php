<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 진단선택</title>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/css/styles.css" />
    <?php include_once $_SERVER["DOCUMENT_ROOT"] . "/common/common_script2.php" ?>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="card border-left-primary shadow mt-2">
                    <div class="card-header">
                        <h6>책읽기진단</h6>
                    </div>
                    <div class="card-body">
                        <p><mark><strong>문해력</strong></mark>은<br>
                            책읽기를 통해 키울 수 있습니다.<br>
                            <mark><strong>학습능력</strong></mark>은<br>
                            책읽기를 어떻게 했는냐에 달려 있습니다.
                        </p>
                        <div class="input-group justify-content-end mb-1">
                            <div class="form-inline">
                                <div class="form-floating">
                                    <select id="selGrade1" class="form-select">
                                        <option value="1">1학년</option>
                                        <option value="2">2학년</option>
                                        <option value="3">3학년</option>
                                        <option value="4">4학년</option>
                                        <option value="5">5학년</option>
                                        <option value="6">6학년</option>
                                    </select>
                                    <label for="selGrade1">학년</label>
                                </div>
                            </div>
                        </div>
                        <div class="text-end"><button type="button" id="btnSubmmit1" class="btn btn-outline-primary">진단하기</button></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-left-primary shadow mt-2">
                    <div class="card-header">
                        <h6>글쓰기진단</h6>
                    </div>
                    <div class="card-body">
                        <p>우리 아이가<br>
                            풍부한 배경지식을 바탕으로<br>
                            자신의 생각을 말과 글로<br>
                            잘 표현해낼 수 있다면? </p>
                        <div class="input-group justify-content-end mb-1">
                            <div class="form-inline">
                                <div class="form-floating">
                                    <select id="selGrade2" class="form-select">
                                        <option value="1">1학년</option>
                                        <option value="2">2학년</option>
                                        <option value="3">3학년</option>
                                        <option value="4">4학년</option>
                                        <option value="5">5학년</option>
                                        <option value="6">6학년</option>
                                    </select>
                                    <label for="selGrade2">학년</label>
                                </div>
                            </div>
                        </div>
                        <div class="text-end"><button type="button" id="btnSubmmit2" class="btn btn-outline-primary">진단하기</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#btnSubmmit1").click(function() {
                var grade = $("#selGrade1").val();
                location.href = "/reading_analysis/index2_reading.php?grade=" + grade;
            });
            $("#btnSubmmit2").click(function() {
                var grade = $("#selGrade2").val();
                location.href = "/reading_analysis/index2_writing.php?grade=" + grade;
            });
        });
    </script>
</body>

</html>
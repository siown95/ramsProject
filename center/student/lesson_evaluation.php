<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />

    <?php
    $weekDay = array("일", "월", "화", "수", "목", "금", "토");
    $stat = 'student';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
    <script type="text/javascript" src="js/lesson_evaluation.js?v=<?= date('YmdHis') ?>"></script>
    <script>
        var center_idx = '<?= $_SESSION['center_idx'] ?>';
        var student_idx = '<?= $_SESSION['logged_no'] ?>';
    </script>
</head>

<body class="size-14">
    <!-- 헤더 -->
    <?php include "navbar.php"; ?>

    <!-- 컨텐츠 -->
    <main style="min-height: 79vh;">
        <div class="container-fluid">
            <div class="card border-left-primary shadow mt-2 mb-2">
                <div class="card-header">
                    <h6 class="card-title">수업평가</h6>
                </div>
                <div class="card-body">
                    <div class="input-group justify-content-end mb-2">
                        <div class="form-inline">
                            <div class="form-floating">
                                <input type="month" id="dtMonths1" class="form-control" value="<?= date('Y-m') ?>">
                                <label for="dtMonths">년월</label>
                            </div>
                        </div>
                    </div>
                    <table id="WeekEvalTable" class="table table-sm table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="align-middle text-center" width="10%" rowspan="2">일자</th>
                                <th class="align-middle text-center" width="10%" rowspan="2">독서</th>
                                <th class="align-middle text-center" width="40%" colspan="4">토론</th>
                                <th class="align-middle text-center" width="40%" colspan="4">글쓰기</th>
                            </tr>
                            <tr>
                                <th class="align-middle text-center" width="10%">발언</th>
                                <th class="align-middle text-center" width="10%">듣기</th>
                                <th class="align-middle text-center" width="10%">참여</th>
                                <th class="align-middle text-center" width="10%">태도</th>
                                <th class="align-middle text-center" width="10%">어휘문법</th>
                                <th class="align-middle text-center" width="10%">문장문단</th>
                                <th class="align-middle text-center" width="10%">논리사고</th>
                                <th class="align-middle text-center" width="10%">창의사고</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle text-center">
                        </tbody>
                    </table>
                    <div class="input-group justify-content-end mb-2">
                        <div class="form-inline align-self-center w-5 me-2">
                            <div class="form-floating">
                                <input id="evalDatepicker" class="form-control" value="<?= date("Y") ?>" readonly />
                                <label for="evalDatepicker">연도</label>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-floating">
                                <select id="selDivide" class="form-select">
                                    <?php
                                    if ((int)(date('m')) >= 7) {
                                        echo "<option value=\"1\">상반기</option><option value=\"2\" selected>하반기</option>";
                                    } else {
                                        echo "<option value=\"1\" selected>상반기</option><option value=\"2\">하반기</option>";
                                    }
                                    ?>
                                </select>
                                <label for="selDivide">반기</label>
                            </div>
                        </div>
                        <input type="hidden" id="eval_idx">
                    </div>
                    <table class="table table-sm table-hover table-bordered">
                        <thead class="align-middle text-center">
                            <th colspan="2">종합평가</th>
                        </thead>
                        <tbody class="align-middle">
                            <tr>
                                <th width="10%" class="align-middle text-center">종합 관찰내용</th>
                                <td id="eval_c1"></td>
                            </tr>
                            <tr class="text-center">
                                <th colspan="2">학생평가</th>
                            </tr>
                            <tr>
                                <th class="align-middle text-center">책읽기</th>
                                <td id="eval_c2"></td>
                            </tr>
                            <tr>
                                <th class="align-middle text-center">토론/참여</th>
                                <td id="eval_c3"></td>
                            </tr>
                            <tr>
                                <th class="align-middle text-center">글쓰기</th>
                                <td id="eval_c4"></td>
                            </tr>
                            <tr>
                                <th class="align-middle text-center">주도/인성</th>
                                <td id="eval_c5"></td>
                            </tr>
                            <tr>
                                <th class="align-middle text-center">지도한 내용</th>
                                <td id="eval_c6"></td>
                            </tr>
                            <tr>
                                <th class="align-middle text-center">학부모님 요청사항</th>
                                <td id="eval_c7">
                                    <textarea id="eval_c7_contents" class="form-control" rows="4" maxlength="500" placeholder="담당 지도교사에게 요청하실 내용을 입력해주세요."></textarea>
                                    <div class="text-end div_rs mt-1">
                                        <button type="button" id="btnRequestSave" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-reply me-1"></i>전달</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="align-middle text-center">앞으로의 지도방향</th>
                                <td id="eval_c8"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- 푸터 -->
    <?php include "footer.php"; ?>
</body>

</html>
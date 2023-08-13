<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
    <?php
    $stat = 'student';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
    <script src="js/reading_diagnosis.js?v=<?= date('YmdHis') ?>"></script>
</head>

<body class="size-14">
    <!-- 헤더 -->
    <?php
    include "navbar.php";
    $StudentName = $_SESSION['logged_name'];
    ?>
    <script>
        var userInfo = {
            user_no: '<?= $_SESSION['logged_no'] ?>',
            user_id: '<?= $_SESSION['logged_id'] ?>',
            user_name: '<?= $_SESSION['logged_name'] ?>',
            user_phone: '<?= phoneFormat($_SESSION['logged_phone']) ?>',
            user_email: '<?= $_SESSION['logged_email'] ?>'
        }
        var center_no = '<?= $_SESSION['center_idx'] ?>';
    </script>
    <!-- 컨텐츠 -->
    <main style="min-height: 79vh;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-7">
                    <div class="card border-left-primary h-100 shadow mt-2 mb-2">
                        <div class="card-header">
                            <h6 class="card-title">읽은책통계</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <table class="table table-sm table-hover table-bordered">
                                        <thead class="align-middle text-center">
                                            <tr>
                                                <th colspan="5">문학/비문학</th>
                                            </tr>
                                            <tr>
                                                <th>분야</th>
                                                <th><?= $StudentName ?> 님</th>
                                                <th>남자평균</th>
                                                <th>여자평균</th>
                                                <th>전체평균</th>
                                            </tr>
                                        </thead>
                                        <tbody class="align-middle text-center" id="lDiagnosis">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <table class="table table-sm table-hover table-bordered">
                                        <thead class="align-middle text-center">
                                            <tr>
                                                <th colspan="5">주제별</th>
                                            </tr>
                                            <tr>
                                                <th>분야</th>
                                                <th><?= $StudentName ?> 님</th>
                                                <th>남자평균</th>
                                                <th>여자평균</th>
                                                <th>전체평균</th>
                                            </tr>
                                        </thead>
                                        <tbody class="align-middle text-center" id="lnonDiagnosis">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <canvas id="chart1"></canvas>
                                </div>
                                <div class="col-6">
                                    <canvas id="chart2"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="card border-left-primary h-100 shadow mt-2 mb-2">
                        <div class="card-header">
                            <h6 class="card-title"><?= $StudentName ?> 님 분석결과</h6>
                        </div>
                        <div class="card-body">
                            <strong>문학 / 비문학</strong><br>
                            <div id="txtTotalResult1"></div>
                            <strong>주제별 분류(문학, 인문, 사회, 과학수학, 체육예술, 진로, 기타)</strong><br>
                            <div id="txtTotalResult2"></div>
                            <br>
                            <strong>도서 주제별 세부 분류 권수</strong><br>
                            <p class="text-muted">도서에 대한 정보는 &#39;읽은 책 목록&#39;에서 확인하세요&#46;</p>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <table id="BookCategoryDetailTable" class="table table-sm table-bordered table-hover">
                                        <thead class="align-middle text-center">
                                            <th width="25%">1차분류</th>
                                            <th width="25%">2차분류</th>
                                            <th width="25%">3차분류</th>
                                            <th width="25%">권수</th>
                                        </thead>
                                        <tbody id="BookCategoryList" class="align-middle text-center"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-end mt-2">
                                <button type="button" id="btnRecommendBook" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#BookRecommendModal"><i class="fa-solid fa-book-bookmark me-1"></i>도서추천</button>
                            </div>

                            <div class="modal fade" id="BookRecommendModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">도서추천</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>센터에서 보유한 도서 중 읽지 않은 도서에서 무작위로 추출해 추천되며&#44; 기타로 분류된 도서는 추천되지 않습니다&#46;</p>
                                            <table id="RecommandBookTable" class="table table-sm table-bordered table-hover">
                                                <thead class="align-middle text-center">
                                                    <th>번호</th>
                                                    <th>도서명</th>
                                                    <th>저자</th>
                                                    <th>출판사</th>
                                                    <th>카테고리1</th>
                                                    <th>카테고리2</th>
                                                    <th>카테고리3</th>
                                                </thead>
                                                <tbody class="align-middle text-center"></tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- 푸터 -->
    <?php include "footer.php"; ?>
</body>

</html>
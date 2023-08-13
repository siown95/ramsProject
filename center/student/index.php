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
</head>

<body class="size-14">
    <!-- 헤더 -->
    <?php include "navbar.php"; ?>

    <!-- 컨텐츠 -->
    <main style="min-height: 79vh;">
        <div class="container-fluid">
            <div class="row mt-2 mb-2">
                <div class="col-12">
                    <div class="card border-left-primary h-100 shadow mt-2">
                        <div class="card-header bg-primary">
                            <h6 class="text-white">내수업</h6>
                        </div>
                        <div class="card-body">
                            <table id="lessonTable" class="table table-sm table-hover table-bordered">
                                <thead class="align-middle text-center">
                                    <th width="10%">종류</th>
                                    <th width="15%">수업일시</th>
                                    <th width="25%">수업도서</th>
                                    <th width="10%">지도교사</th>
                                    <th width="5%">토론실</th>
                                    <th width="5%">출석여부</th>
                                    <th width="10%">독서평가</th>
                                    <th width="10%">토론평가</th>
                                    <th width="10%">글쓰기평가</th>
                                </thead>
                                <tbody id="lessonList" class="align-middle text-center"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-6">
                    <div class="card border-left-primary h-100 shadow mt-2">
                        <div class="card-header bg-danger">
                            <h6 class="text-white">대출중도서</h6>
                        </div>
                        <div class="card-body">
                            <table id="rentBookTable" class="table table-sm table-hover table-bordered">
                                <thead class="align-middle text-center">
                                    <th>번호</th>
                                    <th>도서명</th>
                                    <th>저자</th>
                                    <th>출판사</th>
                                    <th>대출일</th>
                                    <th>반납예정일</th>
                                </thead>
                                <tbody id="rentBookList" class="align-middle text-center"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-left-primary h-100 shadow mt-2">
                        <div class="card-header bg-success">
                            <h6 class="text-white">읽은도서</h6>
                        </div>
                        <div class="card-body">
                            <table id="readBookTable" class="table table-sm table-hover table-bordered">
                                <thead class="align-middle text-center">
                                    <th>번호</th>
                                    <th>도서명</th>
                                    <th>저자</th>
                                    <th>출판사</th>
                                </thead>
                                <tbody id="readBookList" class="align-middle text-center"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2 mb-2">
                <div class="col-6">
                    <div class="card border-left-primary h-100 shadow mt-2">
                        <div class="card-header bg-dark">
                            <h6 class="text-white">상담내용</h6>
                        </div>
                        <div class="card-body">
                            <table id="RecentCounselTable" class="table table-sm table-hover table-bordered">
                                <thead class="align-middle text-center">
                                    <th>번호</th>
                                    <th>상담종류</th>
                                    <th>작성자</th>
                                    <th>상담일</th>
                                </thead>
                                <tbody id="recentCounselList" class="align-middle text-center"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-left-primary h-100 shadow mt-2">
                        <div class="card-header bg-secondary">
                            <h6 class="text-white">종합평가</h6>
                        </div>
                        <div class="card-body">
                            <table id="SemiAnnualEvalTable" class="table table-sm table-hover table-bordered">
                                <thead class="align-middle text-center">
                                    <th>년도</th>
                                    <th>반기</th>
                                    <th>독서</th>
                                    <th>토론</th>
                                    <th>글쓰기</th>
                                    <th>주도&#47;인성</th>
                                </thead>
                                <tbody id="recentSemiAnnualEvaluationList" class="align-middle text-center"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2 mb-4">
                <div class="col-6">
                    <div class="card border-left-primary h-100 shadow mt-2">
                        <div class="card-header bg-info">
                            <h6 class="text-white">리딩엠공지사항</h6>
                        </div>
                        <div class="card-body">
                            <table id="noticeTable" class="table table-sm table-hover table-bordered">
                                <thead class="align-middle text-center">
                                    <th width="10%">번호</th>
                                    <th width="75%">제목</th>
                                    <th width="15%">작성일</th>
                                </thead>
                                <tbody id="noticeList" class="align-middle text-center"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-left-primary h-100 shadow mt-2">
                        <div class="card-header bg-warning">
                            <h6>교육센터알림</h6>
                        </div>
                        <div class="card-body">
                            <table id="centerNoticeTable" class="table table-sm table-hover table-bordered">
                                <thead class="align-middle text-center">
                                    <th width="10%">번호</th>
                                    <th width="75%">제목</th>
                                    <th width="15%">작성일</th>
                                </thead>
                                <tbody id="centerNoticeList" class="align-middle text-center"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        $(document).ready(function() {
            loadLessonMain();
            loadBookRentMain();
            loadBookReadMain();
            loadRecentCounselMain();
            loadSemiAnnualEvaluationMain();
            loadBoardMain();
            loadCenterNoticeMain();

            $("#lessonTable").click(function() {
                location.href = 'lesson_info.php';
            });

            $("#rentBookTable").click(function() {
                location.href = 'rentbook_list.php';
            });

            $("#readBookTable").click(function() {
                location.href = 'reading_list.php';
            });

            $("#noticeTable").click(function() {
                location.href = 'board_list.php';
            });

            $("#centerNoticeTable").click(function() {
                location.href = 'center_notice_list.php';
            });

            $("#RecentCounselTable").click(function() {
                location.href = 'counsel_list.php';
            });

            $("#SemiAnnualEvalTable").click(function() {
                location.href = 'lesson_evaluation.php';
            });
        });

        function loadLessonMain() {
            $.ajax({
                url: '/center/student/ajax/lessonScheduleControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'loadLessonMain',
                    center_idx: '<?= $_SESSION['center_idx'] ?>',
                    student_idx: '<?= $_SESSION['logged_no'] ?>',
                    standardMonth: '<?= date("Y-m") ?>'
                },
                success: function(result) {
                    if (result.success) {
                        $("#lessonList").html(result.data.tbl);
                    }
                },
                error: function(request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }

        function loadBookRentMain() {
            $.ajax({
                url: '/center/student/ajax/bookRentControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'loadBookRentMain',
                    center_idx: '<?= $_SESSION['center_idx'] ?>',
                    student_no: '<?= $_SESSION['logged_no'] ?>'
                },
                success: function(result) {
                    if (result.success) {
                        $("#rentBookList").html(result.data.tbl);
                    }
                },
                error: function(request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }

        function loadBookReadMain() {
            $.ajax({
                url: '/center/student/ajax/bookRentControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'loadBookReadMain',
                    center_idx: '<?= $_SESSION['center_idx'] ?>',
                    student_no: '<?= $_SESSION['logged_no'] ?>'
                },
                success: function(result) {
                    if (result.success) {
                        $("#readBookList").html(result.data.tbl);
                    }
                },
                error: function(request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }

        function loadBoardMain() {
            $.ajax({
                url: '/center/student/ajax/boardControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'loadBoardMain'
                },
                success: function(result) {
                    if (result.success) {
                        $("#noticeList").html(result.data.tbl);
                    }
                },
                error: function(request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }

        function loadCenterNoticeMain() {
            $.ajax({
                url: '/center/student/ajax/boardCenterNoticeControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'loadCenterNoticedMain',
                    center_idx: '<?= $_SESSION['center_idx'] ?>',
                    student_no: '<?= $_SESSION['logged_no'] ?>',
                },
                success: function(result) {
                    if (result.success) {
                        $("#centerNoticeList").html(result.data.tbl);
                    }
                },
                error: function(request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }

        function loadRecentCounselMain() {
            $.ajax({
                url: '/center/student/ajax/counselControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'loadRecentCounselMain',
                    center_idx: '<?= $_SESSION['center_idx'] ?>',
                    student_no: '<?= $_SESSION['logged_no'] ?>',
                },
                success: function(result) {
                    if (result.success) {
                        $("#recentCounselList").html(result.data.tbl);
                    }
                },
                error: function(request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }

        function loadSemiAnnualEvaluationMain() {
            $.ajax({
                url: '/center/student/ajax/lessonEvaluationControll.ajax.php',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    action: 'loadSemiAnnualEvaluationMain',
                    center_idx: '<?= $_SESSION['center_idx'] ?>',
                    student_no: '<?= $_SESSION['logged_no'] ?>',
                },
                success: function(result) {
                    if (result.success) {
                        $("#recentSemiAnnualEvaluationList").html(result.data.tbl);
                    }
                },
                error: function(request, status, error) {
                    alert("request : " + request + "\n" + "status : " + status + "\n" + "error : " + error);
                }
            });
        }
    </script>
    <!-- 푸터 -->
    <?php include "footer.php"; ?>
</body>

</html>
<nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <div class="container-fluid">
        <!-- 로고 -->
        <a class="navbar-brand ps-3" href="index.php">
            <img class="img rounded-pill" src="/img/logo.png" />
            <span class="align-middle ms-1">리딩엠</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- 링크 -->
        <div id="collapsibleNavbar" class="collapse navbar-collapse justify-content-between">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="lesson_info.php"><i class="fa-solid fa-calendar-days me-1"></i>수업정보</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="lesson_evaluation.php"><i class="fa-solid fa-chalkboard me-1"></i>수업평가</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="counsel_list.php"><i class="fa-solid fa-clipboard-list me-1"></i>상담정보</a>
                </li>
                <li class="nav-item">
                    <span class="nav-link text-white"><i class="fa-solid fa-grip-lines-vertical"></i></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="rentbook_list.php"><i class="fa-solid fa-book me-1"></i>대출도서</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reading_list.php"><i class="fa-solid fa-book-open-reader me-1"></i>읽은 책 목록</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reading_diagnosis.php"><i class="fa-solid fa-chart-column me-1"></i>읽은 책 분석</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="write.php"><i class="fa-regular fa-pen-to-square me-1"></i>글쓰기</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:diagnosis()"><i class="fa-solid fa-square-poll-vertical me-1"></i>독서이력진단</a>
                </li>
                <li class="nav-item">
                    <span class="nav-link text-white"><i class="fa-solid fa-grip-lines-vertical"></i></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="board_list.php"><i class="fa-solid fa-circle-exclamation me-1"></i>공지사항</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="magazine.php"><i class="fa-solid fa-box-archive me-1"></i>리딩엠매거진</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="receipt.php"><i class="fa-solid fa-money-check me-1"></i>원비결제</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user me-1"></i><?= $StudentName ?>님<i class="fas fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">설정</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li><a class="dropdown-item" href="#!">로그아웃</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

</nav>
<?php
$pageName = mb_split('/', $_SERVER['PHP_SELF']);
$len = sizeof($pageName);
?>
<script>
    $("a[href$='<?= $pageName[$len - 1] ?>']").addClass("active");

    function diagnosis() {
        window.open("/center/reading_diagnosis.php", "a", "width=850, height=400, left=300, top=100");
    }
</script>
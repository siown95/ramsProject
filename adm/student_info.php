<!DOCTYPE html>
<html lang="ko">

<head>
    <!-- css / script -->
    <?php
    $stat = 'adm';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
    <script type="text/javascript" src="/adm/js/student_info.js?v=<?= date('YmdHis') ?>"></script>
</head>

<body class="sb-nav-fixed size-14">
    <!-- header navbar -->
    <?php include_once('navbar.html'); ?>
    <div id="layoutSidenav">
        <!-- sidebar -->
        <?php include_once('sidebar.html'); ?>

        <div id="Maincontent">
            <main>
                <div class="container-fluid px-4">
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-sm align-items-start align-self-center"><i class="fa-solid fa-person me-1"></i>학년별 / 합계</div>
                                        <div class="col-sm align-self-center text-end rgDate">
                                            <span>기간</span>
                                        </div>
                                        <div class="col-sm align-self-center">
                                            <input type="month" class="form-control" id="dtformDate" value="<?= date('Y-m') ?>">
                                        </div>
                                        <div class="col-sm align-items-end align-self-center text-end">
                                            <span>가맹구분</span>
                                        </div>
                                        <div class="col-sm align-items-end">
                                            <select id="selFranchiseType" class="form-select">
                                                <option value="all">전체</option>
                                                <option value="01">직영</option>
                                                <option value="02">가맹</option>
                                            </select>
                                        </div>
                                        <div class="col-sm align-items-end align-self-center text-end">
                                            <span>센터구분</span>
                                        </div>
                                        <div class="col-sm align-items-end">
                                            <select id="selFranchise" class="form-select" disabled>
                                                <option value="all">전체</option>
                                            </select>
                                        </div>
                                        <div class="col-sm align-items-end">
                                            <button type="button" id="btnSearch" class="btn btn-sm btn-secondary">검색</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div id="div_c1" class="col d-flex">
                                            <canvas id="Chart1"></canvas>
                                        </div>
                                        <div id="div_c2" class="col d-flex">
                                            <canvas id="Chart2"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php
            include_once('footer.html');
            ?>
        </div>
    </div>
</body>

</html>
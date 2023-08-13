<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 매출현황</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <?php
    $stat = 'adm';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
    <script type="text/javascript" src="js/sales_info.js?v=<?= date('YmdHis') ?>"></script>
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
                            <div class="card border-left-primary shadow h-100 mb-3">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-sm align-items-start align-self-center"><i class="fa-solid fa-sack-dollar me-1"></i>매출현황</div>
                                        <div class="col-sm align-self-center text-end rgDate">
                                            <span>기간</span>
                                        </div>
                                        <div class="col-sm align-self-center rgDate">
                                            <input id="dtYear" class="form-control" value="<?= date('Y') ?>" readonly>
                                        </div>
                                        <div class="col-sm align-items-end align-self-center text-end">
                                            <span>가맹구분</span>
                                        </div>
                                        <div class="col-sm align-items-end">
                                            <select id="selFranchiseType" class="form-select">
                                                <option value="All">전체</option>
                                                <option value="01">직영</option>
                                                <option value="02">가맹</option>
                                            </select>
                                        </div>
                                        <div class="col-sm align-items-end align-self-center text-end">
                                            <span>센터구분</span>
                                        </div>
                                        <div class="col-sm align-items-end">
                                            <select id="selCenter" class="form-select">
                                                <option value="">전체</option>
                                                <?php
                                                if (!empty($franchise_info)) {
                                                    foreach ($franchise_info as $key => $val) {
                                                        echo "<option value=\"" . $val['franchise_idx'] . "\">" . $val['center_name'] . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm align-items-end">
                                            <button type="button" id="btnSearch" class="btn btn-sm btn-secondary"><i class="fa-solid fa-magnifying-glass me-1"></i>검색</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6 chartreport1">
                                            <h6>월별매출</h6>
                                            <canvas id="Chart1"></canvas>
                                        </div>
                                        <div class="col-6 chartreport2">
                                            <h6>각센터별매출</h6>
                                            <canvas id="Chart2"></canvas>
                                        </div>
                                    </div>
                                    <div>
                                        <p>
                                            1. 납부예정 -> 미납부 + 납부 (교육비만)<br>
                                            2. 납부교육비 -> 납부 (교육비만)<br>
                                            3. 매출액 -> 납부 (교육비 + 교구비 + 교재비)<br>
                                            4. 교재교구비 -> 납부(미리내 국어 등) (교육비 제외)<br>
                                            5. 납부율 -> 납부교육비 / 납부예정 * 100 (소수점 3번째에서 반올림)
                                        </p>
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
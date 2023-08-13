<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 매출확정</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <?php
    $stat = "adm";
    include_once $_SERVER["DOCUMENT_ROOT"] . "/common/common_script.php";
    ?>
    <script type="text/javascript" src="js/sales_confirm.js?v=<?= date('YmdHis') ?>"></script>
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
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="card border-left-primary shadow h-100">
                                <div class="card-header">
                                    <div class="row justify-content-between">
                                        <div class="col align-self-center">
                                            <h6>매출내역</h6>
                                        </div>
                                        <div class="col-auto">
                                            <div class="input-group">
                                                <div class="form-inline">
                                                    <div class="form-floating">
                                                        <input type="month" id="dtSalesMonths" class="form-control" value="<?= date('Y-m') ?>" />
                                                        <label for="dtSalesMonths">매출년월</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="SalesInfoTable" class="table table-sm table-bordered table-hover">
                                        <thead class="align-middle text-center">
                                            <th>번호</th>
                                            <th>교육센터명</th>
                                            <th>매출년월</th>
                                            <th>결제일자</th>
                                            <th>결제금액&#40;계속가맹금&#41;</th>
                                            <th>환불일자</th>
                                            <th>환불금액</th>
                                            <th>상태</th>
                                        </thead>
                                        <tbody id="SalesInfoList" class="align-middle text-center"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php
            include_once $_SERVER["DOCUMENT_ROOT"] . "/adm/footer.html";
            ?>
        </div>
    </div>
</body>

</html>
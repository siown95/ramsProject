<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 교사매출현황</title>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
    <!-- css / script -->
    <?php
    $stat = 'adm';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
</head>

<body class="sb-nav-fixed size-14">
    <!-- header navbar -->
    <?php include_once('navbar.php'); ?>
    <div id="layoutSidenav">
        <!-- sidebar -->
        <?php include_once('sidebar.php'); ?>

        <div id="Maincontent">
            <main>
                <div class="container-fluid px-4">
                    <div class="row mt-3">
                        <div class="col-12 mb-2">
                            <div class="card border-left-primary shadow h-100 mb-3">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-sm align-items-start align-self-center"><i class="fa-solid fa-sack-dollar me-1"></i>교사 매출</div>
                                        <div class="col-sm align-self-center text-end rgDate">
                                            <span>기간</span>
                                        </div>
                                        <div class="col-sm align-self-center rgDate">
                                            <input type="month" class="form-control" name="dtDate">
                                        </div>
                                        <div class="col-sm align-items-end align-self-center text-end">
                                            <span>가맹구분</span>
                                        </div>
                                        <div class="col-sm align-items-end">
                                            <select id="selFranchiseType" class="form-select">
                                                <option value="">전체</option>
                                            </select>
                                        </div>
                                        <div class="col-sm align-items-end align-self-center text-end">
                                            <span>센터구분</span>
                                        </div>
                                        <div class="col-sm align-items-end">
                                            <select id="selCenter" class="form-select">
                                                <option value="">전체</option>
                                            </select>
                                        </div>
                                        <div class="col-sm align-items-end align-self-center text-end">
                                            <span>담당구분</span>
                                        </div>
                                        <div class="col-sm align-items-end">
                                            <select id="selTeacher" class="form-select">
                                                <option value="">전체</option>
                                            </select>
                                        </div>
                                        <div class="col-sm align-items-end align-self-center text-end">
                                            <span>수업종류</span>
                                        </div>
                                        <div class="col-sm align-items-end">
                                            <select class="form-select">
                                                <option value="">전체</option>
                                            </select>
                                        </div>
                                        <div class="col-sm align-items-end">
                                            <button type="button" id="btnSearch" class="btn btn-sm btn-secondary"><i class="fa-solid fa-magnifying-glass me-1"></i>검색</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-bordered table-hover">
                                        <thead class="align-middle text-center">
                                            <th>이름</th>
                                            <th>년월</th>
                                            <th>담당학생수</th>
                                            <th>교육비</th>
                                            <th>납부교육비</th>
                                            <th>납부율</th>
                                        </thead>
                                        <tbody>
                                            <tr class="align-middle text-center">
                                                <td></td>
                                                <td></td>
                                                <td class="align-middle text-end"></td>
                                                <td class="align-middle text-end"></td>
                                                <td class="align-middle text-end"></td>
                                                <td class="align-middle text-end"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer text-end"><?php echo Date('Y-m-d H:i:s'); ?> 업데이트됨</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card border-left-primary shadow h-100 mb-3">
                                <div class="card-header">
                                    <h6><i class="fa-solid fa-sack-dollar me-1"></i>상세 교사 매출</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-bordered table-hover">
                                        <thead class="align-middle text-center">
                                            <th>이름</th>
                                            <th>학년</th>
                                            <th>교육비</th>
                                            <th>납부교육비</th>
                                            <th>결제방법</th>
                                        </thead>
                                        <tbody>
                                            <tr class="align-middle text-center">
                                                <td></td>
                                                <td></td>
                                                <td class="align-middle text-end"></td>
                                                <td class="align-middle text-end"></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </main>
            <!-- footer -->
            <?php
            include_once('footer.php');
            ?>
        </div>
    </div>

</body>

</html>
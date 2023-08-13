<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 포인트</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <?php
    $stat = "adm";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/common/common_script.php";
    ?>
    <script src="/adm/js/franchisee_point.js?v=<?= date("YmdHis") ?>"></script>
</head>

<body class="sb-nav-fixed size-14">
    <!-- header navbar -->
    <?php include_once('navbar.html'); ?>
    <div id="layoutSidenav">
        <!-- sidebar -->
        <?php include_once('sidebar.html'); ?>

        <div id="Maincontent">
            <main>
                <div class="container-fluid px-4 mt-3">
                    <!-- 콘텐츠 -->
                    <div class="row">
                        <div class="col-3">
                            <div class="card border-left-primary shadow h-100">
                                <div class="card-body">
                                    <h6>포인트 지급</h6>
                                    <div class="input-group mb-2">
                                        <div class="form-floating">
                                            <select id="selFranchisee" class="form-select">
                                                <option value="">선택</option>
                                                <?php
                                                $infoClassCmp = new infoClassCmp();
                                                $franchisee_option = $infoClassCmp->searchFranchisee(1);
                                                if (!empty($franchisee_option)) {
                                                    foreach ($franchisee_option as $key => $val) {
                                                        echo "<option value=\"" . $val['franchise_idx'] . "\">" . $val['center_name'] . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <label for="selFranchisee">센터(가맹 / 임대)</label>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="form-inline me-2">
                                            <div class="form-floating">
                                                <input type="text" id="txtPoint" class="form-control" placeholder="지급할 포인트를 입력">
                                                <label class="form-label">포인트</label>
                                            </div>
                                        </div>
                                        <div class="form-inline align-self-center">
                                            <button type="button" id="btnSave" class="btn btn-outline-success"><i class="fa-solid fa-gift me-1"></i>지급</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="card border-left-primary shadow h-100">
                                <div class="card-body">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <th>번호</th>
                                            <th>결제일시</th>
                                            <th>교육센터</th>
                                            <th>결제수단</th>
                                            <th>결제금액</th>
                                            <th>포인트잔액</th>
                                        </thead>
                                        <tbody>
                                            <tr class="tc">
                                                <th></th>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
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
            include_once('footer.html');
            ?>
        </div>
    </div>
</body>

</html>
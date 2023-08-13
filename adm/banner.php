<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 배너 관리</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <?php
    $stat = "adm";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/common/common_script.php";
    ?>
    <script type="text/javascript" src="js/banner.js"></script>
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
                                <div class="card-header">
                                    <h6>배너 설정</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-floating mb-2">
                                        <input type="text" id="txtBannerLink" class="form-control" placeholder="연결할 링크를 입력해주세요.">
                                        <label class="form-label" for="txtBannerLink">링크</label>
                                    </div>
                                    <div class="input-group mb-2">
                                        <div class="form-inline me-2">
                                            <div class="form-floating">
                                                <input type="date" id="dtFromDate" class="form-control" value="<?= date('Y-m-d') ?>" />
                                                <label class="form-label" for="dtFromDate">배너노출 시작일</label>
                                            </div>
                                        </div>
                                        <div class="form-inline me-2">
                                            <div class="form-floating">
                                                <input type="date" id="dtToDate" class="form-control" />
                                                <label class="form-label" for="dtToDate">배너노출 종료일</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-2">
                                        <div class="form-check form-check-inline align-self-center">
                                            <input type="checkbox" id="chkUseYn" class="form-check-input" />
                                            <label class="form-check-label" for="chkUseYn">배너숨김</label>
                                        </div>
                                        <div class="form-check form-check-inline align-self-center me-2">
                                            <input type="checkbox" id="chkMainYn" class="form-check-input" />
                                            <label class="form-check-label" for="chkMainYn">우선배너</label>
                                        </div>
                                        <div class="form-inline">
                                            <div class="form-floating mb-2">
                                                <input type="text" id="txtOrders" class="form-control" maxlength="2" numberOnly>
                                                <label class="form-label" for="txtOrders">순서</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-2">
                                        <input type="file" id="fileBannerImage" class="form-control" accept="image/png, image/jpg, image/jpeg, image/bmp, image/gif" />
                                        <input type="hidden" id="txtImageName" />
                                    </div>
                                    <div class="text-end">
                                        <button type="button" id="btnSave" class="btn btn-outline-primary"><i class="fa-solid fa-floppy-disk me-1"></i>저장</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="card border-left-primary shadow h-100">
                                <div class="card-header">
                                    <h6>배너목록</h6>
                                </div>
                                <div class="card-body">
                                    <table id="dataTable" class="table table-sm table-bordered table-hover">
                                        <thead class="align-middle text-center">
                                            <th width="5%">번호</th>
                                            <th width="10%">기간&#40;시작&#41;</th>
                                            <th width="10%">기간&#40;종료&#41;</th>
                                            <th>이미지</th>
                                            <th>링크</th>
                                            <th width="5%">순서</th>
                                            <th width="5%">우선</th>
                                            <th width="7%">숨김</th>
                                            <th width="7%">삭제</th>
                                        </thead>
                                        <tbody class="align-middle text-center">
                                        </tbody>
                                    </table>
                                    <hr>
                                    <p class="text-muted mt-3">배너는 최대 10개까지 우선 체크&#44; 순서순으로 노출됩니다&#46;<br>기간이 만료되거나&#44; 더 이상 사용하지 않는 배너는 삭제하시기 바랍니다&#46;</p>
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
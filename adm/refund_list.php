<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 환불내역</title>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
    <!-- css / script -->
    <?php
    $stat = 'adm';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
    <script type="text/javascript" src="js/refund.js?v=<?= date('YmdHis') ?>"></script>
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
                    <!-- 콘텐츠 -->
                    <div class="card border-left-primary shadow h-100 mt-3">
                        <div class="card-header">
                            <h6>환불내역</h6>
                        </div>
                        <div class="card-body">
                            <div class="input-group justify-content-end mb-2">
                                <div class="form-inline me-1">
                                    <div class="form-floating">
                                        <select id="selSearchType" class="form-select">
                                            <option value="i">원비</option>
                                            <option value="f">가맹금</option>
                                        </select>
                                        <label for="selSearchType">구분</label>
                                    </div>
                                </div>
                                <div class="form-inline">
                                    <div class="form-floating">
                                        <input type="month" id="refundMonth" class="form-control" value="<?= date('Y-m') ?>">
                                        <label for="refundMonth">환불년월</label>
                                    </div>
                                </div>
                            </div>
                            <table id="RefundTable" class="table table-bordered table-hover">
                                <thead class="align-middle text-center">
                                    <th>번호</th>
                                    <th>센터명</th>
                                    <th>이름</th>
                                    <th>전화번호</th>
                                    <th>결제수단</th>
                                    <th>결제금액</th>
                                    <th>환불일자</th>
                                    <th>환불금액</th>
                                    <th>환불신청일시</th>
                                </thead>
                                <tbody class="align-middle text-center"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal fade" id="RefundModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ModalLabel">환불 신청 처리</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="input-group mb-2">
                                            <div class="form-inline me-2">
                                                <div class="form-floating">
                                                    <input type="text" id="txtStudentName" class="form-control" placeholder="이름" readonly />
                                                    <label for="txtStudentName">이름</label>
                                                </div>
                                            </div>
                                            <div class="form-inline me-2">
                                                <div class="form-floating">
                                                    <input type="text" id="txtCenterName" class="form-control" placeholder="센터" readonly />
                                                    <label for="txtCenterName">센터</label>
                                                </div>
                                            </div>
                                            <div class="form-inline">
                                                <div class="form-floating">
                                                    <input type="text" id="txtHp" class="form-control" placeholder="전화번호" readonly />
                                                    <label for="txtHp">전화번호</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-2">
                                            <div class="form-inline me-2">
                                                <div class="form-floating">
                                                    <input type="text" id="txtPayAmount" class="form-control" placeholder="결제금액" readonly />
                                                    <label for="txtPayAmount">결제금액</label>
                                                </div>
                                            </div>
                                            <div class="form-inline me-2">
                                                <div class="form-floating">
                                                    <input type="text" id="txtRefundRequestAmount" class="form-control" placeholder="신청환불금액" readonly />
                                                    <label for="txtRefundRequestAmount">신청환불금액</label>
                                                </div>
                                            </div>
                                            <div class="form-inline">
                                                <div class="form-floating">
                                                    <input type="text" id="txtRefundAmount" class="form-control bg-opacity-25 bg-danger" placeholder="환불금액" />
                                                    <label for="txtRefundAmount">환불금액</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-2">
                                            <input type="text" id="txtRefundEtc" class="form-control" placeholder="환불메모" maxlength="100" />
                                            <label for="txtRefundEtc">환불메모</label>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                                    <button type="button" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i>저장</button>
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
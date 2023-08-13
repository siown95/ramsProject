<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 결재관리</title>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
    <!-- css / script -->
    <?php
        $stat = 'adm';
        include_once ($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
</head>

<body class="sb-nav-fixed">
    <!-- header navbar -->
    <?php include_once('navbar.html'); ?>
    <div id="layoutSidenav">
        <!-- sidebar -->
        <?php include_once('sidebar.html'); ?>

        <div id="Maincontent">
            <main>
                <div class="container-fluid px-4">
                    <!-- 콘텐츠 -->
                    <div class="row">
                        <div class="col-12 mt-3">
                            <div class="card border-left-primary shadow h-100">
                                <div class="card-body">
                                    <h6>결재상신</h6>
                                    <div class="input-group justify-content-end mb-2">
                                        <div class="form-inline me-2">
                                            <label class="form-label">결재종류</label>
                                        </div>
                                        <div class="form-inline">
                                            <select id="selApprovalKind" class="form-select">
                                                <option value="">선택</option>
                                                <option value="01">제안&#40;건의&#41;서</option>
                                                <option value="02">사직서</option>
                                                <option value="03">복직원</option>
                                                <option value="04">시간외 근무신청서</option>
                                                <option value="05">서면진술서</option>
                                                <option value="06">휴직신청서</option>
                                                <option value="07">휴가/조퇴 신청서</option>
                                                <option value="08">육아휴직 신청서</option>
                                                <option value="09">출산전후 휴가신청서</option>
                                                <option value="10">지각사유서</option>
                                            </select>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-hover">
                                        <thead class="text-center">
                                            <th>번호</th>
                                            <th>결재종류</th>
                                            <th>결재처리일</th>
                                            <th>결재상신일</th>
                                            <th>상태</th>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                    <div class="text-end">
                                        <button type="button" id="btnAdd" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-signature"></i> 결재작성</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="ApprovalModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 id="ModalLabel" class="modal-title">결재 정보</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- 테이블 클릭 시 해당 정보 Ajax 처리 -->
                                <form id="approvalForm">

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                                <button type="button" class="btn btn-primary">결재하기</button>
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

    <script>
        $('#btnAdd').click(function() {
            var val = $('#selApprovalKind').val();
            if (val == '') {
                alert('결재 종류를 선택해주세요.');
                return false;
            } else {
                $.ajax({
                    url: 'approval_document.php',
                    type: 'post',
                    data: {
                        data: val
                    },

                    success: function(result) {
                        console.log(result);
                        $('#approvalForm').html(result.data);
                        $('#ModalLabel').text(result.title);
                        $('#ApprovalModal').modal('show');
                    },
                    error: function(data) {
                        console.log(data.msg);
                    }
                });
            }
        });
    </script>

</body>

</html>
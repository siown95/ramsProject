<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 다빈치 수업 영상</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <!-- css -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
    <!-- script -->
    <script src="js/scripts.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js" integrity="sha512-pax4MlgXjHEPfCwcJLQhigY7+N8rt6bVvWLFyUMuxShv170X53TRzGPmPkZmGBhk+jikR8WBM4yl7A9WMHHqvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.0/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <!-- header navbar -->
    <?php include_once('navbar.php'); ?>
    <div id="layoutSidenav">
        <!-- sidebar -->
        <?php include_once('sidebar.php'); ?>

        <div id="Maincontent">
            <main>
                <div class="container-fluid px-4 mt-3">
                    <!-- 콘텐츠 -->
                    <div class="card border-left-primary shadow h-100">
                        <div class="card-body">
                            <h6 class="mb-3">활동지목록</h6>
                            <table class="table table-bordered table-hover table-responsive">
                                <thead class="text-center align-middle">
                                    <th>번호</th>
                                    <th>수업영상명</th>
                                    <th>내용</th>
                                    <th>분야</th>
                                    <th>세부분류</th>
                                    <th>레벨</th>
                                </thead>
                                <tbody class="align-middle">
                                    <tr class="tc">
                                        <th class="text-center"></th>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card border-left-primary shadow h-100 mt-2">
                        <div class="card-body">
                            <div class="input-group mb-2">
                                <label class="form-label">수업영상 / 도서명</label>
                            </div>
                            <div class="input-group mb-2">
                                <input type="text" id="txtVideoName" class="form-control" maxlength="50" placeholder="수업영상 / 도서명">
                            </div>
                            <div class="input-group mb-2">
                                <div class="form-inline">
                                    <label class="form-label">분야</label>
                                </div>
                                <div class="form-inline ms-2">
                                    <select class="form-select">
                                        <option value="">선택</option>
                                    </select>
                                </div>
                                <div class="form-inline ms-2">
                                    <label class="form-label">세부분류</label>
                                </div>
                                <div class="form-inline ms-2">
                                    <select class="form-select">
                                        <option value="">선택</option>
                                    </select>
                                </div>
                                <div class="form-inline ms-2">
                                    <label class="form-label">레벨</label>
                                </div>
                                <div class="form-inline ms-2">
                                    <select class="form-select">
                                        <option value="">선택</option>
                                    </select>
                                </div>
                            </div>
                            <table class="table">
                                <tbody class="align-middle">
                                    <tr>
                                        <td>동영상 링크</td>
                                    </tr>
                                    <tr>
                                        <td>동영상 링크 1</td>
                                        <td><input type="text" id="txtVideoLink1" class="form-control"></td>
                                        <td>동영상 링크 2</td>
                                        <td><input type="text" id="txtVideoLink2" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>내용 / 지도서 PDF</td>
                                    </tr>
                                    <tr>
                                        <td>책내용소개 PDF</td>
                                        <td><input type="file" id="fileIntroduce" class="form-control"></td>
                                        <td>교사용지도서 PDF</td>
                                        <td><input type="file" id="fileTeacher" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-end">
                                <button type="button" id="btnSave" class="btn btn-outline-primary">저장</button>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="VideoModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ModalLabel">영상 정보</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center">
                                        <a id="linkVideo1" class="btn btn-outline-primary" href="#">동영상 1</a>
                                        <a id="linkVideo2" class="btn btn-outline-primary" href="#">동영상 2</a>
                                        <a id="linkIntroduce" class="btn btn-outline-primary" href="#">책 소개 PDF</a>
                                        <a id="linkTeacher" class="btn btn-outline-primary" href="#">교사용 지도서 PDF</a>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">&copy; 리딩엠 2022</div>
                        <div class="mb-2">
                            <span>책읽기와 글쓰기 리딩엠</span> &#124;
                            <span>서울특별시 서초구 고무래로 10길 27 주호빌딩 402호</span> &#124;
                            <span><a href="tel:02-537-2248">Tel &#58; 02&#45;537&#45;2248</a></span> &#124;
                            <span>FAX &#58; 02&#45;2646&#45;8825</span><br>
                            <span>대표 &#58; 황종일</span> &#124;
                            <span>사업자등록번호 &#58; 105&#45;85&#45;39631</span> &#124;
                            <span>통신판매신고번호 &#58; 제2018&#45;서울서초&#45;2046호</span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script>
        $('.tc').click(function() {
            $('#VideoModal').modal('show');
        });
    </script>
</body>

</html>
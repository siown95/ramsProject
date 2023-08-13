<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 쓴 글보기</title>
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
                            <h6>도서목록</h6>
                            <table class="table table-bordered table-hover">
                                <thead class="text-center">
                                    <th>번호</th>
                                    <th>이름</th>
                                    <th>센터명</th>
                                    <th>도서명</th>
                                    <th>주제</th>
                                    <th>작성일</th>
                                </thead>
                                <tbody class="text-center">
                                    <tr class="tc">
                                        <th scope="row">2</th>
                                        <td>홍길동</td>
                                        <td>목동직영</td>
                                        <td>엄마 돌보기</td>
                                        <td>엄마 돌보기 독후감</td>
                                        <td>2022-05-18</td>
                                    </tr>
                                    <tr class="tc">
                                        <th scope="row">1</th>
                                        <td>김철수</td>
                                        <td>삼성직영</td>
                                        <td> 1(연습편) 초등 영재만 푸는 멘사 퍼즐</td>
                                        <td> 1(연습편) 초등 영재만 푸는 멘사 퍼즐 독후감</td>
                                        <td>2022-05-15</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="modal fade" id="BookReportModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">글쓰기</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form>
                                                <table class="table">
                                                    <tbody>
                                                        <tr class="align-middle">
                                                            <th class="text-center" scope="row">도서명</th>
                                                            <td></td>
                                                            <th class="text-center">저자</th>
                                                            <td></td>
                                                            <th class="text-center">출판사</th>
                                                            <td></td>
                                                        </tr>
                                                        <tr class="align-middle">
                                                            <th class="text-center">센터</th>
                                                            <td colspan="2">
                                                                <select class="form-select" disabled>
                                                                    <option></option>
                                                                </select>
                                                            </td>
                                                            <th class="text-center">이름</th>
                                                            <td colspan="2"></td>
                                                        </tr>
                                                        <tr class="align-middle">
                                                            <th class="text-center">논제</th>
                                                            <td colspan="5"><input type="text" class="form-control"></td>
                                                        </tr>
                                                        <tr class="align-middle">
                                                            <th class="text-center">내용</th>
                                                            <td colspan="5"><textarea class="form-control" rows="8"></textarea></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                                            <button type="button" class="btn btn-primary">저장</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" id="btnAdd" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#BookReportModal">추가</button>
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
            $('#BookReportModal').modal('show');
        });
    </script>
</body>

</html>
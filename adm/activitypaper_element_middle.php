<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 초/중등 활동지</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <!-- css -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
    <!-- script -->
    <script src="js/scripts.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js" integrity="sha512-pax4MlgXjHEPfCwcJLQhigY7+N8rt6bVvWLFyUMuxShv170X53TRzGPmPkZmGBhk+jikR8WBM4yl7A9WMHHqvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <!-- header navbar -->
    <?php include_once $_SERVER['DOCUMENT_ROOT'].'/adm/navbar.html'; ?>
    <div id="layoutSidenav">
        <!-- sidebar -->
        <?php include_once $_SERVER['DOCUMENT_ROOT'].'/adm/sidebar.html'; ?>

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
                                    <th>ISBN</th>
                                    <th style="width: 15%;">도서이미지</th>
                                    <th>도서명</th>
                                    <th>저자</th>
                                    <th>출판사</th>
                                    <th>카테고리</th>
                                    <th>다운로드</th>
                                    <th>뷰어</th>
                                </thead>
                                <tbody class="text-center">
                                    <tr class="align-middle tc">
                                        <th scope="row">2</th>
                                        <td>9788958621782</td>
                                        <td><img src="img/test.jpg" class="img w-50" alt="image sample" /></td>
                                        <td>각시각시 풀각시</td>
                                        <td>이춘희</td>
                                        <td>사파리</td>
                                        <td>사회(비문학)</td>
                                        <td><a class="btn btn-sm btn-outline-primary" href="activitypaper/test.pdf" download>학생용</a></td>
                                        <td><a class="btn btn-sm btn-outline-primary" href="activitypaper/test.pdf" target="_blank">학생용</a></td>
                                    </tr>
                                    <tr class="align-middle tc">
                                        <th scope="row">1</th>
                                        <td>9788958621782</td>
                                        <td><img src="img/test.jpg" class="img w-50" alt="image sample" /></td>
                                        <td>각시각시 풀각시</td>
                                        <td>이춘희</td>
                                        <td>사파리</td>
                                        <td>사회(비문학)</td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-primary" href="activitypaper/test.pdf" download>학생용</a><br>
                                            <a class="btn btn-sm btn-outline-primary" href="activitypaper/test.pdf" download>교사용</a><br>
                                            <a class="btn btn-sm btn-outline-danger" href="activitypaper/test.pdf" download>MB학생용</a><br>
                                            <a class="btn btn-sm btn-outline-danger" href="activitypaper/test.pdf" download>MB교사용</a><br>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-primary" href="activitypaper/test.pdf" target="_blank">학생용</a><br>
                                            <a class="btn btn-sm btn-outline-primary" href="activitypaper/test.pdf" target="_blank">교사용</a><br>
                                            <a class="btn btn-sm btn-outline-danger" href="activitypaper/test.pdf" target="_blank">MB학생용</a><br>
                                            <a class="btn btn-sm btn-outline-danger" href="activitypaper/test.pdf" target="_blank">MB교사용</a><br>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card border-left-primary shadow h-100 mt-2">
                        <div class="card-body">
                            <form>
                                <div class="row align-items-center mb-2">
                                    <div class="input-group col-auto">
                                        <label class="form-label">도서이름</label>
                                    </div>
                                    <div class="input-group col text-start">
                                        <input type="hidden" id="BookNo">
                                        <input type="text" id="txtBookName" class="form-control" placeholder="도서이름" disabled>
                                    </div>
                                    <div class="input-group col">
                                        <button type="button" id="btnSearch" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#BookModal">도서검색</button>
                                    </div>
                                </div>
                                <table class="table">
                                    <tbody class="align-middle">
                                        <tr>
                                            <td>학생용활동지</td>
                                        </tr>
                                        <tr>
                                            <td>활동지1</td>
                                            <td><input type="file" id="fileStudentActivity1" class="form-control"></td>
                                            <td>활동지2</td>
                                            <td><input type="file" id="fileStudentActivity2" class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td>M베이직1</td>
                                            <td><input type="file" id="fileStudentMbActivity1" class="form-control"></td>
                                            <td>M베이직2</td>
                                            <td><input type="file" id="fileStudentMbActivity2" class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td>교사용지도서</td>
                                        </tr>
                                        <tr>
                                            <td>지도서1</td>
                                            <td><input type="file" id="fileTeacherActivity1" class="form-control"></td>
                                            <td>지도서2</td>
                                            <td><input type="file" id="fileTeacherActivity2" class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td>M베이직1</td>
                                            <td><input type="file" id="fileTeacherMbActivity1" class="form-control"></td>
                                            <td>M베이직2</td>
                                            <td><input type="file" id="fileTeacherMbActivity2" class="form-control"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>

                    <div class="modal fade" id="BookModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ModalLabel">도서 정보</h5>
                                    <button type="button" id="btnClose" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="SearchBookNo">
                                    <input type="text" id="txtSearchBookName" class="form-control" disabled>
                                    <form>
                                        <table class="table table-border table-hover">
                                            <thead>
                                                <th>번호</th>
                                                <th>ISBN</th>
                                                <th>도서명</th>
                                                <th>저자</th>
                                                <th>출판사</th>
                                                <th>카테고리</th>
                                            </thead>
                                            <tbody>
                                                <tr class="align-middle booktc">
                                                    <th>2</th>
                                                    <td></td>
                                                    <td>1</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="align-middle booktc">
                                                    <th>1</th>
                                                    <td></td>
                                                    <td>2</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                                    <button type="button" id="btnSelect" class="btn btn-primary">선택</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
            <!-- footer -->
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/adm/footer.html" ?>
        </div>
    </div>
    <script>
        $('.booktc').click(function() {
            $('#SearchBookNo').val($(this).children(":eq(0)").text());
            $('#txtSearchBookName').val($(this).children(":eq(2)").text());
        });
        $('#btnSelect').click(function() {
            $('#BookNo').val($('#SearchBookNo').val());
            $('#txtBookName').val($('#txtSearchBookName').val());
            $('#SearchBookNo').val('');
            $('#txtSearchBookName').val('');
            $('#BookModal').modal('hide');
        });
    </script>
</body>

</html>
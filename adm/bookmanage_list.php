<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 도서현황</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <!-- css -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
    <!-- script -->
    <script src="js/scripts.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js" integrity="sha512-pax4MlgXjHEPfCwcJLQhigY7+N8rt6bVvWLFyUMuxShv170X53TRzGPmPkZmGBhk+jikR8WBM4yl7A9WMHHqvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    <script src="https://cdn.jsdelivr.net/npm/chart.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.12.0/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">리딩엠</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Grid-->
        <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <button id="btnCommute" class="btn btn-sm btn-outline-light" type="button" value="1"><i class="fa-solid fa-clipboard-list"></i>출퇴근</button>
        </div>
        <!-- 네비바 메뉴-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user"></i> 관리자님<i class="fas fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">설정</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="#!">로그아웃</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <!-- 사이드바 메뉴 -->
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            대시보드
                        </a>

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseManage" aria-expanded="false" aria-controls="collapseManage">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            관리
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseManage" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="code.php">코드관리</a>
                            </nav>
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordion1">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#franchiseeManage" aria-expanded="false" aria-controls="franchiseeManage">
                                    교육센터
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="franchiseeManage" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion1">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="franchisee.php">교육센터관리</a>
                                        <a class="nav-link" href="student_info.php">원생현황</a>
                                    </nav>
                                </div>
                            </nav>
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordion2">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#personnelAdministration" aria-expanded="false" aria-controls="personnelAdministration">
                                    인사행정
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="personnelAdministration" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion2">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="employee.php">직원관리</a>
                                        <a class="nav-link" href="workcheck.php">출근부확인</a>
                                        <a class="nav-link" href="employee_edu.php">직원교육</a>
                                    </nav>
                                </div>
                            </nav>
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordion3">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#bookInfoAdmin" aria-expanded="false" aria-controls="bookInfoAdmin">
                                    도서정보
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="bookInfoAdmin" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion3">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="bookmanage.php">도서관리</a>
                                        <a class="nav-link" href="curriculum.php">커리큘럼관리</a>
                                        <a class="nav-link" href="bookmanage_list.php">도서현황</a>
                                        <a class="nav-link" href="bookreport.php">쓴글보기</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCustomerServices" aria-expanded="false" aria-controls="collapseCustomerServices">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            고객지원
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseCustomerServices" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="board_list.php">공지사항</a>
                                <a class="nav-link" href="fileboard_list.php">홍보자료</a>
                                <a class="nav-link" href="fileboard_list.php">운영자료</a>
                                <a class="nav-link" href="fileboard_list.php">인사자료</a>
                                <a class="nav-link" href="activity_error_report.php">활동지오류신고</a>
                            </nav>
                        </div>

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCurriculum" aria-expanded="false" aria-controls="collapseCurriculum">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            교육과정
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseCurriculum" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="activitypaper_element_middle.php">초중등활동지</a>
                            </nav>
                        </div>

                    </div>
                </div>
            </nav>
        </div>

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
                                    <th>ISBN</th>
                                    <th>도서명</th>
                                    <th>카테고리</th>
                                    <th>등록일</th>
                                </thead>
                                <tbody class="text-center">
                                    <tr class="tc">
                                        <th scope="row">2</th>
                                        <td>9788958621782</td>
                                        <td>어린이 살아있는 과학교과서</td>
                                        <td>과학환경</td>
                                        <td>2022-05-18</td>
                                    </tr>
                                    <tr class="tc">
                                        <th scope="row">1</th>
                                        <td>9788990954909</td>
                                        <td> 1(연습편) 초등 영재만 푸는 멘사 퍼즐</td>
                                        <td>수학일반</td>
                                        <td>2022-05-15</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="modal fade" id="BookModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">도서 정보</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form>
                                            <table class="table">
                                                <tbody>
                                                    <tr class="align-middle">
                                                        <th class="text-center" scope="row" colspan="3">도서명</th>
                                                        <td colspan="3"><input type="text" class="form-control" name="txtBookName" /></td>
                                                        <th class="text-center" scope="row" colspan="3">ISBN</th>
                                                        <td colspan="3"><input type="text" class="form-control" name="txtBookISBN" /></td>
                                                    </tr>
                                                    <tr class="align-middle">
                                                        <th class="text-center" scope="row" colspan="3">저자</th>
                                                        <td colspan="3"><input type="text" class="form-control" name="txtWriter" /></td>
                                                        <th class="text-center" colspan="3">출판사</th>
                                                        <td colspan="3"><input type="text" class="form-control" name="txtPublisher" /></td>
                                                    </tr>
                                                    <tr class="align-middle">
                                                        <th class="text-center" scope="row" colspan="3">카테고리</th>
                                                        <th class="text-center">1차</th>
                                                        <td colspan="2">
                                                            <select class="form-select">
                                                                <option value="">선택</option>
                                                                <option value=""></option>
                                                            </select>
                                                        </td>
                                                        <th class="text-center">2차</th>
                                                        <td colspan="2">
                                                            <select class="form-select">
                                                                <option value="">선택</option>
                                                                <option value=""></option>
                                                            </select>
                                                        </td>
                                                        <th class="text-center">3차</th>
                                                        <td colspan="2">
                                                            <select class="form-select">
                                                                <option value="">선택</option>
                                                                <option value=""></option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr class="align-middle">
                                                        <th class="text-center" scope="row" colspan="3">활동지유무</th>
                                                        <td colspan="9">
                                                            <div class="form-check form-switch">
                                                                <input id="chkActivity" class="form-check-input" type="checkbox" role="switch">
                                                                <label id="lblActivity" class="form-check-label" for="chkActivity">없음</label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="align-middle">
                                                        <th class="text-center" scope="row" colspan="3">MB활동지유무</th>
                                                        <td colspan="9">
                                                            <div class="form-check form-switch">
                                                                <input id="chkMbActivity" class="form-check-input" type="checkbox" role="switch">
                                                                <label id="lblMbActivity" class="form-check-label" for="chkMbActivity">없음</label>
                                                            </div>
                                                        </td>
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
                                <button id="btnAdd" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#BookModal">추가</button>
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
            $('#BookModal').modal('show');
        });
        $('#chkActivity').click(function() {
            if ($('#chkActivity').is(':checked') == true) {
                $('#lblActivity').text('있음');
            }
            else {
                $('#lblActivity').text('없음');
            }
        });
        $('#chkMbActivity').click(function() {
            if ($('#chkMbActivity').is(':checked') == true) {
                $('#lblMbActivity').text('있음');
            }
            else {
                $('#lblMbActivity').text('없음');
            }
        });
    </script>
</body>
</html>
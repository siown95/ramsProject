<!DOCTYPE html>
<html>

<head>
    <!-- css / script -->
    <?php
    $stat = 'adm';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
    <script src="/adm/js/activity_error_report.js?v=<?= date("YmdHis") ?>"></script>
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
                    <div class="row">
                        <div class="col-12 mt-3">
                            <div class="card border-left-primary shadow h-100">
                                <div class="card-body">
                                    <h6>활동지오류신고</h6>
                                    <table id="dataTable" class="table table-bordered table-hover">
                                        <thead class="text-center">
                                            <th width="10%">번호</th>
                                            <th width="55%">제목</th>
                                            <th width="10%">일자</th>
                                            <th width="15%">작성자</th>
                                            <th width="10%">상태</th>
                                        </thead>
                                        <tbody class="text-center" id="errorList">
                                        </tbody>
                                    </table>
                                    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ModalLabel">활동지 오류신고 답변</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form>
                                                        <table class="table">
                                                            <input type="hidden" id="error_idx">
                                                            <tbody>
                                                                <tr class="align-middle">
                                                                    <th class="text-center" scope="row" colspan="3">제목</th>
                                                                    <td colspan="9" id="error_title">활동지 오류신고 제목 테스트</td>
                                                                </tr>
                                                                <tr class="align-middle">
                                                                    <th class="text-center" scope="row" colspan="3">작성자</th>
                                                                    <td colspan="3" id="writer">목동직원 홍길동</td>
                                                                    <th class="text-center" colspan="3">작성일</th>
                                                                    <td colspan="3" id="write_date">2022-05-20</td>
                                                                </tr>
                                                                <tr class="align-middle">
                                                                    <th class="text-center align-middle" scope="row" colspan="3">첨부파일</th>
                                                                    <td colspan="3">
                                                                        <a class="btn btn-default" href="" id="exfile" download><i class="fa-solid fa-file-arrow-down"></i></a>
                                                                        <input type="hidden" id="file_name">
                                                                    </td>
                                                                    <th class="text-center align-middle" colspan="3">상태</th>
                                                                    <td colspan="3">
                                                                        <select id="selState" class="form-select">
                                                                            <option value="1">접수중</option>
                                                                            <option value="2">접수완료</option>
                                                                            <option value="3">수정완료</option>
                                                                            <option value="4">답변완료</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <tr class="align-middle">
                                                                    <th class="text-center" scope="row" colspan="3">내용</th>
                                                                    <td colspan="9">
                                                                        <textarea id="error_contents" class="form-control bg-white" rows="6" disabled></textarea>
                                                                    </td>
                                                                </tr>
                                                                <tr class="align-middle">
                                                                    <th class="text-center" scope="row" colspan="3">답변</td>
                                                                    <td colspan="9">
                                                                        <textarea id="error_comments" class="form-control" rows="6"></textarea>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                                                    <button type="button" class="btn btn-primary" id="btnSave"><i class="fa-solid fa-floppy-disk"></i> 저장</button>
                                                    <button type="button" class="btn btn-danger" id="btnDelete"><i class="fas fa-trash"></i> 삭제</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
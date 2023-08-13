<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 교육센터 문의요청사항</title>
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
                                    <h6>교육센터 문의요청사항</h6>
                                    <table class="table table-bordered table-hover">
                                        <thead class="text-center">
                                            <th>번호</th>
                                            <th>제목</th>
                                            <th>일자</th>
                                            <th>작성자</th>
                                            <th>답변</th>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr class="tc">
                                                <td>1</td>
                                                <td>여름특강때 과학탐구보고서 교재가 있나요?</td>
                                                <td>2022-05-20</td>
                                                <td>도곡직영 홍길동</td>
                                                <td>0</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="ModalLabel">교육센터 문의요청사항 답변</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form>
                                                        <div class="form-floating align-items-center mb-2">
                                                            <input type="text" id="txtTitle" class="form-control bg-white" disabled value="여름특강때 과학탐구보고서 교재가 있나요?" placeholder="제목">
                                                            <label for="txtTitle">제목</label>
                                                        </div>
                                                        <div class="input-group">
                                                            <div class="form-inline">
                                                                <div class="form-floating align-items-center mb-2">
                                                                    <input type="text" id="txtWriter" class="form-control bg-white" disabled value="목동직원 홍길동" placeholder="작성자">
                                                                    <label for="txtWriter">작성자</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-inline">
                                                                <div class="form-floating align-items-center mb-2">
                                                                    <input type="text" id="txtDate" class="form-control bg-white" disabled value="2022-05-20" placeholder="작성일">
                                                                    <label for="txtDate">작성일</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-inline">
                                                                <div class="form-floating align-items-center mb-2">
                                                                    <input type="text" id="txtKind" class="form-control bg-white" disabled value="교육도서홍보교재질문기타" placeholder="구분">
                                                                    <label for="txtKind">구분</label>
                                                                </div>
                                                            </div>
                                                            <div class="form-inline ms-3">
                                                                <a class="link-info" href="" download><i class="fa-solid fa-file-arrow-down me-1"></i>sample.hwp</a>
                                                            </div>
                                                        </div>
                                                        <div class="form-floating align-items-center mb-2">
                                                            <textarea id="txtContents" class="form-control bg-white h-25" rows="6" disabled placeholder="내용">
안녕하세요. 저는 삼성교육센터 원장입니다.
저희가 금번 여름방학때 &lt;과학탐구보고서&gt;특강을 하려고 합니다.
적절한지 여부와 함께, 교재는 본사에서 공급해주는지 궁금합니다.
금주까지 답변을 꼭 주시면 준비하는데 도움이 될 것 같습니다.
                                                            </textarea>
                                                            <label for="txtContents">내용</label>
                                                        </div>
                                                        <div class="input-group align-items-center mb-2">
                                                            <label class="form-label">답변내용</label>
                                                        </div>
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr class="align-middle">
                                                                    <td>본사담당자</td>
                                                                    <td>테스트 답변</td>
                                                                    <td class="text-end">
                                                                        <a class="btn btn-sm btn-outline-primary" href="" download><i class="fa-solid fa-file-arrow-down me-1"></i>다운로드</a>
                                                                        <a class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash me-1"></i>삭제</a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="form-floating mb-2">
                                                            <textarea id="txtAnswer" class="form-control h-25" rows="6" placeholder="답변"></textarea>
                                                            <label for="txtAnswer">답변</label>
                                                        </div>
                                                        <div class="input-group align-items-center mb-2">
                                                            <input type="file" id="fileAttach" class="form-control" name="fileAttach" />
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                                                    <button type="button" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i>저장</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        $('.tc').click(function() {
                                            $('#replyModal').modal('show');
                                        });
                                    </script>
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
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />

    <?php
    $stat = 'student';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
    <script src="/center/student/js/book_report.js?v=<?= date("YmdHis") ?>"></script>
</head>

<body class="size-14">
    <!-- 헤더 -->
    <?php include "navbar.php"; ?>
    <!-- 컨텐츠 -->
    <main style="min-height: 79vh;">
        <div class="container-fluid">
            <div class="card border-left-primary shadow mt-2 mb-2">
                <div class="card-header">
                    <h6 class="card-title">글쓰기</h6>
                </div>
                <div class="card-body">
                    <table id="bookReportTable" class="table table-sm table-bordered table-hover">
                        <thead class="text-center align-middle">
                            <th width="5%">번호</th>
                            <th width="10%">이름</th>
                            <th width="5%">학년</th>
                            <th width="35%">읽은책</th>
                            <th width="35%">제목</th>
                            <th width="10%">작성일</th>
                        </thead>
                        <tbody id="bookReportList">
                        </tbody>
                    </table>
                    <div class="text-end mt-2">
                        <button type="button" id="btnAdd" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#NoteWriteModal">추가</button>
                    </div>
                </div>
            </div>

            <!--글쓰기 모달-->
            <div class="modal fade" id="NoteWriteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">글쓰기</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <input type="text" id="txtWriteTitle" class="form-control" placeholder="제목" maxlength="50">
                                <label for="txtWriteTitle">제목</label>
                            </div>
                            <div class="form-floating mb-2">
                                <select id="selLessonBook" class="form-select">
                                    <option value="">선택</option>
                                </select>
                                <label for="selLessonBook">수업도서</label>
                            </div>
                            <div class="input-group">
                                <label for="txtWriteContents">내용</label>
                            </div>
                            <div class="input-group">
                                <textarea id="txtWriteContents" class="form-control" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                            <button type="button" class="btn btn-primary" id="btnWriteSave"><i class="fa-solid fa-floppy-disk me-1"></i>저장</button>
                        </div>
                    </div>
                </div>
            </div>

            <!--글 보기 모달-->
            <div class="modal fade" id="NoteViewModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">글쓰기</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <input type="text" id="txtViewTitle" class="form-control bg-white" placeholder="제목" disabled>
                                <label for="txtViewTitle">제목</label>
                            </div>
                            <div class="input-group">
                                <div class="form-inline">
                                    <div class="form-floating align-items-center mb-2">
                                        <input type="text" id="txtBookReportWriter" class="form-control bg-white" placeholder="작성자" disabled>
                                        <label for="txtBookReportWriter">작성자</label>
                                    </div>
                                </div>
                                <div class="form-inline">
                                    <div class="form-floating align-items-center mb-2">
                                        <input type="text" id="txtBookReportDate" class="form-control bg-white" placeholder="작성일" disabled>
                                        <label for="txtBookReportDate">작성일</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" id="txtViewBookName" class="form-control bg-white" placeholder="책제목" disabled>
                                <label for="txtViewBookName">책제목</label>
                            </div>
                            <div class="input-group">
                                <label for="txtViewContents">내용</label>
                            </div>
                            <div class="input-group">
                                <textarea id="txtViewContents" class="form-control bg-white" rows="10" disabled></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="book_report_idx">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                            <button type="button" id="btnBookReportDelete" class="btn btn-danger" style="display: none;"><i class="fas fa-trash me-1"></i>삭제</button>
                            <button type="button" id="btnBookReportEdit" class="btn btn-success" style="display: none;"><i class="fa-regular fa-pen-to-square me-1"></i>수정</button>
                            <button type="button" id="btnBookReportEditSave" class="btn btn-primary" style="display: none;"><i class="fas fa-save me-1"></i>저장</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- 푸터 -->
    <?php include "footer.php"; ?>
</body>

</html>
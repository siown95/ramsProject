<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
?>
<script src="/center/js/student_note.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
</script>
<div class="mt-2 size-14">
    <div class="card border-left-primary shadow mt-2">
        <div class="card-header">
            <h6>원생쓴글</h6>
        </div>
        <div class="card-body">
            <table id="bookReportTable" class="table table-sm table-bordered table-hover">
                <thead class="text-center align-middle">
                    <th>번호</th>
                    <th>이름</th>
                    <th>학교</th>
                    <th>학년</th>
                    <th>읽은책</th>
                    <th>제목</th>
                    <th>작성일</th>
                </thead>
                <tbody id="bookReportList"></tbody>
            </table>
            <div class="text-end mt-2">
                <button type="button" id="btnAddStudentNote" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#NoteWriteModal">추가</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="NoteWriteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">글쓰기</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-2">
                    <input type="text" id="txtBookReportTitle" class="form-control" placeholder="제목" maxlength="50">
                    <label for="txtBookReportTitle">제목</label>
                </div>
                <div class="input-group mb-2">
                    <div class="input-group input-group-sm mb-2">
                        <div class="form-floating">
                            <input type="hidden" id="writer_no">
                            <input type="text" id="txtStudentName" class="form-control bg-white" placeholder="원생 이름" readonly>
                            <label for="txtStudentName">원생 이름</label>
                        </div>
                        <div class="input-group-append align-self-center ms-2">
                            <button type="button" id="btnBookReportStudentSearch" class="btn btn-sm btn-outline-secondary">원생찾기</button>
                        </div>
                        <div class="input-group-append align-self-center ms-2">
                        <label id="lblGrade" class="form-label"></label>
                        </div>
                        <div class="input-group-append align-self-center ms-2">
                        <label id="lblSchool" class="form-label"></label>
                        </div>
                    </div>
                    <div class="form-inline" id="div_selBook" style="display: none;">
                        <div class="form-floating">
                            <select id="selLessonBook" class="form-select">
                                <option value="">선택</option>
                            </select>
                            <label for="selLessonBook">수업도서</label>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <label for="txtBookReportContents">내용</label>
                </div>
                <div class="input-group">
                    <textarea id="txtBookReportContents" class="form-control" rows="10"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                <button type="button" class="btn btn-primary" id="btnBookRentSave"><i class="fa-solid fa-floppy-disk"></i> 저장</button>
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
                            <label for="txtWriter">작성자</label>
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="form-floating align-items-center mb-2">
                            <input type="text" id="txtBookReportDate" class="form-control bg-white" placeholder="작성일" disabled>
                            <label for="txtDate">작성일</label>
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
                <button type="button" id="btnBookReportDelete" class="btn btn-danger"><i class="fas fa-trash me-1"></i>삭제</button>
                <button type="button" id="btnBookReportEdit" class="btn btn-success"><i class="fa-regular fa-pen-to-square me-1"></i>수정</button>
                <button type="button" id="btnBookReportEditSave" class="btn btn-primary" style="display: none;"><i class="fas fa-save me-1"></i>저장</button>
            </div>
        </div>
    </div>
</div>

<!-- 학생검색 모달 -->
<div class="modal fade" id="BookReportStudentModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">학생검색</h5>
                <button type="button" id="btnClose" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-2">
                    <input type="text" id="txtBookReportStudentName" class="form-control">
                    <div class="input-group-append">
                        <button type="button" id="studentBookReport" class="btn btn-outline-success"><i class="fas fa-search-location me-1"></i>검색</button>
                    </div>
                </div>
                <form>
                    <table class="table table-bordered table-hover">
                        <thead class="text-center align-middle">
                            <th>이름</th>
                            <th>학년</th>
                            <th>학교</th>
                        </thead>
                        <tbody id="studentBookReportList">
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
            </div>
        </div>
    </div>
</div>
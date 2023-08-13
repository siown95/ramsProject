<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />

    <?php
    $stat = 'student';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
</head>

<body>
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
                    <table id="dataTable" class="table table-sm table-bordered table-hover">
                        <thead class="text-center align-middle">
                            <th>번호</th>
                            <th>이름</th>
                            <th>학년</th>
                            <th>제목</th>
                            <th>읽은책</th>
                            <th>작성일</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-end mt-2">
                        <button type="button" id="btnAdd" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#NoteModal">추가</button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="NoteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">글쓰기</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating mb-2">
                                <input type="text" id="txtTitle" class="form-control" placeholder="제목" maxlength="50">
                                <label for="txtTitle">제목</label>
                            </div>
                            <div class="form-floating mb-2">
                                <select id="selLessonBook" class="form-select">
                                    <option value="">선택</option>
                                </select>
                                <label for="selLessonBook">수업도서</label>
                            </div>
                            <div class="input-group">
                                <label for="txtContents">내용</label>
                            </div>
                            <div class="input-group">
                                <textarea id="txtContents" class="form-control" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                            <button type="button" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i>저장</button>
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
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
    <script src="/center/student/js/center_notice.js?v=<?= date("YmdHis") ?>"></script>
</head>

<body class="size-14">
    <!-- 헤더 -->
    <?php include "navbar.php"; ?>
    <!-- 컨텐츠 -->
    <main style="min-height: 79vh;">
        <div class="container-fluid">
            <div class="card border-left-primary shadow mt-2 mb-2">
                <div class="card-header">
                    <h6 class="card-title">센터알림</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover" id="centerNoticeTable">
                        <thead class="text-center align-middle">
                            <th width="10%">번호</th>
                            <th width="80%">제목</th>
                            <th width="10%">작성일</th>
                        </thead>
                        <tbody id="centerNoticeList">
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="CenterNoticeModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">알림</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-2">
                            <input type="text" id="txtTitle" class="form-control bg-white" placeholder="제목" disabled>
                            <label for="txtTitle">제목</label>
                        </div>
                        <div class="input-group">
                            <label for="txtContents">내용</label>
                        </div>
                        <div class="input-group">
                            <textarea id="txtContents" class="form-control bg-white" rows="15" disabled></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- 푸터 -->
    <?php include "footer.php"; ?>
</body>

</html>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
$sql = "SELECT position FROM member_centerM WHERE franchise_idx = '{$_SESSION['center_idx']}' AND user_no = '{$_SESSION['logged_no']}'";
$position = $db->sqlRowOne($sql);
?>
<script src="/center/js/notice_list.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var position = '<?= $position ?>';
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
</script>
<div class="mt-2 size-14">
    <div class="card border-left-primary shadow mt-2">
        <div class="card-header">
            <h6>공지사항</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover" id="noticeBoardTable">
                <thead class="text-center align-middle">
                    <th width="10%">번호</th>
                    <th width="70%">제목</th>
                    <th width="10%">작성일</th>
                    <th width="10%">첨부파일</th>
                </thead>
                <tbody id="noticeBoardList">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="BoardModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">공지사항</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="boardForm">
                    <input type="hidden" id="board_idx">
                    <div class="form-floating align-items-center mb-2">
                        <input type="text" id="txtNoticeTitle" class="form-control bg-white" maxlength="50" placeholder="제목" disabled>
                        <label for="txtTitle">제목</label>
                    </div>
                    <div class="align-items-center mb-2">
                        <div id="txtContents" class="form-control bg-white overflow-auto border p-2"></div>
                    </div>
                    <div class="input-group align-items-center mt-2">
                        <div class="form-inline">
                            <a class="link-info me-2 d-none" href="" id="exfile" download><i class="fa-solid fa-file-arrow-down"></i></a>
                            <input type="hidden" id="file_name">
                            <input type="hidden" id="file_path">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
            </div>
        </div>
    </div>
</div>
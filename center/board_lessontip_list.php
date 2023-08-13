<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$codeInfoCmp = new codeInfoCmp();

$board_type = $codeInfoCmp->getCodeInfo('04');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" integrity="sha512-ZbehZMIlGA8CTIOtdE+M81uj3mrcgyrh6ZFeG33A4FHECakGrOsTPlPQ8ijjLkxgImrdmSVUHn1j+ApjodYZow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js" integrity="sha512-lVkQNgKabKsM1DA/qbhJRFQU8TuwkLF2vSN3iU/c7+iayKs08Y8GXqfFxxTZr1IcpMovXnf2N/ZZoMgmZep1YQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/lang/summernote-ko-KR.min.js" integrity="sha512-Zg4LEmUTxIodfMffiwHk5HUeapoVo2VTSGZS5q6ttOMseXr/ZbkiBgV2lyds3UQFPAX05AlF8RIpszT3l7BXKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="/center/js/board_lesson_tip.js?v=<?= date("YmdHis") ?>"></script>
<div class="mt-2 size-14">
    <div class="card border-left-primary shadow mt-2">
        <div class="card-header">
            <h6>수업 TIP</h6>
        </div>
        <div class="card-body">
            <script>
                var userInfo = {
                    user_no: '<?= $_SESSION['logged_no'] ?>',
                    user_id: '<?= $_SESSION['logged_id'] ?>',
                    user_name: '<?= $_SESSION['logged_name'] ?>',
                    user_phone: '<?= phoneFormat($_SESSION['logged_phone']) ?>',
                    user_email: '<?= $_SESSION['logged_email'] ?>'
                }
                var center_idx = '<?= $_SESSION['center_idx'] ?>';
            </script>
            <table class="table table-bordered table-hover" id="boardTipTable">
                <thead class="text-center align-middle">
                    <th width="10%">번호</th>
                    <th width="65%">제목</th>
                    <th width="15%">작성자</th>
                    <th width="10%">작성일</th>
                </thead>
                <tbody id="boardTipList">
                </tbody>
            </table>
            <div class="text-end mt-2">
                <button type="button" id="btnAddLessonTip" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#BoardModal"><i class="fa-solid fa-plus"></i> 추가</button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="BoardModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">수업 TIP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-floating align-items-center mb-2">
                            <input type="text" id="txtTitle" class="form-control bg-white" placeholder="제목">
                            <label for="txtTitle">제목</label>
                        </div>
                        <div class="input-group mb-2">
                            <div class="form-inline me-2">
                                <div class="form-floating align-items-center">
                                    <select id="selKind" class="form-select">
                                        <option value="">선택</option>
                                        <?php
                                        if (!empty($board_type)) {
                                            foreach ($board_type as $key => $val) {
                                                echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="selKind">머리글</label>
                                </div>
                            </div>
                            <div class="form-inline align-self-center">이미지 첨부 시 클립보드에 저장된 이미지를 붙여넣으면 이미지 첨부가 안될 수 있습니다&#46;</div>
                        </div>
                        <div id="noteDiv" class="mb-2">
                            <textarea id="summernote" class="form-control"></textarea>
                        </div>
                        <div class="input-group mb-2">
                            <input type="text" id="txtLessonTipFileName" class="form-control" placeholder="파일을 선택해주세요" readonly>
                            <input type="file" id="lessonTipFileAttach" class="form-control d-none" name="lessonTipFileAttach">
                            <div class="input-group-append">
                                <button type="button" id="btnUploadTipFile" class="btn btn-outline-secondary me-2"><i class="fa-solid fa-file-arrow-up"></i> 파일선택</button>
                            </div>
                        </div>
                        <div class="input-group">
                            <a class="link-info me-2 d-none" href="" id="exfile" download><i class="fa-solid fa-file-arrow-down"></i></a>
                            <a class="btn btn-sm btn-outline-danger d-none" href="javascript:void(0)" id="imgdel"><i class="fa-solid fa-trash"></i> 삭제</a>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                    <button type="button" id="btnSaveLessonTip" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> 저장</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="BoardViewModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">수업 TIP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="board_idx">
                        <input type="hidden" id="writer_no">
                        <input type="hidden" id="file_path">
                        <input type="hidden" id="file_name">
                        <div class="form-floating align-items-center mb-2">
                            <input type="text" id="txtViewTitle" class="form-control bg-white" placeholder="제목" disabled>
                            <label for="txtTitle">제목</label>
                        </div>
                        <div class="form-control overflow-auto border p-2" id="viewContents"></div>
                        <hr>
                        <div class="row align-items-center">
                            <div class="col-auto" id="file_link"></div>
                            <div class="col-auto ms-auto">
                                <button type="button" id="btnLikes" class="btn btn-outline-primary">
                                    <i class="fa-regular fa-thumbs-up"></i>좋아요<span class="ms-1"></span>
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div id="cmtList"></div>
                        <div class="input-group">
                            <label for="txtComment">댓글</label>
                        </div>
                        <div class="input-group">
                            <input type="text" id="txtComment" class="form-control bg-white" maxlength="200">
                            <div class="input-group-append align-self-center">
                                <button type="button" id="btnCommentSave" class="btn btn-primary">댓글작성</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnUpdateLessonTip" style="display: none;"><i class="fa-solid fa-floppy-disk"></i> 수정</button>
                    <button type="button" class="btn btn-danger" id="btnDeleteLessonTip" style="display: none;"><i class="fas fa-trash"></i> 삭제</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                </div>
            </div>
        </div>
    </div>
</div>
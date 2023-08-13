<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

$codeInfoCmp = new codeInfoCmp();
$inquiryTypeList = $codeInfoCmp->getCodeInfo('72');
?>
<script src="/center/js/inquiry.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var userInfo = {
        user_no: '<?= $_SESSION['logged_no'] ?>',
        user_id: '<?= $_SESSION['logged_id'] ?>',
        user_name: '<?= $_SESSION['logged_name'] ?>',
        user_phone: '<?= phoneFormat($_SESSION['logged_phone']) ?>',
        user_email: '<?= $_SESSION['logged_email'] ?>'
    }
    var center_idx = "<?= $_SESSION['center_idx'] ?>";
</script>
<div class="row mt-2 size-14">
    <div class="col-12">
        <div class="card border-left-primary shadow h-100">
            <div class="card-header">
                <h6>문의요청</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered table-hover" id="inquiryTable">
                    <thead class="text-center">
                        <th width="5%">번호</th>
                        <th width="60%">제목</th>
                        <th width="10%">일자</th>
                        <th width="15%">작성자</th>
                        <th width="10%">답변여부</th>
                    </thead>
                    <tbody class="text-center" id="inquiryList"></tbody>
                </table>
                <div class="text-end">
                    <button type="button" id="btnAddInquiry" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#writeInquiryModal"><i class="fa-solid fa-plus"></i> 추가</button>
                </div>

                <div class="modal fade" id="writeInquiryModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLabel">문의 및 요청하기</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-floating align-items-center mb-2">
                                        <input type="text" id="txtTitle" class="form-control bg-white" placeholder="제목">
                                        <label for="txtTitle">제목</label>
                                    </div>
                                    <div class="input-group">
                                        <div class="form-inline">
                                            <div class="form-floating align-items-center mb-2">
                                                <select id="selInquiryKind" class="form-select">
                                                    <option value="">선택</option>
                                                    <?php
                                                    if (!empty($inquiryTypeList)) {
                                                        foreach ($inquiryTypeList as $key => $val) {
                                                            echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <label for="selInquiryKind">구분</label>
                                            </div>
                                        </div>
                                        <div class="form-inline align-self-center ms-3">
                                            <div class="input-group col">
                                                <input type="text" id="fileInquiryName" class="form-control bg-white" placeholder="파일을 선택해주세요" disabled readonly>
                                                <input type="file" id="fileInquiry" class="form-control d-none">
                                                <button type="button" id="btnInquiryUpload" class="btn btn-outline-secondary me-2"><i class="fa-solid fa-file-arrow-up"></i> 파일선택</button>
                                            </div>
                                        </div>
                                        <div class="form-inline align-self-center ms-3" id="updateFileDiv" style="display: none;">
                                            <a class="link-info me-2" href="" id="updateFileDown" download><i class="fa-solid fa-file-arrow-down"></i></a>
                                            <input type="hidden" id="updateFileName">
                                            <button type="button" class="btn btn-sm btn-outline-danger" id="inquiryFileDelete"><i class="fa-solid fa-trash"></i> 삭제</button>
                                        </div>
                                    </div>
                                    <div class="align-items-center mb-2">
                                        <label for="txtContents">내용</label>
                                        <textarea id="txtContents" class="form-control bg-white" rows="12" placeholder="내용"></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                                <button type="button" class="btn btn-success" id="btnInquiryUpdate" style="display: none;"><i class="fa-solid fa-pen-to-square"></i> 수정</button>
                                <button type="button" class="btn btn-primary" id="btnInquirySave"><i class="fa-regular fa-comment"></i> 작성</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="replyViewModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLabel">문의 및 요청하기</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-floating align-items-center mb-2">
                                        <input type="text" id="txtViewTitle" class="form-control bg-white" placeholder="제목" disabled>
                                        <label for="txtViewTitle">제목</label>
                                    </div>
                                    <div class="input-group">
                                        <div class="form-inline">
                                            <div class="form-floating align-items-center mb-2">
                                                <input type="text" id="txtViewWriter" class="form-control bg-white" placeholder="작성자" disabled>
                                                <label for="txtViewWriter">작성자</label>
                                            </div>
                                        </div>
                                        <div class="form-inline">
                                            <div class="form-floating align-items-center mb-2">
                                                <input type="text" id="txtViewDate" class="form-control bg-white" value="2022-05-20" placeholder="작성일" disabled>
                                                <label for="txtViewDate">작성일</label>
                                            </div>
                                        </div>
                                        <div class="form-inline">
                                            <div class="form-floating align-items-center mb-2">
                                                <input type="text" id="txtInquiryKind" class="form-control bg-white" placeholder="작성일" disabled>
                                                <label for="selInquiryKind">구분</label>
                                            </div>
                                        </div>
                                        <div class="form-inline align-self-center ms-3">
                                            <input type="hidden" id="inquiryFileName">
                                            <a class="link-info" id="inquiryFileDown" href="" download></a>
                                        </div>
                                    </div>
                                    <div class="align-items-center mb-2">
                                        <label for="txtViewContents">내용</label>
                                        <textarea id="txtViewContents" class="form-control bg-white" rows="12" placeholder="내용" disabled></textarea>
                                    </div>
                                    <div id="commentSection" style="display: none;">
                                        <div class="input-group align-items-center mb-2">
                                            <label class="form-label">답변내용</label>
                                        </div>
                                        <table class="table table-bordered">
                                            <tbody id="answerList"></tbody>
                                        </table>
                                    </div>
                                    <input type="hidden" id="inquiry_idx">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                                <button type="button" class="btn btn-success" id="btnInquiryUpdateView" style="display: none;"><i class="fa-solid fa-pen-to-square"></i> 수정</button>
                                <button type="button" class="btn btn-danger" id="btnInquiryDelete" style="display: none;"><i class="fas fa-trash"></i> 삭제</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
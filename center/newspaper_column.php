<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

$db = new DBCmp();

include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$codeInfoCmp = new codeInfoCmp();

$newsKind = $codeInfoCmp->getCodeInfo('06'); //신문사 종류
$subjectList = $codeInfoCmp->getCodeInfo('07'); //주제(칼럼)
?>
<script src="/center/js/newspaper_column.js?v=<?= date("YmdHis") ?>"></script>
<div class="mt-2 size-14">
    <div class="card border-left-primary shadow mt-2">
        <div class="card-header">
            <h6>주제별신문칼럼</h6>
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
            <table id="newsPaperTable" class="table table-sm table-bordered table-hover">
                <thead class="text-center align-middle">
                    <th>번호</th>
                    <th>신문사</th>
                    <th>주제</th>
                    <th>표제</th>
                    <th>작성자</th>
                    <th>일자</th>
                    <th>신문칼럼</th>
                    <th>지도교안</th>
                    <th>등록일자</th>
                </thead>
                <tbody class="text-center" id="newsPaperList">
                </tbody>
            </table>
            <div class="text-end mt-2">
                <button type="button" id="btnAddNewsPaper" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#NewspaperModal">추가</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="NewspaperModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">주제별 신문칼럼</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="mdNewsIdx">
                <div class="form-floating mb-2">
                    <input type="text" id="txtTitle" class="form-control" placeholder="표제" maxlength="100">
                    <label for="txtTitle">표제</label>
                </div>
                <div class="input-group mb-2">
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <select id="selNewspaperCompany" class="form-select">
                                <option value="">선택</option>
                                <?php
                                if (!empty($newsKind)) {
                                    foreach ($newsKind as $key => $val) {
                                        echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <label for="selNewspaperCompany">신문사</label>
                        </div>
                    </div>
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <select id="selSubject" class="form-select">
                                <option value="">선택</option>
                                <?php
                                if (!empty($subjectList)) {
                                    foreach ($subjectList as $key => $val) {
                                        echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <label for="selSubject">주제</label>
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="form-floating">
                            <input type="date" id="dtDate" class="form-control" placeholder="일자">
                            <label for="dtDate">일자</label>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-2">
                    <div class="form-inline align-self-center me-2">
                        <label class="form-label">칼럼파일</label>
                    </div>
                    <div class="form-inline me-2">
                        <input type="text" id="txtColumnName" class="form-control bg-white" placeholder="파일을 선택해주세요" disabled readonly>
                        <input type="file" id="fileColumn" class="d-none">
                    </div>
                    <div class="form-inline me-2">
                        <button type="button" id="btnColumnUpload" class="btn btn-outline-secondary"><i class="fa-solid fa-file-arrow-up"></i> 파일선택</button>
                    </div>
                    <div class="form-inline align-self-center">
                        <a class="link-info me-2 d-none" href="" id="originColumn" download><i class="fa-solid fa-file-arrow-down"></i></a>
                        <input type="hidden" id="file_name1">
                    </div>
                </div>
                <div class="input-group">
                    <div class="form-inline align-self-center me-2">
                        <label class="form-label">교안파일</label>
                    </div>
                    <div class="form-inline me-2">
                        <input type="text" id="txtTeachingName" class="form-control bg-white" placeholder="파일을 선택해주세요" disabled readonly>
                        <input type="file" id="fileTeaching" class="d-none">
                    </div>
                    <div class="form-inline me-2">
                        <button type="button" id="btnTeachingUpload" class="btn btn-outline-secondary"><i class="fa-solid fa-file-arrow-up"></i> 파일선택</button>
                    </div>
                    <div class="form-inline align-self-center">
                        <a class="link-info me-2 d-none" href="" id="originTeaching" download><i class="fa-solid fa-file-arrow-down"></i></a>
                        <input type="hidden" id="file_name2">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                <button type="button" class="btn btn-primary" id="btnSaveNewsPaper"><i class="fa-solid fa-floppy-disk"></i> 저장</button>
                <button type="button" class="btn btn-success" id="btnUpdateNewsPaper" style="display: none;"><i class="fa-solid fa-rotate"></i> 수정</button>
                <button type="button" class="btn btn-danger" id="btnDeleteNewsPaper" style="display: none;"><i class="fas fa-trash"></i> 삭제</button>
            </div>
        </div>
    </div>
</div>
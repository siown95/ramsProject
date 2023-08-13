<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";
?>
<script src="/center/js/activity_error_report.js?v=<?= date("YmdHis") ?>"></script>
<div class="row size-14">
    <script>
        var userInfo = {
            user_no: '<?= $_SESSION['logged_no'] ?>',
            user_id: '<?= $_SESSION['logged_id'] ?>',
            user_name: '<?= $_SESSION['logged_name'] ?>',
            user_phone: '<?= phoneFormat($_SESSION['logged_phone']) ?>',
            user_email: '<?= $_SESSION['logged_email'] ?>'
        }
    </script>
    <div class="col-12 mt-2">
        <div class="card border-left-primary shadow h-100">
            <div class="card-header">
                <h6>활동지오류신고</h6>
            </div>
            <div class="card-body">
                <table id="dataTable" class="table table-bordered table-hover">
                    <thead class="text-center">
                        <th width="10%">번호</th>
                        <th width="55%">제목</th>
                        <th width="10%">일자</th>
                        <th width="15%">작성자</th>
                        <th width="10%">상태</th>
                    </thead>
                    <tbody class="text-center" id="errorList"></tbody>
                </table>
                <div class="text-end mt-2">
                    <button type="button" id="btnAddErrorReport" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#writeErrorReportModal"><i class="fa-solid fa-plus"></i> 추가</button>
                </div>
                <div class="modal fade" id="writeErrorReportModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLabel">활동지 오류신고 작성</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="input-group">
                                        <label for="txtTitle">제목</label>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" id="txtTitle" class="form-control" maxlength="50">
                                    </div>
                                    <div class="input-group mt-2">
                                        <label for="txtTitle">내용</label>
                                    </div>
                                    <div class="input-group">
                                        <textarea id="txtContents" class="form-control" rows="8"></textarea>
                                    </div>
                                    <div class="input-group mt-2">
                                        <label for="txtReportFileName">첨부파일</label>
                                    </div>
                                    <div class="input-group mt-2">
                                        <input type="text" id="txtReportFileName" class="form-control" placeholder="파일을 선택해주세요" readonly>
                                        <input type="file" id="reportFileAttach" class="form-control d-none" name="reportFileAttach">
                                        <div class="input-group-append">
                                            <button type="button" id="btnReportFileUpload" class="btn btn-outline-secondary me-2"><i class="fa-solid fa-file-arrow-up"></i> 파일선택</button>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <a class="link-info me-2 d-none" href="" id="errorReportOriginFile" download><i class="fa-solid fa-file-arrow-down"></i></a>
                                        <input type="hidden" id="errorReportOriginFileName">
                                        <a class="btn btn-sm btn-outline-danger file-del d-none" href="javascript:void(0)" id="errorReportFileDel"><i class="fa-solid fa-trash"></i> 삭제</a>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                                <button type="button" id="btnSaveErrorReport" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> 저장</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="replyViewModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ModalLabel">활동지 오류신고 답변</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <input type="hidden" id="mdErrorIdx">
                                    <table class="table">
                                        <tbody>
                                            <tr class="align-middle">
                                                <th class="text-center" scope="row" colspan="3">제목</th>
                                                <td colspan="9" id="txtErrorRepotTitle">활동지 오류신고 제목 테스트</td>
                                            </tr>
                                            <tr class="align-middle">
                                                <th class="text-center" scope="row" colspan="3">작성자</th>
                                                <td colspan="3" id="txtErrorReportWriter">목동직원 홍길동</td>
                                                <th class="text-center" colspan="3">작성일</th>
                                                <td colspan="3" id="txtErrorReportDate">2022-05-20</td>
                                            </tr>
                                            <tr class="align-middle">
                                                <th class="text-center align-middle" scope="row" colspan="3">첨부파일</th>
                                                <td colspan="3">
                                                    <a class="btn btn-default" href="" id="errorReportFileLink" download><i class="fa-solid fa-file-arrow-down"></i></a>
                                                    <input type="hidden" id="errorReportFileName">
                                                </td>
                                                <th class="text-center align-middle" colspan="3">상태</th>
                                                <td colspan="3" id="errorReportState">접수중</td>
                                            </tr>
                                            <tr class="align-middle">
                                                <th class="text-center" scope="row" colspan="3">내용</th>
                                                <td colspan="9">
                                                    <textarea id="txtErrorContents" class="form-control bg-white" rows="6" disabled></textarea>
                                                </td>
                                            </tr>
                                            <tr class="align-middle">
                                                <th class="text-center" scope="row" colspan="3">답변</td>
                                                <td colspan="9">
                                                    <textarea id="txtErrorComments" class="form-control bg-white" rows="6" disabled></textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                                <button type="button" class="btn btn-success" id="btnUpdateErrorReport" style="display: none;"><i class="fa-solid fa-rotate"></i> 수정</button>
                                <button type="button" class="btn btn-danger" id="btnDeleteErrorReport" style="display: none;"><i class="fas fa-trash"></i> 삭제</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
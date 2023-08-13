<script src="/center/js/file_board.js?v=<?= date("YmdHis") ?>"></script>
<div class="mt-2 size-14">
    <div class="card border-left-primary shadow mt-2">
        <div class="card-header">
            <h6>자료실</h6>
        </div>
        <div class="card-body">
            <table id="fileBoardTable" class="table table-bordered table-hover">
                <thead class="text-center align-middle">
                    <th width="10%">번호</th>
                    <th width="10%">분류</th>
                    <th width="60%">제목</th>
                    <th width="10%">작성일</th>
                    <th width="10%">파일</th>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="fileBoardModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">게시글</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="boardForm">
                    <div class="form-floating align-items-center mb-2">
                        <input type="hidden" id="fileBoardTarget" value="">
                        <input type="text" id="txtFileBoardTitle" class="form-control bg-white" maxlength="50" placeholder="제목" disabled>
                        <label for="txtFileBoardTitle">제목</label>
                    </div>
                    <div class="input-group mb-2">
                        <div class="form-floating align-items-center me-2">
                            <input type="text" id="txtFileBoardKind1" class="form-control bg-white" placeholder="구분1" disabled>
                            <label for="txtFileBoardKind1">구분1</label>
                        </div>
                        <div class="form-floating align-items-center" id="divFileBoardKind2" >
                            <input type="text" id="txtFileBoardKind2" class="form-control bg-white" placeholder="구분2" disabled>
                            <label for="txtFileBoardKind2">구분2</label>
                        </div>
                    </div>
                    <div class="form-floating align-items-center mb-2">
                        <textarea id="txtFileBoardContents" class="form-control bg-white h-25" rows="6" placeholder="내용" disabled></textarea>
                        <label for="txtFileBoardContents">내용</label>
                    </div>
                    <div class="input-group align-items-center mb-2">
                        <div class="form-inline">
                            <a class="link-info me-2 d-none" href="" id="fileBoardFile" download><i class="fa-solid fa-file-arrow-down"></i></a>
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
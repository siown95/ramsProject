<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();

include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/BookController.php";

$bookController = new bookController();

$category1 = $bookController->bookCategory('1');
?>
<script src="/center/js/book.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var franchise_idx = '<?= $_SESSION['center_idx'] ?>';
    var teacher_idx = '<?= $_SESSION['logged_no'] ?>';
</script>
<div class="row mt-2 size-14">
    <div class="col-4">
        <input type="hidden" id="bookManageIdx">
        <div class="card border-left-primary shadow">
            <div class="card-header">
                <h6>도서정보</h6>
            </div>
            <div class="card-body">
                <div class="form-floating mb-2">
                    <input type="text" id="txtBookName" class="form-control bg-white" maxlength="50" placeholder="도서명" readonly />
                    <label for="txtBookName">도서명</label>
                </div>
                <div class="form-floating mb-2">
                    <input type="text" id="txtBookISBN" class="form-control bg-white" maxlength="20" placeholder="ISBN" readonly />
                    <label for="txtBookISBN">ISBN</label>
                </div>
                <div class="input-group mb-2">
                    <div class="form-inline me-1">
                        <div class="form-floating">
                            <input type="text" id="txtWriter" class="form-control bg-white" maxlength="20" placeholder="저자" readonly />
                            <label for="txtWriter">저자</label>
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="form-floating">
                            <input type="text" id="txtPublisher" class="form-control bg-white" maxlength="20" placeholder="출판사" readonly />
                            <label for="txtPublisher">출판사</label>
                        </div>
                    </div>
                </div>
                <div class="form-floating mb-2">
                    <input type="text" id="txtCategory" class="form-control bg-white" placeholder="카테고리" readonly />
                    <label for="txtCategory">카테고리</label>
                </div>
                <div class="input-group mb-2">
                    <div class="form-inline me-1">
                        <div class="form-floating">
                            <input type="text" id="txtBookCount" class="form-control" maxlength="2" placeholder="보유권수" />
                            <label for="txtBookCount">보유권수</label>
                        </div>
                    </div>
                    <div class="form-inline align-self-center me-1">
                        <button type="button" id="btnSaveBookManage" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i>저장</button>
                    </div>
                </div>
                <p class="text-muted"> 도서 수량은 현재 보유권수에 입력하신 수량만큼 추가됩니다.</p>
                <div class="input-group mt-2">
                    <button type="button" class="btn btn-sm btn-outline-success me-2" data-bs-toggle="modal" data-bs-target="#BookRequestModal"><i class="fa-solid fa-file-import me-1"></i>도서등록요청</button>
                    <button type="button" class="btn btn-sm btn-outline-success" id="btnRequestList" data-bs-toggle="modal" data-bs-target="#BookRequestListModal"><i class="fa-solid fa-file-import me-1"></i>도서등록요청확인</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-8">
        <div class="card border-left-primary shadow">
            <div class="card-header">
                <h6>도서목록</h6>
            </div>
            <div class="card-body">
                <div class="input-group justify-content-end">
                    <div class="form-inline">
                        <div class="form-floating">
                            <input type="text" id="searchValue" class="form-control" title="검색어 입력 후 엔터를 눌러주세요." />
                            <label for="searchValue">검색</label>
                        </div>
                    </div>
                </div>
                <table class="table table-sm table-bordered table-hover" id="bookManageTable">
                    <thead class="align-middle text-center">
                        <th>ISBN</th>
                        <th>도서명</th>
                        <th>저자</th>
                        <th>출판사</th>
                        <th>카테고리</th>
                        <th>보유권수</th>
                        <th>등록일</th>
                    </thead>
                    <tbody class="align-middle text-center" id="bookManageList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="BookRequestModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">도서등록정보</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bookRequestForm">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="rbook_name" maxlength="100" placeholder="도서명" />
                        <label for="rbook_name">도서명</label>
                    </div>
                    <div class="input-group mb-2">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="rbook_isbn" maxlength="13" placeholder="ISBN" />
                                <label for="rbook_isbn">ISBN</label>
                            </div>
                        </div>
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="text" id="rbook_writer" class="form-control" maxlength="30" placeholder="저자" />
                                <label for="rbook_writer">저자</label>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-floating">
                                <input type="text" id="rbook_publisher" class="form-control" maxlength="30" placeholder="출판사" />
                                <label for="rbook_publisher">출판사</label>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select id="rBookcategory1" class="form-select" onchange="bookCategoryChange(this.value, '2')">
                                    <option value="">선택</option>
                                    <?php
                                    if (!empty($category1)) {
                                        foreach ($category1 as $key => $val) {
                                            echo "<option value=\"" . $val['code_num1'] . "\">" . $val['code_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="rBookcategory1">1차분류</label>
                            </div>
                        </div>
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select class="form-select" id="rBookcategory2" onchange="bookCategoryChange(this.value, '3')">
                                    <option value="">선택</option>
                                </select>
                                <label for="rBookcategory2">2차분류</label>
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-floating">
                                <select class="form-select" id="rBookcategory3">
                                    <option value="">선택</option>
                                </select>
                                <label for="rBookcategory3">3차분류</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                <button type="button" id="btnRequestBook" class="btn btn-primary"><i class="fa-solid fa-file-import me-1"></i>확인</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="BookRequestListModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">등록 요청</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-hover" id="bookRequestTable">
                    <thead class="align-middle text-center">
                        <th>번호</th>
                        <th>ISBN</th>
                        <th>도서명</th>
                        <th>저자</th>
                        <th>출판사</th>
                        <th>상태</th>
                        <th>비고</th>
                        <th>요청센터</th>
                        <th>등록일</th>
                    </thead>
                    <tbody class="align-middle text-center" id="bookRequestList">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
            </div>
        </div>
    </div>
</div>
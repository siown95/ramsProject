<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();

include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/BookController.php";

$bookController = new bookController();

$category1 = $bookController->bookCategory('1');
?>
<!DOCTYPE html>
<html>

<head>
    <?php
    $stat = "adm";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/common/common_script.php";
    ?>
    <script src="/adm/js/book_request.js?v=<?= date('YmdHis') ?>"></script>
</head>

<body class="sb-nav-fixed size-14">
    <!-- header navbar -->
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/adm/navbar.html'; ?>
    <div id="layoutSidenav">
        <!-- sidebar -->
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/adm/sidebar.html'; ?>

        <div id="Maincontent">
            <main>
                <div class="container-fluid px-4 mt-3">
                    <!-- 콘텐츠 -->
                    <div class="card border-left-primary shadow h-100">
                        <div class="card-body">
                            <h6>신청도서</h6>
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead class="text-center">
                                    <th>번호</th>
                                    <th>ISBN</th>
                                    <th>도서명</th>
                                    <th>출판사</th>
                                    <th>저자</th>
                                    <th>카테고리</th>
                                    <th>신청센터</th>
                                    <th>상태</th>
                                    <th>신청일</th>
                                </thead>
                                <tbody class="text-center">
                                </tbody>
                            </table>
                            <div class="input-group justify-content-end mt-2">
                                <div class="form-inline me-2">
                                    <div class="form-floating">
                                        <select id="selExpireDays" class="form-select">
                                            <option value="30">30일</option>
                                            <option value="60">60일</option>
                                            <option value="90" selected>90일</option>
                                        </select>
                                        <label for="selExpireDays">삭제기간</label>
                                    </div>
                                </div>
                                <div class="form-inline align-self-center">
                                    <button type="button" id="btnExpireDelete" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can me-1"></i>기간만료삭제</button>

                                </div>
                            </div>
                            <div class="modal fade" id="BookModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">신청도서</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="bookForm">
                                                <input type="hidden" id="requestTarget">
                                                <table class="table">
                                                    <tbody>
                                                        <tr class="align-middle">
                                                            <th class="text-center" scope="row" colspan="3">도서명</th>
                                                            <td colspan="9"><input type="text" class="form-control" id="book_name" name="txtBookName" maxlength="100" /></td>
                                                        </tr>
                                                        <tr class="align-middle">
                                                            <th class="text-center" scope="row" colspan="3">ISBN</th>
                                                            <td colspan="9"><input type="text" class="form-control" id="book_isbn" name="txtBookISBN" maxlength="13" /></td>
                                                        </tr>
                                                        <tr class="align-middle">
                                                            <th class="text-center" scope="row" colspan="3">저자</th>
                                                            <td colspan="3">
                                                                <input list="writerList" type="text" id="book_writer" class="form-control" name="txtWriter" maxlength="30" placeholder="저자" />
                                                                <datalist id="writerList"></datalist>
                                                            </td>
                                                            <th class="text-center" colspan="3">출판사</th>
                                                            <td colspan="3">
                                                                <input list="publisherList" type="text" id="book_publisher" class="form-control" name="txtPublisher" maxlength="30" placeholder="출판사" />
                                                                <datalist id="publisherList"></datalist>
                                                            </td>
                                                        </tr>
                                                        <tr class="align-middle">
                                                            <th class="text-center" scope="row" colspan="3">카테고리</th>
                                                            <th class="text-center">1차</th>
                                                            <td colspan="2">
                                                                <select class="form-select" id="category1" onchange="bookCategoryChange(this.value, '2')">
                                                                    <option value="">선택</option>
                                                                    <?php
                                                                    if (!empty($category1)) {
                                                                        foreach ($category1 as $key => $val) {
                                                                            echo "<option value=\"" . $val['code_num1'] . "\">" . $val['code_name'] . "</option>";
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <th class="text-center">2차</th>
                                                            <td colspan="2">
                                                                <select class="form-select" id="category2" onchange="bookCategoryChange(this.value, '3')">
                                                                    <option value="">선택</option>
                                                                </select>
                                                            </td>
                                                            <th class="text-center">3차</th>
                                                            <td colspan="2">
                                                                <select class="form-select" id="category3">
                                                                    <option value="">선택</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr class="align-middle">
                                                            <th class="text-center">상태</th>
                                                            <td colspan="11">
                                                                <div class="input-group">
                                                                    <div class="form-check form-check-inline">
                                                                        <input type="radio" class="form-check-input" id="stateY" name="request_state" value="01" />
                                                                        <label class="form-check-label" for="stateY">승인</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input type="radio" class="form-check-input" id="stateN" name="request_state" value="09" />
                                                                        <label class="form-check-label" for="stateN">거부</label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr class="align-middle">
                                                            <th class="text-center">MEMO</th>
                                                            <td colspan="11">
                                                                <textarea id="request_memo" class="form-control" maxlength="100"></textarea>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                                            <button type="button" class="btn btn-primary" id="btnBookRequestSave"><i class="fa-solid fa-floppy-disk me-1"></i>저장</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/adm/footer.html" ?>
        </div>
    </div>
</body>

</html>
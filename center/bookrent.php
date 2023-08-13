<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

$infoClassCmp = new infoClassCmp();

$teacherList = $infoClassCmp->teacherList($_SESSION['center_idx']);
$studentList = $infoClassCmp->studentList($_SESSION['center_idx']);
?>
<script src="/center/js/book_rent.js?v=<?= date("YmdHis") ?>"></script>
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
<div class="row mt-6 size-14">
    <div class="col-5">
        <div class="card border-left-primary shadow h-100 mt-2">
            <div class="card-header">
                <h6>도서대출</h6>
            </div>
            <div class="card-body">
                <div id="div_regular">
                    <div class="input-group input-group-sm mb-2">
                        <div class="form-floating">
                            <input type="hidden" id="student_no">
                            <input type="text" id="txtStudentName" class="form-control bg-white" placeholder="원생이름" readonly>
                            <label for="txtStudentName">원생이름</label>
                        </div>
                        <div class="input-group-append align-self-center ms-2">
                            <button type="button" id="btnRentStudentSearch" class="btn btn-sm btn-outline-secondary">원생찾기</button>
                        </div>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <div class="input-group-append align-self-center ms-2">
                            <label id="lblGrade" class="form-label"></label>
                        </div>
                        <div class="input-group-append align-self-center ms-2">
                            <label id="lblSchool" class="form-label"></label>
                        </div>
                    </div>
                    <div class="mb-2">
                        <input type="hidden" id="rentBookIdx">
                        <table class="table table-sm table-bordered table-hover" id="rentBookSearchTable">
                            <thead class="align-middle text-center">
                                <th>번호</th>
                                <th>바코드</th>
                                <th>도서명</th>
                                <th>저자</th>
                                <th>출판사</th>
                                <th>카테고리</th>
                                <th>대출여부</th>
                            </thead>
                            <tbody class="align-middle text-center" id="rentBookSearchList">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-inline align-self-center text-end">
                    <input type="hidden" id="rent_possible">
                    <input type="hidden" id="status_idx">
                    <button type="button" id="btnRentInsert" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-right-left"></i> 대출</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-7">
        <div class="card border-left-primary shadow mt-2">
            <div class="card-header">
                <h6>도서대출목록</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-2">
                        <div class="input-group mb-1">
                            <input type="hidden" id="user_no" />
                            <input type="hidden" id="center_book_idx" />
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <select id="selTeacher" class="form-select" onchange="rentListLoad()">
                                        <option value="">전체</option>
                                        <?php
                                        if (!empty($teacherList)) {
                                            foreach ($teacherList as $key => $val) {
                                                echo "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="selTeacher">담당</label>
                                </div>
                            </div>
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <select id="selStudentName" class="form-select" onchange="rentListLoad()">
                                        <option value="">전체</option>
                                        <?php
                                        if (!empty($studentList)) {
                                            foreach ($studentList as $key => $val) {
                                                echo "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label for="selStudentName">이름</label>
                                </div>
                            </div>
                            <div class="form-inline w-20 me-2">
                                <div class="form-floating">
                                    <input type="text" id="txtStudentName2" class="form-control bg-white" placeholder="이름" disabled />
                                    <label for="txtStudentName2">이름</label>
                                </div>
                            </div>
                            <div class="form-inline w-30 me-4">
                                <div class="form-floating">
                                    <input type="text" id="txtBookName2" class="form-control bg-white" placeholder="도서명" disabled />
                                    <label for="txtBookName2">도서명</label>
                                </div>
                            </div>
                            <div class="form-check form-check-inline align-self-center">
                                <input type="radio" id="txtCheck1" class="form-check-input" name="readYN" value="1" checked />
                                <label class="form-check-label" for="txtCheck1">읽음</label>
                            </div>
                            <div class="form-check form-check-inline align-self-center">
                                <input type="radio" id="txtCheck2" class="form-check-input" name="readYN" value="2" />
                                <label class="form-check-label" for="txtCheck2">안읽음</label>
                            </div>
                            <div class="text-end align-self-center">
                                <input type="hidden" id="rent_idx">
                                <input type="hidden" id="rent_status_idx">
                                <button type="button" id="btnReturnBook" class="btn btn-sm btn-outline-success" style="display: none;"><i class="fa-solid fa-right-left"></i> 반납</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <table id="rentListTable" class="table table-sm table-bordered table-hover">
                            <thead class="text-center align-middle">
                                <th>번호</th>
                                <th>이름</th>
                                <th>바코드</th>
                                <th>도서명</th>
                                <th>대출일자</th>
                                <th>대출자</th>
                                <th>반납예정일자</th>
                                <th>반납일자</th>
                                <th>연체일수</th>
                            </thead>
                            <tbody id="rentList">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 학생검색 모달 -->
    <div class="modal fade" id="RentStudentModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">학생검색</h5>
                    <button type="button" id="btnClose" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-2">
                        <input type="text" id="txtRentStudentName" class="form-control">
                        <div class="input-group-append">
                            <button type="button" id="studentRent" class="btn btn-outline-success"><i class="fas fa-search-location me-1"></i>검색</button>
                        </div>
                    </div>
                    <form>
                        <table class="table table-bordered table-hover">
                            <thead class="text-center align-middle">
                                <th>이름</th>
                                <th>학년</th>
                                <th>학교</th>
                            </thead>
                            <tbody id="studentRentList">
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
                </div>
            </div>
        </div>
    </div>

</div>
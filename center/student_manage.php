<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";

$infoClassCmp = new infoClassCmp();
$codeInfoCmp = new codeInfoCmp();

$teacherList = $infoClassCmp->teacherList($_SESSION['center_idx']);
$gradeList = $codeInfoCmp->getCodeInfo('02');
?>
<script src="/center/js/student_manage.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
</script>
<div class="mt-2 size-14">
    <div class="row">
        <!-- 원생목록 검색 조건 -->
        <div class="col-12">
            <div class="card border-left-primary shadow mt-2">
                <div class="card-header">
                    <h6>원생관리</h6>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <div class="form-check form-check-inline align-self-center">
                            <input type="radio" id="rdoType1" class="form-check-input" name="rdoType" value="00" checked />
                            <label for="rdoType1" class="form-check-label">재원</label>
                        </div>
                        <div class="form-check form-check-inline align-self-center">
                            <input type="radio" id="rdoType2" class="form-check-input" name="rdoType" value="01" />
                            <label for="rdoType2" class="form-check-label">휴원</label>
                        </div>
                        <div class="form-check form-check-inline align-self-center">
                            <input type="radio" id="rdoType3" class="form-check-input" name="rdoType" value="02" />
                            <label for="rdoType3" class="form-check-label">퇴원</label>
                        </div>
                        <div class="form-inline me-1">
                            <div class="form-floating">
                                <select id="selGrade" class="form-select" onchange="loadStudent()">
                                    <option value="">전체</option>
                                    <?php
                                    if (!empty($gradeList)) {
                                        foreach ($gradeList as $key => $val) {
                                    ?>
                                            <option value="<?= $val['code_num2'] ?>"><?= $val['code_name'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selGrade">학년</label>
                            </div>
                        </div>
                        <div class="form-inline me-1">
                            <div class="form-floating">
                                <select id="selTeacher" class="form-select" onchange="loadStudent()">
                                    <option value="">전체</option>
                                    <?php
                                    if (!empty($teacherList)) {
                                        foreach ($teacherList as $key => $val) {
                                    ?>
                                            <option value="<?= $val['user_no'] ?>"><?= $val['user_name'] ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selTeacher">담당</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 원생목록 -->
        <div class="col-4">
            <div class="card border-left-primary shadow mt-2">
                <div class="card-header">
                    <h6>원생목록</h6>
                </div>
                <div class="card-body">
                    <input type="hidden" id="student_no">
                    <table id="studentListTable" class="table table-sm table-bordered table-hover">
                        <thead class="text-center align-middle">
                            <th>번호</th>
                            <th>담당</th>
                            <th>이름</th>
                            <th>학년</th>
                            <th>수업</th>
                        </thead>
                        <tbody id="studentList">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 원생목록 선택 표시 정보 -->
        <div class="col-8">
            <div class="card border-left-primary shadow mt-2">
                <div class="card-header">
                    <div class="row">
                        <div class="col align-self-center">
                            <h6>원생정보</h6>
                        </div>
                        <div class="col-auto">
                            <div class="input-group justify-content-end mt-2" id="_divDateControl" style="display: none;">
                                <div class="form-inline">
                                    <input id="studentManageMonth" class="form-control" type="month" value="<?= date("Y-m") ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul id="TabList" class="nav nav-tabs" role="tablist">

                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">기본정보</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#pay" type="button" role="tab" aria-controls="pay" aria-selected="false">결제정보</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#lesson" type="button" role="tab" aria-controls="lesson" aria-selected="false">수업결과</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" data-bs-target="#book" type="button" role="tab" aria-controls="book" aria-selected="false">대출도서</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="TabContent">

                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <table id="profileTable" class="table table-sm table-bordered">
                                <thead class="align-middle text-center">
                                    <th colspan="4">원생정보</th>
                                </thead>
                                <tbody class="align-middle text-center" id="profileData">
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="pay" role="tabpanel" aria-labelledby="pay-tab">
                            <table class="table table-sm table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <th width="15%">색깔태그</th>
                                        <td width="20%" id="color_tag_td"></td>
                                        <th width="15%">비고</th>
                                        <td width="50%" id="etc_td" class="text-start" colspan="3"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="payTable" class="table table-sm table-bordered">
                                <thead class="align-middle text-center">
                                    <tr>
                                        <th colspan="6">결제목록</th>
                                    </tr>
                                    <tr>
                                        <th>번호</th>
                                        <th>금액</th>
                                        <th>결제일자</th>
                                        <th>결제수단</th>
                                        <th>입력자</th>
                                        <th>메모</th>
                                    </tr>
                                </thead>
                                <tbody class="align-middle text-center" id="payData">
                                </tbody>
                            </table>
                            <div class="text-end">
                                <button type="button" id="btnPayCertificate" class="btn btn-sm btn-outline-secondary" data-bs-target="#PayCertificateModal" data-bs-toggle="modal"><i class="fa-solid fa-receipt"></i>교육비납입증명서 출력</button>
                                <!-- Modal -->
                                <div class="modal fade" id="PayCertificateModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="ModalLabel">교육비납입증명서 출력</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="payCertificateForm" method="post" action="/common/paycertificate_print.php" target="_blank">
                                                    <input type="hidden" id="txtpay_fidx" name="txtpay_fidx" value="<?= $_SESSION['center_idx'] ?>" />
                                                    <input type="hidden" id="txtpay_sidx" name="txtpay_sidx" value="" />
                                                    <div class="input-group w-25 mb-2">
                                                        <div class="form-inline">
                                                            <div class="form-floating">
                                                                <input id="txtcYear" class="form-control" name="txtcYear" placeholder="대상년도" value="<?= date('Y', strtotime('-1 Years')) ?>" readonly />
                                                                <label for="txtcYear">대상년도</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-floating mb-2">
                                                        <input type="text" id="txtDayofLessonTime" class="form-control" name="txtDayofLessonTime" placeholder="1일 수업시간" maxlength="15" value="2.0 시간/2.5 시간" />
                                                        <label for="txtDayofLessonTime">1일 수업시간</label>
                                                    </div>
                                                    <div class="form-floating mb-2">
                                                        <input type="text" id="txtWeekofLessonTime" class="form-control" name="txtWeekofLessonTime" placeholder="1주 수업시간" maxlength="5" value="1 일" />
                                                        <label for="txtWeekofLessonTime">1주 수업시간</label>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                                                <button type="button" id="btnPayCertificatePrint" class="btn btn-primary"><i class="fa-solid fa-receipt"></i>교육비납입증명서 출력</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="lesson" role="tabpanel" aria-labelledby="lesson-tab">
                            <table id="lessonTable" class="table table-sm table-bordered">
                                <thead class="align-middle text-center">
                                    <tr>
                                        <th colspan="7">수업평가</th>
                                    </tr>
                                    <tr>
                                        <th>일자</th>
                                        <th>수업종류</th>
                                        <th>독서</th>
                                        <th>토론</th>
                                        <th>글쓰기</th>
                                        <th>출석</th>
                                        <th>메모</th>
                                    </tr>
                                </thead>
                                <tbody class="align-middle text-center" id="lessonData">
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="book" role="tabpanel" aria-labelledby="book-tab">
                            <table id="bookTable" class="table table-sm">
                                <thead class="align-middle text-center">
                                    <tr>
                                        <th colspan="8">도서목록</th>
                                    </tr>
                                    <tr>
                                        <th>바코드</th>
                                        <th>도서명</th>
                                        <th>대출일</th>
                                        <th>반납예정일</th>
                                        <th>연체</th>
                                    </tr>
                                </thead>
                                <tbody class="align-middle text-center" id="bookData">
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
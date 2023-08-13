<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/session_start.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER["DOCUMENT_ROOT"] . "/common/commonClass.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/common/function.php";

$infoClassCmp = new infoClassCmp();
$codeInfoCmp = new codeInfoCmp();
$teacherList = $infoClassCmp->teacherList($_SESSION['center_idx']);
$classRoomCnt = $infoClassCmp->getClassRoom($_SESSION['center_idx']);
$gradeList = $codeInfoCmp->getCodeInfo('02');
$classList = $codeInfoCmp->getCodeInfo('04');

$calendarTeacherOption = '';
$colorArr = array(
    "#a9e34b",
    "#f03e3e",
    "#cc5de8",
    "#20c997",
    "#ffe066",
    "#adb5bd",
    "#4c6ef5",
    "#ff922b",
    "#845ef7",
    "#51cf66",
    "#74c0fc",
    "#9C7717",
    "#f783ac",
    "#0c8599",
    "#4E7682"
);
if (!is_numeric($classRoomCnt)) {
    echo "<script>alert('강의실 정보가 부정확합니다.\\n본사로 문의 바랍니다.'); location.reload();</script>";
}

if (!empty($teacherList)) {
    foreach ($teacherList as $key => $val) {
        $calendarTeacherOption .= "{
            id: 'c" . $val['user_no'] . "',
            name: '" . $val['user_name'] . "',
            backgroundColor: '" . $colorArr[$key] . "',
            borderColor: '#f0f0f0',
        },";
    }
}

?>
<style>
    #calendars>div.toastui-calendar-floating-layer>div.toastui-calendar-event-detail-popup-slot>div>div.toastui-calendar-detail-container>div.toastui-calendar-popup-section.toastui-calendar-section-button {
        display: none;
    }

    #calendars>div.toastui-calendar-layout.toastui-calendar-day-view>div.toastui-calendar-panel.toastui-calendar-allday,
    #calendars>div.toastui-calendar-layout.toastui-calendar-week-view>div.toastui-calendar-panel.toastui-calendar-allday {
        display: none;
    }

    #calendars>div.toastui-calendar-layout.toastui-calendar-day-view,
    #calendars>div.toastui-calendar-layout.toastui-calendar-week-view,
    #calendars>div.toastui-calendar-layout.toastui-calendar-month {
        min-height: 55vh;
    }

    #calendars>div.toastui-calendar-layout.toastui-calendar-month>div.toastui-calendar-month-daygrid>div {
        min-height: 10vh;
    }
</style>
<script>
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
    var user_idx = '<?= $_SESSION['logged_no'] ?>';
</script>
<script src="/center/js/lesson_schedule.js?v=<?= date("YmdHis") ?>"></script>
<div class="card border-left-primary shadow mt-2">
    <div class="card-header">
        <h6>수업</h6>
    </div>
    <div class="card-body">
        <div class="row mt-2">
            <div class="col-6">
                <div class="input-group">
                    <?php
                    if (!empty($teacherList)) {
                        foreach ($teacherList as $key => $val) {
                            echo "<div class=\"form-check form-inline me-2\">
                        <input type=\"checkbox\" id=\"chktv" . $val['user_no'] . "\" class=\"form-check-input chktv\" value=\"c" . $val['user_no'] . "\" checked>
                        <label class=\"form-chcek-label\" for=\"chktv" . $val['user_no'] . "\">" . $val['user_name'] . "</label>
                        </div>";
                        }
                    }
                    ?>
                </div>
                <span id="calendar_range" class="fs-3 fw-bold text-center"></span>
                <div class="btn-toolbar justify-content-end mb-2" role="toolbar" aria-label="button group toolbar">
                    <div class="btn-group me-3" role="group" aria-label="btngroup 1">
                        <button type="button" id="btnCVm" class="btn btn-sm btn-outline-secondary"><i class="fa-regular fa-calendar me-1"></i>월 달력</button>
                        <button type="button" id="btnCVw" class="btn btn-sm btn-outline-secondary"><i class="fa-regular fa-calendar me-1"></i>주 달력</button>
                        <button type="button" id="btnCVd" class="btn btn-sm btn-outline-secondary"><i class="fa-regular fa-calendar me-1"></i>일 달력</button>
                    </div>
                    <div class="btn-group" role="group" aria-label="btngroup 1">
                        <button type="button" id="btnCPrev" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-caret-left"></i></button>
                        <button type="button" id="btnCToday" class="btn btn-sm btn-outline-secondary"><i class="fa-regular fa-square"></i></button>
                        <button type="button" id="btnCNext" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-caret-right"></i></button>
                    </div>
                </div>
                <div id="calendars" style="min-height: 600px;"></div>
            </div>
            <div class="col-6">
                <div>
                    <div class="input-group mb-2">
                        <div class="form-check form-switch form-check-inline align-self-center">
                            <input type="checkbox" id="chkLOnOff" class="form-check-input" />
                            <label id="lblLOnOff" class="form-check-label" for="chkLOnOff">오프라인</label>
                        </div>
                        <div class="form-check form-switch form-check-inline align-self-center">
                            <input type="checkbox" id="chkLFree" class="form-check-input" />
                            <label class="form-check-label" for="chkLFree">재량수업</label>
                        </div>
                        <div class="form-check form-check-inline align-self-center">
                            <input type="checkbox" id="chkLmChk" class="form-check-input" />
                            <label class="form-check-label" for="chkLmChk">보강여부</label>
                        </div>
                        <div id="div_Lm" class="form-check form-switch form-check-inline align-self-center" style="display: none;">
                            <input type="checkbox" id="chkLm" class="form-check-input" />
                            <label id="lblLm" class="form-check-label" for="chkLm">개인보강</label>
                        </div>
                        <div class="form-inline align-self-center">
                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#lessonMoveModal"><i class="fa-solid fa-forward me-1"></i>수업이월</button>
                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#lessonMoveWeekModal"><i class="fa-solid fa-forward me-1"></i>특정주수업이월</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#HolidayModal"><i class="fa-solid fa-calendar-day me-1"></i>휴일관리</button>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="form-floating">
                                <select id="selLessonType" class="form-select">
                                    <option value="">선택</option>
                                    <?php
                                    if (!empty($classList)) {
                                        foreach ($classList as $key => $val) {
                                            echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selLessonType">수업종류</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <select id="selLessonTeacher" class="form-select">
                                    <option value="">선택</option>
                                    <?php
                                    if (!empty($teacherList)) {
                                        foreach ($teacherList as $key => $val) {
                                            echo "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selLessonTeacher">담당</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <select id="selLessonGrade" class="form-select" onchange="loadCurriculumnBook()">
                                    <option value="">선택</option>
                                    <?php
                                    if (!empty($gradeList)) {
                                        foreach ($gradeList as $key => $val) {
                                            echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selLessonGrade">학년</label>
                            </div>
                        </div>
                        <div id="receipt_div" class="col" style="display: none;">
                            <div class="form-floating">
                                <select id="selReceiptItem" class="form-select">
                                    <option value="">선택</option>
                                </select>
                                <label for="selReceiptItem">특강종류</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="form-floating">
                                <select id="selLessonRoom" class="form-select">
                                    <option value="">선택</option>
                                    <?php
                                    for ($i = 1; $i <= $classRoomCnt; $i++) {
                                        echo "<option value=\"" . $i . "\">" . $i . " 토론실</option>";
                                    }
                                    ?>
                                </select>
                                <label for="selLessonRoom">토론실</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="date" id="dtsd" class="form-control" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" placeholder="수업일자" onchange="loadCurriculumnBook()">
                                <label for="dtsd">수업일자</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="time" id="dtsft" class="form-control" value="<?= date('H:00') ?>" placeholder="수업시작시간">
                                <label for="dtsft">수업시작시간</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="time" id="dtstt" class="form-control" value="<?= date('H:00', strtotime('+1 hours')) ?>" placeholder="수업종료시간">
                                <label for="dtstt">수업종료시간</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <select id="selLessonBook" class="form-select">
                        </select>
                        <label for="selLessonBook">수업도서</label>
                    </div>
                    <div class="text-end mb-2">
                        <div class="form-check form-check-inline align-self-center">
                            <input type="checkbox" id="chkRepeat" class="form-check-input" />
                            <label class="form-check-label" for="chkRepeat">월말반복</label>
                        </div>
                        <button type="button" id="btnAddSchedule" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-person-chalkboard me-1"></i>수업추가</button>
                        <button type="button" id="btnUpdateSchedule" class="btn btn-sm btn-outline-primary" style="display: none;"><i class="fa-solid fa-chalkboard-user me-1"></i>수업수정</button>
                    </div>
                    <table id="lessonScheduleTable" class="table table-sm table-hover table-bordered">
                        <thead class="align-middle text-center">
                            <th width="6%">번호</th>
                            <th width="9%">수업종류</th>
                            <th width="6%">학년</th>
                            <th width="14%">수업일시</th>
                            <th width="8%">담당</th>
                            <th width="8%">토론실</th>
                            <th width="31%">수업도서</th>
                            <th width="8%"></th>
                        </thead>
                        <tbody class="align-middle text-center" id="lessonScheduleList">
                        </tbody>
                    </table>
                    <hr>
                    <div class="input-group mb-2">
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select id="selScheduleGrade" class="form-select" onchange="loadStudentList()">
                                    <option value="">전체</option>
                                    <?php
                                    if (!empty($gradeList)) {
                                        foreach ($gradeList as $key => $val) {
                                            echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <label for="selScheduleGrade">학년</label>
                            </div>
                        </div>
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <select id="selScheduleStudent" class="form-select">
                                </select>
                                <label for="selScheduleStudent">수강학생</label>
                            </div>
                        </div>
                        <div class="form-inline align-self-center me-2">
                            <input type="hidden" id="targetScheduleIdx">
                            <button type="button" id="btnLStudentAdd" class="btn btn-sm btn-outline-success" style="display: none;"><i class="fa-solid fa-plus me-1"></i>추가</button>
                        </div>
                    </div>

                </div>
                <table id="LessonStudentTable" class="table table-sm table-hover table-bordered">
                    <thead class="align-middle text-center">
                        <th width="20%">번호</th>
                        <th width="25%">이름</th>
                        <th width="25%">학교</th>
                        <th width="20%">학년</th>
                        <th width="10%"></th>
                    </thead>
                    <tbody id="LessonStudentList" class="align-middle text-center">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="lessonMoveModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">수업이월</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>선택한 주의 시작일 포함 4주&#40;28일&#41; 또는 5주&#40;35일&#41;의 수업 데이터를 종료일 다음 날짜부터 이월합니다&#46;</p>
                <div class="input-group">
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="standardLessonMonth" value="<?= date("Y-m-d", strtotime('-28 days')) ?>">
                            <label for="standardLessonMonth">시작일</label>
                        </div>
                    </div>
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <select id="selLessonWeek" class="form-select">
                                <option value="4">4주</option>
                                <option value="5">5주</option>
                            </select>
                            <label for="selLessonWeek">주차</label>
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="targetLessonMonth" value="<?= date("Y-m-d") ?>" readonly>
                            <label for="targetLessonMonth">종료일</label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                <button type="button" id="btnLessonMove" class="btn btn-primary"><i class="fas fa-save me-1"></i>데이터 이월</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="lessonMoveWeekModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">특정주 수업 이월</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>선택한 주&#40;7일&#41;를 대상 주차로 이월합니다&#46; 단&#44; 대상 주차에 등록된 수업은 삭제 후 이월됩니다&#46;</p>
                <div class="input-group mb-2">
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="startLessonWeek1" value="<?= date("Y-m-d") ?>" />
                            <label for="startLessonWeek1">시작일</label>
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="endLessonWeek1" value="<?= date("Y-m-d", strtotime("+6 Days")) ?>" readonly />
                            <label for="endLessonWeek1">종료일</label>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="startLessonWeek2" value="<?= date("Y-m-d", strtotime("+7 Days")) ?>" />
                            <label for="startLessonWeek2">시작일</label>
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="endLessonWeek2" value="<?= date("Y-m-d", strtotime("+13 Days")) ?>" readonly />
                            <label for="endLessonWeek2">종료일</label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
                <button type="button" id="btnLessonMoveWeek" class="btn btn-primary"><i class="fas fa-save me-1"></i>특정주 데이터 이월</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="HolidayModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">휴일관리</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="input-group mb-2">
                            <div class="form-inline">
                                <div class="form-floating">
                                    <input type="date" id="dtHolidayDate" class="form-control" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" />
                                    <label for="dtHolidayDate">휴일</label>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="form-floating">
                                <input type="text" id="txtHolidayMemo" class="form-control" placeholder="휴일날짜 메모" maxlength="15" />
                                <label for="txtHolidayMemo">휴일날짜 메모</label>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" id="btnHolidaySave" class="btn btn-primary"><i class="fas fa-save me-1"></i>저장</button>
                        </div>
                    </div>
                    <div class="col-6">
                        <table id="HolidayInfoTable" class="table table-sm table-bordered table-hover">
                            <thead class="text-center align-middle">
                                <th>날짜</th>
                                <th>비고</th>
                                <th>&nbsp;</th>
                            </thead>
                            <tbody class="text-center align-middle"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark me-1"></i>닫기</button>
            </div>
        </div>
    </div>
</div>

<script>
    var hiViewType = 'month';
    var calendar;
    var range = $("#calendar_range");
    var options = {
        defaultView: 'month',
        usageStatistics: false,
        useFormPopup: false,
        useCreationPopup: false,
        useDetailPopup: true,
        isReadOnly: true,
        calendars: [<?= $calendarTeacherOption ?>],
        month: {
            dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
            startDayOfWeek: 0,
            narrowWeekend: false,
            taskView: false,
            isAlways6Weeks: false,
        },
        week: {
            dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
            startDayOfWeek: 0,
            narrowWeekend: false,
            taskView: false
        },
        gridSelection: {
            enableDblClick: false,
            enableClick: true,
        },
        theme: {
            week: {
                today: {
                    color: 'rgba(35, 199, 71, 1)',
                },
            },
            common: {
                saturday: {
                    color: 'blue',
                },
                today: {
                    color: 'white',
                },
            },
        },
        template: {
            monthDayName(model) {
                return `<span class="fw-bold">${model.label}</span>`;
            },
        },
    };
</script>
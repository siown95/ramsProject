<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/session_start.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER["DOCUMENT_ROOT"] . "/common/commonClass.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/common/function.php";

$infoClassCmp = new infoClassCmp();
$codeInfoCmp = new codeInfoCmp();
$teacherList = $infoClassCmp->teacherList($_SESSION['center_idx']);
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
<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />

    <?php
    $stat = 'student';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
    <script type="text/javascript" src="js/lesson_schedule.js?v=<?= date('YmdHis') ?>"></script>
    <style>
        #calendars {
            min-height: 600px;
        }

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
            min-height: 60vh;
        }

        #calendars>div.toastui-calendar-layout.toastui-calendar-month>div.toastui-calendar-month-daygrid>div {
            min-height: 9.5vh;
            overflow-y: auto;
        }
    </style>
</head>

<body class="size-14">
    <!-- 헤더 -->
    <?php include "navbar.php"; ?>

    <!-- 컨텐츠 -->
    <main>
        <div class="container-fluid">

            <div class="row">
                <div class="col-7">
                    <div class="card border-left-primary shadow mt-2 mb-2">
                        <div class="card-header">
                            <h6 class="card-title">시간표</h6>
                        </div>
                        <div class="card-body">
                            <span id="calendar_range" class="fs-3 fw-bold text-center"></span>
                            <div class="text-end">
                                <button type="button" id="btnCVm" class="btn btn-sm btn-outline-secondary"><i class="fa-regular fa-calendar me-1"></i>월 시간표</button>
                                <button type="button" id="btnCVw" class="btn btn-sm btn-outline-secondary"><i class="fa-regular fa-calendar me-1"></i>주 시간표</button>
                                <button type="button" id="btnCVd" class="btn btn-sm btn-outline-secondary"><i class="fa-regular fa-calendar me-1"></i>일 시간표</button>
                                <button type="button" id="btnCPrev" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-caret-left"></i></button>
                                <button type="button" id="btnCToday" class="btn btn-sm btn-outline-secondary"><i class="fa-regular fa-square"></i></button>
                                <button type="button" id="btnCNext" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-caret-right"></i></button>
                            </div>
                            <div id="calendars"></div>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="card border-left-primary shadow mt-2 mb-2">
                        <div class="card-header">
                            <h6 class="card-title">수업정보</h6>
                        </div>
                        <div class="card-body">
                            <table id="LessonInfoTable" class="table table-sm table-hover table-bordered">
                                <thead class="align-middle text-center">
                                    <th width="10%">종류</th>
                                    <th width="15%">일시</th>
                                    <th width="35%">수업도서</th>
                                    <th width="15%">지도교사</th>
                                    <th width="10%">강의실</th>
                                    <th width="15%">출석여부</th>
                                </thead>
                                <tbody class="align-middle text-center">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                var center_idx = '<?= $_SESSION['center_idx'] ?>';
                var student_idx = '<?= $_SESSION['logged_no'] ?>';
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
        </div>
    </main>
    <!-- 푸터 -->
    <?php include "footer.php"; ?>
</body>

</html>
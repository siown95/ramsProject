<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/LessonSchedule.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class LessonScheduleContoller extends Controller
{
    private $lessonScheduleInfo;

    function __construct()
    {
        $this->lessonScheduleInfo = new LessonScheduleInfo();
    }

    public function getCalendarData($request)
    {
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $student_idx = !empty($request['student_idx']) ? $request['student_idx'] : '';
        $standardMonth = !empty($request['standardMonth']) ? $request['standardMonth'] : '';

        try {
            if (empty($franchise_idx) || empty($student_idx) || empty($standardMonth)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonScheduleInfo->getCalendarData($franchise_idx, $student_idx, $standardMonth);
            $calendarData = array();
            $bodyTxtArr = array();
            $bodyTxt = '';

            if (!empty($result)) {
                foreach ($result as $key => $val) {

                    if (!empty($val['book_name'])) {
                        $bodyTxtArr[] .= $val['book_name'];
                    }

                    if (!empty($val['onoff_yn'])) {
                        $bodyTxtArr[] .= "온라인";
                    }

                    if (!empty($val['freehand_yn'])) {
                        $bodyTxtArr[] .= "재량";
                    }

                    if (!empty($val['supple_yn'])) {
                        if ($val['supple_type'] == '1') {
                            $bodyTxtArr[] .= "정규보강";
                        } else if ($val['supple_type'] == '2') {
                            $bodyTxtArr[] .= "개인보강";
                        } else {
                            $bodyTxtArr[] .= "";
                        }
                    }

                    $bodyTxt = implode(" ", $bodyTxtArr);

                    $calendarData[] = array(
                        "id" => ($key + 1),
                        "calendarId" => 'c' . $val['teacher_idx'],
                        "title" => $val['lesson_type'] . " (" . $val['teacher_name'] . ")",
                        "body" => $bodyTxt,
                        "state" => $val['lesson_type'],
                        "location" => $val['lesson_room'] . " 강의실",
                        "start" => $val['lesson_date'] . " " . $val['lesson_fromtime'],
                        "end" => $val['lesson_date'] . " " . $val['lesson_totime'],
                        "attendees" => explode(",", $val['student_name']),
                    );
                    $bodyTxt = '';
                    $bodyTxtArr = array();
                }
            }

            $return_data['data'] = $calendarData;

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadLessonSchedule($request)
    {
        $franchise_idx  = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_idx = !empty($request['student_idx']) ? $request['student_idx'] : '';
        $standardMonth  = !empty($request['standardMonth']) ? $request['standardMonth'] : '';

        try {
            if (empty($franchise_idx) || empty($student_idx) || empty($standardMonth)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonScheduleInfo->loadLessonSchedule($franchise_idx, $student_idx, $standardMonth);

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    if (!empty($val['onoff_yn'])) {
                        $onoffTxt = "(온)";
                    } else {
                        $onoffTxt = '';
                    }

                    if ($val['supple_type'] == '1') {
                        $suppleTxt = '<br>(정규보강)';
                    } else if ($val['supple_type'] == '2') {
                        $suppleTxt = '<br>(개인보강)';
                    } else {
                        $suppleTxt = '';
                    }

                    $result[$key]['no'] = ($key + 1);
                    $result[$key]['lesson_type'] = $val['lesson_type'] . $onoffTxt . $suppleTxt;
                    $result[$key]['lesson_room'] = $val['lesson_room'] . " 강의실";
                    $result[$key]['lesson_date'] = $val['lesson_date'] . " " . getWeekday($val['lesson_date']) . '<br>' . $val['lesson_time'];
                    $result[$key]['btnDelete'] = "<button type=\"button\" class=\"btn btn-sm btn-outline-danger schedule_del\"><i class=\"fas fa-trash me-1\"></i>삭제</button>";
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadLessonMain($request)
    {
        $franchise_idx  = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_idx = !empty($request['student_idx']) ? $request['student_idx'] : '';
        $standardMonth  = !empty($request['standardMonth']) ? $request['standardMonth'] : '';

        try {
            if (empty($franchise_idx) || empty($student_idx) || empty($standardMonth)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonScheduleInfo->loadLessonMain($franchise_idx, $student_idx, $standardMonth);
            $tbl = '';
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $tbl .= "<tr>
                                 <td class=\"text-center\">" . $val['lesson_type'] . "</td>
                                 <td class=\"text-center\">" . $val['lesson_date'] . getWeekday($val['lesson_date']) . " " . $val['lesson_time'] . "</td>
                                 <td class=\"text-start\">" . $val['book_name'] . "</td>
                                 <td class=\"text-center\">" . $val['teacher_name'] . "</td>
                                 <td class=\"text-center\">" . $val['lesson_room'] . " 강의실</td>
                                 <td class=\"text-center\">" . $val['Attend_Yn'] . "</td>
                                 <td class=\"text-center\">" . $val['score_read'] . "</td>
                                 <td class=\"text-center\">" . $val['score_debate'] . "</td>
                                 <td class=\"text-center\">" . $val['score_write'] . "</td>
                             </tr>";
                }
            } else {
                $tbl = "<td colspan='9'>" . date('Y-m') . " 수업 정보가 없습니다.</td>";
            }
            $result['tbl'] = $tbl;
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function lessonScheduleSelect($request)
    {
        $center_idx  = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $schedule_idx = !empty($request['schedule_idx']) ? $request['schedule_idx'] : '';

        try {
            if (empty($center_idx) || empty($schedule_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonScheduleInfo->lessonScheduleSelect($center_idx, $schedule_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadScheduleStudent($request)
    {
        $schedule_idx  = !empty($request['schedule_idx']) ? $request['schedule_idx'] : '';
        $center_idx    = !empty($request['center_idx']) ? $request['center_idx'] : '';

        try {
            if (empty($schedule_idx) || empty($center_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonScheduleInfo->loadScheduleStudent($schedule_idx, $center_idx);

            $tbl = '';

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $tbl .= "<tr class=\"align-middle text-center\" data-class-idx=\"" . $val['class_idx'] . "\">
                                 <td>" . ($key + 1) . "</td>
                                 <td>" . $val['student_name'] . "</td>
                                 <td>" . $val['school_name'] . "</td>
                                 <td>" . getGrade($val['age']) . "</td>
                                 <td><button type=\"button\" class=\"btn btn-sm btn-outline-danger student_del\"><i class=\"fas fa-trash me-1\"></i>삭제</button></td>
                             </tr>";
                }
            }
            $return_data['tbl'] = $tbl;

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$lessonScheduleController = new LessonScheduleContoller();

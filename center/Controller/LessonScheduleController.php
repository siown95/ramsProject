<?php
// error_reporting( E_ALL );
// ini_set( "display_errors", 1 );
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/LessonSchedule.php";
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
        $standardMonth = !empty($request['standardMonth']) ? $request['standardMonth'] : '';

        try {
            if (empty($franchise_idx) || empty($standardMonth)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonScheduleInfo->getCalendarData($franchise_idx, $standardMonth);
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
                        "location" => $val['lesson_room'] . " 토론실",
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

    public function loadCurriculumnBook($request)
    {
        $franchise_idx  = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $selLessonGrade = !empty($request['selLessonGrade']) ? $request['selLessonGrade'] : '';
        $optionDate     = !empty($request['optionDate']) ? date("m", strtotime($request['optionDate'])) : '';

        try {
            if (empty($franchise_idx) || empty($optionDate)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonScheduleInfo->loadCurriculumnBook($franchise_idx, $selLessonGrade, $optionDate);
            $selLessonBook = "<option value=\"\">선택</option>";

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $selLessonBook .= "<option value=\"" . $val['book_idx'] . "\">" . $val['book_name'] . "</option>";
                }
            }

            $return_data['data'] = $selLessonBook;

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadLessonSchedule($request)
    {
        $franchise_idx  = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $standardMonth  = !empty($request['standardMonth']) ? $request['standardMonth'] : '';

        try {
            if (empty($franchise_idx) || empty($standardMonth)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonScheduleInfo->loadLessonSchedule($franchise_idx, $standardMonth);

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
                    $result[$key]['lesson_room'] = $val['lesson_room'] . " 토론실";
                    $result[$key]['lesson_date'] = $val['lesson_date'] . " " . getWeekday($val['lesson_date']) . '<br>' . $val['lesson_time'];
                    $result[$key]['btnDelete'] = "<button type=\"button\" class=\"btn btn-sm btn-outline-danger schedule_del\"><i class=\"fas fa-trash me-1\"></i>삭제</button>";
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadStudentList($request)
    {
        $franchise_idx  = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $selScheduleGrade = !empty($request['selScheduleGrade']) ? $request['selScheduleGrade'] : '';

        try {
            if (empty($franchise_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if (!empty($selScheduleGrade)) {
                $studentAge = getAge($selScheduleGrade);
            }

            $result = $this->lessonScheduleInfo->loadStudentList($franchise_idx, $studentAge);
            $selStudentList = "<option value=\"\">선택</option>";

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $selStudentList .= "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                }
            }

            $return_data['data'] = $selStudentList;

            return $return_data;
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

    public function lessonScheduleInsert($request)
    {
        $franchise_idx  = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $onoff_yn  = !empty($request['onoff_yn']) ? $request['onoff_yn'] : '';
        $freehand_yn  = !empty($request['freehand_yn']) ? $request['freehand_yn'] : '';
        $supple_yn  = !empty($request['supple_yn']) ? $request['supple_yn'] : '';
        $supple_type  = !empty($request['supple_type']) ? $request['supple_type'] : '';
        $selLessonType  = !empty($request['selLessonType']) ? $request['selLessonType'] : '';
        $selLessonTeacher  = !empty($request['selLessonTeacher']) ? $request['selLessonTeacher'] : '';
        $selLessonRoom  = !empty($request['selLessonRoom']) ? $request['selLessonRoom'] : '';
        $selLessonGrade  = !empty($request['selLessonGrade']) ? $request['selLessonGrade'] : '';
        $selReceiptItem  = !empty($request['selReceiptItem']) ? $request['selReceiptItem'] : '';
        $dtsd  = !empty($request['dtsd']) ? $request['dtsd'] : '';
        $dtsft  = !empty($request['dtsft']) ? $request['dtsft'] : '';
        $dtstt  = !empty($request['dtstt']) ? $request['dtstt'] : '';
        $selLessonBook  = !empty($request['selLessonBook']) ? $request['selLessonBook'] : '';
        $chkRepeat  = !empty($request['chkRepeat']) ? $request['chkRepeat'] : '';

        try {
            if (empty($franchise_idx) || empty($selLessonType) || empty($selLessonTeacher) || empty($selLessonRoom) || empty($dtsd) || empty($dtsft) || empty($dtstt)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                "teacher_idx" => !empty($selLessonTeacher) ? $selLessonTeacher : '',
                "lesson_book_idx" => !empty($selLessonBook) ? $selLessonBook : '',
                "lesson_type" => !empty($selLessonType) ? $selLessonType : '',
                "lesson_date" => !empty($dtsd) ? $dtsd : '',
                "lesson_fromtime" => !empty($dtsft) ? $dtsft : '',
                "lesson_totime" => !empty($dtstt) ? $dtstt : '',
                "lesson_room" => !empty($selLessonRoom) ? $selLessonRoom : '',
                "lesson_grade" => !empty($selLessonGrade) ? $selLessonGrade : '',
                "receipt_idx" => !empty($selReceiptItem) ? $selReceiptItem : '0',
                "onoff_yn" => !empty($onoff_yn) ? $onoff_yn : '',
                "freehand_yn" => !empty($freehand_yn) ? $freehand_yn : '',
                "supple_yn" => !empty($supple_yn) ? $supple_yn : '',
                "supple_type" => !empty($supple_type) ? $supple_type : '',
            );

            if ($chkRepeat == 'Y') {
                $result = $this->lessonScheduleInfo->lessonScheduleInsert($params);
            } else {
                $result = $this->lessonScheduleInfo->insert($params);
            }

            if ($result) {
                $return_data['msg'] = '수업 정보가 등록되었습니다.';
            } else {
                throw new Exception('수업 정보가 등록되지 않았습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function lessonScheduleUpdate($request)
    {
        $schedule_idx  = !empty($request['schedule_idx']) ? $request['schedule_idx'] : '';
        $onoff_yn  = !empty($request['onoff_yn']) ? $request['onoff_yn'] : '';
        $freehand_yn  = !empty($request['freehand_yn']) ? $request['freehand_yn'] : '';
        $supple_yn  = !empty($request['supple_yn']) ? $request['supple_yn'] : '';
        $supple_type  = !empty($request['supple_type']) ? $request['supple_type'] : '';
        $selLessonType  = !empty($request['selLessonType']) ? $request['selLessonType'] : '';
        $selLessonTeacher  = !empty($request['selLessonTeacher']) ? $request['selLessonTeacher'] : '';
        $selLessonRoom  = !empty($request['selLessonRoom']) ? $request['selLessonRoom'] : '';
        $selLessonGrade  = !empty($request['selLessonGrade']) ? $request['selLessonGrade'] : '';
        $selReceiptItem  = !empty($request['selReceiptItem']) ? $request['selReceiptItem'] : '';
        $dtsd  = !empty($request['dtsd']) ? $request['dtsd'] : '';
        $dtsft  = !empty($request['dtsft']) ? $request['dtsft'] : '';
        $dtstt  = !empty($request['dtstt']) ? $request['dtstt'] : '';
        $selLessonBook  = !empty($request['selLessonBook']) ? $request['selLessonBook'] : '';

        try {
            if (empty($schedule_idx) || empty($selLessonType) || empty($selLessonTeacher) || empty($selLessonRoom) || empty($dtsd) || empty($dtsft) || empty($dtstt)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "teacher_idx" => !empty($selLessonTeacher) ? $selLessonTeacher : '',
                "lesson_book_idx" => !empty($selLessonBook) ? $selLessonBook : '',
                "lesson_type" => !empty($selLessonType) ? $selLessonType : '',
                "lesson_date" => !empty($dtsd) ? $dtsd : '',
                "lesson_fromtime" => !empty($dtsft) ? $dtsft : '',
                "lesson_totime" => !empty($dtstt) ? $dtstt : '',
                "lesson_room" => !empty($selLessonRoom) ? $selLessonRoom : '',
                "lesson_grade" => !empty($selLessonGrade) ? $selLessonGrade : '',
                "receipt_idx" => !empty($selReceiptItem) ? $selReceiptItem : '0',
                "onoff_yn" => !empty($onoff_yn) ? $onoff_yn : '',
                "freehand_yn" => !empty($freehand_yn) ? $freehand_yn : '',
                "supple_yn" => !empty($supple_yn) ? $supple_yn : '',
                "supple_type" => !empty($supple_type) ? $supple_type : '',
            );
            $this->lessonScheduleInfo->where_qry = " schedule_idx = '" . $schedule_idx . "' ";
            $result = $this->lessonScheduleInfo->update($params);

            if ($result) {
                $return_data['msg'] = '수업 정보가 수정되었습니다.';
            } else {
                throw new Exception('수업 정보가 수정되지 않았습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function lessonScheduleDelete($request)
    {
        $schedule_idx  = !empty($request['schedule_idx']) ? $request['schedule_idx'] : '';

        try {
            if (empty($schedule_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $this->lessonScheduleInfo->where_qry = " schedule_idx = '" . $schedule_idx . "' ";
            $result = $this->lessonScheduleInfo->delete();

            if ($result) {
                $return_data['msg'] = '수업 정보가 삭제되었습니다.';
            } else {
                throw new Exception('수업 정보가 삭제되지 않았습니다.', 701);
            }

            return $return_data;
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

    public function scheduleStudentInsert($request)
    {
        $schedule_idx  = !empty($request['schedule_idx']) ? $request['schedule_idx'] : '';
        $center_idx    = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_idx    = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($schedule_idx) || empty($center_idx) || empty($student_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $checkCnt = $this->lessonScheduleInfo->checkScheduleStudent($schedule_idx, $student_idx);

            if ($checkCnt) {
                throw new Exception('기존 등록된 학생입니다.', 701);
            }

            $params = array(
                "schedule_idx" => !empty($schedule_idx) ? $schedule_idx : '',
                "franchise_idx" => !empty($center_idx) ? $center_idx : '',
                "student_idx" => !empty($student_idx) ? $student_idx : '',
            );

            $this->lessonScheduleInfo->table_name = $this->lessonScheduleInfo->lesson_table_name;
            $result = $this->lessonScheduleInfo->insert($params);

            if ($result) {
                $return_data['msg'] = 'success';
            } else {
                throw new Exception('학생 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function scheduleStudentDelete($request)
    {
        $class_idx = !empty($request['class_idx']) ? $request['class_idx'] : '';

        try {
            if (empty($class_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $this->lessonScheduleInfo->table_name = $this->lessonScheduleInfo->lesson_table_name;
            $this->lessonScheduleInfo->where_qry = " class_idx = '" . $class_idx . "' ";
            $result = $this->lessonScheduleInfo->delete();

            if ($result) {
                $return_data['msg'] = 'success';
            } else {
                throw new Exception('학생 제외에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function lessonScheduleMove($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $standardMonth = !empty($request['standardMonth']) ? $request['standardMonth'] : '';
        $targetMonth = !empty($request['targetMonth']) ? $request['targetMonth'] : '';
        $selLessonWeek = !empty($request['selLessonWeek']) ? $request['selLessonWeek'] : '';

        try {
            if (empty($center_idx) || empty($standardMonth) || empty($targetMonth) || empty($selLessonWeek)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonScheduleInfo->lessonScheduleMove($center_idx, $standardMonth, $targetMonth, $selLessonWeek);

            if($result){
                $return_data['msg'] = "데이터가 이월되었습니다.";
            }else{
                throw new Exception('데이터 이월에 실패하였습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function lessonScheduleMoveWeek($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $startweek1 = !empty($request['startweek1']) ? $request['startweek1'] : '';
        $startweek2 = !empty($request['startweek2']) ? $request['startweek2'] : '';
        $endweek1 = !empty($request['endweek1']) ? $request['endweek1'] : '';
        $endweek2 = !empty($request['endweek2']) ? $request['endweek2'] : '';

        try {
            if (empty($center_idx) || empty($startweek1) || empty($startweek2) || empty($endweek1) || empty($endweek2)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonScheduleInfo->lessonScheduleMoveWeek($center_idx, $startweek1, $startweek2, $endweek1, $endweek2);

            if($result){
                $return_data['msg'] = "데이터가 이월되었습니다.";
            }else{
                throw new Exception('데이터 이월에 실패하였습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$lessonScheduleController = new LessonScheduleContoller();

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/LessonList.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class LessonListContoller extends Controller
{
    private $lessonListInfo;

    function __construct()
    {
        $this->lessonListInfo = new LessonListInfo();
    }

    public function loadLessonList($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $teacher_no    = !empty($request['teacher_no']) ? $request['teacher_no'] : '';
        $now_date      = !empty($request['now_date']) ? $request['now_date'] : '';

        try {
            if (empty($franchise_idx) || empty($teacher_no) || empty($now_date)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonListInfo->loadLessonList($franchise_idx, $teacher_no, $now_date);

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    if (date("Y-m-d") == $val['lesson_date']) {
                        $result[$key]['now_flag'] = 'Y';
                    } else {
                        $result[$key]['now_flag'] = '';
                    }

                    if ($val['supple_type'] == '1') {
                        $suppleTxt = ' (정규보강)';
                    } else if ($val['supple_type'] == '2') {
                        $suppleTxt = ' (개인보강)';
                    } else {
                        $suppleTxt = '';
                    }

                    $result[$key]['lesson_type'] = $val['lesson_type'] . $suppleTxt;
                    $result[$key]['lesson_date'] = $val['lesson_date'] . " " . getWeekday($val['lesson_date']);
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadLessonStudentList($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $schedule_idx  = !empty($request['schedule_idx']) ? $request['schedule_idx'] : '';

        try {
            if (empty($franchise_idx) || empty($schedule_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonListInfo->loadLessonStudentList($franchise_idx, $schedule_idx);
            $activityResult = $this->lessonListInfo->loadActivityPaper($franchise_idx, $schedule_idx);

            $tbl = '';
            $divShow = '';
            $activityList = '<div class="form-inline align-self-center me-2">
                                 <label class="form-label">활동지</label>
                             </div>';

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    if ($val['attend_yn'] == "Y") {
                        $tableClassTxt = "table-primary";
                    } else if ($val['attend_yn'] == "N") {
                        $tableClassTxt = "table-danger";
                    } else {
                        $tableClassTxt = "";
                    }

                    $tbl .= "<tr class=\"align-middle text-center " . $tableClassTxt . " sl\" data-student-idx=\"" . $val['student_idx'] . "\" data-score-check=\"" . ($val['score_chk'] ? 'Y' : 'N') . "\" data-attend-check=\"" . $val['attend_yn'] . "\">
                                 <td>" . ($key + 1) . "</td>
                                 <td>" . $val['student_name'] . " (" . $val['school_name'] . ")</td>
                                 <td>" . getGrade($val['age']) . "</td>
                             </tr>";
                }
                $divShow = 'Y';
            } else {
                $tbl = "<tr class='text-center align-middle'><td colspan='3'>학생이 존재하지 않습니다.<tr>";
                $divShow = 'N';
            }

            if (!empty($activityResult)) {
                if (!empty($activityResult['activity_student1'])) {
                    $activityList .= "<div class=\"form-inline align-self-center me-2\">
                                          <a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $activityResult['activity_student1'] . "\" target=\"_blank\"><i class=\"fa-regular fa-file-lines\"></i> 학생용 1</a>
                                      </div>";
                }

                if (!empty($activityResult['activity_student2'])) {
                    $activityList .= "<div class=\"form-inline align-self-center me-2\">
                                          <a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $activityResult['activity_student2'] . "\" target=\"_blank\"><i class=\"fa-regular fa-file-lines\"></i> 학생용 2</a>
                                      </div>";
                }

                if (!empty($activityResult['activity_teacher1'])) {
                    $activityList .= "<div class=\"form-inline align-self-center me-2\">
                                          <a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $activityResult['activity_teacher1'] . "\" target=\"_blank\"><i class=\"fa-regular fa-file-lines\"></i> 교사용 1</a>
                                      </div>";
                }

                if (!empty($activityResult['activity_teacher2'])) {
                    $activityList .= "<div class=\"form-inline align-self-center me-2\">
                                          <a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $activityResult['activity_teacher2'] . "\" target=\"_blank\"><i class=\"fa-regular fa-file-lines\"></i> 교사용 2</a>
                                      </div>";
                }
            }

            $return_data['tbl'] = $tbl;
            $return_data['divShow'] = $divShow;
            $return_data['activityList'] = $activityList;

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function lessonAllAttend($request)
    {
        $schedule_idx = !empty($request['schedule_idx']) ? $request['schedule_idx'] : '';
        $attend_flag = !empty($request['attend_chk']) ? $request['attend_chk'] : '';

        try {
            if (empty($schedule_idx) || empty($attend_flag)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $this->lessonListInfo->table_name = $this->lessonListInfo->attend_table_name;
            $this->lessonListInfo->where_qry = " schedule_idx = '" . $schedule_idx . "' ";
            $del_result = $this->lessonListInfo->delete();

            if ($del_result) {
                $sql = "";
                $lesson_student_list = $this->lessonListInfo->lessonStudentList($schedule_idx);

                if (!empty($lesson_student_list)) {
                    foreach ($lesson_student_list as $key => $val) {
                        $sql .= " INSERT INTO lesson_attendT (schedule_idx, student_idx, attend_yn) VALUES ('" . $schedule_idx . "','" . $val['student_idx'] . "','" . $attend_flag . "')";
                    }
                }

                $result = $this->lessonListInfo->db->execute($sql);

                if ($result) {
                    $return_data['msg'] = "출결정보가 저장되었습니다.";
                } else {
                    throw new Exception('출결정보 저장에 실패하였습니다.', 701);
                }
            } else {
                throw new Exception('기존정보 삭제에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function lessonAttendCheck($request)
    {
        $schedule_idx = !empty($request['schedule_idx']) ? $request['schedule_idx'] : '';
        $student_idx = !empty($request['student_idx']) ? $request['student_idx'] : '';
        $attend_flag = !empty($request['attend_chk']) ? $request['attend_chk'] : '';

        try {
            if (empty($schedule_idx) || empty($student_idx) || empty($attend_flag)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $attend_chk = $this->lessonListInfo->attendListCheck($schedule_idx, $student_idx);

            $this->lessonListInfo->table_name = $this->lessonListInfo->attend_table_name;

            if ($attend_chk) {
                $params = array(
                    "attend_yn" => !empty($attend_flag) ? $attend_flag : '',
                    "mod_date" => 'getdate()'
                );
                $this->lessonListInfo->where_qry = " schedule_idx = '" . $schedule_idx . "' AND student_idx = '" . $student_idx . "' ";
                $result = $this->lessonListInfo->update($params);
            } else {
                $params = array(
                    "schedule_idx" => !empty($schedule_idx) ? $schedule_idx : '',
                    "student_idx" => !empty($student_idx) ? $student_idx : '',
                    "attend_yn" => !empty($attend_flag) ? $attend_flag : ''
                );
                $result = $this->lessonListInfo->insert($params);
            }

            if ($result) {
                $return_data['msg'] = "출결정보가 저장되었습니다.";
            } else {
                throw new Exception('출결정보 저장에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadLessonScore($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $schedule_idx = !empty($request['schedule_idx']) ? $request['schedule_idx'] : '';
        $student_idx = !empty($request['student_idx']) ? $request['student_idx'] : '';

        try {
            if (empty($center_idx) || empty($schedule_idx) || empty($student_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonListInfo->loadLessonScore($schedule_idx, $center_idx, $student_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function scoreInsert($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $schedule_idx = !empty($request['schedule_idx']) ? $request['schedule_idx'] : '';
        $student_idx = !empty($request['student_idx']) ? $request['student_idx'] : '';
        $selRead = !empty($request['selRead']) ? $request['selRead'] : '';
        $selDebate1 = !empty($request['selDebate1']) ? $request['selDebate1'] : '';
        $selDebate2 = !empty($request['selDebate2']) ? $request['selDebate2'] : '';
        $selDebate3 = !empty($request['selDebate3']) ? $request['selDebate3'] : '';
        $selDebate4 = !empty($request['selDebate4']) ? $request['selDebate4'] : '';
        $selWrite1 = !empty($request['selWrite1']) ? $request['selWrite1'] : '';
        $selWrite2 = !empty($request['selWrite2']) ? $request['selWrite2'] : '';
        $selWrite3 = !empty($request['selWrite3']) ? $request['selWrite3'] : '';
        $selWrite4 = !empty($request['selWrite4']) ? $request['selWrite4'] : '';
        $selLead = !empty($request['selLead']) ? $request['selLead'] : '';
        $txtMemo = !empty($request['txtMemo']) ? $request['txtMemo'] : '';

        try {
            if (
                empty($center_idx) || empty($schedule_idx) || empty($student_idx)
                || empty($selRead) || empty($selDebate1) || empty($selDebate2) || empty($selDebate3) || empty($selDebate4)
                || empty($selWrite1) || empty($selWrite2) || empty($selWrite3) || empty($selWrite4) || empty($selLead)
            ) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "schedule_idx" => !empty($schedule_idx) ? $schedule_idx : '',
                "franchise_idx" => !empty($center_idx) ? $center_idx : '',
                "student_idx" => !empty($student_idx) ? $student_idx : '',
                "score_read" => !empty($selRead) ? $selRead : '',
                "score_debate1" => !empty($selDebate1) ? $selDebate1 : '',
                "score_debate2" => !empty($selDebate2) ? $selDebate2 : '',
                "score_debate3" => !empty($selDebate3) ? $selDebate3 : '',
                "score_debate4" => !empty($selDebate4) ? $selDebate4 : '',
                "score_write1" => !empty($selWrite1) ? $selWrite1 : '',
                "score_write2" => !empty($selWrite2) ? $selWrite2 : '',
                "score_write3" => !empty($selWrite3) ? $selWrite3 : '',
                "score_write4" => !empty($selWrite4) ? $selWrite4 : '',
                "score_lead" => !empty($selLead) ? $selLead : '',
                "score_memo" => !empty($txtMemo) ? $txtMemo : '',
            );

            $this->lessonListInfo->table_name = $this->lessonListInfo->score_table_name;
            $result = $this->lessonListInfo->insert($params);

            if ($result) {
                $return_data['msg'] = "점수가 저장되었습니다.";
            } else {
                throw new Exception('점수 저장에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getLessonStudentData($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_idx = !empty($request['student_idx']) ? $request['student_idx'] : '';
        $studentManageMonth = !empty($request['studentManageMonth']) ? $request['studentManageMonth'] : '';

        try {
            if (empty($center_idx) || empty($student_idx) || empty($studentManageMonth)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->lessonListInfo->getLessonStudentData($center_idx, $student_idx, $studentManageMonth);

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['lesson_date'] = $val['lesson_date'] . " " . getWeekday($val['lesson_date']);

                    if ($val['onoff_yn']) {
                        $onoffTxt = '(온)';
                    } else {
                        $onoffTxt = '';
                    }

                    if ($val['freehand_yn']) {
                        $freehandTxt = '(재량)';
                    } else {
                        $freehandTxt = '';
                    }

                    if ($val['supple_type'] == '1') {
                        $suppleTxt = ' (정규보강)';
                    } else if ($val['supple_type'] == '2') {
                        $suppleTxt = ' (개인보강)';
                    } else {
                        $suppleTxt = '';
                    }

                    $result[$key]['lesson_type'] = $val['lesson_type'] . $onoffTxt . $freehandTxt . $suppleTxt;
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$lessonListController = new LessonListContoller();

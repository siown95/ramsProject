<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/LessonScheduleBatch.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class LessonScheduleBatchContoller extends Controller
{
    private $lessonScheduleBatchInfo;

    function __construct()
    {
        $this->lessonScheduleBatchInfo = new LessonScheduleBatchInfo();
    }

    public function loadLessonBookList($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $grade = !empty($request['grade']) ? $request['grade'] : '';
        $now_date = !empty($request['now_date']) ? $request['now_date'] : '';

        try {
            if (empty($center_idx) || empty($grade) || empty($now_date)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonScheduleBatchInfo->loadLessonBookList($center_idx, $now_date, $grade);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $key + 1;
                    $result[$key]['lack_cnt'] = ($val['lesson_student_cnt'] + 3) - ($val['lesson_book_cnt'] - $val['book_rent_cnt']);
                    $result[$key]['pre_cnt'] = $val['lesson_student_cnt'];
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadLessonScheduleList($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $grade = !empty($request['grade']) ? $request['grade'] : '';
        $now_date = !empty($request['now_date']) ? $request['now_date'] : '';

        try {
            if (empty($center_idx) || empty($grade) || empty($now_date)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonScheduleBatchInfo->loadLessonScheduleList($center_idx, $grade, $now_date);

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['lesson_date'] = $val['lesson_date'] . " " . getWeekday($val['lesson_date']);
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function lessonBookBatch($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $grade = !empty($request['grade']) ? $request['grade'] : '';
        $now_date = !empty($request['now_date']) ? $request['now_date'] : '';

        try {
            if (empty($center_idx) || empty($grade) || empty($now_date)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $curriculumBookList = $this->lessonScheduleBatchInfo->getCurriculumBook($center_idx, $grade, $now_date);
            $lessonScheduleList = $this->lessonScheduleBatchInfo->getScheduleList($center_idx, $grade, $now_date);

            if (empty($curriculumBookList)) {
                throw new Exception('커리큘럼 정보가 존재하지 않습니다.', 701);
            }

            if (empty($lessonScheduleList)) {
                throw new Exception('수업 정보가 존재하지 않습니다.', 701);
            }


            $schedule_arr = array();
            foreach ($lessonScheduleList as $key => $val) {
                $schedule_arr["lesson_schedule_" . $key] = explode(",", $val['lesson_schedule']);
            }
            $c = 0;
            $tempArr = $curriculumBookList;

            for ($i = 0; $i < count($schedule_arr); $i++) {
                for ($j = 0; $j < count($schedule_arr["lesson_schedule_" . $i]); $j++) {
                    for ($k = 0; $k < count($curriculumBookList); $k++) {
                        $curriculumBookList[$k]['book_idx'] = $curriculumBookList[($k + 1)]['book_idx'];
                        if (($k + 1) == count($curriculumBookList)) {
                            if ($c >= count($curriculumBookList)) {
                                $c = 0;
                            }
                            $curriculumBookList[$k]['book_idx'] = $tempArr[$c]['book_idx'];
                            $c++;
                            $params = array("lesson_book_idx" => $curriculumBookList[$k]['book_idx']);
                            $this->lessonScheduleBatchInfo->table_name = "lessonM";
                            $this->lessonScheduleBatchInfo->where_qry = " schedule_idx = '" . $schedule_arr["lesson_schedule_" . $i][$j] . "'";
                            $result = $this->lessonScheduleBatchInfo->update($params);
                        }
                    }
                }
            }

            if (!$result) {
                throw new Exception('도서 배치에 실패하였습니다.', 701);
            } else {
                $return_data['msg'] = "도서배치가 완료되었습니다.";
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$lessonScheduleBatchController = new LessonScheduleBatchContoller();

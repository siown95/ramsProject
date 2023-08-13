<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class LessonScheduleInfo extends Model
{
    var $table_name = 'lessonM';
    var $lesson_table_name = 'lessonT';

    function __construct()
    {
        parent::__construct();
    }

    public function getCalendarData($franchise_idx, $standardMonth)
    {
        $startDate = date("Y-m", strtotime($standardMonth));
        $endDate = date("Y-m-t", strtotime($standardMonth));
        $sql = "SELECT LM.schedule_idx, LM.teacher_idx, C.code_name lesson_type, LM.onoff_yn, LM.freehand_yn 
                , LM.supple_yn, LM.supple_type, LM.lesson_date, LM.lesson_fromtime, LM.lesson_totime, LM.lesson_room
                , ISNULL(B.book_name,'') book_name, T.user_name teacher_name
                , ISNULL((SELECT STRING_AGG(user_name, ',') FROM member_studentM WHERE user_no IN 
                    (SELECT student_idx FROM lessonT WHERE schedule_idx = LM.schedule_idx)) ,'') student_name
                FROM {$this->table_name} LM 
                LEFT OUTER JOIN codeM C ON C.code_num2 = LM.lesson_type AND C.code_num1 = '04'
                LEFT OUTER JOIN bookM B ON B.book_idx = LM.lesson_book_idx
                LEFT OUTER JOIN member_centerM T ON T.user_no = LM.teacher_idx
                WHERE LM.franchise_idx = '" . $franchise_idx . "'
                AND LM.lesson_date BETWEEN '" . $startDate . "-01' AND '" . $endDate . "'";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function loadCurriculumnBook($franchise_idx, $selLessonGrade, $optionDate)
    {
        $where_arr = array(
            " C.franchise_idx = '" . $franchise_idx . "' ",
            " C.months = '" . $optionDate . "' "
        );

        if (!empty($selLessonGrade)) {
            $where_arr[] .= " C.grade = '" . $selLessonGrade . "' ";
        }

        $where_qry = implode(" AND ", $where_arr);
        $sql = "SELECT B.book_idx, B.book_name 
                FROM curriculumM C 
                LEFT OUTER JOIN bookM B ON B.book_idx = C.book_idx 
                WHERE " . $where_qry . " ORDER BY C.months, C.grade, C.orders";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function loadLessonSchedule($franchise_idx, $standardMonth)
    {
        $to_date = date("Y-m-t", strtotime($standardMonth));

        $sql = "SELECT L.schedule_idx, C.code_name lesson_type, L.lesson_date, CONCAT(L.lesson_fromtime, ' ~ ',L.lesson_totime) lesson_time 
                , M.user_name teacher_name, L.lesson_room, ISNULL(C2.code_name, '') lesson_grade, ISNULL(B.book_name, '') book_name, L.supple_yn, L.supple_type, L.onoff_yn
                FROM {$this->table_name} L 
                LEFT OUTER JOIN codeM C ON C.code_num2 = L.lesson_type AND C.code_num1 = '04'
                LEFT OUTER JOIN codeM C2 ON C2.code_num2 = L.lesson_grade AND C2.code_num1 = '02' AND C2.code_num2 <> ''
                LEFT OUTER JOIN bookM B ON B.book_idx = L.lesson_book_idx
                LEFT OUTER JOIN member_centerM M ON M.user_no = L.teacher_idx
                WHERE L.franchise_idx = '" . $franchise_idx . "'
                AND L.lesson_date BETWEEN '" . $standardMonth . "-01' AND '" . $to_date . "'
                ORDER BY L.lesson_date, L.lesson_fromtime";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function loadStudentList($franchise_idx, $studentAge)
    {
        $where_arr = array(
            " franchise_idx = '" . $franchise_idx . "' ",
            " state = '00' "
        );

        if (!empty($studentAge)) {
            if ($studentAge == '6') {
                $where_arr[] .= ' (YEAR(getdate()) - LEFT(birth, 4) + 1) <= 6 ';
            } else {
                $where_arr[] .= " (YEAR(getdate()) - LEFT(birth, 4) + 1) = '" . $studentAge . "' ";
            }
        }

        $where_qry = "WHERE" . implode(" AND ", $where_arr);

        $sql = "SELECT user_no, user_name FROM member_studentM " . $where_qry;
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function lessonScheduleSelect($center_idx, $schedule_idx)
    {
        $sql = "SELECT teacher_idx, lesson_book_idx, lesson_type, lesson_date, lesson_fromtime, lesson_totime, lesson_room, lesson_grade, receipt_idx, onoff_yn
                , freehand_yn, supple_yn, supple_type
                FROM {$this->table_name} WHERE franchise_idx = '" . $center_idx . "' AND schedule_idx = '" . $schedule_idx . "'";
        
        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function lessonScheduleInsert($request)
    {
        $sql = '';
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $teacher_idx = !empty($request['teacher_idx']) ? $request['teacher_idx'] : '';
        $lesson_book_idx = !empty($request['lesson_book_idx']) ? $request['lesson_book_idx'] : '';
        $lesson_type = !empty($request['lesson_type']) ? $request['lesson_type'] : '';
        $lesson_date = !empty($request['lesson_date']) ? $request['lesson_date'] : '';
        $lesson_fromtime = !empty($request['lesson_fromtime']) ? $request['lesson_fromtime'] : '';
        $lesson_totime = !empty($request['lesson_totime']) ? $request['lesson_totime'] : '';
        $lesson_room = !empty($request['lesson_room']) ? $request['lesson_room'] : '';
        $onoff_yn = !empty($request['onoff_yn']) ? $request['onoff_yn'] : '';
        $freehand_yn = !empty($request['freehand_yn']) ? $request['freehand_yn'] : '';
        $supple_yn = !empty($request['supple_yn']) ? $request['supple_yn'] : '';
        $supple_type = !empty($request['supple_type']) ? $request['supple_type'] : '';

        $selLessonLastDate = '';
        $selLessonDateArr = array();
        $lessonEndRepeat = date("Y-m-t", strtotime($lesson_date));

        for ($selLessonLastDate = $lesson_date; $selLessonLastDate <= $lessonEndRepeat; $selLessonLastDate = date("Y-m-d", strtotime($selLessonLastDate . '+7 days'))) {
            $selLessonDateArr[] .= $selLessonLastDate;
        }

        for ($i = 0; $i < count($selLessonDateArr); $i++) {
            $sql .= "INSERT INTO {$this->table_name} (franchise_idx, teacher_idx, lesson_book_idx, lesson_type, lesson_date, lesson_fromtime
            , lesson_totime, lesson_room, onoff_yn, freehand_yn, supple_yn, supple_type) 
            VALUES ('" . $franchise_idx . "','" . $teacher_idx . "','" . $lesson_book_idx . "','" . $lesson_type . "','" . $selLessonDateArr[$i] . "','" . $lesson_fromtime . "'
            ,'" . $lesson_totime . "','" . $lesson_room . "','" . $onoff_yn . "','" . $freehand_yn . "','" . $supple_yn . "','" . $supple_type . "') ";
        }

        $result = $this->db->execute($sql);
        return $result;
    }

    public function loadScheduleStudent($schedule_idx, $center_idx)
    {
        $sql = "SELECT L.class_idx, M.user_name student_name, M.school_name, (YEAR(getdate()) - LEFT(M.birth, 4) + 1) age
                FROM {$this->lesson_table_name} L
                LEFT OUTER JOIN member_studentM M
                ON M.user_no = L.student_idx
                WHERE L.schedule_idx = '" . $schedule_idx . "' AND L.franchise_idx = '" . $center_idx . "'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function checkScheduleStudent($schedule_idx, $student_idx)
    {
        $sql = "SELECT COUNT(0) FROM {$this->lesson_table_name} WHERE schedule_idx = '" . $schedule_idx . "' AND student_idx = '" . $student_idx . "'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function lessonScheduleMove($franchise_idx, $standardMonth, $targetMonth, $selLessonWeek)
    {
        $sql = "exec sp_schedule_holdover '" . $franchise_idx . "','" . $standardMonth . "','" . $targetMonth . "','" . ($selLessonWeek * 7) . "'";
        $result = $this->db->execute($sql);

        return $result;
    }

    public function lessonScheduleMoveWeek($franchise_idx, $startweek1, $startweek2, $endweek1, $endweek2)
    {
        $sql = "exec sp_schedule_holdover_week '" . $franchise_idx . "','" . $startweek1 . "','" . $startweek2 . "','" . $endweek1 . "','" . $endweek2 . "'";
        $result = $this->db->execute($sql);

        return $result;
    }
}

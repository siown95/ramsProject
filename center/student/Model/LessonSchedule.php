<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Model.php";

class LessonScheduleInfo extends Model
{
    var $table_name = 'lessonM';
    var $lesson_table_name = 'lessonT';
    var $lesson_score_table_name = 'lesson_scoreT';

    function __construct()
    {
        parent::__construct();
    }

    public function getCalendarData($franchise_idx, $student_idx, $standardMonth)
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
                LEFT OUTER JOIN lessonT LT ON LM.schedule_idx = LT.schedule_idx
                WHERE LM.franchise_idx = '" . $franchise_idx . "' AND LT.student_idx = '" . $student_idx . "'
                AND LM.lesson_date BETWEEN '" . $startDate . "-01' AND '" . $endDate . "'";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function loadLessonSchedule($franchise_idx, $student_idx, $standardMonth)
    {
        $to_date = date("Y-m-t", strtotime($standardMonth));

        $sql = "SELECT L.schedule_idx, C.code_name lesson_type, L.lesson_date, CONCAT(L.lesson_fromtime, ' ~ ',L.lesson_totime) lesson_time 
                , M.user_name teacher_name, L.lesson_room, ISNULL(C2.code_name, '') lesson_grade, ISNULL(B.book_name, '') book_name, L.supple_yn, L.supple_type, L.onoff_yn, 
                CASE WHEN LA.attend_yn = 'Y' THEN '출석'
                WHEN LA.attend_yn = 'N' THEN '결석'
                ELSE '' END Attend_Yn
                FROM {$this->table_name} L 
                LEFT OUTER JOIN codeM C ON C.code_num2 = L.lesson_type AND C.code_num1 = '04'
                LEFT OUTER JOIN codeM C2 ON C2.code_num2 = L.lesson_grade AND C2.code_num1 = '02' AND C2.code_num2 <> ''
                LEFT OUTER JOIN bookM B ON B.book_idx = L.lesson_book_idx
                LEFT OUTER JOIN member_centerM M ON M.user_no = L.teacher_idx
                LEFT OUTER JOIN {$this->lesson_table_name} LT ON L.schedule_idx = LT.schedule_idx
                LEFT OUTER JOIN {$this->lesson_score_table_name} LS ON L.schedule_idx = LS.schedule_idx
                LEFT OUTER JOIN lesson_attendT LA ON L.schedule_idx = LA.schedule_idx AND LT.student_idx = LA.student_idx
                WHERE L.franchise_idx = '" . $franchise_idx . "' AND LT.student_idx = '" . $student_idx . "'
                AND L.lesson_date BETWEEN '" . $standardMonth . "-01' AND '" . $to_date . "'
                ORDER BY L.lesson_date, L.lesson_fromtime";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function loadLessonMain($franchise_idx, $student_idx, $standardMonth)
    {
        $to_date = date("Y-m-t", strtotime($standardMonth));

        $sql = "SELECT L.schedule_idx, C.code_name lesson_type, L.lesson_date, CONCAT(L.lesson_fromtime, ' ~ ',L.lesson_totime) lesson_time,
                ISNULL(LS.score_read, '0') score_read, 
                ISNULL((LS.score_debate1+LS.score_debate2+LS.score_debate3+LS.score_debate4) / 4, '0') score_debate, 
                ISNULL((LS.score_write1+LS.score_write2+LS.score_write3+LS.score_write4) / 4, '0') score_write,
                M.user_name teacher_name, L.lesson_room, ISNULL(C2.code_name, '') lesson_grade, ISNULL(B.book_name, '') book_name, L.supple_yn, L.supple_type, L.onoff_yn, 
                CASE WHEN LA.attend_yn = 'Y' THEN '출석'
                WHEN LA.attend_yn = 'N' THEN '결석'
                ELSE '' END Attend_Yn
                FROM {$this->table_name} L 
                LEFT OUTER JOIN codeM C ON C.code_num2 = L.lesson_type AND C.code_num1 = '04'
                LEFT OUTER JOIN codeM C2 ON C2.code_num2 = L.lesson_grade AND C2.code_num1 = '02' AND C2.code_num2 <> ''
                LEFT OUTER JOIN bookM B ON B.book_idx = L.lesson_book_idx
                LEFT OUTER JOIN member_centerM M ON M.user_no = L.teacher_idx
                LEFT OUTER JOIN {$this->lesson_table_name} LT ON L.schedule_idx = LT.schedule_idx
                LEFT OUTER JOIN {$this->lesson_score_table_name} LS ON L.schedule_idx = LS.schedule_idx
                LEFT OUTER JOIN lesson_attendT LA ON L.schedule_idx = LA.schedule_idx AND LT.student_idx = LA.student_idx
                WHERE L.franchise_idx = '" . $franchise_idx . "' AND LT.student_idx = '" . $student_idx . "'
                AND L.lesson_date BETWEEN '" . $standardMonth . "-01' AND '" . $to_date . "'
                ORDER BY L.lesson_date, L.lesson_fromtime";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function lessonScheduleSelect($center_idx, $schedule_idx)
    {
        $sql = "SELECT teacher_idx, lesson_book_idx, lesson_type, lesson_date, lesson_fromtime, lesson_totime, lesson_room, lesson_grade, onoff_yn, freehand_yn, supple_yn, supple_type
                FROM {$this->table_name} WHERE franchise_idx = '" . $center_idx . "' AND schedule_idx = '" . $schedule_idx . "'";

        $result = $this->db->sqlRow($sql);
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
}

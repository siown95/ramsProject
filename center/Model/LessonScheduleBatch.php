<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class LessonScheduleBatchInfo extends Model
{
    var $table_name = 'lessonM';
    var $lesson_table_name = 'lessonT';

    function __construct()
    {
        parent::__construct();
    }

    public function loadLessonBookList($franchise_idx, $now_date, $grade)
    {
        $standardMonth = date("m", strtotime($now_date));
        $endMonth = date("Y-m-t", strtotime($now_date));

        $nextMonth = date("Y-m", strtotime($now_date . '+ 1 month'));

        $sql = "SELECT 
                (SELECT book_name FROM bookm WHERE book_idx = c.book_idx) lesson_book_name
                , COUNT(bs.book_idx) lesson_book_cnt
                , (SELECT COUNT(0) FROM book_rentT WHERE book_idx = C.book_idx AND franchise_idx = '" . $franchise_idx . "' AND return_date = '' AND ex_return_date <= '" . $nextMonth . "-01') book_rent_cnt
                , (SELECT TOP(1) COUNT(T.schedule_idx) FROM lessonT T 
                    LEFT OUTER JOIN lessonM M ON M.schedule_idx = T.schedule_idx 
                    WHERE M.franchise_idx = '" . $franchise_idx . "' AND M.lesson_date BETWEEN '" . $now_date . "-01' AND '" . $endMonth . "' AND M.lesson_grade = '" . $grade . "'
                    GROUP BY T.schedule_idx
                    ORDER BY COUNT(T.schedule_idx) DESC) lesson_student_cnt
                FROM curriculumM c
                LEFT OUTER JOIN book_statusT bs ON c.book_idx = bs.book_idx AND bs.franchise_idx = '" . $franchise_idx . "'
                WHERE c.franchise_idx = '" . $franchise_idx . "' AND c.months = '" . $standardMonth . "' AND c.grade = '" . $grade . "'
                GROUP BY c.book_idx, c.orders
                ORDER BY c.orders";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function loadLessonScheduleList($franchise_idx, $grade, $now_date)
    {
        $endMonth = date("Y-m-t", strtotime($now_date));

        $sql = "SELECT LM.lesson_date, CONCAT(LM.lesson_fromtime, ' ~ ', LM.lesson_totime) lesson_time, M.user_name teacher_name, isnull(B.book_name,'') book_name
                , LM.freehand_yn, LM.onoff_yn, ISNULL(C.code_name,'') lesson_grade
                , (SELECT COUNT(0) FROM lessonT T WHERE T.schedule_idx = LM.schedule_idx) student_cnt
                FROM lessonM LM 
                LEFT OUTER JOIN bookM B ON B.book_idx = LM.lesson_book_idx
                LEFT OUTER JOIN member_centerM M ON M.user_no = LM.teacher_idx
                LEFT OUTER JOIN codeM C ON C.code_num2 = LM.lesson_grade AND C.code_num1 = '02' AND C.code_num2 <> ''
                WHERE LM.lesson_date BETWEEN '" . $now_date . "-01' AND '" . $endMonth . "'
                AND LM.franchise_idx = '" . $franchise_idx . "'  AND LM.lesson_grade = '" . $grade . "'
                ORDER BY LM.teacher_idx, DATEPART(WEEKDAY,LM.lesson_date), LM.lesson_date, LM.lesson_fromtime";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function getCurriculumBook($franchise_idx, $grade, $now_date)
    {
        $nowMonth = date('m', strtotime($now_date));

        $sql = "SELECT book_idx FROM curriculumM WHERE franchise_idx = '" . $franchise_idx . "' AND grade = '" . $grade . "' AND months = '" . $nowMonth . "' ORDER BY orders";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function getScheduleList($franchise_idx, $grade, $now_date)
    {
        $endMonth = date("Y-m-t", strtotime($now_date));

        $sql = "SELECT STRING_AGG(schedule_idx, ',') WITHIN GROUP(ORDER BY schedule_idx) lesson_schedule, teacher_idx, DATEPART(WEEKDAY,lesson_date) day_val, lesson_room, lesson_fromtime
                FROM lessonM 
                WHERE franchise_idx = '" . $franchise_idx . "' AND lesson_grade = '" . $grade . "' 
                AND lesson_date BETWEEN '" . $now_date . "-01' AND '" . $endMonth . "'
                AND supple_yn = '' AND freehand_yn = '' AND onoff_yn = '' 
                GROUP BY teacher_idx, DATEPART(WEEKDAY,lesson_date), lesson_room, lesson_fromtime
                ORDER BY DATEPART(WEEKDAY,lesson_date)";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}

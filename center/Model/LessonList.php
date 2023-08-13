<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class LessonListInfo extends Model
{
    var $attend_table_name = "lesson_attendT";
    var $score_table_name = "lesson_scoreT";
    var $lessonM_table_name = 'lessonM';
    var $lessonT_table_name = 'lessonT';

    function __construct()
    {
        parent::__construct();
    }

    public function loadLessonList($franchise_idx, $teacher_no, $now_date)
    {
        $sql = "SELECT LM.schedule_idx, C.code_name lesson_type, LM.lesson_date
                , CONCAT(LM.lesson_fromtime, ' ~ ', LM.lesson_totime) lesson_time
                , T.user_name teacher_name, LM.lesson_room, LM.supple_type
                , (SELECT COUNT(0) FROM {$this->lessonT_table_name} LT WHERE LT.schedule_idx = LM.schedule_idx) student_count
                , ISNULL(B.book_name,'') lesson_book_name
                FROM {$this->lessonM_table_name} LM
                LEFT OUTER JOIN codeM C ON C.code_num2 = LM.lesson_type AND C.code_num1 = '04'
                LEFT OUTER JOIN member_centerM T ON T.user_no = LM.teacher_idx
                LEFT OUTER JOIN bookM B ON B.book_idx = LM.lesson_book_idx
                WHERE LM.franchise_idx = '" . $franchise_idx . "' AND LM.teacher_idx = '" . $teacher_no . "'
                AND LM.lesson_date BETWEEN '" . $now_date . "' AND '" . date("Y-m-t", strtotime($now_date)) . "'
                ORDER BY LM.lesson_date, LM.lesson_fromtime";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function loadLessonStudentList($franchise_idx, $schedule_idx)
    {
        $sql = "SELECT L.class_idx, M.user_no student_idx, M.user_name student_name, M.school_name, (YEAR(getdate()) - LEFT(M.birth, 4) + 1) age, ISNULL(A.attend_yn,'') attend_yn
                , (SELECT COUNT(0) FROM lesson_scoreT S WHERE S.franchise_idx = '2' AND S.schedule_idx = L.schedule_idx AND S.student_idx = L.student_idx) score_chk
                FROM {$this->lessonT_table_name} L
                LEFT OUTER JOIN member_studentM M ON M.user_no = L.student_idx
                LEFT OUTER JOIN lesson_attendT A ON A.schedule_idx = '" . $schedule_idx . "' AND A.student_idx = L.student_idx
                WHERE L.schedule_idx = '" . $schedule_idx . "' AND L.franchise_idx = '" . $franchise_idx . "'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function loadActivityPaper($franchise_idx, $schedule_idx)
    {
        $sql = "SELECT ISNULL(AC.activity_student1,'') activity_student1
                , ISNULL(AC.activity_student2,'') activity_student2
                , ISNULL(AC.activity_teacher1,'') activity_teacher1
                , ISNULL(AC.activity_teacher2,'') activity_teacher2
                FROM lessonM LM
                LEFT OUTER JOIN activitypaperT AC ON AC.book_idx = LM.lesson_book_idx
                WHERE LM.schedule_idx = '" . $schedule_idx . "' AND LM.franchise_idx = '" . $franchise_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }

    public function attendListCheck($schedule_idx, $student_idx)
    {
        $sql = "SELECT COUNT(0) FROM {$this->attend_table_name} WHERE schedule_idx = '" . $schedule_idx . "' AND student_idx = '" . $student_idx . "'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function lessonStudentList($schedule_idx)
    {
        $sql = "SELECT student_idx FROM lessonT WHERE schedule_idx = '" . $schedule_idx . "'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function loadLessonScore($schedule_idx, $franchise_idx, $student_idx)
    {
        $sql = "SELECT score_read, score_debate1, score_debate2, score_debate3, score_debate4
                , score_write1, score_write2, score_write3, score_write4, score_lead, score_memo
                FROM lesson_scoreT WHERE schedule_idx = '" . $schedule_idx . "' AND franchise_idx = '" . $franchise_idx . "' AND student_idx = '" . $student_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }

    public function getLessonStudentData($center_idx, $student_idx, $standardMonth)
    {
        $sql = "SELECT LM.lesson_date, C.code_name lesson_type, LM.onoff_yn, LM.supple_yn, LM.supple_type, LM.freehand_yn
                , ISNULL(LST.score_read,'') score_read
                , ISNULL((LST.score_debate1 + LST.score_debate2 + LST.score_debate3 + LST.score_debate4) / 4, '') score_debate 
                , ISNULL((LST.score_write1 + LST.score_write2 + LST.score_write3 + LST.score_write4) / 4, '') score_write
                , ISNULL(LST.score_lead,'') score_lead, ISNULL(LAT.attend_yn,'') attend_yn
                , ISNULL(LST.score_memo,'') score_memo
                FROM lessonT LT
                LEFT OUTER JOIN lesson_attendT LAT ON LAT.schedule_idx = LT.schedule_idx AND LAT.student_idx = '" . $student_idx . "'
                LEFT OUTER JOIN lesson_scoreT LST ON LST.schedule_idx = LT.schedule_idx AND LST.student_idx = '" . $student_idx . "'
                LEFT OUTER JOIN lessonM LM ON LM.schedule_idx = LT.schedule_idx
                LEFT OUTER JOIN codeM C ON C.code_num2 = LM.lesson_type AND C.code_num1 = '04' AND C.code_num2 <> ''
                WHERE LT.student_idx = '" . $student_idx . "' AND LM.franchise_idx = '" . $center_idx . "' 
                AND LM.lesson_date BETWEEN '".$standardMonth."-01' AND '".date("Y-m-t", strtotime($standardMonth))."'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}

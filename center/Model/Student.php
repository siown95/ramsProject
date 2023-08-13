<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class StudentInfo extends Model
{
    var $table_name = 'member_studentm';

    function __construct()
    {
        parent::__construct();
    }

    public function loadStudent($franchise_idx, $state)
    {
        $sql = "SELECT S.user_no, S.user_name, S.user_phone, ISNULL(C.user_name, '') teacher_name, CC.color_code, (YEAR(getdate()) - LEFT(S.birth, 4) + 1) age
                FROM {$this->table_name} S
                LEFT OUTER JOIN member_centerm C
                ON S.teacher_no = C.user_no
                LEFT OUTER JOIN color_codet CC
                ON S.color_tag = CC.color_idx
                WHERE S.franchise_idx = '" . $franchise_idx . "' 
                AND S.state = '" . $state . "'
                ORDER BY S.birth DESC, S.user_name ASC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function loadStudentDetail($franchise_idx, $state, $grade, $teacher_no)
    {
        $where_query = '';
        $where_arr = array(
            "S.franchise_idx = '" . $franchise_idx . "'",
            "S.state = '" . $state . "'"
        );

        if (!empty($grade)) {
            if ($grade == '6') {
                $where_arr[] .= ' (YEAR(getdate()) - LEFT(S.birth, 4) + 1) <= 6 ';
            } else {
                $where_arr[] .= " (YEAR(getdate()) - LEFT(S.birth, 4) + 1) = '" . $grade . "' ";
            }
        }

        if (!empty($teacher_no)) {
            $where_arr[] .= " S.teacher_no = '" . $teacher_no . "' ";
        }

        $where_query = "WHERE " . implode(" AND ", $where_arr);

        $sql = "SELECT S.user_no, S.user_name, ISNULL(C.user_name, '') teacher_name, CC.color_code, (YEAR(getdate()) - LEFT(S.birth, 4) + 1) age
                FROM {$this->table_name} S
                LEFT OUTER JOIN member_centerm C
                ON S.teacher_no = C.user_no
                LEFT OUTER JOIN color_codet CC
                ON S.color_tag = CC.color_idx " . $where_query . " ORDER BY S.birth DESC, S.user_name ASC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function selectStudent($franchise_idx, $user_no)
    {
        $sql = "SELECT S.user_no, S.user_name, S.gender, S.birth, S.user_phone, S.email, convert(varchar(19), S.reg_date, 120) reg_date , S.state, S.school_name, 
                       S.user_id, ISNULL(C.user_no, '') teacher_no, S.color_tag, S.address, S.zipcode, S.user_memo, CC.color_code, CC.color_detail
                       , ISNULL((SELECT TOP(1) lesson_date FROM lessonM LM1 LEFT OUTER JOIN lessonT LT1 ON LM1.schedule_idx = LT1.schedule_idx
                       WHERE LM1.franchise_idx = S.franchise_idx AND LT1.student_idx = S.user_no) , '') first_lesson_date
                       , ISNULL((SELECT top(1)
                       C1.code_name + ' ' + DATENAME(WEEKDAY,LM.lesson_date) + ' ' + LM.lesson_fromtime + ' / ' + MC.user_name FROM LessonM LM
                       LEFT OUTER JOIN lessonT LT ON LM.schedule_idx = LT.schedule_idx
                       LEFT OUTER JOIN member_centerM MC ON LM.teacher_idx = MC.user_no
                       LEFT OUTER JOIN codeM C1 ON LM.lesson_type = C1.code_num2 AND C1.code_num1 = '04'
                       WHERE LM.franchise_idx = S.franchise_idx AND LM.lesson_date >= '" . date('Y-m-d') . "' AND LT.student_idx = S.user_no), '') lesson_info
                FROM {$this->table_name} S
                LEFT OUTER JOIN member_centerm C
                ON S.teacher_no = C.user_no
                LEFT OUTER JOIN color_codet CC
                ON S.color_tag = CC.color_idx
                WHERE S.franchise_idx = '" . $franchise_idx . "' 
                AND S.user_no = '" . $user_no . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }

    public function getStudentData($franchise_idx)
    {
        $year = date("Y");
        $last_year = date('Y', strtotime("-1 year"));

        $sql = "exec sp_student_statistic_franchise '" . $last_year . "-01-01 00:00:00','" . $year . "-12-31 23:59:59','" . $franchise_idx . "'";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function msgInfoSelect($franchise_idx, $teacher_no, $grade, $state, $months)
    {
        $where_arr = array(
            "franchise_idx = '" . $franchise_idx . "'",
            "state = '" . $state . "'"
        );

        if (!empty($grade)) {
            if ($grade == '6') {
                $where_arr[] .= ' (YEAR(getdate()) - LEFT(birth, 4) + 1) <= 6 ';
            } else {
                $where_arr[] .= " (YEAR(getdate()) - LEFT(birth, 4) + 1) = '" . $grade . "' ";
            }
        }

        if (!empty($months)) {
            $where_arr[] .= " mod_date BETWEEN '". date('Y-m-d', strtotime($months . '-01')) ."' AND '". date('Y-m-t', strtotime($months)) ."' ";
        }

        if (!empty($teacher_no)) {
            $where_arr[] .= " teacher_no = '" . $teacher_no . "' ";
        }

        $where_query = "WHERE " . implode(" AND ", $where_arr);

        $sql = "SELECT user_no, user_name, user_phone FROM {$this->table_name} " . $where_query;
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }
}

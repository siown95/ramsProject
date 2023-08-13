<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class StudentEvaluationInfo extends Model
{
    var $table_name = 'lesson_evaluationt';

    function __construct()
    {
        parent::__construct();
    }

    public function studentSearch($franchise_idx, $student_name)
    {
        $sql = "SELECT user_no, user_name, school_name, (YEAR(getdate()) - LEFT(birth, 4) + 1) user_age FROM member_studentm 
                WHERE state = '00' AND  franchise_idx = '" . $franchise_idx . "' AND user_name LIKE ('%" . $student_name . "%')";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function evaluationLoad($franchise_idx, $EvalYear, $SemiAnnual, $grade, $student_no)
    {
        $where_qry = '';

        $where_arr = array(
            " E.franchise_idx = '" . $franchise_idx . "' ",
            " E.eval_year = '" . $EvalYear . "' ",
            " E.eval_semiannual = '" . $SemiAnnual . "' ",
        );

        if (!empty($grade) && $grade != 'all') {
            if ($grade == '6') {
                $where_arr[] .= " (YEAR(getdate()) - LEFT(S.birth, 4) + 1) <= 6 ";
            } else {
                $where_arr[] .= " (YEAR(getdate()) - LEFT(S.birth, 4) + 1) = '" . $grade . "' ";
            }
        }

        if (!empty($student_no) && $student_no != 'all') {
            $where_arr[] .= " E.student_no = '" . $student_no . "'";
        }

        $where_qry = implode(" AND ", $where_arr);

        $sql = "SELECT 
                E.eval_idx
                , (YEAR(getdate()) - LEFT(S.birth, 4) + 1) grade
                , S.user_name student_name
                , C.user_name writer_name
                , convert(varchar(19), E.reg_date, 120) reg_date
                FROM {$this->table_name} E 
                LEFT OUTER JOIN member_studentm S 
                ON E.student_no = S.user_no 
                LEFT OUTER JOIN member_centerm C 
                ON E.writer_no = C.user_no
                WHERE " . $where_qry . " ORDER BY E.reg_date DESC";
                // echo $sql;
                // exit;
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function selectEvaluation($eval_idx)
    {
        $sql = "SELECT E.eval_content, E.read_score, E.read_content, E.debate_score, E.debate_content, E.write_score, E.write_content, E.student_no
                , E.lead_score, E.lead_content, E.guide_content, E.next_guide_content, E.parent_request, (YEAR(getdate()) - LEFT(S.birth, 4) + 1) grade, S.user_name, S.school_name
                FROM {$this->table_name} E 
                LEFT OUTER JOIN member_studentm S 
                ON E.student_no = S.user_no 
                WHERE eval_idx = '" . $eval_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }

    public function getStudentList($franchise_idx, $grade)
    {
        $where_qry = '';
        if ($grade == 'all') {
            $where_qry = '';
        } else if ($grade == '6') {
            $where_qry = ' AND (YEAR(getdate()) - LEFT(birth, 4) + 1) <= 6';
        } else {
            $where_qry = " AND (YEAR(getdate()) - LEFT(birth, 4) + 1) = '" . $grade . "'";
        }

        $sql = "SELECT user_no, user_name FROM member_studentm WHERE franchise_idx = '" . $franchise_idx . "' AND state = '00' " . $where_qry;
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function getStudentScore($franchise_idx, $student_idx, $evalyear, $semiannual)
    {
        $date_arr = '';
        if ($semiannual == '1') {
            $date_arr = "'" . $evalyear . "-01-01' AND " . "'"  . $evalyear . "-06-30'";
        } else if ($semiannual == '2') {
            $date_arr = "'" . $evalyear . "-07-01' AND " . "'"  . $evalyear . "-12-31'";
        }

        $sql = "SELECT
        ISNULL(AVG(LS.score_read), '0') score_read,
        ISNULL(AVG(LS.score_debate1 + LS.score_debate2 + LS.score_debate3 + LS.score_debate4) / 4, '0') score_debate,
        ISNULL(AVG(LS.score_write1 + LS.score_write2 + LS.score_write3 + LS.score_write4) / 4, '0') score_write,
        ISNULL(AVG(LS.score_lead), '0') score_lead
        FROM lesson_scoreT LS
        LEFT OUTER JOIN lessonM LM ON LS.schedule_idx = LM.schedule_idx
        WHERE LS.franchise_idx = '" . $franchise_idx . "' AND LS.student_idx = '" . $student_idx . "' AND lm.lesson_date BETWEEN " . $date_arr;
        $result = $this->db->sqlRow($sql);

        return $result;
    }
}

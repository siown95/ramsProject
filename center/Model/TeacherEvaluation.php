<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class TeacherEvaluationInfo extends Model
{
    var $table_name = 'teacher_evaluationt';

    function __construct()
    {
        parent::__construct();
    }

    public function checkEvaluation($franchise_idx, $user_no)
    {
        $sql = "SELECT COUNT(0) FROM {$this->table_name}
                WHERE franchise_idx = '" . $franchise_idx . "' AND user_no = '" . $user_no . "' 
                AND reg_date BETWEEN '" . date("Y-m") . "-01 00:00:00' AND '" . date("Y-m-t") . " 23:59:59'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function teacherEvalLoad($franchise_idx, $user_no, $months)
    {
        $where_qry = "";
        if (!empty($user_no)) {
            $where_qry = " AND E.user_no = '" . $user_no . "' ";
        }

        $sql = "SELECT 
                E.teval_idx,
                SUBSTRING(E.teval_score1, 2, 1) teval_score1,
                SUBSTRING(E.teval_score2, 2, 1) teval_score2,
                SUBSTRING(E.teval_score3, 2, 1) teval_score3,
                SUBSTRING(E.teval_score4, 2, 1) teval_score4,
                SUBSTRING(E.teval_score5, 2, 1) teval_score5,
                SUBSTRING(E.teval_score6, 2, 1) teval_score6,
                SUBSTRING(E.teval_score7, 2, 1) teval_score7,
                SUBSTRING(E.teval_score8, 2, 1) teval_score8,
                SUBSTRING(E.teval_score9, 2, 1) teval_score9,
                SUBSTRING(E.teval_score10, 2, 1) teval_score10,
                M.user_name
                FROM {$this->table_name} E
                LEFT OUTER JOIN
                member_centerm M 
                ON E.user_no = M.user_no
                WHERE E.franchise_idx = '" . $franchise_idx . "'
                AND E.reg_date BETWEEN '" . date("Y-m", strtotime($months)) . "-01 00:00:00' AND '" . date("Y-m-t", strtotime($months)) . " 23:59:59'
                " . $where_qry . "
                ORDER BY E.reg_date DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function teacherEvalSelect($teval_idx)
    {
        $sql = "SELECT
                teval_idx,
                teval_sub1,
                teval_score1,
                teval_sub2,
                teval_score2,
                teval_sub3,
                teval_score3,
                teval_sub4,
                teval_score4,
                teval_sub5,
                teval_score5,
                teval_sub6,
                teval_score6,
                teval_sub7,
                teval_score7,
                teval_sub8,
                teval_score8,
                teval_sub9,
                teval_score9,
                teval_sub10,
                teval_score10
                FROM {$this->table_name} WHERE teval_idx = '" . $teval_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }
}
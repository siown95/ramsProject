<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Model.php";

class CounselInfo extends Model
{
    var $table_name = "counselt";

    function __construct()
    {
        parent::__construct();
    }

    public function counselLoad($student_no)
    {
        $sql = "SELECT C.counsel_idx, M.user_name teacher_name, C.counsel_kind, C.counsel_date
                FROM {$this->table_name} C
                LEFT OUTER JOIN member_centerm M
                ON C.writer_no = M.user_no
                WHERE C.student_no = '" . $student_no . "' AND C.counsel_open = 'Y'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function counselSelect($counsel_idx)
    {
        $sql = "SELECT T.user_name teacher_name, C.counsel_kind, C.counsel_date, C.counsel_contents, C.counsel_followup, C.counsel_request
                FROM {$this->table_name} C
                LEFT OUTER JOIN member_centerm T
                ON C.writer_no = T.user_no 
                WHERE C.counsel_idx = '" . $counsel_idx . "'";

        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function loadRecentCounselMain($franchise_idx, $student_no)
    {
        $sql = "SELECT TOP(3) C.counsel_idx, M.user_name teacher_name, C.counsel_kind, C.counsel_date
        FROM {$this->table_name} C
        LEFT OUTER JOIN member_centerm M
        ON C.writer_no = M.user_no
        WHERE C.franchise_idx = '" . $franchise_idx . "' AND C.student_no = '" . $student_no . "' AND C.counsel_open = 'Y'
        ORDER BY C.reg_date DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}

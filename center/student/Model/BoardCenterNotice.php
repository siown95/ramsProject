<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Model.php";

class BoardCenterNoticeInfo extends Model
{
    var $table_name = "board_center_noticet";

    function __construct()
    {
        parent::__construct();
    }

    public function loadCenterNoticedMain($franchise_idx, $student_no)
    {
        $sql = "SELECT TOP (3) notice_idx, notice_title, convert(varchar(10), reg_date, 120) reg_date 
                FROM {$this->table_name} 
                WHERE franchise_idx = '" . $franchise_idx . "' AND notice_target = 'a'
                UNION
                SELECT notice_idx, notice_title, convert(varchar(19), reg_date, 120) reg_date 
                FROM {$this->table_name} 
                WHERE franchise_idx = '" . $franchise_idx . "' AND notice_target = 's' AND notice_target_no = '" . $student_no . "'
                ORDER BY reg_date DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function centerNoticeLoad($franchise_idx, $student_no)
    {
        $sql = "SELECT notice_idx, notice_title, convert(varchar(19), reg_date, 120) reg_date 
                FROM {$this->table_name} 
                WHERE franchise_idx = '" . $franchise_idx . "' AND notice_target = 'a'
                UNION
                SELECT notice_idx, notice_title, convert(varchar(19), reg_date, 120) reg_date
                FROM {$this->table_name} 
                WHERE franchise_idx = '" . $franchise_idx . "' AND notice_target = 's' AND notice_target_no = '" . $student_no . "'
                ORDER BY reg_date DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function centerNoticeSelect($notice_idx)
    {
        $sql = "SELECT notice_title, notice_contents FROM {$this->table_name} WHERE notice_idx = '" . $notice_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }
}

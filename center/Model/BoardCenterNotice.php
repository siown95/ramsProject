<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class BoardCenterNoticeInfo extends Model
{
    var $table_name = "board_center_noticet";

    function __construct()
    {
        parent::__construct();
    }

    public function centerNoticeLoad($franchise_idx, $teacher_no, $is_admin)
    {

        if ($is_admin == 'Y') {
            $sql = "SELECT notice_idx, notice_title, convert(varchar(19), reg_date, 120) reg_date, notice_target
                    FROM {$this->table_name} 
                    WHERE franchise_idx = '" . $franchise_idx . "'
                    ORDER BY reg_date DESC";
        } else {
            $sql = "SELECT notice_idx, notice_title, convert(varchar(19), reg_date, 120) reg_date, notice_target 
                    FROM {$this->table_name} 
                    WHERE franchise_idx = '" . $franchise_idx . "' AND notice_target  = 'a'
                    UNION
                    SELECT notice_idx, notice_title, convert(varchar(19), reg_date, 120) reg_date, notice_target
                    FROM {$this->table_name} 
                    WHERE franchise_idx = '" . $franchise_idx . "' AND notice_target  = 't' AND notice_target_no = '" . $teacher_no . "'
                    UNION
                    SELECT notice_idx, notice_title, convert(varchar(19), reg_date, 120) reg_date, notice_target
                    FROM {$this->table_name} 
                    WHERE franchise_idx = '" . $franchise_idx . "' AND notice_target  = 's'
                    ORDER BY reg_date DESC";
        }

        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function centerNoticeSelect($notice_idx, $notice_target)
    {
        if ($notice_target == 'a') {
            $sql = "SELECT notice_title, notice_contents FROM {$this->table_name} WHERE notice_idx = '" . $notice_idx . "'";
        } else if ($notice_target == 't') {
            $sql = "SELECT N.notice_title, N.notice_contents, M.user_name target_name
                    FROM {$this->table_name} N
                    LEFT OUTER JOIN member_centerm M
                    ON M.user_no = N.notice_target_no
                    WHERE N.notice_idx = '" . $notice_idx . "'";
        } else {
            $sql = "SELECT N.notice_title, N.notice_contents, M.user_name target_name
                    FROM {$this->table_name} N
                    LEFT OUTER JOIN member_studentm M
                    ON M.user_no = N.notice_target_no
                    WHERE N.notice_idx = '" . $notice_idx . "'";
        }
        
        $result = $this->db->sqlRow($sql);

        return $result;
    }
}

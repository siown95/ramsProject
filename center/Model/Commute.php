<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class CommuteInfo extends Model
{
    var $table_name = 'commute_logt';

    function __construct()
    {
        parent::__construct();
    }

    public function selectEmployee($franchise_idx, $user_no = null)
    {
        $where = '';
        if (!empty($user_no)) {
            $where = "AND m.user_no = '" . $user_no . "'";
        }
        $sql = "SELECT m.user_no, m.user_name, c.code_name, m.position FROM member_centerm m
                LEFT JOIN codem c
                  ON m.position = c.code_num2 AND c.code_num1 = '20' AND c.code_num2 <> ''
                WHERE m.show_yn = 'Y'
                AND m.state = '00'
                AND m.franchise_idx = '" . $franchise_idx . "'
                AND m.user_id <> 'admin'
                " . $where . "
                ORDER BY m.position ASC";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function checkTodayLog($user_no, $franchise_idx, $state)
    {
        $date = date("Y-m-d");

        $sql = "SELECT COUNT(0) FROM {$this->table_name}
                WHERE user_no = '" . $user_no . "'
                      AND franchise_idx = '" . $franchise_idx . "'
                      AND state = '" . $state . "' 
                      AND reg_date BETWEEN '" . $date . " 00:00:00' AND '" . $date . " 23:59:59'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function commuteSelect($user_no, $franchise_idx, $now_date)
    {
        $sql = "SELECT CONVERT(varchar(19), CL.reg_date, 120) reg_date, convert(varchar(10), CL.reg_date, 120) date, EC.paid_holiday, EC.unpaid_holiday 
                FROM {$this->table_name} CL
                LEFT OUTER JOIN employee_commutem EC
                ON CL.user_no = EC.user_no
                WHERE CL.user_no = '" . $user_no . "' AND CL.franchise_idx = '" . $franchise_idx . "' AND EC.franchise_idx = '" . $franchise_idx . "'
                AND CL.reg_date BETWEEN '" . $now_date . "-01' AND '" . date("Y-m-t", strtotime($now_date)) . "'
                ORDER BY CL.reg_date";

        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}

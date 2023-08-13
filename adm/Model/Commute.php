<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class CommuteInfo extends Model
{
    var $table_name = 'commute_logt';

    function __construct()
    {
        parent::__construct();
    }

    public function selectEmployee($user_no = null)
    {
        $where = "";
        if (!empty($user_no)) {
            $where = "AND e.user_no = '" . $user_no . "'";
        }
        $sql = "SELECT e.user_no, e.user_name, c.code_name, e.position FROM member_employeem e
                LEFT OUTER JOIN codem c
                ON e.position = c.code_num2
                WHERE e.show_yn = 'Y'
                AND c.code_num1 = '08'
                AND e.state = '00'
                AND e.user_id <> 'admin'
                " . $where . "
                ORDER BY e.position ASC";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function checkTodayLog($user_no, $state)
    {
        $date = date("Y-m-d");

        $sql = "SELECT TOP(1) count(0) FROM {$this->table_name}
                WHERE user_no = '" . $user_no . "'
                AND state = '" . $state . "' 
                AND franchise_idx = '1'
                AND reg_date between '" . $date . " 00:00:00' AND '" . $date . " 23:59:59'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function commuteSelect($user_no, $now_date)
    {
        $sql = "SELECT CONVERT(varchar(19), CL.reg_date, 120) reg_date, convert(varchar(10), CL.reg_date, 120) date, EC.paid_holiday, EC.unpaid_holiday 
                FROM commute_logt CL
                LEFT OUTER JOIN employee_commutem EC
                ON CL.user_no = EC.user_no
                WHERE CL.user_no = '" . $user_no . "' AND CL.franchise_idx = '1' AND EC.franchise_idx = '1'
                AND CL.reg_date BETWEEN '" . $now_date . "-01' AND '" . date("Y-m-t", strtotime($now_date)) . "'
                ORDER BY CL.reg_date";

        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}

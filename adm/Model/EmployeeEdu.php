<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class EmployeeEduInfo extends Model
{
    var $table_name = 'employee_edum';
    var $schedule_table = 'employee_eduschedulet';
    var $edu_table = 'employee_edut';

    function __construct()
    {
        parent::__construct();
    }

    public function eduInfoSelect($edu_idx)
    {
        $sql = "SELECT edu_idx, edu_name, edu_type, edu_target, edu_way FROM employee_edum WHERE edu_idx = '" . $edu_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }

    public function eduCenterSelect($franchise_idx)
    {
        if ($franchise_idx == '1') {
            $member_table = "member_employeem";
        } else {
            $member_table = "member_centerm";
        }
        $sql = "SELECT user_no, user_name FROM " . $member_table . " WHERE franchise_idx = '" . $franchise_idx . "' AND user_id <> 'admin' AND state = '00'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function eduScheduleInsert($params)
    {
        $franchise_idx = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';
        $edu_idx = !empty($params['edu_idx']) ? $params['edu_idx'] : '';
        $from_time = !empty($params['from_time']) ? $params['from_time'] : '';
        $to_time = !empty($params['to_time']) ? $params['to_time'] : '';
        $user_list = !empty($params['user_list']) ? $params['user_list'] : '';

        $key_arr = array("edu_idx", "franchise_idx", "user_list", "edu_from_time", "edu_to_time");
        $val_arr = array("'" . $edu_idx . "'", "'" . $franchise_idx . "'", "'" . $user_list . "'", "'" . $from_time . "'", "'" . $to_time . "'");

        $key_str = implode(",", $key_arr);
        $val_str = implode(",", $val_arr);

        $sql = "INSERT INTO {$this->schedule_table} (" . $key_str . ") VALUES (" . $val_str . ")";

        $result = $this->db->execute($sql);

        return $result;
    }

    public function eduScheduleDelete($eduschedule_idx)
    {
        $sql = "DELETE FROM {$this->edu_table} WHERE eduschedule_idx = '" . $eduschedule_idx . "'; ";
        $sql .= "DELETE FROM {$this->schedule_table} WHERE eduschedule_idx = '" . $eduschedule_idx . "'";

        $result = $this->db->multiExecute($sql);
        return $result;
    }

    public function eduEmployeeInsert()
    {
        $eduschedule_sql = "SELECT eduschedule_idx, edu_idx, franchise_idx, user_list FROM {$this->schedule_table} WHERE edu_flag = ''";
        $eduschedule_info = $this->db->sqlRowArr($eduschedule_sql);

        foreach ($eduschedule_info as $key => $val) {
            $user_list_arr = explode(",", $eduschedule_info[$key]['user_list']);

            foreach ($user_list_arr as $val) {
                $sql = "INSERT INTO {$this->edu_table} (eduschedule_idx, franchise_idx, user_no)
                        VALUES ('" . $eduschedule_info[$key]['eduschedule_idx'] . "', '" . $eduschedule_info[$key]['franchise_idx'] . "', '" . $val . "')";
                $result = $this->db->execute($sql);
            }
            $eduscedule_update_sql = "UPDATE {$this->schedule_table} SET edu_flag = '1' WHERE eduschedule_idx = '" . $eduschedule_info[$key]['eduschedule_idx'] . "'";
            $result = $this->db->execute($eduscedule_update_sql);
        }

        return $result;
    }

    public function eduCenterScheduleSelect($franchise_idx)
    {
        $sql = "SELECT 
                s.eduschedule_idx
                , e.edu_name
                , case when e.edu_type = 1 then '법정의무교육' 
                       when e.edu_type = 2 then '사내직무교육' 
                       else '' end 'edu_type'
                , s.user_list
                , CONCAT(s.edu_from_time, ' ~ ', s.edu_to_time) 'edu_time'
                ,(SELECT count(0) FROM {$this->edu_table} WHERE eduschedule_idx = s.eduschedule_idx AND file_name <> '') cnt
                FROM {$this->schedule_table} s LEFT OUTER JOIN {$this->table_name} e 
                  ON s.edu_idx = e.edu_idx
                WHERE franchise_idx = '" . $franchise_idx . "'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function eduScheduleSelect($eduschedule_idx, $franchise_idx)
    {
        if($franchise_idx == '1'){
            $member_table = 'member_employeem';
        }else{
            $member_table = 'member_centerm';
        }

        $sql = "SELECT e.employee_edu_idx, e.eduschedule_idx, e.franchise_idx, e.user_no, m.user_name, e.file_name, e.edu_complete_date
                FROM {$this->edu_table} e 
                LEFT OUTER JOIN {$member_table} m 
                ON m.user_no = e.user_no 
                WHERE e.eduschedule_idx = '" . $eduschedule_idx . "'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function eduScheduleSelectOne($eduschedule_idx)
    {
        $sql = "SELECT edu_idx, franchise_idx, user_list
                , convert(varchar(19), edu_from_time, 120) edu_from_time
                , convert(varchar(19), edu_to_time, 120) edu_to_time 
                FROM {$this->schedule_table}
                WHERE eduschedule_idx = '" . $eduschedule_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }

    public function certificatesUpload($params)
    {
        $now_date = date("Y-m-d");
        $file_name = !empty($params['file_name']) ? $params['file_name'] : '';
        $employee_edu_idx = !empty($params['employee_edu_idx']) ? $params['employee_edu_idx'] : '';
        $eduschedule_idx = !empty($params['eduschedule_idx']) ? $params['eduschedule_idx'] : '';
        $franchise_idx = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';
        $user_no = !empty($params['user_no']) ? $params['user_no'] : '';

        $sql = "UPDATE {$this->edu_table} SET file_name = '" . addslashes($file_name) . "', edu_complete_date = '" . $now_date . "' 
                WHERE employee_edu_idx = '" . $employee_edu_idx . "' 
                AND eduschedule_idx = '" . $eduschedule_idx . "' 
                AND franchise_idx = '" . $franchise_idx . "' 
                AND user_no = '" . $user_no . "'";
        $result = $this->db->execute($sql);

        return $result;
    }
}

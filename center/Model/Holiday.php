<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class HolidayInfo extends Model
{
    var $table_name = "holidayT";

    function __construct()
    {
        parent::__construct();
    }

    public function holidayLoad($franchise_idx)
    {
        $sql = "SELECT holiday_idx, holiday_date, holiday_memo FROM {$this->table_name}
                WHERE franchise_idx = '" . $franchise_idx . "' AND useYn = 'Y' ORDER BY holiday_date";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function holidayCount($franchise_idx, $holiday_date)
    {
        $sql = "SELECT COUNT(0) FROM {$this->table_name}
                WHERE franchise_idx = '" . $franchise_idx . "' AND holiday_date = '" . $holiday_date . "' AND useYn = 'Y'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }
}

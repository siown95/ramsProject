<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class WeekInfo extends Model
{
    var $table_name = 'weekT';

    function __construct()
    {
        parent::__construct();
    }

    public function getWeekYearData()
    {
        $sql = "SELECT DISTINCT weekYear FROM {$this->table_name}";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function getWeekData($year)
    {
        $sql = "SELECT week_idx, weekYear, weekMonth, weekCount, weekStartDate, weekEndDate, weekDetail FROM {$this->table_name} WHERE weekYear = '{$year}'";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function selectWeekData($week_idx)
    {
        $sql = "SELECT week_idx, weekYear, weekMonth, weekCount, weekStartDate, weekEndDate, weekDetail FROM {$this->table_name} WHERE week_idx = '{$week_idx}'";
        $result = $this->db->sqlRow($sql);
        return $result;
    }
}

<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class ColorCodeInfo extends Model
{
    var $table_name = 'color_codet';

    function __construct()
    {
        parent::__construct();
    }

    public function colorCodeLoad($franchise_idx)
    {
        $sql = "SELECT color_idx, color_detail, color_code FROM {$this->table_name} WHERE franchise_idx = '" . $franchise_idx . "' ORDER BY color_idx DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function colorCodeSelect($color_idx)
    {
        $sql = "SELECT color_detail, color_code FROM {$this->table_name} WHERE color_idx = '" . $color_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }
}
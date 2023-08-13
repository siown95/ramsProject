<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class MsgGroupInfo extends Model
{
    var $table_name = "msg_groupT";
    var $master_table_name = "msg_groupM";

    function __construct()
    {
        parent::__construct();
    }

    public function msgGroupLoad($franchise_idx, $user_idx)
    {
        $sql = "SELECT group_idx, group_name FROM {$this->master_table_name} WHERE franchise_idx = '" . $franchise_idx . "' AND user_idx = '" . $user_idx . "'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function msgGroupSelect($group_idx)
    {
        $sql = "SELECT group_reg_idx, group_idx, group_user_name ,group_user_hp FROM {$this->table_name} WHERE group_idx = '" . $group_idx . "'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function msgInfoSelect($group_idx)
    {
        $sql = "SELECT group_user_name AS user_name, group_user_hp AS user_phone FROM {$this->table_name} WHERE group_idx = '" . $group_idx . "'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}

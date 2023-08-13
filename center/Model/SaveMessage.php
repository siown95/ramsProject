<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class SaveMessageInfo extends Model
{
    var $table_name = "save_msgT";

    function __construct()
    {
        parent::__construct();
    }

    public function saveMsgLoad($franchise_idx, $user_idx)
    {
        $sql = "SELECT msg_idx, msg_title FROM {$this->table_name} WHERE franchise_idx = '" . $franchise_idx . "' AND user_idx = '" . $user_idx . "'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function saveMsgSelect($msg_idx, $franchise_idx, $user_idx)
    {
        $sql = "SELECT msg_title, msg_contents FROM {$this->table_name} WHERE msg_idx = '" . $msg_idx . "' AND franchise_idx = '" . $franchise_idx . "' AND user_idx = '" . $user_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }
}

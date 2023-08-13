<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class FranchiseInfo extends Model
{
    var $table_name = 'franchisem';

    function __construct()
    {
        parent::__construct();
    }

    public function centerLoad()
    {
        $sql = "SELECT franchise_idx, center_name, owner_name, useyn FROM {$this->table_name}";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function centerSelect($franchise_idx)
    {
        $sql = "SELECT franchise_type, center_name, center_eng_name, owner_name, owner_id, useyn, address, zipcode, tel_num, fax_num, email, location
                , biz_reg_date, biz_no, class_no, report_date, franchisee_start, franchisee_end
                , rams_fee, sales_confirm, royalty, sms_fee, lms_fee, mms_fee, shop_id, shop_key
                FROM {$this->table_name} WHERE franchise_idx = '" . $franchise_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }

    public function updateOwner($owner_id)
    {
        $sql = "UPDATE member_centerm SET
                state = '00', is_admin = 'Y' WHERE user_id = '{$owner_id}'";

        $result = $this->db->execute($sql);
        return $result;
    }
}
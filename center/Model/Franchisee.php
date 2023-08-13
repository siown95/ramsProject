<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class FranchiseInfo extends Model
{
    var $table_name = 'franchisem';

    function __construct()
    {
        parent::__construct();
    }

    public function centerLoad()
    {
        $sql = "SELECT franchise_idx, center_name, owner_name, useyn FROM {$this->table_name} WHERE franchise_type <> '00'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function centerSelect($franchise_idx)
    {
        $sql = "SELECT franchise_type, center_name, center_eng_name, owner_name, owner_id, useyn, address, tel_num, fax_num, email, location
                , biz_reg_date, biz_no, class_no, report_date, franchisee_start, franchisee_end
                , rams_fee, sales_confirm, royalty, sms_fee, mms_fee, shop_id, shop_key
                FROM {$this->table_name} WHERE franchise_idx = '" . $franchise_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }

    public function getPointBalance($center_idx, $charge_amount)
    {
        $sql = "SELECT point FROM {$this->table_name} WHERE franchise_idx = '{$center_idx}' AND point >= '{$charge_amount}'";
        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function getPointChargeList($center_idx)
    {
        $sql = "SELECT TOP(30) P.order_num, PL.paymentKey, P.order_money, P.pay_date FROM invoice_pointM P
        LEFT OUTER JOIN payment_logT PL ON P.order_num = PL.orderId
        WHERE P.franchise_idx = '{$center_idx}'";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }
}

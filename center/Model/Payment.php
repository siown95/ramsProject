<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class PaymentInfo extends Model
{
    var $table_name = "payment_logT";

    function __construct()
    {
        parent::__construct();
    }

    public function getPaymentKey($order_num)
    {
        $sql = "SELECT TOP(1) paymentKey FROM {$this->table_name} WHERE orderId = '{$order_num}' ORDER BY payment_idx DESC";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }
}

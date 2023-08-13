<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Model.php";

class ReceiptInfo extends Model
{
    var $table_name = 'invoiceM';

    function __construct()
    {
        parent::__construct();
    }

    public function getReceiptData($franchise_idx, $student_no, $paymonth)
    {
        $sql = "SELECT IM.order_num, IM.order_ym, IM.order_state, C1.code_name AS order_state_nm, IM.order_method, ISNULL(C2.code_name, '') AS order_method_nm,
        SUM(IM.order_money) AS order_money, SUM(IM.refund_money) AS refund_money, IM.pay_date, ISNULL(PL.totalAmount, '0') AS pay_amount, ISNULL(PL.cancels_cancelAmount, '0') AS cancel_amount FROM {$this->table_name} IM
        LEFT OUTER JOIN codeM C1 ON IM.order_state = C1.code_num2 AND C1.code_num1 = '45' AND C1.code_num2 <> ''
        LEFT OUTER JOIN codeM C2 ON IM.order_method = C2.code_num2 AND C2.code_num1 = '41' AND C2.code_num2 <> ''
        LEFT OUTER JOIN payment_logT PL ON IM.order_num = PL.orderId
        WHERE IM.franchise_idx = '{$franchise_idx}' AND IM.student_idx = '{$student_no}' AND IM.order_ym = '{$paymonth}'
        GROUP BY IM.order_num, IM.order_ym, IM.order_state, C1.code_name, IM.order_method, C2.code_name, IM.pay_date, PL.totalAmount, PL.cancels_cancelAmount";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function getReceiptDataDetail($franchise_idx, $student_no, $order_num, $paymonth)
    {
        $sql = "SELECT IM.receipt_name, CASE WHEN R.receipt_amount > 0 THEN R.receipt_amount ELSE IM.order_money END AS unitamt
        , IM.order_quantity, IM.order_money FROM {$this->table_name} IM
        LEFT OUTER JOIN receiptT R ON IM.receipt_idx = R.receipt_item_idx
        WHERE IM.franchise_idx = '{$franchise_idx}' AND IM.student_idx = '{$student_no}' AND IM.order_ym = '{$paymonth}' AND IM.order_num = '{$order_num}'";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }
}

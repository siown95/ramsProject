<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class RefundInfo extends Model
{
    var $table_name = 'payment_logT';
    var $invoice_refund_table_name = 'invoice_refundT';
    var $franchise_fee_table_name = 'franchise_feeM';
    var $invoice_point_table_name = 'invoice_pointM';
    var $order_goods_table_name = 'order_goodsT';

    function __construct()
    {
        parent::__construct();
    }

    public function getStudentRefundData($refund_month)
    {
        $sql = "SELECT IR.order_num, F.center_name, MS.user_name, MS.user_phone, C1.code_name AS order_method, SUM(I.order_money) AS pay_amount,
        IR.refund_date, SUM(IR.refund_amount) AS refund_amount, FORMAT(IR.reg_date, 'yyyy-MM-dd HH:mm:ss') AS reg_date FROM {$this->invoice_refund_table_name} IR
        LEFT OUTER JOIN invoiceM I ON I.invoice_idx = IR.invoice_idx
        LEFT OUTER JOIN franchiseM F ON IR.franchise_idx = F.franchise_idx
        LEFT OUTER JOIN member_studentM MS ON IR.franchise_idx = MS.franchise_idx AND IR.student_idx = MS.user_no
        LEFT OUTER JOIN codeM C1 ON I.order_method = C1.code_num2 AND C1.code_num1 = '41'
        WHERE IR.refund_ym = '{$refund_month}'
        GROUP BY IR.order_num, F.center_name, MS.user_name, MS.user_phone, C1.code_name, IR.refund_date, FORMAT(IR.reg_date, 'yyyy-MM-dd HH:mm:ss')";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function getFranchiseFeeRefundData($refund_month)
    {
        $sql = "SELECT FF.order_num, F.center_name, MC.user_name, MC.user_phone, C1.code_name AS order_method, C2.code_name AS order_state,
        FF.franchise_fee_date, SUM(FF.franchise_fee_money) AS pay_amount,
        FF.refund_date, SUM(FF.refund_request_amount) AS refund_request_amount, SUM(FF.refund_money) AS refund_amount FROM {$this->franchise_fee_table_name} FF
        LEFT OUTER JOIN franchiseM F ON FF.franchise_idx = F.franchise_idx
        LEFT OUTER JOIN member_centerM MC ON FF.franchise_idx = MC.franchise_idx AND F.owner_id = MC.user_id
        LEFT OUTER JOIN codeM C1 ON FF.franchise_fee_state = C1.code_num2 AND C1.code_num1 = '41'
        LEFT OUTER JOIN codeM C2 ON FF.franchise_fee_state = C2.code_num2 AND C2.code_num1 = '42'
        WHERE FF.franchise_fee_ym = '{$refund_month}' AND FF.refund_request_reason <> ''
        GROUP BY FF.order_num, F.center_name, MC.user_name, MC.user_phone, C1.code_name, C2.code_name, FF.franchise_fee_date, FF.refund_date";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function getRequestRefundData1($order_num)
    {
        $sql = "SELECT IR.order_num, F.center_name, MS.user_name, MS.user_phone, SUM(I.order_money) AS pay_amount, SUM(IR.refund_amount) AS refund_amount, IR.refund_etc FROM {$this->invoice_refund_table_name} IR
        LEFT OUTER JOIN invoiceM I ON I.invoice_idx = IR.invoice_idx
        LEFT OUTER JOIN franchiseM F ON IR.franchise_idx = F.franchise_idx
        LEFT OUTER JOIN member_studentM MS ON IR.franchise_idx = MS.franchise_idx AND IR.student_idx = MS.user_no
        WHERE IR.order_num = '{$order_num}'
        GROUP BY IR.order_num, F.center_name, MS.user_name, MS.user_phone, IR.refund_etc";
        $result = $this->db->sqlRow($sql);
        return $result;
    }
    
    public function getRequestRefundData2($order_num)
    {
        $sql = "SELECT FF.order_num, F.center_name, MC.user_name, MC.user_phone,
        SUM(FF.franchise_fee_money) AS pay_amount, SUM(FF.refund_request_amount) AS refund_request_amount, SUM(FF.refund_money) AS refund_amount, FF.refund_request_reason FROM {$this->franchise_fee_table_name} FF
        LEFT OUTER JOIN franchiseM F ON FF.franchise_idx = F.franchise_idx
        LEFT OUTER JOIN member_centerM MC ON FF.franchise_idx = MC.franchise_idx AND F.owner_id = MC.user_id
        WHERE FF.order_num = '{$order_num}'
        GROUP BY FF.order_num, F.center_name, MC.user_name, MC.user_phone, FF.franchise_fee_date, FF.refund_date, FF.refund_request_reason";
        $result = $this->db->sqlRow($sql);
        return $result;
    }
}

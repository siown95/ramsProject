<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";
include $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class StudentRefundInfo extends Model
{
    var $table_name = 'invoiceM';
    var $student_table_name = 'member_studentm';

    function __construct()
    {
        parent::__construct();
    }

    public function loadFeeStudent($franchise_idx, $paymonth, $grade)
    {
        if (!empty($paymonth)) {
            $where_qry = "AND I.order_ym = '" . $paymonth . "' ";
        } else {
            $where_qry = "";
        }

        if (getAge($grade) == 6) {
            $where_qry .= "AND (YEAR(getdate()) - LEFT(S.birth, 4) + 1) <= '6'";
        } else if (getAge($grade) > 6 && getAge($grade) < 20) {
            $where_qry .= "AND (YEAR(getdate()) - LEFT(S.birth, 4) + 1) = '" . getAge($grade) . "'";
        } else {
            $where_qry .=  '';
        }

        $sql = "SELECT DISTINCT S.user_no, S.user_name, S.user_phone, ISNULL(C.user_name, '') teacher_name, ISNULL(CC.color_code, '') color_code, S.birth
                FROM {$this->student_table_name} S
                LEFT OUTER JOIN member_centerM C
                ON S.teacher_no = C.user_no
                LEFT OUTER JOIN color_codeT CC
                ON S.color_tag = CC.color_idx
                LEFT OUTER JOIN {$this->table_name} I
                ON S.franchise_idx = I.franchise_idx AND S.user_no = I.student_idx
                WHERE S.franchise_idx = '" . $franchise_idx . "'" . $where_qry . "
                AND S.state = '00' AND I.order_state IN ('02','03','04')
                ORDER BY S.birth DESC, S.user_name ASC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function loadStudentFeeList($franchise_idx, $student_idx, $paymonth)
    {
        $sql = "SELECT IM.order_num, IM.order_ym, C.code_name AS pay_method, IM.order_method, SUM(IM.order_money) AS pay_amount
        ,SUM(IM.refund_money) AS refund_amount, IM.pay_date, IM.order_date , IM.refund_date
        FROM invoiceM IM 
        LEFT OUTER JOIN codeM C ON C.code_num2 = IM.order_method AND C.code_num1 = '41'
        WHERE IM.franchise_idx = '{$franchise_idx}' AND IM.student_idx = '{$student_idx}' AND IM.order_state IN ('02','03','04') AND IM.order_ym = '{$paymonth}'
        GROUP BY IM.order_num, IM.order_ym, IM.pay_date, IM.order_date, IM.order_method, IM.refund_date, C.code_name";

        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function getPayedAmount($franchise_idx, $student_idx, $paymonth)
    {
        $sql = "SELECT order_num, (ISNULL(SUM(order_money),0) - ISNULL(SUM(refund_money),0)) AS totamt 
                FROM invoiceM WHERE franchise_idx = '{$franchise_idx}' AND student_idx = '{$student_idx}' AND order_ym = '{$paymonth}'
                GROUP BY order_num";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function getInvoiceItemData($order_num, $center_idx)
    {
        $sql = "SELECT II.invoice_idx, R.receipt_name
                , CASE WHEN R.receipt_amount > 0 THEN R.receipt_amount ELSE II.order_money END AS unitamt
                , II.order_quantity, II.order_money, II.refund_money, II.order_state, IR.refund_etc
                FROM invoiceM II
                LEFT OUTER JOIN receiptT R ON II.receipt_idx = R.receipt_item_idx
                LEFT OUTER JOIN invoice_refundT IR ON IR.invoice_idx = II.invoice_idx
                WHERE II.order_num = '{$order_num}' AND II.franchise_idx = '{$center_idx}'";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function getPaymentKey($order_num)
    {
        $sql = "SELECT paymentKey FROM payment_logT WHERE orderId = '{$order_num}'";
        $result = $this->db->sqlRow($sql);
        return $result;
    }
}

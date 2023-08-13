<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class SaleConfirmInfo extends Model
{
    var $invoice_table_name = "invoiceM";
    var $table_name = "franchise_feeM";

    function __construct()
    {
        parent::__construct();
    }

    public function getInvoiceData($center_idx, $payym)
    {
        $sql = "SELECT C.code_name AS grade_name, R.receipt_name, COUNT(I.student_idx) AS student_cnt, SUM(I.order_money) AS amount
        , CAST(SUM(I.order_money * F.royalty / 100) AS INT) AS royalty
        FROM {$this->invoice_table_name} I
        LEFT OUTER JOIN receiptT R ON I.receipt_idx = R.receipt_item_idx
        LEFT OUTER JOIN codeM C ON C.code_num2 = R.receipt_grade AND C.code_num1 = '02'
        LEFT OUTER JOIN franchiseM F ON I.franchise_idx = F.franchise_idx
        WHERE I.franchise_idx = '{$center_idx}' AND I.order_ym = '{$payym}' AND R.receipt_lesson_type <> '' AND I.order_state <> '99'
        GROUP BY C.code_name, R.receipt_name";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function getInvoiceRefundData($center_idx, $payym)
    {
        $sql = "SELECT COUNT(IR.student_idx) AS refund_cnt, ISNULL(SUM(IR.refund_money), 0) AS refund_amount, ISNULL(CAST(SUM(IR.refund_money * F.royalty / 100) AS INT), 0) AS adjust_royalty
        FROM {$this->invoice_table_name} IR
        LEFT OUTER JOIN receiptT R ON IR.receipt_idx = R.receipt_item_idx
        LEFT OUTER JOIN codeM C ON C.code_num2 = R.receipt_grade AND C.code_num1 = '02'
        LEFT OUTER JOIN franchiseM F ON IR.franchise_idx = F.franchise_idx
        WHERE IR.franchise_idx = '{$center_idx}' AND IR.order_ym = '{$payym}' AND R.receipt_lesson_type <> '' AND IR.order_state IN ('03', '04')";
        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function getFranchiseFeeInfo($center_idx, $payym)
    {
        $sql = "SELECT C.code_name AS fee_state, FF.franchise_fee_date
                FROM franchise_feeM FF
                LEFT OUTER JOIN codeM C ON C.code_num2 = FF.franchise_fee_state AND C.code_num1 = '42'
                WHERE franchise_idx = '{$center_idx}' AND franchise_fee_ym = '{$payym}' ";

        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function getRoyaltyData($center_idx, $year)
    {
        $syear = date('Y-m', strtotime($year . "-01"));
        $eyear = date('Y-m', strtotime($year . "-12"));
        $sql = "SELECT order_num, franchise_fee_ym AS order_ym, franchise_fee_money AS tot_money, C.code_name AS fee_state, franchise_fee_state FROM franchise_feeM FF
        LEFT OUTER JOIN codeM C ON FF.franchise_fee_state = C.code_num2 AND C.code_num1 = '42'
        WHERE FF.franchise_idx = '{$center_idx}' AND FF.franchise_fee_ym BETWEEN '{$syear}' AND '{$eyear}'
        UNION
        SELECT ISNULL(FF.order_num, '') AS order_num, I.order_ym, CAST(((SELECT CAST(SUM(IM.order_money * F.royalty / 100) AS INT) AS royalty
        FROM invoiceM IM
        LEFT OUTER JOIN receiptT R ON IM.receipt_idx = R.receipt_item_idx
        LEFT OUTER JOIN codeM C ON C.code_num2 = R.receipt_grade AND C.code_num1 = '02'
        LEFT OUTER JOIN franchiseM F ON IM.franchise_idx = F.franchise_idx
        WHERE I.franchise_idx = I.franchise_idx AND I.order_ym = IM.order_ym AND R.receipt_lesson_type <> '' AND IM.order_state <> '99') -
        (SELECT 
        ISNULL(CAST(SUM(IR.refund_money * F.royalty / 100) AS INT), 0) AS adjust_royalty
        FROM invoiceM IR
        LEFT OUTER JOIN receiptT R ON IR.receipt_idx = R.receipt_item_idx
        LEFT OUTER JOIN codeM C ON C.code_num2 = R.receipt_grade AND C.code_num1 = '02'
        LEFT OUTER JOIN franchiseM F ON IR.franchise_idx = F.franchise_idx
        WHERE IR.franchise_idx = I.franchise_idx AND IR.order_ym = I.order_ym AND R.receipt_lesson_type <> '' AND IR.order_state <> '99') +
        (SELECT CAST(F.rams_fee AS INT) FROM franchiseM F WHERE franchise_idx = I.franchise_idx)) * 1.1 AS INT) AS tot_money,
        ISNULL(C.code_name, '결제대기') AS fee_state, ISNULL(FF.franchise_fee_state, '00') AS franchise_fee_state
        FROM invoiceM I
        LEFT OUTER JOIN franchiseM F ON I.franchise_idx = F.franchise_idx
        LEFT OUTER JOIN receiptT R ON I.receipt_idx = R.receipt_item_idx
        LEFT OUTER JOIN franchise_feeM FF ON FF.franchise_idx = I.franchise_idx AND FF.franchise_fee_ym = I.order_ym
        LEFT OUTER JOIN codeM C ON C.code_num2 = FF.franchise_fee_state AND C.code_num1 = '42'
        WHERE I.franchise_idx = '{$center_idx}' AND I.order_ym BETWEEN '{$syear}' AND '{$eyear}' AND I.order_state <> '99' AND R.receipt_lesson_type <> ''
        GROUP BY I.franchise_idx, FF.order_num, I.order_ym, FF.franchise_fee_state, C.code_name";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function getRoyaltyPaymentData($center_idx, $payym)
    {
        $sql = "SELECT
        (SELECT CAST(SUM(I.order_money * F.royalty / 100) AS INT) AS royalty
        FROM invoiceM I
        LEFT OUTER JOIN receiptT R ON I.receipt_idx = R.receipt_item_idx
        LEFT OUTER JOIN codeM C ON C.code_num2 = R.receipt_grade AND C.code_num1 = '02'
        LEFT OUTER JOIN franchiseM F ON I.franchise_idx = F.franchise_idx
        WHERE I.franchise_idx = '{$center_idx}' AND I.order_ym = '{$payym}' AND R.receipt_lesson_type <> '' AND I.order_state <> '99') AS royalty,
        (SELECT 
        ISNULL(CAST(SUM(IR.refund_money * F.royalty / 100) AS INT), 0) AS adjust_royalty
        FROM invoiceM IR
        LEFT OUTER JOIN receiptT R ON IR.receipt_idx = R.receipt_item_idx
        LEFT OUTER JOIN codeM C ON C.code_num2 = R.receipt_grade AND C.code_num1 = '02'
        LEFT OUTER JOIN franchiseM F ON IR.franchise_idx = F.franchise_idx
        WHERE IR.franchise_idx = '{$center_idx}' AND IR.order_ym = '{$payym}' AND R.receipt_lesson_type <> '' AND IR.order_state <> '99') AS adjust_royalty,
        (SELECT F.rams_fee FROM franchiseM F WHERE franchise_idx = '{$center_idx}') AS rams_fee";

        $result = $this->db->sqlRow($sql);
        return $result;
    }
}

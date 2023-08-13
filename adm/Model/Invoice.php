<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class InvoiceInfo extends Model
{
    var $table_name = "invoiceT";

    function __construct()
    {
        parent::__construct();
    }

    public function getSalesChartData($center_type)
    {
        $year = date("Y") . "-12-31";
        $last_year = date('Y', strtotime("-1 year")) . "-01-01";

        if ($center_type == 'all') {
            $type_where = " AND franchise_type <> '00' ";
        } else if ($center_type == '01') {
            $type_where = " AND franchise_type = '01' "; //직영
        } else {
            $type_where = " AND franchise_type = '02' "; //가맹
        }

        $sql = "SELECT IM.order_ym AS ym, ( ISNULL(SUM(IM.order_money),0) - ISNULL(SUM(IM.refund_money),0) ) AS pay_amount
                FROM invoiceM IM
                LEFT OUTER JOIN receiptT R ON R.receipt_item_idx = IM.receipt_idx
                WHERE IM.order_state IN ('02', '04') AND IM.pay_date BETWEEN '{$last_year}' AND '{$year}' AND R.receipt_lesson_type <> ''
                AND IM.franchise_idx IN (SELECT franchise_idx FROM franchiseM WHERE useYn = 'Y' {$type_where})
                GROUP BY IM.order_ym";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function getSalesInfoChartData($center_type, $center_idx, $year)
    {
        if ($center_type == 'All') {
            $type_where = " AND franchise_type <> '00' ";
        } else if ($center_type == '01') {
            $type_where = " AND franchise_type = '01' "; //직영
        } else {
            $type_where = " AND franchise_type = '02' "; //가맹
        }

        if (!empty($center_idx)) {
            $type_where .= " AND franchise_idx = '{$center_idx}' ";
        }

        $start_year = $year . "-01";
        $end_year = $year . "-12";

        $sql = "SELECT IM.order_ym
        , (ISNULL(SUM(IM2.order_money), 0) - ISNULL(SUM(IM2.refund_money), 0)) AS tot_edu_money
        , (ISNULL(SUM(IM3.order_money), 0) - ISNULL(SUM(IM3.refund_money), 0)) AS real_edu_money
        , (ISNULL(SUM(IM4.order_money), 0) - ISNULL(SUM(IM4.refund_money), 0)) AS edu_book_money
        FROM invoiceM IM
        LEFT OUTER JOIN receiptT R ON IM.receipt_idx = R.receipt_item_idx
        LEFT OUTER JOIN invoiceM IM2 ON IM2.invoice_idx = IM.invoice_idx AND R.receipt_lesson_type <> ''
        LEFT OUTER JOIN invoiceM IM3 ON IM3.invoice_idx = IM.invoice_idx AND IM3.order_state = '02' AND R.receipt_lesson_type <> ''
        LEFT OUTER JOIN invoiceM IM4 ON IM4.invoice_idx = IM.invoice_idx AND IM4.order_state = '02' AND R.receipt_lesson_type = '' AND R.receipt_type = '99'
        WHERE IM.franchise_idx IN (SELECT franchise_idx FROM franchiseM WHERE useYn = 'Y' {$type_where})
        AND IM.order_ym BETWEEN '{$start_year}' AND '{$end_year}'
        GROUP BY IM.order_ym";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function getSalesCenterInfoChartData($center_type, $center_idx, $year)
    {
        if ($center_type == 'All') {
            $type_where = " AND franchise_type <> '00' ";
        } else if ($center_type == '01') {
            $type_where = " AND franchise_type = '01' "; //직영
        } else {
            $type_where = " AND franchise_type = '02' "; //가맹
        }

        if (!empty($center_idx)) {
            $type_where .= " AND franchise_idx = '{$center_idx}'";
        }

        $start_year = $year . "-01";
        $end_year = $year . "-12";

        $sql = "SELECT F.center_name
        , (ISNULL(SUM(IM2.order_money), 0) - ISNULL(SUM(IM2.refund_money), 0)) AS tot_edu_money
        , (ISNULL(SUM(IM3.order_money), 0) - ISNULL(SUM(IM3.refund_money), 0)) AS real_edu_money
        , (ISNULL(SUM(IM4.order_money), 0) - ISNULL(SUM(IM4.refund_money), 0)) AS edu_book_money
		FROM invoiceM IM
        LEFT OUTER JOIN franchiseM F ON IM.franchise_idx = F.franchise_idx
        LEFT OUTER JOIN receiptT R ON IM.receipt_idx = R.receipt_item_idx
        LEFT OUTER JOIN invoiceM IM2 ON IM2.invoice_idx = IM.invoice_idx AND R.receipt_lesson_type <> ''
		LEFT OUTER JOIN invoiceM IM3 ON IM3.invoice_idx = IM.invoice_idx AND IM3.order_state = '02' AND R.receipt_lesson_type <> ''
        LEFT OUTER JOIN invoiceM IM4 ON IM4.invoice_idx = IM.invoice_idx AND IM4.order_state = '02' AND R.receipt_lesson_type = '' AND R.receipt_type ='99'
        WHERE IM.franchise_idx IN (SELECT franchise_idx FROM franchiseM WHERE useYn = 'Y' {$type_where})
        AND IM.order_ym BETWEEN '{$start_year}' AND '{$end_year}'
        GROUP BY F.center_name";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function getFranchiseData($center_type)
    {
        if ($center_type == 'All') {
            $type_where = " AND franchise_type <> '00' ";
        } else if ($center_type == '01') {
            $type_where = " AND franchise_type = '01' "; //직영
        } else {
            $type_where = " AND franchise_type = '02' "; //가맹
        }
        $sql = "SELECT franchise_idx, center_name FROM franchiseM WHERE useYn = 'Y' {$type_where} AND franchise_idx <> '1'";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }
}

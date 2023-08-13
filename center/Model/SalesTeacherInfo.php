<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class SalesTeacherInfo extends Model
{
    var $table_name = 'invoiceM';

    function __construct()
    {
        parent::__construct();
    }

    public function getSalesData($franchise_idx, $user_idx, $salesmonth, $lesson_class_type)
    {
        $where_qry = "";
        if ($lesson_class_type == 'All') {
            $where_qry .= " AND R.receipt_lesson_type <> '' ";
        } else if ($lesson_class_type == '01') {
            $where_qry .= " AND R.receipt_lesson_type = '01' ";
        } else if ($lesson_class_type == '02') {
            $where_qry .= " AND R.receipt_lesson_type = '02' ";
        } else if ($lesson_class_type == '03') {
            $where_qry .= " AND R.receipt_lesson_type = '03' ";
        } else if ($lesson_class_type == '04') {
            $where_qry .= " AND R.receipt_lesson_type = '04' ";
        } else if ($lesson_class_type == '05') {
            $where_qry .= " AND R.receipt_lesson_type = '05' ";
        } else {
            $where_qry .= "";
        }

        if ($user_idx == 'All') {
            $where_qry .= " AND MS.teacher_no <> '0' ";
        } else {
            $where_qry .= " AND MS.teacher_no = '{$user_idx}' ";
        }

        $sql = "SELECT
        MC.user_no, MC.user_name, I.order_ym, C1.code_name AS lesson_type, R.receipt_lesson_type
        , COUNT(DISTINCT I.student_idx) AS teach_cnt
        , ISNULL(SUM(I.order_money) - SUM(I.refund_money), 0) AS edu_fee
        , ISNULL(SUM(I2.order_money) - SUM(I2.refund_money), 0) AS edu_fee2 FROM {$this->table_name} I
        LEFT OUTER JOIN member_centerM MC ON I.franchise_idx = MC.franchise_idx
        LEFT OUTER JOIN member_studentM MS ON I.student_idx = MS.user_no AND MS.teacher_no = MC.user_no
        LEFT OUTER JOIN receiptT R ON I.receipt_idx = R.receipt_item_idx AND I.franchise_idx = R.franchise_idx
        LEFT OUTER JOIN codeM C1 ON C1.code_num2 = R.receipt_lesson_type AND C1.code_num1 = '04'
        LEFT OUTER JOIN invoiceM I2 ON I.invoice_idx = I2.invoice_idx AND I2.order_state = '02'
        WHERE I.franchise_idx = '{$franchise_idx}' AND I.order_ym = '{$salesmonth}' " . $where_qry . "
        GROUP BY MC.user_no, MC.user_name, I.order_ym, C1.code_name, R.receipt_lesson_type
        ORDER BY MC.user_no, R.receipt_lesson_type, C1.code_name";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }
}

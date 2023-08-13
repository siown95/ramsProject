<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";
include $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class StudentFeeInfo extends Model
{
    var $table_name = 'invoiceT';
    var $item_table_name = 'invoice_itemT';
    var $member_table_name = 'member_studentm';

    function __construct()
    {
        parent::__construct();
    }

    public function getSalesChartData($franchise_idx)
    {
        $year = date("Y") . "-12-31";
        $last_year = date('Y', strtotime("-1 year")) . "-01-01";

        $sql = "SELECT IM.order_ym AS ym , ( ISNULL(SUM(IM.order_money),0) - ISNULL(SUM(IM.refund_money),0) ) AS pay_amount
                FROM invoiceM IM
                LEFT OUTER JOIN receiptT R ON R.receipt_item_idx = IM.receipt_idx
                WHERE IM.order_state <> '99' AND IM.pay_date BETWEEN '{$last_year}' AND '{$year}' AND R.receipt_lesson_type <> ''
                AND IM.franchise_idx = '{$franchise_idx}'
                GROUP BY IM.order_ym";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function loadStudent($franchise_idx, $grade)
    {
        if (getAge($grade) == 6) {
            $where_qry = "AND (YEAR(getdate()) - LEFT(S.birth, 4) + 1) <= '6'";
        } else if (getAge($grade) > 6 && getAge($grade) < 20) {
            $where_qry = "AND (YEAR(getdate()) - LEFT(S.birth, 4) + 1) = '" . getAge($grade) . "'";
        } else {
            $where_qry =  '';
        }

        $sql = "SELECT S.user_no, S.user_name, S.user_phone, ISNULL(C.user_name, '') teacher_name, CC.color_code
                FROM {$this->member_table_name} S
                LEFT OUTER JOIN member_centerM C
                ON S.teacher_no = C.user_no
                LEFT OUTER JOIN color_codeT CC
                ON S.color_tag = CC.color_idx
                WHERE S.franchise_idx = '" . $franchise_idx . "'" . $where_qry . "
                AND S.state = '00'
                ORDER BY S.birth DESC, S.user_name ASC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function loadStudentFeeList($franchise_idx, $student_idx)
    {
        $sql = "SELECT IM.order_num, IM.order_ym, C.code_name AS pay_state, ISNULL(C2.code_name, '') AS pay_method, SUM(IM.order_money) AS pay_amount, IM.pay_date, IM.order_date 
                FROM invoiceM IM 
                LEFT OUTER JOIN codeM C ON C.code_num2 = IM.order_state AND C.code_num1 = '45'
                LEFT OUTER JOIN codeM C2 ON C2.code_num2 = IM.order_method AND C2.code_num1 = '41' AND C2.code_num2 <> ''
                WHERE IM.franchise_idx = '{$franchise_idx}' AND IM.student_idx = '{$student_idx}' AND IM.order_state IN ('01', '02', '99')
                GROUP BY IM.order_num, IM.order_ym, IM.pay_date, IM.order_date, C.code_name, C2.code_name";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function loadInvoiceDetail($franchise_idx, $order_num)
    {
        $sql = "SELECT R.receipt_item_idx, R.receipt_name, R.receipt_amount, IM.order_quantity
                , IM.order_money, IM.order_ym, IM.invoice_idx, IM.pay_date, IM.pay_memo, IM.order_state, IM.order_method
                FROM invoiceM IM
                LEFT OUTER JOIN receiptT R ON R.receipt_item_idx = IM.receipt_idx AND IM.franchise_idx = R.franchise_idx
                WHERE IM.order_num = '{$order_num}' AND IM.franchise_idx = '{$franchise_idx}'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function getLessonFeeInfo($franchise_idx, $student_idx, $paymonth)
    {
        $sql = "SELECT RT.receipt_item_idx AS receipt_idx, RT.receipt_name, RT.receipt_amount, C.code_name, ISNULL(RT.receipt_amount * COUNT(LM.lesson_type), '0') AS amount 
        , COUNT(LM.lesson_type) AS lesson_count
        FROM lessonM LM
        LEFT OUTER JOIN lessonT LT ON LM.schedule_idx = LT.schedule_idx
        LEFT OUTER JOIN codeM C ON LM.lesson_type = C.code_num2 AND C.code_num1 = '04'
        LEFT OUTER JOIN member_studentM MS ON LT.student_idx = MS.user_no
        LEFT OUTER JOIN receiptT RT ON LM.lesson_type = RT.receipt_lesson_type AND RT.receipt_grade = dbo.fn_birth2GradeCode(MS.birth) AND LM.franchise_idx = RT.franchise_idx
        WHERE LM.franchise_idx = '{$franchise_idx}' AND LT.student_idx = '{$student_idx}' AND LM.lesson_date BETWEEN '" . date('Y-m-d', strtotime($paymonth . '-01')) . "' AND '" . date('Y-m-t', strtotime($paymonth)) . "'
        GROUP BY RT.receipt_item_idx, RT.receipt_name, C.code_name, RT.receipt_amount";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function getMonthLessonFeeInfo($center_idx, $month)
    {
        $smonth = date('Y-m-d', strtotime($month . '-01'));
        $emonth = date('Y-m-t', strtotime($month));
        $sql = "SELECT MS.user_no AS student_idx, RT.receipt_item_idx AS receipt_idx, RT.receipt_name+'('+C.code_name+')' AS receipt_name
        , CASE WHEN LM.lesson_type = '02' THEN '1' ELSE COUNT(LM.lesson_type) END AS order_quantity
        , CASE WHEN LM.lesson_type = '02' THEN ISNULL(RT.receipt_amount, '0') ELSE ISNULL(RT.receipt_amount * COUNT(LM.lesson_type), '0') END AS order_money
        FROM lessonM LM
        LEFT OUTER JOIN lessonT LT ON LM.schedule_idx = LT.schedule_idx
        LEFT OUTER JOIN codeM C ON LM.lesson_type = C.code_num2 AND C.code_num1 = '04'
        LEFT OUTER JOIN member_studentM MS ON LT.student_idx = MS.user_no
        LEFT OUTER JOIN receiptT RT ON LM.lesson_type = RT.receipt_lesson_type AND RT.receipt_grade = dbo.fn_birth2GradeCode(MS.birth) AND LM.franchise_idx = RT.franchise_idx
        WHERE LM.franchise_idx = '{$center_idx}' AND MS.user_no IS NOT NULL
        AND LM.lesson_date BETWEEN '{$smonth}' AND '{$emonth}' 
        GROUP BY MS.user_no, RT.receipt_item_idx, RT.receipt_name, LM.lesson_type, C.code_name, RT.receipt_amount";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function payInvoiceCheck($order_num, $franchise_idx)
    {
        $sql = "SELECT CONVERT(VARCHAR(10), reg_date, 23) reg_date FROM invoiceM WHERE order_num = '{$order_num}' AND franchise_idx = '{$franchise_idx}'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function getPayData($center_idx, $student_idx, $paymonth)
    {
        $sql = "SELECT I.order_ym, I.order_date, (I.order_money - I.refund_money) AS amount, C.code_name, MC.user_name, I.pay_memo FROM invoiceM I
        LEFT OUTER JOIN member_centerM MC ON I.franchise_idx = MC.franchise_idx AND I.teacher_idx = MC.user_no
        LEFT OUTER JOIN codeM C ON I.order_method = C.code_num2 AND C.code_num1 = '41'
        WHERE I.franchise_idx = '{$center_idx}' AND I.student_idx = '{$student_idx}' AND I.order_ym = '{$paymonth}' AND I.order_state IN ('02', '04')";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function getStudentEtc($center_idx, $student_idx)
    {
        $sql = "SELECT MS.user_memo, CC.color_detail FROM member_studentM MS LEFT OUTER JOIN color_codeT CC ON MS.color_tag = CC.color_idx WHERE MS.franchise_idx = '{$center_idx}' AND MS.user_no = '{$student_idx}'";
        $result = $this->db->sqlRow($sql);
        return $result;
    }
}

<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class ReceiptItemtInfo extends Model
{
    var $table_name = 'receiptT';

    function __construct()
    {
        parent::__construct();
    }

    public function receiptItemCheck($receipt_type, $lesson_type, $grade, $franchise_idx)
    {
        $sql = "SELECT COUNT(0) FROM {$this->table_name} 
                WHERE receipt_type = '" . $receipt_type . "' AND receipt_lesson_type = '" . $lesson_type . "' AND receipt_grade = '" . $grade . "' AND franchise_idx = '" . $franchise_idx . "'
                AND useYn = 'Y'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function receiptItemLoad($center_idx)
    {
        $sql = "SELECT RT.receipt_item_idx, C.code_name AS receipt_type, ISNULL(C2.code_name, '') AS lesson_type, ISNULL(C3.code_name, '') AS grade , RT.receipt_name, RT.receipt_amount, RT.useYn
                FROM {$this->table_name} RT
                LEFT OUTER JOIN codeM C ON C.code_num2 = RT.receipt_type AND C.code_num1 = '40'
                LEFT OUTER JOIN codeM C2 ON C2.code_num2 = RT.receipt_lesson_type AND C2.code_num1 = '04' AND C2.code_num2 <> ''
                LEFT OUTER JOIN codeM C3 ON C3.code_num2 = RT.receipt_grade AND C3.code_num1 = '02' AND C3.code_num2 <> ''
                WHERE RT.franchise_idx = '" . $center_idx . "'
                ORDER BY RT.receipt_type, C3.code_num2";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function receiptItemSelect($receiptItemIdx)
    {
        $sql = "SELECT franchise_idx, receipt_type, receipt_lesson_type, receipt_grade, receipt_name, receipt_amount, useYn FROM {$this->table_name} WHERE receipt_item_idx = '" . $receiptItemIdx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }

    public function receiptItemBatch($franchise_idx)
    {
        if ($franchise_idx == 'all') {
            $where_qry = "F.franchise_idx <> '1'";
        } else {
            $where_qry = "F.franchise_idx = '" . $franchise_idx . "'";
        }

        $sql = "INSERT INTO {$this->table_name} (franchise_idx, receipt_type, receipt_lesson_type, receipt_grade, receipt_name, receipt_amount, headYn)
                (SELECT F.franchise_idx, RT.receipt_type, RT.receipt_lesson_type, RT.receipt_grade, RT.receipt_name, RT.receipt_amount, 'Y' headYn FROM {$this->table_name} RT, franchiseM F 
                WHERE F.useyn = 'Y' AND RT.useYn = 'Y' AND RT.franchise_idx = '1'  AND " . $where_qry . ")";

        $result = $this->db->execute($sql);
        return $result;
    }
}

<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class BookStatusInfo extends Model
{
    var $table_name = "book_statust";

    function __construct()
    {
        parent::__construct();
    }

    public function bookStatusLoad($franchise_idx)
    {
        $sql = "SELECT S.status_idx, B.book_name, ISNULL(C.user_name, '') teacher_name, ISNULL(M.user_name, '') student_name, S.book_barcode, S.last_rent_date, S.last_return_date
                FROM {$this->table_name} S
                LEFT OUTER JOIN member_centerM C ON C.user_no = S.last_teacher_no AND C.franchise_idx = '" . $franchise_idx . "'
                LEFT OUTER JOIN member_studentM M ON M.user_no = S.last_student_no AND M.franchise_idx = '" . $franchise_idx . "'
                LEFT OUTER JOIN bookM B ON B.book_idx = S.book_idx
                WHERE S.franchise_idx = '" . $franchise_idx . "'
                ORDER BY B.book_name";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function statusSelect($status_idx)
    {
        $sql = "SELECT B.book_name, S.book_barcode FROM {$this->table_name} S
                LEFT OUTER JOIN bookM B ON B.book_idx = S.book_idx
                WHERE S.status_idx = '" . $status_idx . "'";

        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function barcodeCheck($franchise_idx, $book_barcode)
    {
        $sql = "SELECT COUNT(0) FROM {$this->table_name} WHERE franchise_idx = '" . $franchise_idx . "' AND book_barcode = '" . $book_barcode . "'";
        $result = $this->db->sqlRowOne($sql);
        return $result;
    }

    public function lastBarcode($franchise_idx)
    {
        $sql = "SELECT MAX(book_barcode) FROM {$this->table_name} WHERE franchise_idx = '" . $franchise_idx . "' AND book_barcode <> ''";
        $result = $this->db->sqlRowOne($sql);
        return $result;
    }

    public function getEmptyBarcode($franchise_idx)
    {
        $sql = "SELECT status_idx FROM {$this->table_name} WHERE franchise_idx = '" . $franchise_idx . "' AND book_barcode = ''";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }
}

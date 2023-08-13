<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Model.php";

class BookRentInfo extends Model
{
    var $table_name = "book_rentt";

    function __construct()
    {
        parent::__construct();
    }

    public function rentListLoad($center_idx, $student_no)
    {
        $sql = "SELECT TOP (100) R.rent_idx, B.book_name, B.book_writer, B.book_publisher
                , convert(varchar(19), R.rent_date, 120) rent_date
                , convert(varchar(19), R.ex_return_date, 120) ex_return_date
                , convert(varchar(19), R.return_date, 120) return_date
                , R.readYn
                FROM {$this->table_name} R
                LEFT OUTER JOIN bookm B ON B.book_idx = R.book_idx 
                WHERE R.franchise_idx = '" . $center_idx . "' AND R.student_no = '" . $student_no . "'
                ORDER BY R.rent_idx DESC";

        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function readListLoad($center_idx, $student_no)
    {
        $sql = "SELECT B.book_name, B.book_writer, B.book_publisher, MAX(R.rent_date) last_rent_date, MAX(R.return_date) last_return_date
        FROM {$this->table_name} R
        LEFT OUTER JOIN bookm B
        ON B.book_idx = R.book_idx 
        WHERE R.franchise_idx = '" . $center_idx . "'
        AND R.student_no = '" . $student_no . "'
        AND R.return_date <> ''
        AND R.readYn = '1'
        GROUP BY B.book_name, B.book_writer, B.book_publisher
        ORDER BY MAX(R.rent_date) DESC, MAX(R.return_date) DESC";

        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function loadBookRentMain($center_idx, $student_no)
    {
        $sql = "SELECT B.book_name, B.book_writer, B.book_publisher, R.rent_date, R.ex_return_date
                FROM bookm B 
                LEFT OUTER JOIN {$this->table_name} R ON R.book_idx = B.book_idx
                WHERE R.franchise_idx = '" . $center_idx . "' AND R.student_no = '" . $student_no . "' AND R.return_date = ''";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function loadBookReadMain($center_idx, $student_no)
    {
        $sql = "SELECT TOP (4) B.book_name, B.book_writer, B.book_publisher 
                FROM bookm B 
                LEFT OUTER JOIN {$this->table_name} R ON R.book_idx = B.book_idx
                WHERE R.franchise_idx = '" . $center_idx . "' AND R.student_no = '" . $student_no . "' AND R.return_date <> '' AND R.readYn = '1'
                ORDER BY R.return_date DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}

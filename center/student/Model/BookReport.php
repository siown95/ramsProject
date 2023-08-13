<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Model.php";

class BookReportInfo extends Model
{
    var $table_name = "board_book_reportt";

    function __construct()
    {
        parent::__construct();
    }

    public function readBookListLoad($center_idx, $student_no)
    {
        $sql = "SELECT B.book_idx, B.book_name
                FROM book_rentt R
                LEFT OUTER JOIN bookm B
                ON B.book_idx = R.book_idx 
                WHERE R.franchise_idx = '" . $center_idx . "'
                AND R.student_no = '" . $student_no . "'
                AND R.return_date <> ''
                AND R.readYn = '1'
                GROUP BY R.book_idx, B.book_idx, B.book_name
                ORDER BY B.book_name";

        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function bookReportLoad($center_idx)
    {
        $sql = "SELECT R.book_report_idx, R.book_report_title, R.writer_no, convert(varchar(19), R.reg_date, 120) reg_date
                , S.user_name student_name, (YEAR(getdate()) - LEFT(S.birth, 4) + 1) age, B.book_name
                FROM {$this->table_name} R
                LEFT OUTER JOIN member_studentm S 
                ON S.user_no = R.writer_no
                LEFT OUTER JOIN bookm B
                ON B.book_idx = R.book_idx
                WHERE R.franchise_idx = '" . $center_idx . "'
                ORDER BY R.reg_date DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function bookReportSelect($book_report_idx)
    {
        $sql = "SELECT R.book_report_title, R.book_report_contents, R.writer_no, convert(varchar(19), R.reg_date, 120) reg_date
                , S.user_name student_name, (YEAR(getdate()) - LEFT(S.birth, 4) + 1) age
                , B.book_name
                FROM {$this->table_name} R
                LEFT OUTER JOIN member_studentm S 
                ON S.user_no = R.writer_no
                LEFT OUTER JOIN bookm B
                ON B.book_idx = R.book_idx
                WHERE R.book_report_idx = '" . $book_report_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }
}

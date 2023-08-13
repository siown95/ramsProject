<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class BookRentInfo extends Model
{
    var $table_name = "book_rentt";

    function __construct()
    {
        parent::__construct();
    }

    public function rentStudentSearch($center_idx, $student_name)
    {
        $sql = "SELECT user_no, user_name, school_name, (YEAR(getdate()) - LEFT(birth, 4) + 1) user_age FROM member_studentm 
                WHERE state = '00' AND  franchise_idx = '" . $center_idx . "' AND user_name LIKE ('%" . $student_name . "%')";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function rentBookSearch($center_idx)
    {
        $sql = "SELECT b.book_idx, b.book_name, b.book_writer, b.book_publisher, c.detail, S.book_barcode, S.status_idx
                , (select COUNT(R.rent_idx) FROM book_rentT R WHERE r.book_status_idx = s.status_idx AND r.franchise_idx = '" . $center_idx . "' AND r.return_date = '') rentYn
                FROM bookm b
                LEFT OUTER JOIN book_statusT S
                ON S.book_idx = b.book_idx AND S.franchise_idx = '" . $center_idx . "'
                LEFT OUTER JOIN codem c
                ON (b.book_category1 = c.code_num1 AND b.book_category2 = c.code_num2 AND b.book_category3 = c.code_num3)
                WHERE b.useYn = 'Y' AND c.code_num2 <> '' 
                AND (SELECT COUNT(0) FROM book_statusT S where S.book_idx = b.book_idx and s.franchise_idx = '" . $center_idx . "') > 0
                ORDER BY b.book_name";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function rentBookCheck($student_no, $book_no)
    {
        $sql = "SELECT COUNT(0) FROM {$this->table_name} 
                WHERE student_no = '" . $student_no . "' AND book_idx = '" . $book_no . "' AND return_date = ''";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function rentStatusCheck($status_idx)
    {
        $sql = "SELECT COUNT(0) FROM {$this->table_name} 
                WHERE book_status_idx = '" . $status_idx . "' AND return_date = ''";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function rentListLoad($center_idx, $student_no, $teacher_no)
    {
        $where_qry = "";
        $where_arr = array(
            "R.franchise_idx = '" . $center_idx . "'",
            "S.franchise_idx = '" . $center_idx . "'"
        );

        if (!empty($student_no)) {
            $where_arr[] .= "R.student_no = '" . $student_no . "'";
        }

        if (!empty($teacher_no)) {
            $where_arr[] .= "R.teacher_no = '" . $teacher_no . "'";
        }

        $where_qry = "WHERE " . implode(" AND ", $where_arr);

        $sql = "SELECT R.rent_idx, S.user_name student_name, B.book_name, R.book_status_idx
                , R.rent_date
                , T.user_name teacher_name
                , R.ex_return_date
                , R.return_date
                , R.readYn
                , BS.book_barcode
                FROM {$this->table_name} R
                LEFT OUTER JOIN book_statusT BS
                ON R.book_status_idx = BS.status_idx
                LEFT OUTER JOIN member_studentm S
                ON S.user_no = R.student_no
                LEFT OUTER JOIN member_centerm T
                ON T.user_no = R.teacher_no
                LEFT OUTER JOIN bookm B
                ON B.book_idx = R.book_idx " . $where_qry . " 
                ORDER BY R.rent_idx DESC";

        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function rentSelect($rent_idx)
    {
        $sql = "SELECT S.user_name student_name, B.book_name, R.return_date, R.readYn
                FROM book_rentt R
                LEFT OUTER JOIN member_studentm S
                ON S.user_no = R.student_no
                LEFT OUTER JOIN bookm B
                ON B.book_idx = R.book_idx
                WHERE R.rent_idx = '" . $rent_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }

    public function rentBookManageList($center_idx, $student_no)
    {
        $sql = "SELECT B.book_name, R.rent_date, R.ex_return_date, S.book_barcode
                FROM {$this->table_name} R
                LEFT OUTER JOIN bookm B ON B.book_idx = R.book_idx
                LEFT OUTER JOIN book_statusT S ON S.status_idx = R.book_status_idx
                WHERE R.franchise_idx = '" . $center_idx . "' AND R.student_no = '" . $student_no . "' AND R.rent_date = ''";

        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}

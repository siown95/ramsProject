<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Model.php";

class BookDiagnosisInfo extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function loadDiagnosis1($center_idx, $student_no)
    {
        $sql = "exec sp_literary_statistic '" . $student_no . "', '" . $center_idx . "'";
        $result = $this->db->sqlRow($sql, 1);

        return $result;
    }

    public function loadDiagnosis2($center_idx, $student_no)
    {
        $sql = "exec sp_book_category_statistic '" . $student_no . "', '" . $center_idx . "'";
        $result = $this->db->sqlRow($sql, 1);

        return $result;
    }

    public function getReadBookCategoryDetail($center_idx, $student_no)
    {
        $sql = "SELECT
            C.code_name code_name1,
            C2.code_name code_name2,
            C3.detail,
            COUNT(distinct BR.book_idx) cnt
        FROM book_rentT BR
        LEFT OUTER JOIN bookM B ON B.book_idx = BR.book_idx
        LEFT OUTER JOIN codeM C ON B.book_category1 = C.code_num1 AND C.code_num2 = ''
        LEFT OUTER JOIN codeM C2 ON B.book_category1 = C2.code_num1 AND B.book_category2 = C2.code_num2
        LEFT OUTER JOIN codeM C3 ON B.book_category1 = C3.code_num1 AND B.book_category2 = C3.code_num2 AND B.book_category3 = C3.code_num3
        WHERE BR.franchise_idx = '{$center_idx}' AND BR.student_no = '{$student_no}' AND BR.readYn = '1'
        GROUP BY C.code_name, C2.code_name, C3.detail";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function getRecommendBookList($center_idx, $student_no)
    {
        $sql = "SELECT TOP(10) B.book_name, B.book_writer, B.book_publisher, C.code_name AS cate1, C2.code_name AS cate2, C3.detail AS cate3 FROM book_statusT BS
        LEFT OUTER JOIN bookM B ON BS.book_idx = B.book_idx
        LEFT OUTER JOIN codeM C ON B.book_category1 = C.code_num1 AND C.code_num2 = ''
        LEFT OUTER JOIN codeM C2 ON B.book_category1 = C2.code_num1 AND B.book_category2 = C2.code_num2
        LEFT OUTER JOIN codeM C3 ON B.book_category1 = C3.code_num1 AND B.book_category2 = C3.code_num2 AND B.book_category3 = C3.code_num3
        WHERE BS.franchise_idx = '{$center_idx}' AND BS.book_idx NOT IN (SELECT book_idx FROM book_rentT WHERE franchise_idx = '{$center_idx}' AND student_no = '{$student_no}' AND readYn <> '1')
        AND B.book_category1 <> '89'
        GROUP BY B.book_name, B.book_writer, B.book_publisher, C.code_name, C2.code_name, C3.detail
        ORDER BY NEWID()";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function getAllUserCnt()
    {
        $sql = "SELECT COUNT(0) FROM member_studentm WHERE state <> '99'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function getMaleUserCnt()
    {
        $sql = "SELECT COUNT(0) FROM member_studentm WHERE state <> '99' AND gender = 'M'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function getFemaleUserCnt()
    {
        $sql = "SELECT COUNT(0) FROM member_studentm WHERE state <> '99' AND gender = 'F'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }
}

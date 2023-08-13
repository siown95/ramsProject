<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class BookInfo extends Model
{
    var $table_name = "bookm";
    var $status_table = "book_statust";

    function __construct()
    {
        parent::__construct();
    }

    public function bookLoad($franchise_idx)
    {
        $sql = "SELECT b.book_idx, b.book_isbn, b.book_name, b.book_writer, b.book_publisher, b.reg_date, c.detail
                , (SELECT COUNT(0) FROM {$this->status_table} s WHERE s.book_idx = b.book_idx AND s.franchise_idx = '" . $franchise_idx . "') amount
                FROM {$this->table_name} b
                LEFT OUTER JOIN codem c
                ON (b.book_category1 = c.code_num1 AND b.book_category2 = c.code_num2 AND b.book_category3 = c.code_num3)
                WHERE b.useYn = 'Y' AND c.code_num2 <> '' 
                ORDER BY b.book_idx DESC";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function bookSelect($book_idx, $franchise_idx)
    {
        $sql = "SELECT b.book_isbn, b.book_name, b.book_writer, b.book_publisher
                , c.code_name category1, c2.code_name category2, c3.detail category3
                , (SELECT COUNT(0) FROM {$this->status_table} s WHERE s.book_idx = b.book_idx AND s.franchise_idx = '" . $franchise_idx . "') amount
                FROM {$this->table_name} b
                LEFT OUTER JOIN codem c
                ON (b.book_category1 = c.code_num1 AND code_num2 = '' AND code_num3 = '')
                LEFT OUTER JOIN codem c2
                ON (b.book_category1 = c2.code_num1 AND b.book_category2 = c2.code_num2 AND c2.code_num3 = '')
                LEFT OUTER JOIN codem c3
                ON (b.book_category1 = c3.code_num1 AND b.book_category2 = c3.code_num2 AND b.book_category3 = c3.code_num3)
                WHERE b.useYn = 'Y' AND b.book_idx = '" . $book_idx . "'";

        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function bookSearch($book_name)
    {
        $sql = "SELECT 
                  b.book_idx
                , b.book_isbn
                , b.book_name
                , b.book_writer
                , b.book_publisher
                , c.detail
              FROM {$this->table_name} b
              LEFT OUTER JOIN codem c
                 ON (b.book_category1 = c.code_num1 AND b.book_category2 = c.code_num2 AND b.book_category3 = c.code_num3)
              WHERE useYn = 'Y'
              AND c.code_num2 <> '' 
              AND book_name LIKE '%" . addslashes($book_name) . "%'";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function bookStatusInsert($params)
    {
        $book_idx = !empty($params['book_idx']) ? $params['book_idx'] : '';
        $franchise_idx = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';
        $book_amount = !empty($params['book_amount']) ? $params['book_amount'] : '';

        for ($i = $book_amount; $i > 0; $i--) {
            $sql = "INSERT INTO {$this->status_table} (franchise_idx, book_idx, book_barcode) VALUES ('" . $franchise_idx . "','" . $book_idx . "','')";
            $result = $this->db->execute($sql);
        }

        return $result;
    }

    public function bookCategory($position, $category1 = null, $category2 = null)
    {
        if ($position == '1') {
            $sql = "SELECT code_num1, code_name FROM codem WHERE code_num1 BETWEEN '80' AND '86' AND code_num2 = '' AND code_num3 = '' AND code_use = 'Y'";
        } else if ($position == '2') {
            $sql = "SELECT code_num2, code_name FROM codem WHERE code_num1 = '" . $category1 . "' AND code_num2 <> '' AND code_num3 = '' AND code_use = 'Y'";
        } else {
            $sql = "SELECT code_num3, detail FROM codem WHERE code_num1 = '" . $category1 . "' AND code_num2 = '" . $category2 . "' AND code_num3 <> '' AND code_use = 'Y'";
        }

        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function bookRequestLoad()
    {
        $sql = "SELECT RT.request_book_isbn, RT.request_book_name, RT.request_book_writer, RT.request_book_publisher
                , CASE WHEN RT.request_state = '00' THEN '등록요청'
                       WHEN RT.request_state = '01' THEN '승인'
                       WHEN RT.request_state = '09' THEN '거절' END request_state
                , RT.request_memo, RT.reg_date, F.center_name
                FROM bookRequestT RT 
                LEFT OUTER JOIN franchiseM F ON F.franchise_idx = RT.franchise_idx 
                ORDER BY request_idx DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function bookIsbnCheck($book_isbn)
    {
        $sql = "SELECT (SELECT COUNT(0) FROM bookM WHERE book_isbn = '" . $book_isbn . "') book_cnt
                , (SELECT COUNT(0) FROM bookRequestT WHERE request_book_isbn = '" . $book_isbn . "' AND request_state IN ('00', '01')) request_cnt";
        $result = $this->db->sqlRow($sql);

        return $result;
    }
}

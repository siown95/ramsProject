<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Model.php";

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
        $sql = "SELECT b.book_idx, b.book_isbn, b.book_name, b.book_writer, b.book_publisher, b.reg_date, c.detail, ISNULL(s.book_amount, 0) amount
                FROM {$this->table_name} b
                LEFT OUTER JOIN codem c
                ON (b.book_category1 = c.code_num1 AND b.book_category2 = c.code_num2 AND b.book_category3 = c.code_num3)
                LEFT OUTER JOIN {$this->status_table} s
                on s.book_idx = b.book_idx AND s.franchise_idx = '" . $franchise_idx . "'
                WHERE b.useYn = 'Y' AND c.code_num2 <> '' 
                ORDER BY b.book_idx DESC";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function bookSelect($book_idx, $franchise_idx)
    {
        $sql = "SELECT b.book_isbn, b.book_name, b.book_writer, b.book_publisher
                , c.code_name category1, c2.code_name category2, c3.detail category3, ISNULL(s.book_amount, 0) amount
                FROM bookm b
                LEFT OUTER JOIN codem c
                ON (b.book_category1 = c.code_num1 AND code_num2 = '' AND code_num3 = '')
                LEFT OUTER JOIN codem c2
                ON (b.book_category1 = c2.code_num1 AND b.book_category2 = c2.code_num2 AND c2.code_num3 = '')
                LEFT OUTER JOIN codem c3
                ON (b.book_category1 = c3.code_num1 AND b.book_category2 = c3.code_num2 AND b.book_category3 = c3.code_num3)
                LEFT OUTER JOIN book_statust s
                ON s.book_idx = b.book_idx AND s.franchise_idx = '" . $franchise_idx . "'
                WHERE b.useYn = 'Y' AND b.book_idx = '" . $book_idx . "'";

        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function statusCheck($book_idx, $franchise_idx)
    {
        $sql = "SELECT COUNT(0) FROM {$this->status_table} WHERE book_idx = '" . $book_idx . "' AND franchise_idx = '" . $franchise_idx . "'";

        $result = $this->db->sqlRowOne($sql);
        return $result;
    }

    public function statusInsert($params)
    {
        $book_idx = !empty($params['book_idx']) ? $params['book_idx'] : '';
        $franchise_idx = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';
        $book_amount = !empty($params['book_amount']) ? $params['book_amount'] : '0';

        $sql = "INSERT INTO {$this->status_table} SET
                  book_idx = '" . $book_idx . "'
                , franchise_idx = '" . $franchise_idx . "'
                , book_amount = '" . $book_amount . "'";
        $result = $this->db->execute($sql);
        return $result;
    }

    public function statusUpdate($params)
    {
        $book_idx = !empty($params['book_idx']) ? $params['book_idx'] : '';
        $franchise_idx = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';
        $book_amount = !empty($params['book_amount']) ? $params['book_amount'] : '0';

        $sql = "UPDATE {$this->status_table} SET
                book_amount = '" . $book_amount . "', mod_date = getdate()
                WHERE book_idx = '" . $book_idx . "' AND franchise_idx = '" . $franchise_idx . "'";

        $result = $this->db->execute($sql);
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
              AND book_name LIKE '%".addslashes($book_name)."%'";

      $result = $this->db->sqlRowArr($sql);
      return $result;
    }
}
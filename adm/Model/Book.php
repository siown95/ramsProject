<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class BookInfo extends Model
{
    var $table_name = "bookm";

    function __construct()
    {
        parent::__construct();
    }

    public function checkBookIsbn($book_isbn, $book_id = null)
    {
        $where = '';
        $where_arr = array(
            " book_isbn = '" . $book_isbn . "'"
        );

        if (!empty($book_id)) {
            $where_arr[] .= "book_idx <> '" . $book_id . "'";
        }

        $where = " WHERE " . implode(" AND ", $where_arr);

        $sql = "SELECT COUNT(0) FROM {$this->table_name} " . $where;
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function bookSelect($book_idx)
    {
        $sql = "SELECT 
                  book_idx
                  , book_isbn
                  , book_name
                  , book_writer
                  , book_publisher
                  , book_category1
                  , book_category2
                  , book_category3
                FROM {$this->table_name} 
                WHERE book_idx='" . $book_idx . "'";

        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function bookCategory($position, $category1 = null, $category2 = null)
    {
        if ($position == '1') {
            $sql = "SELECT code_num1, code_name FROM codem WHERE code_num1 BETWEEN '80' AND '89' AND code_num2 = '' AND code_num3 = '' AND code_use = 'Y'";
        } else if ($position == '2') {
            $sql = "SELECT code_num2, code_name FROM codem WHERE code_num1 = '" . $category1 . "' AND code_num2 <> '' AND code_num3 = '' AND code_use = 'Y'";
        } else {
            $sql = "SELECT code_num3, detail FROM codem WHERE code_num1 = '" . $category1 . "' AND code_num2 = '" . $category2 . "' AND code_num3 <> '' AND code_use = 'Y'";
        }

        $result = $this->db->sqlRowArr($sql);

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
              WHERE b.useYn = 'Y'
              AND c.code_num2 <> '' 
              AND book_name LIKE '%" . str_replace("'", "''", $book_name) . "%'";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function bookCategoryCnt()
    {
        $sql = "SELECT code_name, cnt from vw_book_category_count
                ORDER BY category1";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function bookPublisherSearch($inputValue)
    {
        $sql = "SELECT distinct TOP(10) book_publisher FROM bookM WHERE PATINDEX(dbo.fn_searchText('" . $inputValue . "') + '%', book_publisher) > 0";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function bookWriterSearch($inputValue)
    {
        $sql = "SELECT distinct TOP(10) book_writer FROM bookM WHERE PATINDEX(dbo.fn_searchText('" . $inputValue . "') + '%', book_writer) > 0";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }
}

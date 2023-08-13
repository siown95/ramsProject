<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class BookRequestInfo extends Model
{
    var $table_name = "bookRequestT";

    function __construct()
    {
        parent::__construct();
    }

    public function loadBookRequest()
    {
        $sql = "SELECT BR.request_idx, F.center_name, BR.request_book_name, BR.request_book_isbn, BR.request_book_writer, BR.request_book_publisher, C.detail, 
        CASE WHEN BR.request_state = '00' THEN '등록요청'
        WHEN BR.request_state = '01' THEN '승인'
        WHEN BR.request_state = '09' THEN '거절' END request_state, 
        BR.reg_date
        FROM {$this->table_name} BR
        LEFT OUTER JOIN franchiseM F ON BR.franchise_idx = F.franchise_idx
        LEFT OUTER JOIN codeM C ON BR.request_book_category1 = C.code_num1 AND BR.request_book_category2 = C.code_num2 AND BR.request_book_category3 = C.code_num3
        ORDER BY BR.request_idx DESC";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function bookRequestSelect($request_idx)
    {
        $sql = "SELECT request_book_name, request_book_isbn, request_book_writer, request_book_publisher, request_book_category1, request_book_category2, request_book_category3
                , request_state, request_memo FROM bookRequestT WHERE request_idx = '" . $request_idx . "'";

        $result = $this->db->sqlRow($sql);

        return $result;
    }

    public function bookIsbnCheck($book_isbn)
    {
        $sql = "SELECT COUNT(0) FROM bookM WHERE book_isbn = '" . $book_isbn . "'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }
}

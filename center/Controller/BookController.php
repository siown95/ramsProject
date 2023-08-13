<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Book.php";

class BookController extends Controller
{
    private $bookInfo;

    function __construct()
    {
        $this->bookInfo = new BookInfo();
    }

    public function bookSelect($params)
    {
        $book_idx = !empty($params['book_idx']) ? $params['book_idx'] : '';
        $franchise_idx = !empty($params['franchise_idx']) ? $params['franchise_idx'] : $_SESSION['center_idx'];

        try {
            if (empty($book_idx) || empty($franchise_idx)) {
                throw new Exception('에러가 발생하였습니다.', 708);
            }

            $result = $this->bookInfo->bookSelect($book_idx, $franchise_idx);
            if (!empty($result)) {
                $result['category'] = $result['category1'] . " > " . $result['category2'] . " > " . $result['category3'];
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bookStatus($request)
    {
        $book_idx = !empty($request['book_idx']) ? $request['book_idx'] : '';
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : $_SESSION['center_idx'];
        $book_amount = !empty($request['amount']) ? $request['amount'] : '';

        try {
            if (empty($book_idx) || empty($franchise_idx) || empty($book_amount)) {
                throw new Exception('에러가 발생하였습니다.', 708);
            }

            if (!is_numeric($book_amount)) {
                throw new Exception('정확한 수량을 입력하세요.', 708);
            }

            $params = array(
                "book_idx" => $book_idx,
                "franchise_idx" => $franchise_idx,
                "book_amount" => $book_amount
            );

            $result = $this->bookInfo->bookStatusInsert($params);

            if ($result) {
                $return_data['msg'] = "보유 수량이 수정되었습니다.";
            } else {
                throw new Exception('보유 수량 변경에 실패하였습니다.', 708);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bookSearch($params)
    {
        $book_name = !empty($params['book_name']) ? $params['book_name'] : '';

        try {
            if (empty($book_name)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookInfo->bookSearch($book_name);
            if (!empty($result)) {
                $no = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $no--;
                }
                return $result;
            } else {
                throw new Exception('데이터가 존재하지 않습니다.', 701);
            }
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bookCategory($position, $category1 = null, $category2 = null)
    {
        try {
            $result = $this->bookInfo->bookCategory($position, $category1, $category2);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bookRequestLoad()
    {
        try {
            $result = $this->bookInfo->bookRequestLoad();

            if(!empty($result)){
                $cnt = count($result);

                foreach($result as $key => $val){
                    $result[$key]['no'] = $cnt--;
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bookRequestInsert($request)
    {
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $teacher_idx = !empty($request['teacher_idx']) ? $request['teacher_idx'] : '';
        $request_book_name = !empty($request['rbook_name']) ? $request['rbook_name'] : '';
        $request_book_isbn = !empty($request['rbook_isbn']) ? $request['rbook_isbn'] : '';
        $request_book_writer = !empty($request['rbook_writer']) ? $request['rbook_writer'] : '';
        $request_book_publisher = !empty($request['rbook_publisher']) ? $request['rbook_publisher'] : '';
        $request_book_category1 = !empty($request['rBookcategory1']) ? $request['rBookcategory1'] : '';
        $request_book_category2 = !empty($request['rBookcategory2']) ? $request['rBookcategory2'] : '';
        $request_book_category3 = !empty($request['rBookcategory3']) ? $request['rBookcategory3'] : '';

        try {
            if (
                empty($franchise_idx) || empty($teacher_idx) || empty($request_book_name) || empty($request_book_isbn) || empty($request_book_writer)
                || empty($request_book_publisher) || empty($request_book_category1) || empty($request_book_category2) || empty($request_book_category3)
            ) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $bookIsbnChk = $this->bookInfo->bookIsbnCheck($request_book_isbn);

            if($bookIsbnChk['book_cnt'] > 0){
                throw new Exception('이미 등록된 도서입니다.', 701);
            }

            if($bookIsbnChk['request_cnt'] > 0){
                throw new Exception('이미 등록 요청한 도서입니다.', 701);
            }

            $params = array(
                "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                "teacher_idx" => !empty($teacher_idx) ? $teacher_idx : '',
                "request_book_name" => !empty($request_book_name) ? $request_book_name : '',
                "request_book_isbn" => !empty($request_book_isbn) ? $request_book_isbn : '',
                "request_book_writer" => !empty($request_book_writer) ? $request_book_writer : '',
                "request_book_publisher" => !empty($request_book_publisher) ? $request_book_publisher : '',
                "request_book_category1" => !empty($request_book_category1) ? $request_book_category1 : '',
                "request_book_category2" => !empty($request_book_category2) ? $request_book_category2 : '',
                "request_book_category3" => !empty($request_book_category3) ? $request_book_category3 : '',
                "request_state" => '00'
            );

            $this->bookInfo->table_name = "bookRequestT";
            $result = $this->bookInfo->insert($params);

            if ($result) {
                $return_data['msg'] = '도서 등록 요청이 확인되었습니다.';
            } else {
                throw new Exception('도서 등록요청에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$bookController = new BookController();

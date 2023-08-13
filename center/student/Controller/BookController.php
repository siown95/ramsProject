<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Book.php";

class BookController extends Controller
{
    private $bookInfo;

    function __construct()
    {
        $this->bookInfo = new BookInfo();
    }

    public function bookLoad($params)
    {
        $franchise_idx = !empty($params['franchise_idx']) ? $params['franchise_idx'] : $_SESSION['center_idx'];

        try {
            $result = $this->bookInfo->bookLoad($franchise_idx);

            if (!empty($result)) {
                $list_no = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                    $result[$key]['no']       = $list_no--;
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
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
        $book_amount = !empty($request['amount']) ? $request['amount'] : '0';

        try {
            if (empty($book_idx) || empty($franchise_idx)) {
                throw new Exception('에러가 발생하였습니다.', 708);
            }

            $check_cnt = $this->bookInfo->statusCheck($book_idx, $franchise_idx);

            $params = array(
                "book_idx" => $book_idx,
                "franchise_idx" => $franchise_idx,
                "book_amount" => $book_amount
            );

            if($check_cnt){
                $result = $this->bookInfo->statusUpdate($params);
            }else{
                $result = $this->bookInfo->statusInsert($params);
            }

            if($result){
                $return_data['msg'] = "보유 수량이 수정되었습니다.";
            }else{
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

        try{
            if(empty($book_name)){
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            
            $result = $this->bookInfo->bookSearch($book_name);
            if(!empty($result)){
                return $result;
            }else{
                throw new Exception('데이터가 존재하지 않습니다.', 701);
            }
        }catch(Exception $e){
            return $this->getMsgException($e);
        }
    }
}

$bookController = new BookController();
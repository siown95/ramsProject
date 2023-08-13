<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/BookRequest.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class BookRequestController extends Controller
{
    private $bookRequestInfo;

    function __construct()
    {
        $this->bookRequestInfo = new BookRequestInfo();
    }

    public function loadBookRequest()
    {
        try {
            $result = $this->bookRequestInfo->loadBookRequest();

            if (!empty($result)) {
                $cnt = count($result);

                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $cnt--;
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bookRequestSelect($request)
    {
        $request_idx = !empty($request['requestTarget']) ? $request['requestTarget'] : '';

        try {
            if (empty($request_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 708);
            }

            $result = $this->bookRequestInfo->bookRequestSelect($request_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bookRequestUpdate($request)
    {
        $requestTarget = !empty($request['requestTarget']) ? $request['requestTarget'] : '';
        $book_name = !empty($request['book_name']) ? $request['book_name'] : '';
        $book_isbn = !empty($request['book_isbn']) ? $request['book_isbn'] : '';
        $book_writer = !empty($request['book_writer']) ? $request['book_writer'] : '';
        $book_publisher = !empty($request['book_publisher']) ? $request['book_publisher'] : '';
        $book_category1 = !empty($request['book_category1']) ? $request['book_category1'] : '';
        $book_category2 = !empty($request['book_category2']) ? $request['book_category2'] : '';
        $book_category3 = !empty($request['book_category3']) ? $request['book_category3'] : '';
        $state = !empty($request['state']) ? $request['state'] : '';
        $memo = !empty($request['memo']) ? $request['memo'] : '';

        try {
            if (empty($requestTarget) || empty($state)) {
                throw new Exception('필수값이 누락되었습니다.', 708);
            }

            if ($state == '01') {
                if (empty($book_name) || empty($book_isbn) || empty($book_writer) || empty($book_publisher) || empty($book_category1) || empty($book_category2) || empty($book_category3)) {
                    throw new Exception('필수값이 누락되었습니다.', 708);
                }

                $bookIsbnChk = $this->bookRequestInfo->bookIsbnCheck($book_isbn);

                if ($bookIsbnChk) {
                    throw new Exception('기존에 등록된 도서입니다.', 708);
                }

                if (!empty($book_isbn) && strlen($book_isbn) >= 10) {
                    $isbn_query = http_build_query(
                        array(
                            'query' => $book_isbn
                        )
                    );
                    $res = getImageLink($isbn_query);
                    $dt = json_decode($res, true);
                    if (!empty($dt['documents'][0]['thumbnail'])) {
                        $img_link = $dt['documents'][0]['thumbnail'];
                    }
                }

                $book_params = array(
                    'book_name'      => !empty($book_name) ? $book_name : '',
                    'book_isbn'      => !empty($book_isbn) ? $book_isbn : '',
                    'book_writer'    => !empty($book_writer) ? $book_writer : '',
                    'book_publisher' => !empty($book_publisher) ? $book_publisher : '',
                    'book_category1' => !empty($book_category1) ? $book_category1 : '',
                    'book_category2' => !empty($book_category2) ? $book_category2 : '',
                    'book_category3' => !empty($book_category3) ? $book_category3 : '',
                    'img_link'       => !empty($img_link) ? $img_link : ''
                );

                $this->bookRequestInfo->table_name = "bookM";
                $book_result = $this->bookRequestInfo->insert($book_params);

                if ($book_result) {
                    $params = array(
                        "request_book_name" => !empty($book_name) ? $book_name : '',
                        "request_book_isbn" => !empty($book_isbn) ? $book_isbn : '',
                        "request_book_writer" => !empty($book_writer) ? $book_writer : '',
                        "request_book_publisher" => !empty($book_publisher) ? $book_publisher : '',
                        "request_book_category1" => !empty($book_category1) ? $book_category1 : '',
                        "request_book_category2" => !empty($book_category2) ? $book_category2 : '',
                        "request_book_category3" => !empty($book_category3) ? $book_category3 : '',
                        "request_state" => !empty($state) ? $state : '',
                        "request_memo" => !empty($memo) ? $memo : '',
                        "mod_date" => 'getdate()'
                    );

                    $this->bookRequestInfo->table_name = "bookRequestT";
                    $this->bookRequestInfo->where_qry = " request_idx = '" . $requestTarget . "' ";
                    $result = $this->bookRequestInfo->update($params);

                    if ($result) {
                        $return_data['msg'] = "요청정보가 수정되었습니다.";
                    } else {
                        throw new Exception('요청정보 수정에 실패하였습니다.', 708);
                    }
                } else {
                    throw new Exception('도서 등록에 실패하였습니다.', 708);
                }
            } else {
                $params = array(
                    "request_state" => !empty($state) ? $state : '',
                    "request_memo" => !empty($memo) ? $memo : '',
                    "mod_date" => 'getdate()'
                );

                $this->bookRequestInfo->where_qry = " request_idx = '" . $requestTarget . "' ";
                $result = $this->bookRequestInfo->update($params);

                if ($result) {
                    $return_data['msg'] = "요청정보가 수정되었습니다.";
                } else {
                    throw new Exception('요청정보 수정에 실패하였습니다.', 708);
                }
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bookRequestExpireDelete($request)
    {
        $expire_day = !empty($request['expire_day']) ? $request['expire_day'] : '';

        try {
            if (empty($expire_day)) {
                throw new Exception('필수값이 누락되었습니다.', 708);
            }

            $this->bookRequestInfo->table_name = "bookRequestT";
            $this->bookRequestInfo->where_qry = " request_idx IN (SELECT request_idx FROM bookRequestT WHERE request_state <> '00' AND DATEADD(DAY, {$expire_day}, mod_date) < GETDATE())";
            $result = $this->bookRequestInfo->delete();

            if ($result) {
                $return_data['msg'] = "요청기간이 만료된 데이터가 삭제되었습니다.";
            } else {
                throw new Exception('요청정보 삭제에 실패했습니다.', 708);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$bookRequestController = new BookRequestController();

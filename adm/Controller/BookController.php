<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Book.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class BookController extends Controller
{
    private $bookInfo;

    function __construct()
    {
        $this->bookInfo = new BookInfo();
    }

    public function bookInsert($request)
    {
        $book_name      = !empty($request['book_name']) ? $request['book_name'] : '';
        $book_isbn      = !empty($request['book_isbn']) ? $request['book_isbn'] : '';
        $book_writer    = !empty($request['book_writer']) ? $request['book_writer'] : '';
        $book_publisher = !empty($request['book_publisher']) ? $request['book_publisher'] : '';
        $book_category1 = !empty($request['book_category1']) ? $request['book_category1'] : '';
        $book_category2 = !empty($request['book_category2']) ? $request['book_category2'] : '';
        $book_category3 = !empty($request['book_category3']) ? $request['book_category3'] : '';

        try {
            if (empty($book_name) || empty($book_writer) || empty($book_publisher) || empty($book_category1) || empty($book_category2) || empty($book_category3)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
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

            if (!empty($book_isbn)) {
                $checkBookCnt = $this->bookInfo->checkBookIsbn($book_isbn);
                if ($checkBookCnt) {
                    throw new Exception('이미 등록된 도서입니다.', 701);
                }
            }

            $params = array(
                'book_name'      => !empty($book_name) ? $book_name : '',
                'book_isbn'      => !empty($book_isbn) ? $book_isbn : '',
                'book_writer'    => !empty($book_writer) ? $book_writer : '',
                'book_publisher' => !empty($book_publisher) ? $book_publisher : '',
                'book_category1' => !empty($book_category1) ? $book_category1 : '',
                'book_category2' => !empty($book_category2) ? $book_category2 : '',
                'book_category3' => !empty($book_category3) ? $book_category3 : '',
                'img_link'       => !empty($img_link) ? $img_link : ''
            );

            $result = $this->bookInfo->insert($params);

            if ($result) {
                $return_data['msg'] = "교재가 생성되었습니다.";
            } else {
                throw new Exception('교재 생성에 실패하였습니다.', 702);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bookUpdate($request)
    {
        $book_id        = !empty($request['origin_book_id']) ? $request['origin_book_id'] : '';
        $book_name      = !empty($request['book_name']) ? $request['book_name'] : '';
        $book_isbn      = !empty($request['book_isbn']) ? $request['book_isbn'] : '';
        $book_writer    = !empty($request['book_writer']) ? $request['book_writer'] : '';
        $book_publisher = !empty($request['book_publisher']) ? $request['book_publisher'] : '';
        $book_category1 = !empty($request['book_category1']) ? $request['book_category1'] : '';
        $book_category2 = !empty($request['book_category2']) ? $request['book_category2'] : '';
        $book_category3 = !empty($request['book_category3']) ? $request['book_category3'] : '';

        try {
            if (
                empty($book_id) || empty($book_name) || empty($book_writer) || empty($book_publisher) || empty($book_category1) || empty($book_category2) || empty($book_category3)
            ) {
                throw new Exception('필수값이 누락되었습니다.', 701);
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

            if (!empty($book_isbn)) {
                $checkBookCnt = $this->bookInfo->checkBookIsbn($book_isbn, $book_id);
                if ($checkBookCnt) {
                    throw new Exception('이미 등록된 도서입니다.', 701);
                }
            }

            $params = array(
                'book_name'      => !empty($book_name) ? $book_name : '',
                'book_isbn'      => !empty($book_isbn) ? $book_isbn : '',
                'book_writer'    => !empty($book_writer) ? $book_writer : '',
                'book_publisher' => !empty($book_publisher) ? $book_publisher : '',
                'book_category1' => !empty($book_category1) ? $book_category1 : '',
                'book_category2' => !empty($book_category2) ? $book_category2 : '',
                'book_category3' => !empty($book_category3) ? $book_category3 : '',
                'img_link'       => !empty($img_link) ? $img_link : '',
                'mod_date'       => 'getdate()'
            );

            $this->bookInfo->where_qry = "book_idx = " . $book_id;
            $result = $this->bookInfo->update($params);

            if ($result) {
                $return_data['msg'] = "교재가 수정되었습니다.";
            } else {
                throw new Exception('교재 수정에 실패하였습니다.', 702);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bookDelete($request)
    {
        $book_idx = !empty($request['book_idx']) ? $request['book_idx'] : '';

        try {
            if (empty($book_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $this->bookInfo->where_qry = " book_idx = '" . $book_idx . "' ";
            $result = $this->bookInfo->delete();

            if ($result) {
                $return_data['msg'] = "교재가 삭제되었습니다.";
            } else {
                throw new Exception('교재 삭제에 실패하였습니다.', 702);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bookCategoryCnt()
    {
        try {
            $result = $this->bookInfo->bookCategoryCnt();

            $tbl = '';
            $tableContents = '';
            $tot = 0;
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $tableContents .= "<th>" . $val['code_name'] . "</th><td>" . $val['cnt'] . "</td>";
                    $tot += $val['cnt'];
                }

                $tbl = "<tr>" . $tableContents . "<th>보유권수</th><td>" . $tot . "</td></tr>";
            }
            $result['tbl'] = $tbl;

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bookSelect($params)
    {
        $book_idx = !empty($params['book_idx']) ? $params['book_idx'] : '';

        try {
            if (empty($book_idx)) {
                throw new Exception('에러가 발생하였습니다.', 708);
            }

            $result = $this->bookInfo->bookSelect($book_idx);
            return $result;
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

    public function bookSearch($params)
    {
        $book_name = !empty($params['book_name']) ? $params['book_name'] : '';

        try {
            if (empty($book_name)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookInfo->bookSearch($book_name);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $key + 1;
                }

                return $result;
            } else {
                throw new Exception('데이터가 존재하지 않습니다.', 701);
            }
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bookPublisherSearch($request)
    {
        $inputValue = !empty($request['inputdata']) ? $request['inputdata'] : '';

        try {
            if (empty($inputValue)) {
                return false;
            }
            $optList = '';
            $result = $this->bookInfo->bookPublisherSearch($inputValue);
            if (!empty($result)) {
                return $result;
            } else {
                throw new Exception('데이터가 존재하지 않습니다.', 701);
            }
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bookWriterSearch($request)
    {
        $inputValue = !empty($request['inputdata']) ? $request['inputdata'] : '';

        try {
            if (empty($inputValue)) {
                return false;
            }
            $result = $this->bookInfo->bookWriterSearch($inputValue);
            if (!empty($result)) {
                return $result;
            } else {
                throw new Exception('데이터가 존재하지 않습니다.', 701);
            }
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$bookController = new BookController();

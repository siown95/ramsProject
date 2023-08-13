<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/BookRent.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class BookRentController extends Controller
{
    private $bookRentInfo;

    function __construct()
    {
        $this->bookRentInfo = new BookRentInfo();
    }

    public function loadBookRentMain($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($center_idx) || empty($student_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookRentInfo->loadBookRentMain($center_idx, $student_no);

            $tbl = '';
            if (!empty($result)) {
                $no = count($result);
                foreach ($result as $key => $val) {
                    $tbl .= "<tr>
                                 <td>" . $no-- . "</td>
                                 <td class=\"text-start\">" . $val['book_name'] . "</td>
                                 <td>" . $val['book_writer'] . "</td>
                                 <td>" . $val['book_publisher'] . "</td>
                                 <td>" . $val['rent_date'] . "</td>
                                 <td>" . $val['ex_return_date'] . "</td>
                             </tr>";
                }
            } else {
                $tbl = "<tr><td colspan='6'>대출중인 도서가 없습니다</td></tr>";
            }
            $result['tbl'] = $tbl;

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadBookReadMain($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($center_idx) || empty($student_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookRentInfo->loadBookReadMain($center_idx, $student_no);

            $tbl = '';
            if (!empty($result)) {
                $no = count($result);
                foreach ($result as $key => $val) {
                    $tbl .= "<tr>
                                 <td class=\"text-center\">" . $no-- . "</td>
                                 <td class=\"text-start\">" . $val['book_name'] . "</td>
                                 <td class=\"text-center\">" . $val['book_writer'] . "</td>
                                 <td class=\"text-center\">" . $val['book_publisher'] . "</td>
                             </tr>";
                }
            } else {
                $tbl = "<tr><td colspan='4'>읽은 도서가 없습니다</td></tr>";
            }
            $result['tbl'] = $tbl;

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function rentListLoad($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($center_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookRentInfo->rentListLoad($center_idx, $student_no);

            if (!empty($result)) {
                $num = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $num--;

                    $now_date = date("Y-m-d");

                    if (!empty($val['return_date'])) {
                        if ($val['return_date'] > $val['ex_return_date']) {
                            $result[$key]['over_date'] = (strtotime($val['return_date']) - strtotime($val['ex_return_date'])) / 86400;
                        } else {
                            $result[$key]['over_date'] = '';
                        }
                    } else {
                        if ($now_date > $val['ex_return_date']) {
                            $result[$key]['over_date'] = (strtotime($now_date) - strtotime($val['ex_return_date'])) / 86400;
                        } else {
                            $result[$key]['over_date'] = '';
                        }
                    }

                    if ($val['readYn'] == '1') {
                        $result[$key]['readYn'] = '읽음';
                    } else if ($val['readYn'] == '2') {
                        $result[$key]['readYn'] = '읽지않음';
                    } else {
                        $result[$key]['readYn'] = '';
                    }
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function readListLoad($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($center_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookRentInfo->readListLoad($center_idx, $student_no);

            if (!empty($result)) {
                $num = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $num--;
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$bookRentController = new BookRentController();

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/BookRent.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class BookRentController extends Controller
{
    private $bookRentInfo;

    function __construct()
    {
        $this->bookRentInfo = new BookRentInfo();
    }

    public function rentStudentSearch($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_name = !empty($request['student_name']) ? $request['student_name'] : '';

        try {
            if (empty($center_idx) || empty($student_name)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookRentInfo->rentStudentSearch($center_idx, $student_name);

            $table = '';
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $table .= "<tr class=\"tc text-center align-middel\" data-student-no=\"" . $val['user_no'] . "\">
                                   <td>" . $val['user_name'] . "</td>
                                   <td>" . getGrade($val['user_age']) . "</td>
                                   <td>" . $val['school_name'] . "</td>
                               </tr>";
                }
            } else {
                $return_data['msg'] = "해당 정보의 학생은 존재하지 않습니다.";
            }
            $return_data['table'] = $table;

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function rentListLoad($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';
        $teacher_no = !empty($request['teacher_no']) ? $request['teacher_no'] : '';

        try {
            if (empty($center_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookRentInfo->rentListLoad($center_idx, $student_no, $teacher_no);

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

    public function rentSelect($request)
    {
        $rent_idx = !empty($request['rent_idx']) ? $request['rent_idx'] : '';

        try {
            if (empty($rent_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookRentInfo->rentSelect($rent_idx);

            if (!empty($result['return_date'])) {
                $result['mod_flag'] = 'N';
            } else {
                $result['mod_flag'] = 'Y';
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function rentBookSearch($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';

        try {
            if (empty($center_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookRentInfo->rentBookSearch($center_idx);

            if (!empty($result)) {
                $num = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $num--;
                    $result[$key]['rentTxt'] = ($val['rentYn']) ? '대출중' : '';
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function rentBookInsert($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';
        $teacher_no = !empty($request['teacher_no']) ? $request['teacher_no'] : '';
        $book_no = !empty($request['book_no']) ? $request['book_no'] : '';
        $status_idx = !empty($request['status_idx']) ? $request['status_idx'] : '';

        try {
            if (empty($center_idx) || empty($student_no) || empty($teacher_no) || empty($book_no) || empty($status_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $overlapCheck = $this->bookRentInfo->rentBookCheck($student_no, $book_no);
            $statusCheck = $this->bookRentInfo->rentStatusCheck($status_idx);

            if ($overlapCheck > 0) {
                throw new Exception('이미 대여중인 도서입니다.', 701);
            }

            if ($statusCheck > 0) {
                throw new Exception('이미 대여중인 도서입니다.', 701);
            }

            $params = array(
                "franchise_idx" => !empty($center_idx) ? $center_idx : '',
                "student_no" => !empty($student_no) ? $student_no : '',
                "teacher_no" => !empty($teacher_no) ? $teacher_no : '',
                "book_idx" => !empty($book_no) ? $book_no : '',
                "book_status_idx" => !empty($status_idx) ? $status_idx : '',
                "rent_date" => date("Y-m-d"),
                "ex_return_date" => date("Y-m-d", strtotime("+7 day"))
            );

            $result = $this->bookRentInfo->insert($params);

            if ($result) {
                $statusParams = array(
                    "last_student_no" => !empty($student_no) ? $student_no : '',
                    "last_teacher_no" => !empty($teacher_no) ? $teacher_no : '',
                    "last_rent_date" => date("Y-m-d"),
                    'mod_date' => 'getdate()'
                );

                $this->bookRentInfo->table_name = 'book_statusT';
                $this->bookRentInfo->where_qry = " status_idx = '" . $status_idx . "'";
                $this->bookRentInfo->update($statusParams);

                $return_data['msg'] = "도서가 대출되었습니다.";
            } else {
                throw new Exception('도서 대출에 실패하였습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function rentBookReturn($request)
    {
        $rent_idx = !empty($request['rent_idx']) ? $request['rent_idx'] : '';
        $readYn = !empty($request['readYn']) ? $request['readYn'] : '';
        $teacher_no = !empty($request['teacher_no']) ? $request['teacher_no'] : '';
        $status_idx = !empty($request['status_idx']) ? $request['status_idx'] : '';

        try {
            if (empty($rent_idx) || empty($readYn) || empty($teacher_no) || empty($status_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "return_date" => date("Y-m-d"),
                "readYn" => !empty($readYn) ? $readYn : '0'
            );

            $this->bookRentInfo->where_qry = " rent_idx = '" . $rent_idx . "' ";
            $result = $this->bookRentInfo->update($params);

            if ($result) {
                $statusParams = array(
                    'last_teacher_no' => $teacher_no,
                    'last_return_date' => date("Y-m-d"),
                    'mod_date' => 'getdate()'
                );

                $this->bookRentInfo->table_name = 'book_statusT';
                $this->bookRentInfo->where_qry = " status_idx = '" . $status_idx . "'";
                $this->bookRentInfo->update($statusParams);

                $return_data['msg'] = "도서가 반납되었습니다.";
            } else {
                throw new Exception('도서 반납에 실패하였습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function rentBookManageList($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($center_idx) || empty($student_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookRentInfo->rentBookManageList($center_idx, $student_no);

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $now_date = date("Y-m-d");

                    if ($now_date > $val['ex_return_date']) {
                        $result[$key]['over_date'] = (strtotime($now_date) - strtotime($val['ex_return_date'])) / 86400;
                    } else {
                        $result[$key]['over_date'] = '';
                    }
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$bookRentController = new BookRentController();

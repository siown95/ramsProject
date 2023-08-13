<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/BookReport.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class BookReportController extends Controller
{
    private $bookReportInfo;

    function __construct()
    {
        $this->bookReportInfo = new BookReportInfo();
    }

    public function bookReportStudentSearch($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_name = !empty($request['student_name']) ? $request['student_name'] : '';

        try {
            if (empty($center_idx) || empty($student_name)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookReportInfo->bookReportStudentSearch($center_idx, $student_name);

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

    //읽은 책 목록 불러오기
    public function readBookListLoad($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($center_idx) || empty($student_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookReportInfo->readBookListLoad($center_idx, $student_no);
            $selectOption = "<option value=\"\">선택</option>";

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $selectOption .= "<option value=\"" . $val['book_idx'] . "\">" . $val['book_name'] . "</option>";
                }
            }
            $result['selectOption'] = $selectOption;

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //글쓰기 목록 불러오기
    public function bookReportLoad($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';

        try {
            if (empty($center_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookReportInfo->bookReportLoad($center_idx);

            if (!empty($result)) {
                $cnt = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $cnt--;
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                    $result[$key]['age'] = getGrade($val['age']);
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //글쓰기 선택
    public function bookReportSelect($request)
    {
        $book_report_idx = !empty($request['book_report_idx']) ? $request['book_report_idx'] : '';

        try {
            if (empty($book_report_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookReportInfo->bookReportSelect($book_report_idx);

            if (!empty($result)) {
                $result['student_name'] = $result['student_name'] . " (" . $result['school_name'] . " / " . getGrade($result['age']) . ")";
                $result['reg_date'] = date("Y-m-d", strtotime($result['reg_date']));
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //등록
    public function bookReportInsert($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';
        $txtWriteTitle = !empty($request['txtWriteTitle']) ? $request['txtWriteTitle'] : '';
        $selLessonBook = !empty($request['selLessonBook']) ? $request['selLessonBook'] : '';
        $txtWriteContents = !empty($request['txtWriteContents']) ? $request['txtWriteContents'] : '';

        try {
            if (empty($center_idx) || empty($student_no) || empty($txtWriteTitle) || empty($selLessonBook) || empty($txtWriteContents)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "franchise_idx" => !empty($center_idx) ? $center_idx : '',
                "writer_no" => !empty($student_no) ? $student_no : '',
                "book_idx" => !empty($selLessonBook) ? $selLessonBook : '',
                "book_report_title" => !empty($txtWriteTitle) ? $txtWriteTitle : '',
                "book_report_contents" => !empty($txtWriteContents) ? $txtWriteContents : ''
            );

            $result = $this->bookReportInfo->insert($params);
            if ($result) {
                $return_data['msg'] = '게시글이 저장되었습니다.';
            } else {
                throw new Exception('게시글 저장에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //수정
    public function bookReportUpdate($request)
    {
        $book_report_idx = !empty($request['book_report_idx']) ? $request['book_report_idx'] : '';
        $txtViewTitle = !empty($request['txtViewTitle']) ? $request['txtViewTitle'] : '';
        $txtViewContents = !empty($request['txtViewContents']) ? $request['txtViewContents'] : '';

        try {
            if (empty($book_report_idx) || empty($txtViewTitle) || empty($txtViewContents)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "book_report_title" => !empty($txtViewTitle) ? $txtViewTitle : '',
                "book_report_contents" => !empty($txtViewContents) ? $txtViewContents : '',
                "mod_date" => 'getdate()'
            );

            $this->bookReportInfo->where_qry = " book_report_idx = '" . $book_report_idx . "' ";
            $result = $this->bookReportInfo->update($params);
            if ($result) {
                $return_data['msg'] = '게시글이 수정되었습니다.';
            } else {
                throw new Exception('게시글 수정에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //삭제
    public function bookReportDelete($request)
    {
        $book_report_idx = !empty($request['book_report_idx']) ? $request['book_report_idx'] : '';

        try {
            if (empty($book_report_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $this->bookReportInfo->where_qry = " book_report_idx = '" . $book_report_idx . "' ";
            $result = $this->bookReportInfo->delete();
            if ($result) {
                $return_data['msg'] = '게시글이 삭제되었습니다.';
            } else {
                throw new Exception('게시글 삭제에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$bookReportController = new BookReportController();

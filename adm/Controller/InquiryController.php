<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Inquiry.php";

class InquiryController extends Controller
{
    private $inquiryInfo;

    function __construct()
    {
        $this->inquiryInfo = new InquiryInfo();
    }

    public function inquiryLoad()
    {
        try {
            $result = $this->inquiryInfo->inquiryLoad();

            if (!empty($result)) {
                $num = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['num'] = $num--;
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                    $result[$key]['cmt_exist'] = (!empty($val['inquiry_comment'])) ? '답변완료' : '확인중';
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function inquirySelect($request)
    {
        $inquiry_idx = !empty($request['inquiry_idx']) ? $request['inquiry_idx'] : '';

        try {
            if (empty($inquiry_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->inquiryInfo->inquirySelect($inquiry_idx);

            $downloadBtn = "";

            if (!empty($result['comment_file'])) {
                $downloadBtn = "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/inquiry_file/" . $result['comment_file'] . "\" download><i class=\"fa-solid fa-file-arrow-down\"></i> 다운로드</a>";
            }

            if (!empty($result['inquiry_comment'])) {
                $result['inquiry_comment'] = "<tr class=\"align-middle\">
                                                  <td width=\"10%\" class=\"text-center\">본사</td>
                                                  <td width=\"65%\" class=\"text-start\">" . nl2br($result['inquiry_comment']) . "</td>
                                                  <td width=\"25%\" class=\"text-center\">
                                                      " . $downloadBtn . "
                                                      <a class=\"btn btn-sm btn-outline-secondary\" id=\"btnCommentUpdate\"><i class=\"fa-solid fa-pen-to-square\"></i> 수정</a>
                                                      <a class=\"btn btn-sm btn-outline-danger\" id=\"btnCommentDelete\"><i class=\"fa-solid fa-trash\"></i> 삭제</a>
                                                  </td>
                                              </tr>";
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function inquiryCmtInsert($request)
    {
        $inquiry_idx = !empty($request['inquiry_idx']) ? $request['inquiry_idx'] : '';
        $txtAnswer = !empty($request['txtAnswer']) ? $request['txtAnswer'] : '';
        $fileInquiryCmt = !empty($_FILES['fileInquiryCmt']) ? $_FILES['fileInquiryCmt'] : '';

        try {
            if (empty($inquiry_idx) || empty($txtAnswer)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "inquiry_idx" => !empty($inquiry_idx) ? $inquiry_idx : '',
                "inquiry_comment" => !empty($txtAnswer) ? $txtAnswer : ''
            );

            if (!empty($fileInquiryCmt)) {

                $nameArr = explode(".", $fileInquiryCmt['name']);
                $extension = end($nameArr);
                $file_name = 'comment_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/inquiry_file/";
                copy($fileInquiryCmt['tmp_name'], $path . $file_name);
                $params['file_name'] = $file_name;
            }

            $result = $this->inquiryInfo->insert($params);

            if ($result) {
                $return_data['msg'] = "답변이 등록되었습니다.";
            } else {
                throw new Exception('답변 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function inquiryCmtUpdate($request)
    {
        $comment_idx = !empty($request['comment_idx']) ? $request['comment_idx'] : '';
        $txtAnswer = !empty($request['txtAnswer']) ? $request['txtAnswer'] : '';
        $cmtFileName = !empty($request['cmtFileName']) ? $request['cmtFileName'] : '';
        $fileInquiryCmt = !empty($_FILES['fileInquiryCmt']) ? $_FILES['fileInquiryCmt'] : '';

        try {
            if (empty($comment_idx) || empty($txtAnswer)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "inquiry_comment" => !empty($txtAnswer) ? $txtAnswer : '',
                "mod_date" => "getdate()"
            );

            if (!empty($fileInquiryCmt)) {

                $nameArr = explode(".", $fileInquiryCmt['name']);
                $extension = end($nameArr);
                $file_name = 'comment_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/inquiry_file/";

                if (!empty($cmtFileName)) {
                    unlink($path . $cmtFileName);
                }

                copy($fileInquiryCmt['tmp_name'], $path . $file_name);
                $params['file_name'] = $file_name;
            }

            $this->inquiryInfo->where_qry = " inquiry_comment_idx = '" . $comment_idx . "' ";
            $result = $this->inquiryInfo->update($params);

            if ($result) {
                $return_data['msg'] = "답변이 수정되었습니다.";
            } else {
                throw new Exception('답변 수정에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function inquiryCmtDelete($request)
    {
        $comment_idx = !empty($request['comment_idx']) ? $request['comment_idx'] : '';
        $cmtFileName = !empty($request['cmtFileName']) ? $request['cmtFileName'] : '';

        try {
            if (empty($comment_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if (!empty($cmtFileName)) {
                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/inquiry_file/";
                unlink($path . $cmtFileName);
            }

            $this->inquiryInfo->where_qry = " inquiry_comment_idx = '" . $comment_idx . "' ";
            $result = $this->inquiryInfo->delete();

            if ($result) {
                $return_data['msg'] = "답변이 삭제되었습니다.";
            } else {
                throw new Exception('답변 삭제에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function inquiryCmtFileDelete($request)
    {
        $comment_idx = !empty($request['comment_idx']) ? $request['comment_idx'] : '';
        $cmtFileName = !empty($request['cmtFileName']) ? $request['cmtFileName'] : '';

        try {
            if (empty($comment_idx) || empty($cmtFileName)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }


            $path = $_SERVER['DOCUMENT_ROOT'] . "/files/inquiry_file/";
            unlink($path . $cmtFileName);

            $params = array("file_name" => "");

            $this->inquiryInfo->where_qry = " inquiry_comment_idx = '" . $comment_idx . "' ";
            $result = $this->inquiryInfo->update($params);

            if ($result) {
                $return_data['msg'] = "파일이 삭제되었습니다.";
            } else {
                throw new Exception('파일 삭제에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}


$inquiryController = new InquiryController();

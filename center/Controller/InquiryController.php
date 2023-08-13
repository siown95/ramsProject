<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Inquiry.php";

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
        $user_no     = !empty($request['user_no']) ? $request['user_no'] : '';

        try {
            if (empty($inquiry_idx) || empty($user_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->inquiryInfo->inquirySelect($inquiry_idx);
            if (!empty($result)) {
                if (($result['writer_no'] == $user_no) && (empty($result['inquiry_comment']))) {
                    $result['mod_flag'] = 'Y';
                } else {
                    $result['mod_flag'] = 'N';
                }

                $result['reg_date'] = date("Y-m-d", strtotime($result['reg_date']));
            }

            $downloadBtn = "";

            if (!empty($result['comment_file'])) {
                $downloadBtn = "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/inquiry_file/" . $result['comment_file'] . "\" download><i class=\"fa-solid fa-file-arrow-down\"></i> 다운로드</a>";
            }

            if (!empty($result['inquiry_comment'])) {
                $result['inquiry_comment'] = "<tr class=\"align-middle\">
                                                  <td width=\"10%\" class=\"text-center\">본사</td>
                                                  <td width=\"75%\" class=\"text-start\">" . nl2br($result['inquiry_comment']) . "</td>
                                                  <td width=\"15%\" class=\"text-center\">
                                                      " . $downloadBtn . "
                                                  </td>
                                              </tr>";
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function inquiryUpdateLoad($request)
    {
        $inquiry_idx = !empty($request['inquiry_idx']) ? $request['inquiry_idx'] : '';

        try {
            if (empty($inquiry_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->inquiryInfo->inquiryUpdateLoad($inquiry_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function inquiryInsert($request)
    {
        $writer_no = !empty($request['writer_no']) ? $request['writer_no'] : '';
        $txtTitle = !empty($request['txtTitle']) ? $request['txtTitle'] : '';
        $selInquiryKind = !empty($request['selInquiryKind']) ? $request['selInquiryKind'] : '';
        $txtContents = !empty($request['txtContents']) ? $request['txtContents'] : '';
        $fileInquiry = !empty($_FILES['fileInquiry']) ? $_FILES['fileInquiry'] : '';

        try {
            if (empty($writer_no) || empty($txtTitle) || empty($selInquiryKind) || empty($txtContents)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "inquiry_writer" => !empty($writer_no) ? $writer_no : '',
                "inquiry_title" => !empty($txtTitle) ? $txtTitle : '',
                "inquiry_type" => !empty($selInquiryKind) ? $selInquiryKind : '',
                "inquiry_contents" => !empty($txtContents) ? $txtContents : ''
            );

            if (!empty($fileInquiry)) {

                $nameArr = explode(".", $fileInquiry['name']);
                $extension = end($nameArr);
                $file_name = 'inquiry_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/inquiry_file/";
                copy($fileInquiry['tmp_name'], $path . $file_name);
                $params['file_name'] = $file_name;
                $params['origin_file_name'] = $fileInquiry['name'];
            }

            $result = $this->inquiryInfo->insert($params);

            if ($result) {
                $return_data['msg'] = "문의 및 요청사항이 등록되었습니다.";
            } else {
                throw new Exception('문의사항 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function inquiryUpdate($request)
    {
        $inquiry_idx = !empty($request['inquiry_idx']) ? $request['inquiry_idx'] : '';
        $txtTitle = !empty($request['txtTitle']) ? $request['txtTitle'] : '';
        $selInquiryKind = !empty($request['selInquiryKind']) ? $request['selInquiryKind'] : '';
        $txtContents = !empty($request['txtContents']) ? $request['txtContents'] : '';
        $updateFileName = !empty($request['updateFileName']) ? $request['updateFileName'] : '';
        $fileInquiry = !empty($_FILES['fileInquiry']) ? $_FILES['fileInquiry'] : '';

        try {
            if (empty($inquiry_idx) || empty($txtTitle) || empty($selInquiryKind) || empty($txtContents)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "inquiry_title" => !empty($txtTitle) ? $txtTitle : '',
                "inquiry_type" => !empty($selInquiryKind) ? $selInquiryKind : '',
                "inquiry_contents" => !empty($txtContents) ? $txtContents : '',
                "mod_date" => "getdate()"
            );

            if (!empty($fileInquiry)) {

                $nameArr = explode(".", $fileInquiry['name']);
                $extension = end($nameArr);
                $file_name = 'inquiry_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/inquiry_file/";

                if (!empty($updateFileName)) {
                    unlink($path . $updateFileName);
                }

                copy($fileInquiry['tmp_name'], $path . $file_name);
                $params['file_name'] = $file_name;
                $params['origin_file_name'] = $fileInquiry['name'];
            }

            $this->inquiryInfo->where_qry = " inquiry_idx = '" . $inquiry_idx . "' ";
            $result = $this->inquiryInfo->update($params);

            if ($result) {
                $return_data['msg'] = "문의 및 요청사항이 수정되었습니다.";
            } else {
                throw new Exception('문의사항 수정에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function inquiryDelete($request)
    {
        $inquiry_idx = !empty($request['inquiry_idx']) ? $request['inquiry_idx'] : '';
        $inquiryFileName = !empty($request['inquiryFileName']) ? $request['inquiryFileName'] : '';

        try {
            if (empty($inquiry_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if (!empty($inquiryFileName)) {
                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/inquiry_file/";
                unlink($path . $inquiryFileName);
            }

            $this->inquiryInfo->where_qry = " inquiry_idx = '" . $inquiry_idx . "' ";
            $result = $this->inquiryInfo->delete();

            if ($result) {
                $return_data['msg'] = "문의 및 요청사항이 삭제되었습니다.";
            } else {
                throw new Exception('문의사항 삭제에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function inquiryFileDelete($request)
    {
        $inquiry_idx = !empty($request['inquiry_idx']) ? $request['inquiry_idx'] : '';
        $updateFileName = !empty($request['updateFileName']) ? $request['updateFileName'] : '';

        try {
            if (empty($inquiry_idx) || empty($updateFileName)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "file_name" => '',
                "origin_file_name" => '',
                "mod_date" => "getdate()"
            );

            $path = $_SERVER['DOCUMENT_ROOT'] . "/files/inquiry_file/";
            unlink($path . $updateFileName);

            $this->inquiryInfo->where_qry = " inquiry_idx = '" . $inquiry_idx . "' ";
            $result = $this->inquiryInfo->update($params);

            if ($result) {
                $return_data['msg'] = "첨부파일이 삭제 되었습니다.";
            } else {
                throw new Exception('첨부파일이 삭제에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}


$inquiryController = new InquiryController();

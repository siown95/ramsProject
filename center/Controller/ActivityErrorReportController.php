<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/ActivityErrorReport.php";

class ActivityErrorReportController extends Controller
{
    private $activityErrorReportInfo;

    function __construct()
    {
        $this->activityErrorReportInfo = new ActivityErrorReportInfo();
    }

    public function activityErrorLoad()
    {
        try {
            $result = $this->activityErrorReportInfo->activityErrorLoad();
            if (!empty($result)) {
                $cnt = count($result);

                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $cnt--;

                    if ($val['state'] == '1') {
                        $result[$key]['state'] = '접수중';
                    } else if ($val['state'] == '2') {
                        $result[$key]['state'] = '접수완료';
                    } else if ($val['state'] == '3') {
                        $result[$key]['state'] = '수정완료';
                    } else if ($val['state'] == '4') {
                        $result[$key]['state'] = '답변완료';
                    }

                    $result[$key]['writer'] = $val['center_name'] . ' ' . $val['user_name'];
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function activityErrorSelect($params)
    {
        $error_idx = !empty($params['error_idx']) ? $params['error_idx'] : '';

        try {
            if (empty($error_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->activityErrorReportInfo->activityErrorSelect($error_idx);

            if (!empty($result)) {
                $result['writer'] = $result['center_name'] . ' ' . $result['user_name'];
                $result['reg_date'] = date("Y-m-d", strtotime($result['reg_date']));

                if ($result['state'] == '1') {
                    $result['state'] = '접수중';
                } else if ($result['state'] == '2') {
                    $result['state'] = '접수완료';
                } else if ($result['state'] == '3') {
                    $result['state'] = '수정완료';
                } else if ($result['state'] == '4') {
                    $result['state'] = '답변완료';
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function activityErrorInsert($request)
    {

        $title     = !empty($request['title']) ? $request['title'] : '';
        $contents  = !empty($request['contents']) ? $request['contents'] : '';
        $writer_no = !empty($request['writer_no']) ? $request['writer_no'] : '';

        try {
            if (empty($title) || empty($contents) || empty($writer_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "title"     => !empty($title) ? $title : '',
                "contents"  => !empty($contents) ? $contents : '',
                "writer_no" => !empty($writer_no) ? $writer_no : '',
                "state"     => '1',
                "reg_date"  => "getdate()"
            );

            if (!empty($_FILES['files'])) {
                $nameArr = explode(".", $_FILES['files']['name']);
                $extension = end($nameArr);
                $file_name = 'file_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/activity_error/";
                copy($_FILES['files']['tmp_name'], $path . $file_name);
                $params['file_name'] = $file_name;
                $params['origin_name'] = $_FILES['files']['name'];
            }

            $result = $this->activityErrorReportInfo->insert($params);

            if ($result) {
                $return_data['msg'] = '게시글이 등록되었습니다.';
            } else {
                throw new Exception('게시글 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function activityErrorUpdate($request)
    {
        $error_idx = !empty($request['error_idx']) ? $request['error_idx'] : '';
        $title     = !empty($request['title']) ? $request['title'] : '';
        $contents  = !empty($request['contents']) ? $request['contents'] : '';
        $origin_file_name = !empty($request['file_name']) ? $request['file_name'] : '';

        try {
            if (empty($error_idx) || empty($title) || empty($contents)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "title"     => !empty($title) ? $title : '',
                "contents"  => !empty($contents) ? $contents : '',
                "mod_date"  => "getdate()"
            );

            if (!empty($_FILES['files'])) {
                $nameArr = explode(".", $_FILES['files']['name']);
                $extension = end($nameArr);
                $file_name = 'file_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/activity_error/";

                if (!empty($origin_file_name)) {
                    unlink($path . $origin_file_name);
                }

                copy($_FILES['files']['tmp_name'], $path . $file_name);
                $params['file_name'] = $file_name;
                $params['origin_name'] = $_FILES['files']['name'];
            }

            $this->activityErrorReportInfo->where_qry = " error_report_idx = '" . $error_idx . "' ";
            $result = $this->activityErrorReportInfo->update($params);

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

    public function activityErrorDelete($request)
    {
        $error_idx = !empty($request['error_idx']) ? $request['error_idx'] : '';
        $file_name = !empty($request['file_name']) ? $request['file_name'] : '';

        try {
            if (empty($error_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if (!empty($file_name)) {
                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/activity_error/";
                unlink($path . $file_name);
            }

            $this->activityErrorReportInfo->where_qry = " error_report_idx = '" . $error_idx . "' ";
            $result = $this->activityErrorReportInfo->delete();

            if ($result) {
                $return_data['msg'] = '신고내역이 삭제되었습니다.';
            } else {
                throw new Exception('신고내역 삭제에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function activityErrorFileDelete($request)
    {
        $error_idx = !empty($request['error_idx']) ? $request['error_idx'] : '';
        $file_name = !empty($request['file_name']) ? $request['file_name'] : '';

        try {
            if (empty($error_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if (!empty($file_name)) {
                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/activity_error/";
                unlink($path . $file_name);
            }

            $params = array(
                "file_name" => '',
                "origin_name" => ''
            );

            $this->activityErrorReportInfo->where_qry = " error_report_idx = '" . $error_idx . "' ";
            $result = $this->activityErrorReportInfo->update($params);

            if ($result) {
                $return_data['msg'] = '첨부파일이 삭제되었습니다.';
            } else {
                throw new Exception('첨부파일 삭제에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$activityErrorReportController = new ActivityErrorReportController();
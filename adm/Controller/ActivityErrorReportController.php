<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/ActivityErrorReport.php";

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
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function activityErrorComments($request)
    {
        $error_report_idx = !empty($request['error_idx']) ? $request['error_idx'] : '';
        $state = !empty($request['state']) ? $request['state'] : '';
        $comments = !empty($request['comments']) ? $request['comments'] : '';

        try {
            if (empty($error_report_idx) || empty($state)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "state"            => !empty($state) ? $state : '',
                "comments"         => !empty($comments) ? $comments : ''
            );

            $this->activityErrorReportInfo->where_qry = " error_report_idx = '" . $error_report_idx . "'";
            $result = $this->activityErrorReportInfo->update($params);

            if ($result) {
                $return_data['msg'] = '답변이 등록되었습니다.';
            } else {
                throw new Exception('답변 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function activityErrorDelete($request)
    {
        $error_report_idx = !empty($request['error_idx']) ? $request['error_idx'] : '';
        $file_name = !empty($request['file_name']) ? $request['file_name'] : '';

        try {
            if (empty($error_report_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if (!empty($file_name)) {
                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/activity_error/";
                unlink($path . $file_name);
            }

            $this->activityErrorReportInfo->where_qry = " error_report_idx = '" . $error_report_idx . "'";
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
}

$activityErrorReportController = new ActivityErrorReportController();
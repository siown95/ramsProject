<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/BoardCenterNotice.php";

class BoardCenterNoticeController extends Controller
{
    private $boardCenterNoticeInfo;

    function __construct()
    {
        $this->boardCenterNoticeInfo = new BoardCenterNoticeInfo();
    }

    public function centerNoticeLoad($request)
    {
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $teacher_no = !empty($request['teacher_no']) ? $request['teacher_no'] : '';
        $is_admin = !empty($request['is_admin']) ? $request['is_admin'] : $_SESSION['is_admin'];

        try {
            if (empty($franchise_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->boardCenterNoticeInfo->centerNoticeLoad($franchise_idx, $teacher_no, $is_admin);

            if (!empty($result)) {
                $cnt = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $cnt--;

                    if($val['notice_target'] == 'a'){
                        $result[$key]['notice_text'] = '전체';
                    }else if($val['notice_target'] == 't'){
                        $result[$key]['notice_text'] = '직원';
                    }else{
                        $result[$key]['notice_text'] = '학생';
                    }
                    
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //알림 저장
    public function centerNoticeInsert($request)
    {
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $txtWriteTitle = !empty($request['txtWriteTitle']) ? $request['txtWriteTitle'] : '';
        $txtWriteContents = !empty($request['txtWriteContents']) ? $request['txtWriteContents'] : '';
        $selTarget = !empty($request['selTarget']) ? $request['selTarget'] : '';
        $selTargetNo = !empty($request['selTargetNo']) ? $request['selTargetNo'] : '';

        try {
            if (empty($franchise_idx) || empty($txtWriteTitle) || empty($txtWriteContents) || empty($selTarget)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                "notice_title" => !empty($txtWriteTitle) ? $txtWriteTitle : '',
                "notice_contents" => !empty($txtWriteContents) ? $txtWriteContents : '',
                "notice_target" => !empty($selTarget) ? $selTarget : '',
                "notice_target_no" => !empty($selTargetNo) ? $selTargetNo : '',
            );

            $result = $this->boardCenterNoticeInfo->insert($params);

            if ($result) {
                $return_data['msg'] = "알림이 등록되었습니다.";
            } else {
                throw new Exception('알림이 등록되지 않았습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //알림 수정 
    public function centerNoticeUpdate($request)
    {
        $notice_idx = !empty($request['notice_idx']) ? $request['notice_idx'] : '';
        $txtViewTitle = !empty($request['txtViewTitle']) ? $request['txtViewTitle'] : '';
        $txtViewContents = !empty($request['txtViewContents']) ? $request['txtViewContents'] : '';

        try {
            if (empty($notice_idx) || empty($txtViewTitle) || empty($txtViewContents)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "notice_title" => !empty($txtViewTitle) ? $txtViewTitle : '',
                "notice_contents" => !empty($txtViewContents) ? $txtViewContents : '',
                "mod_date" => 'getdate()',
            );

            $this->boardCenterNoticeInfo->where_qry = " notice_idx = '" . $notice_idx . "' ";
            $result = $this->boardCenterNoticeInfo->update($params);

            if ($result) {
                $return_data['msg'] = "알림이 수정되었습니다.";
            } else {
                throw new Exception('알림이 수정되지 않았습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
    
    //알림 삭제
    public function centerNoticeDelete($request)
    {
        $notice_idx = !empty($request['notice_idx']) ? $request['notice_idx'] : '';

        try {
            if (empty($notice_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $this->boardCenterNoticeInfo->where_qry = " notice_idx = '" . $notice_idx . "' ";
            $result = $this->boardCenterNoticeInfo->delete();

            if ($result) {
                $return_data['msg'] = "알림이 삭제되었습니다.";
            } else {
                throw new Exception('알림이 삭제되지 않았습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function centerNoticeSelect($request)
    {
        $notice_idx = !empty($request['notice_idx']) ? $request['notice_idx'] : '';
        $notice_target = !empty($request['notice_target']) ? $request['notice_target'] : '';

        try {
            if (empty($notice_idx) || empty($notice_target)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->boardCenterNoticeInfo->centerNoticeSelect($notice_idx, $notice_target);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$boardCenterNoticeController = new BoardCenterNoticeController();

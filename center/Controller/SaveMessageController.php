<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/SaveMessage.php";

class SaveMessageController extends Controller
{
    private $saveMessageInfo;

    function __construct()
    {
        $this->saveMessageInfo = new SaveMessageInfo();
    }

    public function saveMsgLoad($request)
    {
        $franchise_idx  = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';
        try {
            if (empty($franchise_idx) || empty($user_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->saveMessageInfo->saveMsgLoad($franchise_idx, $user_idx);
            $opt = "<option value=\"\">선택</option>";
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $opt .= "<option value=\"" . $val['msg_idx'] . "\">" . $val['msg_title'] . "</option>";
                }
            }
            $result['opt'] = $opt;

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function saveMsgSelect($request)
    {
        $msg_idx  = !empty($request['msg_idx']) ? $request['msg_idx'] : '';
        $franchise_idx  = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';
        try {
            if (empty($msg_idx) || empty($franchise_idx) || empty($user_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->saveMessageInfo->saveMsgSelect($msg_idx, $franchise_idx, $user_idx);

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function saveMsgInsert($request)
    {
        $franchise_idx  = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';
        $msgtitle = !empty($request['msgtitle']) ? $request['msgtitle'] : '';
        $msgcontents = !empty($request['msgcontents']) ? $request['msgcontents'] : '';

        try {
            if (empty($franchise_idx) || empty($user_idx) || empty($msgtitle) || empty($msgcontents)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "franchise_idx" => $franchise_idx,
                "user_idx" => $user_idx,
                "msg_title" => $msgtitle,
                "msg_contents" => $msgcontents
            );

            $result = $this->saveMessageInfo->insert($params);
            if ($result) {
                $return_data['msg'] = '메시지가 저장되었습니다.';
            } else {
                throw new Exception('메시지가 저장되지 않았습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function saveMsgDelete($request)
    {
        $msg_idx  = !empty($request['msg_idx']) ? $request['msg_idx'] : '';
        $franchise_idx  = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';

        $this->saveMessageInfo->where_qry = " msg_idx = '" . $msg_idx . "' AND franchise_idx = '" . $franchise_idx . "' AND user_idx = '" . $user_idx . "' ";
        $result = $this->saveMessageInfo->delete();
        if ($result) {
            $return_data['msg'] = '저장 메시지가 삭제되었습니다.';
        } else {
            throw new Exception('저장 메시지가 삭제되지 않았습니다.', 701);
        }

        return $return_data;
    }
}

$saveMessageController = new SaveMessageController();

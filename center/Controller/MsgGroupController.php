<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/MsgGroup.php";

class MsgGroupController extends Controller
{
    private $msgGroupInfo;

    function __construct()
    {
        $this->msgGroupInfo = new MsgGroupInfo();
    }

    public function msgGroupLoad($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';

        try {
            if (empty($franchise_idx) || empty($user_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->msgGroupInfo->msgGroupLoad($franchise_idx, $user_idx);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['chk_msggroup'] = "<input type=\"checkbox\" class=\"form-check-input chkgrp\" />";
                    $result[$key]['del_msggroup'] = "<button type=\"button\" class=\"btn btn-sm btn-outline-danger btngrpdel\"><i class=\"fas fa-trash me-1\"></i>삭제</button>";
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function selMsgGroupLoad($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';

        try {
            if (empty($franchise_idx) || empty($user_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->msgGroupInfo->msgGroupLoad($franchise_idx, $user_idx);
            $opt = '<option value="">선택</option>';
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $opt  .= "<option value=\"" . $val['group_idx'] . "\">" . $val['group_name'] . "</option>";
                }
            }
            $result['opt'] .= $opt;
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function msgGroupSelect($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';
        $group_idx = !empty($request['group_idx']) ? $request['group_idx'] : '';

        try {
            if (empty($franchise_idx) || empty($user_idx) || empty($group_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->msgGroupInfo->msgGroupSelect($group_idx);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['chk_msggrpd'] = "<input type=\"checkbox\" class=\"form-check-input chkgrpd\" />";
                    $result[$key]['del_msggrpd'] = "<button type=\"button\" class=\"btn btn-sm btn-outline-danger btngrpddel\"><i class=\"fas fa-trash me-1\"></i>삭제</button>";
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function msgInfoSelect($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';
        $group_idx = !empty($request['group_idx']) ? $request['group_idx'] : '';

        try {
            if (empty($franchise_idx) || empty($user_idx) || empty($group_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->msgGroupInfo->msgInfoSelect($group_idx);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['chkmsg'] = "<input type=\"checkbox\" class=\"form-check-input chkMsg\" name=\"chkNo[]\" />";
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function msgGroupInsert($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';
        $group_name = !empty($request['group_name']) ? $request['group_name'] : '';
        try {
            if (empty($franchise_idx) || empty($user_idx) || empty($group_name)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $sql = "INSERT INTO msg_groupM (franchise_idx, user_idx, group_name) values ('{$franchise_idx}', '{$user_idx}', '{$group_name}')";
            $result = $this->msgGroupInfo->db->execute($sql);
            if ($result) {
                $return_data['msg'] = '그룹이 생성되었습니다.';
            } else {
                throw new Exception('그룹 생성에 실패하였습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function msgGroupListInsert($request)
    {
        $group_idx = !empty($request['group_idx']) ? $request['group_idx'] : '';
        $lists = !empty($request['lists']) ? $request['lists'] : '';
        try {
            if (empty($group_idx) || empty($lists)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $sql = '';
            foreach ($lists as $key => $val) {
                $sql .= "INSERT INTO msg_groupT (group_idx, group_user_name, group_user_hp) VALUES ('" . $group_idx . "', '" . $lists[$key][0] . "', '" . $lists[$key][1] . "') ";
            }
            $result = $this->msgGroupInfo->db->Execute($sql);
            if ($result) {
                $return_data['msg'] = '선택하신 연락처가 등록했습니다.';
            } else {
                throw new Exception('선택하신 연락처 등록에 실패했습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
    
    public function saveGrpInsert($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';
        $group_name = !empty($request['group_name']) ? $request['group_name'] : '';
        $lists = !empty($request['lists']) ? $request['lists'] : '';
        try {
            if (empty($franchise_idx) || empty($user_idx) || empty($group_name)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $sql = "INSERT INTO msg_groupM (franchise_idx, user_idx, group_name) values ('{$franchise_idx}', '{$user_idx}', '{$group_name}')";
            $result = $this->msgGroupInfo->db->execute($sql);

            if($result){
                $sql = "SELECT @@IDENTITY AS idx";
                $group_idx = $this->msgGroupInfo->db->sqlRowOne($sql);
            }

            $sql = '';
            foreach ($lists as $key => $val) {
                $sql .= "INSERT INTO msg_groupT (group_idx, group_user_name, group_user_hp) VALUES ('" . $group_idx . "', '" . $lists[$key][0] . "', '" . $lists[$key][1] . "') ";
            }
            $result = $this->msgGroupInfo->db->Execute($sql);
            if ($result) {
                $return_data['msg'] = '그룹이 생성되었습니다.';
            } else {
                throw new Exception('그룹 생성에 실패하였습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function msgGroupDeleteOne($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';
        $group_idx = !empty($request['group_idx']) ? $request['group_idx'] : '';

        try {
            if (empty($franchise_idx) || empty($user_idx) || empty($group_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $this->msgGroupInfo->where_qry = " group_idx = '" . $group_idx . "'";
            $result = $this->msgGroupInfo->delete();
            if ($result) {
                $sql = "DELETE FROM msg_groupM WHERE franchise_idx = '" . $franchise_idx . "' AND user_idx = '" . $user_idx . "' AND group_idx = '" . $group_idx . "'";
                $result2 = $this->msgGroupInfo->db->execute($sql);
                if ($result2) {
                    $return_data['msg'] = '그룹과 연락처가 삭제되었습니다.';
                } else {
                    $return_data['msg'] = '그룹과 연락처 삭제에 실패했습니다.';
                }
                return $return_data;
            } else {
                throw new Exception('그룹 삭제에 실패했습니다.', 701);
            }
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function msgGroupListDelete($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';
        $lists = !empty($request['lists']) ? $request['lists'] : '';

        try {
            if (empty($franchise_idx) || empty($user_idx) || empty($lists)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $idx_arr = implode(",", $lists);
            $sql = "DELETE FROM msg_groupT WHERE group_idx IN (" . $idx_arr . ") DELETE FROM msg_groupM WHERE group_idx IN (" . $idx_arr . ")";
            $result = $this->msgGroupInfo->db->execute($sql);
            if ($result) {
                $return_data['msg'] = '그룹과 그룹에 포함된 연락처가 삭제되었습니다.';
            } else {
                throw new Exception('그룹 삭제에 실패했습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function msgGroupDetailDeleteOne($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';
        $group_reg_idx = !empty($request['group_reg_idx']) ? $request['group_reg_idx'] : '';

        try {
            if (empty($franchise_idx) || empty($user_idx) || empty($group_reg_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $this->msgGroupInfo->where_qry = " group_reg_idx = '" . $group_reg_idx . "'";
            $result = $this->msgGroupInfo->delete();
            if ($result) {
                $return_data['msg'] = '연락처가 삭제되었습니다.';
                return $return_data;
            } else {
                throw new Exception('연락처 삭제에 실패했습니다.', 701);
            }
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function msgGroupDetailListDelete($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';
        $lists = !empty($request['lists']) ? $request['lists'] : '';

        try {
            if (empty($franchise_idx) || empty($user_idx) || empty($lists)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $idx_arr = implode(",", $lists);
            $sql = "DELETE FROM msg_groupT WHERE group_reg_idx IN (" . $idx_arr . ")";
            $result = $this->msgGroupInfo->db->execute($sql);
            if ($result) {
                $return_data['msg'] = '선택한 연락처가 삭제되었습니다.';
            } else {
                throw new Exception('선택한 연락처 삭제에 실패했습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function msgGroupShare($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';
        $target_user_idx = !empty($request['target_user_idx']) ? $request['target_user_idx'] : '';
        $target_group_idx = !empty($request['target_group_idx']) ? $request['target_group_idx'] : '';

        try {
            if (empty($franchise_idx) || empty($user_idx) || empty($target_user_idx) || empty($target_group_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $sql = "EXEC dbo.sp_msg_group_share '{$franchise_idx}', '{$user_idx}', '{$target_user_idx}', '{$target_group_idx}'";
            $result = $this->msgGroupInfo->db->execute($sql);
            if ($result) {
                $return_data['msg'] = '그룹이 공유되었습니다.';
            } else {
                throw new Exception('그룹 공유에 실패했습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}
$msgGroupController = new MsgGroupController();

<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Franchisee.php";


class FranchiseController extends Controller
{
    private $franchiseInfo;

    function __construct()
    {
        $this->franchiseInfo = new FranchiseInfo();
    }

    public function centerLoad()
    {
        try {
            $result = $this->franchiseInfo->centerLoad();
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $key + 1;
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function centerSelect($params)
    {
        $franchise_idx = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';

        try {
            if (empty($franchise_idx)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 600);
            }

            $result = $this->franchiseInfo->centerSelect($franchise_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function centerInsert($request)
    {
        $franchise_type = (!empty($request['franchise_type'])) ? $request['franchise_type'] : '';
        $center_name = (!empty($request['center_name'])) ? $request['center_name'] : '';
        $center_eng_name = (!empty($request['center_eng_name'])) ? $request['center_eng_name'] : '';
        $owner_name = (!empty($request['owner_name'])) ? $request['owner_name'] : '';
        $owner_id = (!empty($request['owner_id'])) ? $request['owner_id'] : '';
        $useyn = (!empty($request['useyn'])) ? $request['useyn'] : '';
        $address = (!empty($request['address'])) ? $request['address'] : '';
        $zipcode = (!empty($request['zipcode'])) ? $request['zipcode'] : '';
        $tel_num = (!empty($request['tel_num'])) ? $request['tel_num'] : '';
        $fax_num = (!empty($request['fax_num'])) ? $request['fax_num'] : '';
        $email = (!empty($request['email'])) ? $request['email'] : '';
        $location = (!empty($request['location'])) ? $request['location'] : '';
        $biz_reg_date = (!empty($request['biz_reg_date'])) ? $request['biz_reg_date'] : '';
        $biz_no = (!empty($request['biz_no'])) ? $request['biz_no'] : '';
        $class_no = (!empty($request['class_no'])) ? $request['class_no'] : '';
        $report_date = (!empty($request['report_date'])) ? $request['report_date'] : '';
        $franchisee_start = (!empty($request['franchisee_start'])) ? $request['franchisee_start'] : '';
        $franchisee_end = (!empty($request['franchisee_end'])) ? $request['franchisee_end'] : '';
        $rams_fee = (!empty($request['rams_fee'])) ? $request['rams_fee'] : '';
        $sales_confirm = (!empty($request['sales_confirm'])) ? $request['sales_confirm'] : '';
        $royalty = (!empty($request['royalty'])) ? $request['royalty'] : '';
        $sms_fee = (!empty($request['sms_fee'])) ? $request['sms_fee'] : '';
        $lms_fee = (!empty($request['lms_fee'])) ? $request['lms_fee'] : '';
        $mms_fee = (!empty($request['mms_fee'])) ? $request['mms_fee'] : '';
        $shop_id = (!empty($request['shop_id'])) ? $request['shop_id'] : '';
        $shop_key = (!empty($request['shop_key'])) ? $request['shop_key'] : '';

        try {
            if (empty($center_name) || empty($owner_name) || empty($address) || empty($tel_num) || empty($location) || empty($zipcode)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 600);
            }

            $params = array(
                "franchise_type" => !empty($franchise_type) ? $franchise_type : '',
                "center_name" => !empty($center_name) ? $center_name : '',
                "center_eng_name" => !empty($center_eng_name) ? $center_eng_name : '',
                "owner_name" => !empty($owner_name) ? $owner_name : '',
                "owner_id" => !empty($owner_id) ? $owner_id : '',
                "useyn" => !empty($useyn) ? $useyn : '',
                "address" => !empty($address) ? $address : '',
                "zipcode" => !empty($zipcode) ? $zipcode : '',
                "tel_num" => !empty($tel_num) ? $tel_num : '',
                "fax_num" => !empty($fax_num) ? $fax_num : '',
                "email" => !empty($email) ? $email : '',
                "location" => !empty($location) ? $location : '',
                "biz_reg_date" => !empty($biz_reg_date) ? $biz_reg_date : '',
                "biz_no" => !empty($biz_no) ? $biz_no : '',
                "class_no" => !empty($class_no) ? $class_no : '',
                "report_date" => !empty($report_date) ? $report_date : '',
                "franchisee_start" => !empty($franchisee_start) ? $franchisee_start : '',
                "franchisee_end" => !empty($franchisee_end) ? $franchisee_end : '',
                "rams_fee" => !empty($rams_fee) ? $rams_fee : '',
                "sales_confirm" => !empty($sales_confirm) ? $sales_confirm : '',
                "royalty" => !empty($royalty) ? $royalty : '',
                "sms_fee" => !empty($sms_fee) ? $sms_fee : '',
                "lms_fee" => !empty($lms_fee) ? $lms_fee : '',
                "mms_fee" => !empty($mms_fee) ? $mms_fee : '',
                "shop_id" => !empty($shop_id) ? $shop_id : '',
                "shop_key" => !empty($shop_key) ? $shop_key : ''
            );

            $result = $this->franchiseInfo->insert($params);

            if ($result) {
                $return_data['msg'] = '교육센터가 등록되었습니다.';
            } else {
                throw new Exception('등록에 실패하였습니다.', 600);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function centerUpdate($request)
    {
        $franchise_idx = (!empty($request['franchise_idx'])) ? $request['franchise_idx'] : '';
        $franchise_type = (!empty($request['franchise_type'])) ? $request['franchise_type'] : '';
        $center_name = (!empty($request['center_name'])) ? $request['center_name'] : '';
        $center_eng_name = (!empty($request['center_eng_name'])) ? $request['center_eng_name'] : '';
        $owner_name = (!empty($request['owner_name'])) ? $request['owner_name'] : '';
        $owner_id = (!empty($request['owner_id'])) ? $request['owner_id'] : '';
        $useyn = (!empty($request['useyn'])) ? $request['useyn'] : '';
        $address = (!empty($request['address'])) ? $request['address'] : '';
        $zipcode = (!empty($request['zipcode'])) ? $request['zipcode'] : '';
        $tel_num = (!empty($request['tel_num'])) ? $request['tel_num'] : '';
        $fax_num = (!empty($request['fax_num'])) ? $request['fax_num'] : '';
        $email = (!empty($request['email'])) ? $request['email'] : '';
        $location = (!empty($request['location'])) ? $request['location'] : '';
        $biz_reg_date = (!empty($request['biz_reg_date'])) ? $request['biz_reg_date'] : '';
        $biz_no = (!empty($request['biz_no'])) ? $request['biz_no'] : '';
        $class_no = (!empty($request['class_no'])) ? $request['class_no'] : '';
        $report_date = (!empty($request['report_date'])) ? $request['report_date'] : '';
        $franchisee_start = (!empty($request['franchisee_start'])) ? $request['franchisee_start'] : '';
        $franchisee_end = (!empty($request['franchisee_end'])) ? $request['franchisee_end'] : '';
        $rams_fee = (!empty($request['rams_fee'])) ? $request['rams_fee'] : '';
        $sales_confirm = (!empty($request['sales_confirm'])) ? $request['sales_confirm'] : '';
        $royalty = (!empty($request['royalty'])) ? $request['royalty'] : '';
        $sms_fee = (!empty($request['sms_fee'])) ? $request['sms_fee'] : '';
        $lms_fee = (!empty($request['lms_fee'])) ? $request['lms_fee'] : '';
        $mms_fee = (!empty($request['mms_fee'])) ? $request['mms_fee'] : '';
        $shop_id = (!empty($request['shop_id'])) ? $request['shop_id'] : '';
        $shop_key = (!empty($request['shop_key'])) ? $request['shop_key'] : '';

        try {
            if (empty($franchise_idx) || empty($center_name) || empty($owner_name) || empty($address) || empty($zipcode) || empty($tel_num) || empty($location)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 600);
            }

            if (!empty($owner_id) && !empty($owner_name)) {
                $rs = $this->franchiseInfo->updateOwner($owner_id);

                if (!$rs) {
                    throw new Exception('잘못된 접근입니다. ', 600);
                }
            }

            $params = array(
                "franchise_type" => !empty($franchise_type) ? $franchise_type : '',
                "center_name" => !empty($center_name) ? $center_name : '',
                "center_eng_name" => !empty($center_eng_name) ? $center_eng_name : '',
                "owner_name" => !empty($owner_name) ? $owner_name : '',
                "owner_id" => !empty($owner_id) ? $owner_id : '',
                "useyn" => !empty($useyn) ? $useyn : '',
                "address" => !empty($address) ? $address : '',
                "zipcode" => !empty($zipcode) ? $zipcode : '',
                "tel_num" => !empty($tel_num) ? $tel_num : '',
                "fax_num" => !empty($fax_num) ? $fax_num : '',
                "email" => !empty($email) ? $email : '',
                "location" => !empty($location) ? $location : '',
                "biz_reg_date" => !empty($biz_reg_date) ? $biz_reg_date : '',
                "biz_no" => !empty($biz_no) ? $biz_no : '',
                "class_no" => !empty($class_no) ? $class_no : '',
                "report_date" => !empty($report_date) ? $report_date : '',
                "franchisee_start" => !empty($franchisee_start) ? $franchisee_start : '',
                "franchisee_end" => !empty($franchisee_end) ? $franchisee_end : '',
                "rams_fee" => !empty($rams_fee) ? $rams_fee : '',
                "sales_confirm" => !empty($sales_confirm) ? $sales_confirm : '',
                "royalty" => !empty($royalty) ? $royalty : '',
                "sms_fee" => !empty($sms_fee) ? $sms_fee : '',
                "lms_fee" => !empty($lms_fee) ? $lms_fee : '',
                "mms_fee" => !empty($mms_fee) ? $mms_fee : '',
                "shop_id" => !empty($shop_id) ? $shop_id : '',
                "shop_key" => !empty($shop_key) ? $shop_key : '',
                "mod_date" => 'getdate()'
            );


            $this->franchiseInfo->where_qry = "franchise_idx = " . $franchise_idx;
            $result = $this->franchiseInfo->update($params);

            if ($result) {
                $return_data['msg'] = '교육센터가 수정되었습니다.';
            } else {
                throw new Exception('수정에 실패하였습니다.', 600);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function centerDelete($request)
    {
        $franchise_idx = (!empty($request['franchise_idx'])) ? $request['franchise_idx'] : '';

        try {
            if (empty($franchise_idx)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 600);
            }

            $this->franchiseInfo->where_qry = "franchise_idx = " . $franchise_idx;
            $result = $this->franchiseInfo->delete();

            if ($result) {
                $return_data['msg'] = '교육센터가 삭제되었습니다.';
            } else {
                throw new Exception('삭제에 실패하였습니다.', 600);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function giveFranchiseePoint($request)
    {
        $franchise_idx = (!empty($request['franchise_idx'])) ? $request['franchise_idx'] : '';
        $point = (!empty($request['point'])) ? $request['point'] : '';

        try {
            if (empty($franchise_idx) || empty($point)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 600);
            }

            $params = "UPDATE franchiseM SET point = point + {$point} WHERE franchise_idx = {$franchise_idx}";
            $result = $this->franchiseInfo->db->execute($params);

            if ($result) {
                $return_data['msg'] = '포인트가 정상적으로 지급되었습니다.';
            } else {
                throw new Exception('포인트 지급에 실패하였습니다.', 600);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function registerCodeInsert()
    {
        try {
            $auth_code = date('YmdHis') . sprintf('%06d', random_int(0, 999999));
            $auth_code = hash('sha256', $auth_code);
            $auth_code = substr($auth_code, 0, 10);
            $params = "INSERT INTO franchise_authT (auth_code) VALUES ('{$auth_code}')";
            $result = $this->franchiseInfo->db->execute($params);
            if ($result) {
                $return_data['msg'] = '가입코드가 생성되었습니다.';
                $return_data['data'] = $auth_code;
                $params2 = "DELETE FROM franchise_authT WHERE auth_expire = 'Y'";
                $result2 = $this->franchiseInfo->db->execute($params2);
            } else {
                throw new Exception('가입코드 생성에 실패하였습니다.', 600);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$franchiseeController = new FranchiseController();

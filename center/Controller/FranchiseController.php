<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Franchisee.php";


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
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function infoManage($request)
    {
        $franchise_idx = (!empty($request['franchise_idx'])) ? $request['franchise_idx'] : '';
        $center_name = (!empty($request['center_name'])) ? $request['center_name'] : '';
        $owner_name = (!empty($request['owner_name'])) ? $request['owner_name'] : '';
        $address = (!empty($request['address'])) ? $request['address'] : '';
        $tel_num = (!empty($request['tel_num'])) ? $request['tel_num'] : '';
        $fax_num = (!empty($request['fax_num'])) ? $request['fax_num'] : '';
        $email = (!empty($request['email'])) ? $request['email'] : '';
        $biz_no = (!empty($request['biz_no'])) ? $request['biz_no'] : '';
        $center_no = (!empty($request['center_no'])) ? $request['center_no'] : '';

        try {
            if (empty($franchise_idx) || empty($center_name) || empty($owner_name) || empty($address) || empty($tel_num)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 600);
            }

            $params = array(
                "center_name" => !empty($center_name) ? $center_name : '',
                "owner_name" => !empty($owner_name) ? $owner_name : '',
                "address" => !empty($address) ? $address : '',
                "tel_num" => !empty($tel_num) ? $tel_num : '',
                "fax_num" => !empty($fax_num) ? $fax_num : '',
                "email" => !empty($email) ? $email : '',
                "biz_no" => !empty($biz_no) ? $biz_no : '',
                "center_no" => !empty($center_no) ? $center_no : ''
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

    public function getPointBalance($request)
    {
        $center_idx = (!empty($request['center_idx'])) ? $request['center_idx'] : '';
        $charge_amount = (!empty($request['charge_amount'])) ? $request['charge_amount'] : '';
        try {
            if (empty($center_idx) || empty($center_idx)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 600);
            }
            $result = $this->franchiseInfo->getPointBalance($center_idx, $charge_amount);

            if (!$result) {
                throw new Exception('포인트 잔액이 부족합니다.', 600);
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getPointChargeList($request)
    {
        $center_idx = (!empty($request['center_idx'])) ? $request['center_idx'] : '';
        try {
            if (empty($center_idx)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 600);
            }
            $result = $this->franchiseInfo->getPointChargeList($center_idx);

            if ($result) {
                $tbl = "";
                foreach ($result as $key => $val) {
                    $tbl .= "<tr data-order_num=\"{$val['order_num']}\" data-payment_key=\"{$val['paymentKey']}\">
                    <td>" . ($key + 1) . "</td>
                    <td>{$val['pay_date']}</td>
                    <td>{$val['order_money']}</td>";
                    if (date('Y-m-d') > date('Y-m-d', strtotime("+6 Days", strtotime($val['pay_date'])))) {
                        $tbl .= "<td></td>";
                    } else {
                        $tbl .= "<td><button type=\"button\" class=\"btn btn-sm btn-outline-danger pointcancel\"><i class=\"fa-solid fa-xmark me-1\"></i>결제취소</button></td>";
                    }
                    $tbl .= "</tr>";
                }
                $return_data['tbl'] = $tbl;
            } else {
                $tbl = "<tr><td colspan\"4\">데이터가 없습니다.</td></tr>";
                $return_data['tbl'] = $tbl;
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$franchiseeController = new FranchiseController();

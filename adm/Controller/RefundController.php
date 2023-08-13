<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Refund.php";

class RefundController extends Controller
{
    private $refundInfo;

    function __construct()
    {
        $this->refundInfo = new RefundInfo();
    }

    public function getStudentRefundData($request)
    {
        $refund_month = !empty($request['refund_month']) ? $request['refund_month'] : "";
        try {
            if (empty($refund_month)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->refundInfo->getStudentRefundData($refund_month);
            if (!empty($result)) {
                $tbl = "";
                foreach ($result as $key => $val) {
                    $tbl .= "<tr class=\"align-middle text-center tc\" data-order-num=\"{$val['order_num']}\">
                    <td>" . ($key + 1) . "</td>
                    <td>{$val['center_name']}</td>
                    <td>{$val['user_name']}</td>
                    <td>{$val['user_phone']}</td>
                    <td>{$val['order_method']}</td>
                    <td>" . number_format($val['pay_amount']) . "</td>
                    <td>{$val['refund_date']}</td>
                    <td>" . number_format($val['refund_amount']) . "</td>
                    <td>{$val['reg_date']}</td>
                    </tr>";
                }
                $return_data['tbl'] = $tbl;
            } else {
                $tbl = "<tr><td colspan=\"9\">데이터가 존재하지 않습니다.</td></tr>";
                $return_data['tbl'] = $tbl;
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getFranchiseFeeRefundData($request)
    {
        $refund_month = !empty($request['refund_month']) ? $request['refund_month'] : "";
        try {
            if (empty($refund_month)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->refundInfo->getFranchiseFeeRefundData($refund_month);
            if (!empty($result)) {
                $tbl = "";
                foreach ($result as $key => $val) {
                    $tbl .= "<tr class=\"align-middle text-center tc\" data-order-num=\"{$val['order_num']}\">
                    <td>" . ($key + 1) . "</td>
                    <td>{$val['center_name']}</td>
                    <td>{$val['user_name']}</td>
                    <td>{$val['user_phone']}</td>
                    <td>{$val['order_method']}</td>
                    <td>{$val['order_state']}</td>
                    <td>{$val['franchise_fee_date']}</td>
                    <td>" . number_format($val['pay_amount']) . "</td>
                    <td>{$val['refund_date']}</td>
                    <td>" . number_format($val['refund_request_amount']) . "</td>
                    <td>" . number_format($val['refund_amount']) . "</td>
                    </tr>";
                }
                $return_data['tbl'] = $tbl;
            } else {
                $tbl = "<tr><td colspan=\"11\">데이터가 존재하지 않습니다.</td></tr>";
                $return_data['tbl'] = $tbl;
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getRequestRefundData($request)
    {
        $order_num = !empty($request['order_num']) ? $request['order_num'] : "";
        $type = !empty($request['type']) ? $request['type'] : "";
        try {
            if (empty($order_num) || empty($type)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            if ($type == 'i') {
                $result = $this->refundInfo->getRequestRefundData1($order_num);
            } else if ($type == 'f') {
                $result = $this->refundInfo->getRequestRefundData2($order_num);
            }
            if (!empty($result)) {
                return $result;
            } else {
                throw new Exception('잘못된 접근입니다.', 701);
            }
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$refundController = new RefundController();

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Receipt.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class ReceiptController extends Controller
{
    private $receiptInfo;

    function __construct()
    {
        $this->receiptInfo = new ReceiptInfo();
    }

    public function getReceiptData($params)
    {
        $franchise_idx = !empty($params['center_idx']) ? $params['center_idx'] : $_SESSION['center_idx'];
        $student_no = !empty($params['student_no']) ? $params['student_no'] : $_SESSION['logged_no'];
        $paymonth = !empty($params['paymonth']) ? $params['paymonth'] : date('Y-m');

        try {
            $result = $this->receiptInfo->getReceiptData($franchise_idx, $student_no, $paymonth);
            if ($result) {
                $tbl = "";
                foreach ($result as $key => $val) {
                    $amt = 0;
                    if ($val['order_state'] == '01' || ($val['order_state'] != '01' && $val['pay_amount'] == '0')) {
                        $amt = ($val['order_money'] - $val['refund_money'] - $val['pay_amount']);
                    } else if ($val['order_state'] == '02') {
                        $amt = ($val['order_money'] - $val['refund_money']);
                    } else {
                        $amt = 0;
                    }
                    $tbl .= "<tr class=\"rtc\" data-order_num=\"{$val['order_num']}\"
                    data-order_ym=\"{$val['order_ym']}\"
                    data-amount=\"" . ($val['order_state'] == '02' ? 0 : number_format($val['order_money'])) . "\"
                    data-order_amount=\"{$val['order_money']}\"
                    data-pay_amount=\"{$val['pay_amount']}\">
                    <td>{$val['order_ym']}</td>
                    <td>{$val['order_state_nm']}</td>
                    <td>{$val['order_method_nm']}</td>
                    <td>{$val['pay_date']}</td>
                    <td>" . ($val['order_state'] == '02' ? number_format($val['order_money'] - $val['refund_money']) : number_format($val['pay_amount'] - $val['cancel_amount'])) . " / "
                    . ($val['order_state'] == '02' ? 0 : number_format($val['order_money'])) . "</td>
                    </tr>";
                }
                $return_data['tbl'] = $tbl;
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getReceiptDataDetail($params)
    {
        $franchise_idx = !empty($params['center_idx']) ? $params['center_idx'] : $_SESSION['center_idx'];
        $student_no = !empty($params['student_no']) ? $params['student_no'] : $_SESSION['logged_no'];
        $paymonth = !empty($params['paymonth']) ? $params['paymonth'] : date('Y-m');
        $order_num = !empty($params['order_num']) ? $params['order_num'] : '';

        try {
            $result2 = $this->receiptInfo->getReceiptDataDetail($franchise_idx, $student_no, $order_num, $paymonth);
            $tbl2 = "";
            if ($result2) {
                foreach ($result2 as $key => $val) {
                    $tbl2 .= "<tr>
                    <td>" . ($key + 1) . "</td><td>{$val['receipt_name']}</td>
                    <td>" . number_format($val['unitamt']) . "</td>
                    <td>{$val['order_quantity']}</td><td>" . number_format($val['order_money']) . "</td>
                    </tr>";
                }
            }
            $return_data['tbl2'] = $tbl2;
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$receiptController = new ReceiptController();

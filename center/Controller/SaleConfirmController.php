<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/SaleConfirm.php";

class SaleConfirmController extends Controller
{
    private $saleConfirmInfo;

    function __construct()
    {
        $this->saleConfirmInfo = new SaleConfirmInfo();
    }

    public function getInvoiceData($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $payym = !empty($request['payym']) ? $request['payym'] : '';
        try {
            if (empty($center_idx) || empty($payym)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->saleConfirmInfo->getInvoiceData($center_idx, $payym);
            if ($result) {
                $tbl = '';
                $stdcnt = 0;
                $amtcnt = 0;
                $royalcnt = 0;
                foreach ($result as $key => $val) {
                    $tbl .= "<tr class=\"align-middle\">
                    <td class=\"text-center\">" . $val['grade_name'] . "</td>
                    <td class=\"text-center\">" . $val['receipt_name'] . "</td>
                    <td class=\"text-center\">" . $val['student_cnt'] . "</td>
                    <td class=\"text-end\">" . number_format($val['amount']) . "</td>
                    <td class=\"text-end\">" . number_format($val['royalty']) . "</td>
                </tr>";
                    $stdcnt += $val['student_cnt'];
                    $amtcnt += $val['amount'];
                    $royalcnt += $val['royalty'];
                }
                $tbl .= "<tr class=\"align-middle\">
                <td class=\"text-center\">합계</td>
                <td></td>
                <td class=\"text-center\">" . $stdcnt . "</td>
                <td class=\"text-end\">" . number_format($amtcnt) . "</td>
                <td class=\"text-end\">" . number_format($royalcnt) . "</td>
            </tr>";
                $result['tbl'] = $tbl;
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getInvoiceRefundData($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $payym = !empty($request['payym']) ? $request['payym'] : '';
        try {
            if (empty($center_idx) || empty($payym)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->saleConfirmInfo->getInvoiceRefundData($center_idx, $payym);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getFranchiseFeeInfo($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $payym = !empty($request['payym']) ? $request['payym'] : '';

        try {
            if (empty($center_idx) || empty($payym)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->saleConfirmInfo->getFranchiseFeeInfo($center_idx, $payym);

            if (empty($result['fee_state'])) {
                $result['fee_state'] = "결제대기";
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getRoyaltyData($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $year = !empty($request['year']) ? $request['year'] : '';

        try {
            if (empty($center_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->saleConfirmInfo->getRoyaltyData($center_idx,  $year);

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['tot_money'] = number_format($val['tot_money']);
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getRoyaltyPaymentData($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $payym = !empty($request['payym']) ? $request['payym'] : '';

        try {
            if (empty($center_idx) || empty($payym)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->saleConfirmInfo->getRoyaltyPaymentData($center_idx, $payym);

            if (!empty($result)) {
                $tax = ($result['royalty'] + $result['rams_fee'] - $result['adjust_royalty']) * 0.1;
                $return_data['royalty'] = number_format($result['royalty']);
                $return_data['rams_fee'] = number_format($result['rams_fee']);
                $return_data['adjust_royalty'] = number_format($result['adjust_royalty']);
                $return_data['usage_fee'] = number_format($result['royalty'] + $result['rams_fee'] - $result['adjust_royalty']);
                $return_data['tax'] = number_format($tax);
                $return_data['totamount'] = number_format($result['royalty'] + $result['rams_fee'] - $result['adjust_royalty'] + $tax);
                $return_data['refundableAmt'] = number_format(($result['royalty'] - $result['adjust_royalty']) * 1.1);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function invoiceInsert($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $amount = !empty($request['amount']) ? $request['amount'] : '';
        $order_ym = !empty($request['order_ym']) ? $request['order_ym'] : '';
        try {
            if (empty($center_idx) || empty($amount) || empty($order_ym)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $order_num = "f" . date('YmdHis') . "_" . $center_idx;
            $franchise_fee_arr = array(
                "order_num" => $order_num,
                "franchise_idx" => $center_idx,
                "franchise_fee_ym" => $order_ym,
                "franchise_fee_money" => $amount,
                "franchise_fee_state" => '01'
            );
            $result = $this->saleConfirmInfo->insert($franchise_fee_arr);
            if ($result) {
                $return_data['success'] = true;
            }
            else {
                $return_data['success'] = false;
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function refundRequestInsert($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $order_num = !empty($request['order_num']) ? $request['order_num'] : '';
        $req_reason = !empty($request['req_reason']) ? $request['req_reason'] : '';
        $req_amount = !empty($request['req_amount']) ? $request['req_amount'] : '';
        try {
            if (empty($center_idx) || empty($order_num) || empty($req_reason) || empty($req_amount)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $franchise_fee_arr = array(
                "refund_request_reason" => $req_reason,
                "refund_request_amount" => $req_amount
            );
            $this->saleConfirmInfo->table_name = "franchise_feeM";
            $this->saleConfirmInfo->where_qry = " franchise_idx = '{$center_idx}' AND order_num = '{$order_num}' ";
            $result = $this->saleConfirmInfo->update($franchise_fee_arr);
            if (!empty($result)) {
                $return_data['msg'] = "환불 신청이 등록되었습니다.";
            } else {
                throw new Exception('환불 신청이 실패했습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$saleConfirmController = new SaleConfirmController();

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/StudentRefund.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class StudentRefundController extends Controller
{
    private $studentRefundInfo;

    function __construct()
    {
        $this->studentRefundInfo = new StudentRefundInfo();
    }

    public function loadFeeStudent($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $paymonth = !empty($request['paymonth']) ? $request['paymonth'] : '';
        $grade = !empty($request['grade']) ? $request['grade'] : '';

        try {
            if (empty($franchise_idx) || empty($paymonth) || empty($grade)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentRefundInfo->loadFeeStudent($franchise_idx, $paymonth, $grade);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $key + 1;
                    if (!empty($val['color_code'])) {
                        $result[$key]['user_name'] = $val['user_name'] . "<span class=\"ms-1\" style=\"color:" . $val['color_code'] . ";\"><i class=\"fa-solid fa-circle\"></i></span>";
                    }
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadStudentFeeList($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_idx = !empty($request['student_no']) ? $request['student_no'] : '';
        $paymonth = !empty($request['paymonth']) ? $request['paymonth'] : '';

        try {
            if (empty($franchise_idx) || empty($student_idx) || empty($paymonth)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentRefundInfo->loadStudentFeeList($franchise_idx, $student_idx, $paymonth);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['pay_amount'] = number_format($val['pay_amount']) . "/" . number_format($val['refund_amount']);
                    if (!empty($val['refund_date'])) {
                        $result[$key]['pay_date'] = $val['pay_date'] . "/" . $val['refund_date'];
                    }
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getPayedAmount($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_idx = !empty($request['student_no']) ? $request['student_no'] : '';
        $paymonth = !empty($request['paymonth']) ? $request['paymonth'] : '';

        try {
            if (empty($franchise_idx) || empty($student_idx) || empty($paymonth)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->studentRefundInfo->getPayedAmount($franchise_idx, $student_idx, $paymonth);
            if (!empty($result) && $result > 0) {
                return $result;
            } else {
                $return_data['msg'] = "환불할 수 있는 결제금액이 없습니다.";
                return $return_data;
            }
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function studentRefundInsert($request)
    {
        $order_num = !empty($request['order_num']) ? $request['order_num'] : '';
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $teacher_idx = !empty($request['teacher_idx']) ? $request['teacher_idx'] : '';
        $student_idx = !empty($request['student_no']) ? $request['student_no'] : '';
        $PayedMonth = !empty($request['PayedMonth']) ? $request['PayedMonth'] : '';
        $refund_date = !empty($request['refunddate']) ? $request['refunddate'] : '';
        $refund_amount = !empty($request['refundamount']) ? $request['refundamount'] : '';
        $refund_method = !empty($request['refundMethod']) ? $request['refundMethod'] : '';
        $refund_etc = !empty($request['refundetc']) ? $request['refundetc'] : '';
        $pay_arr = !empty($request['pay_arr']) ? $request['pay_arr'] : '';

        try {
            if (
                empty($order_num) || empty($franchise_idx) || empty($teacher_idx) || empty($student_idx) || empty($PayedMonth) || empty($refund_date) || empty($refund_method)
                || empty($refund_amount) || empty($pay_arr)
            ) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            foreach ($pay_arr as $key => $val) {
                $invoice_array = array(
                    "order_state" => !empty($val[2]) ? $val[2] : '',
                    "refund_date" => !empty($refund_date) ? $refund_date : '',
                    "refund_method" => !empty($refund_method) ? $refund_method : '',
                    "refund_money" => !empty($val[3]) ? $val[3] : '0',
                );
                $this->studentRefundInfo->table_name = "invoiceM";
                $this->studentRefundInfo->where_qry = " order_num = '{$order_num}' AND invoice_idx = '{$val[0]}' ";
                $result = $this->studentRefundInfo->update($invoice_array);

                if ($result) {
                    $sql = "SELECT COUNT(0) FROM invoice_refundT WHERE order_num = '{$order_num}' AND franchise_idx = '{$franchise_idx}'";
                    $chk = $this->studentRefundInfo->db->sqlRowOne($sql);
                    if ($chk == 0) {
                        $refund_params = array(
                            "invoice_idx" => !empty($val[0]) ? $val[0] : '',
                            "order_num" => !empty($order_num) ? $order_num : '',
                            "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                            "teacher_idx" => !empty($teacher_idx) ? $teacher_idx : '',
                            "student_idx" => !empty($student_idx) ? $student_idx : '',
                            "refund_ym" => !empty($PayedMonth) ? $PayedMonth : '',
                            "refund_amount" => !empty($val[3]) ? $val[3] : '0',
                            "refund_state" => !empty($val[2]) ? $val[2] : '',
                            "refund_date" => !empty($refund_date) ? $refund_date : '',
                            "refund_etc" => !empty($refund_etc) ? $refund_etc : '',
                        );
                        $this->studentRefundInfo->table_name = "invoice_refundT";
                        $result2 = $this->studentRefundInfo->insert($refund_params);

                        if ($result2) {
                            $return_data['msg'] = '환불 처리되었습니다.';
                        } else {
                            throw new Exception('환불 처리에 실패했습니다. (2)', 701);
                        }
                    } else {
                        $refund_params = array(
                            "invoice_idx" => !empty($val[0]) ? $val[0] : '',
                            "order_num" => !empty($order_num) ? $order_num : '',
                            "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                            "teacher_idx" => !empty($teacher_idx) ? $teacher_idx : '',
                            "student_idx" => !empty($student_idx) ? $student_idx : '',
                            "refund_ym" => !empty($PayedMonth) ? $PayedMonth : '',
                            "refund_amount" => !empty($val[3]) ? $val[3] : '0',
                            "refund_state" => !empty($val[2]) ? $val[2] : '',
                            "refund_date" => !empty($refund_date) ? $refund_date : '',
                            "refund_etc" => !empty($refund_etc) ? $refund_etc : '',
                        );
                        $this->studentRefundInfo->table_name = "invoice_refundT";
                        $this->studentRefundInfo->where_qry = " invoice_refund_idx = '{$val[0]}' AND order_num = '{$order_num}' AND franchise_idx = '{$franchise_idx}' ";
                        $result2 = $this->studentRefundInfo->update($refund_params);

                        if ($result2) {
                            $return_data['msg'] = '환불 처리가 수정되었습니다.';
                        } else {
                            throw new Exception('환불 처리 수정에 실패했습니다. (3)', 701);
                        }
                    }
                } else {
                    throw new Exception('환불 처리에 실패했습니다. (1)', 701);
                }
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getInvoiceItemData($request)
    {
        $order_num = !empty($request['order_num']) ? $request['order_num'] : '';
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';

        try {
            if (empty($order_num) || empty($franchise_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->studentRefundInfo->getInvoiceItemData($order_num, $franchise_idx);



            $payStateListSql = "SELECT code_num2, code_name FROM codeM WHERE code_num1 = '45' AND code_num2 <> ''";
            $payStateList = $this->studentRefundInfo->db->sqlRowArr($payStateListSql);

            $selectOption = "<option value=\"\">선택</option>";

            if ($result) {
                $tbl = "";
                foreach ($result as $key => $val) {
                    $readonlyOption = "readonly";

                    if ($val['order_state'] == '04') {
                        $readonlyOption = "";
                    }
                    $tbl .= "<tr class=\"align-middle text-center\" data-item-idx=\"" . $val['invoice_idx'] . "\">
                    <td>" . $val['receipt_name'] . "</td>
                    <td>" . $val['order_quantity'] . "</td>
                    <td class=\"text-end\">" . number_format($val['order_money']) . "</td>
                    <td class=\"text-end\">" . number_format($val['unitamt']) . "</td>
                    <td><select class=\"form-select selState\">";
                    foreach ($payStateList as $key2 => $val2) {
                        $selectedOption = '';
                        if ($val['order_state'] == $val2['code_num2']) {
                            $selectedOption = "selected";
                        }
                        if ($val2['code_num2'] == '03' || $val2['code_num2'] == '04') {
                            $selectOption .= "<option value='{$val2['code_num2']}' {$selectedOption}>{$val2['code_name']}</option>";
                        }
                    }
                    $tbl .= $selectOption;
                    $tbl .= "</select></td>
                    <td>
                        <input type=\"text\" class=\"form-control ramt\" value=\"" . $val['refund_money'] . "\" maxlength=\"7\" " . $readonlyOption . " />
                    </td>
                    </tr>";
                    $selectOption = "<option value=\"\">선택</option>";
                    if (!empty($val['refund_etc'])) {
                        $refund_etc = $val['refund_etc'];
                    } else {
                        $refund_etc = '';
                    }
                }
                $result['tbl'] = $tbl;
                $result['refund_etc'] = $refund_etc;
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getPaymentKey($request)
    {
        $order_num = !empty($request['order_num']) ? $request['order_num'] : '';
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';

        try {
            if (empty($order_num) || empty($center_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->studentRefundInfo->getPaymentKey($order_num);
            if (!empty($result)) {
                return $result;
            } else {
                $return_data['msg'] = "결제 정보가 없습니다.";
                return $return_data;
            }
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$studentRefundController = new StudentRefundController();

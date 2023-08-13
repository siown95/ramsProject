<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/StudentFee.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class StudentFeeController extends Controller
{
    private $studentFeeInfo;

    function __construct()
    {
        $this->studentFeeInfo = new StudentFeeInfo();
    }

    public function getSalesChartData($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';

        try {
            if (empty($center_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentFeeInfo->getSalesChartData($center_idx);

            if (!empty($result)) {
                $temp_arr1 = array();
                $temp_arr2 = array();

                $dataset1 = array();
                $dataset2 = array();

                for ($i = 1; $i <= 12; $i++) {
                    $lastYearMonth = date('Y', strtotime("-1 year")) . "-" . sprintf("%02d", $i);
                    $nowYearMonth = date('Y') . "-" . sprintf("%02d", $i);;

                    foreach ($result as $key => $val) {
                        if ($lastYearMonth == $val['ym']) {
                            $temp_arr1[$i] = $val['pay_amount'];
                        }

                        if ($nowYearMonth == $val['ym']) {
                            $temp_arr2[$i] = $val['pay_amount'];
                        }
                    }

                    if (!empty($temp_arr1[$i])) {
                        $dataset1[] = $temp_arr1[$i];
                    } else {
                        $dataset1[] = 0;
                    }

                    if (!empty($temp_arr2[$i])) {
                        $dataset2[] = $temp_arr2[$i];
                    } else {
                        $dataset2[] = 0;
                    }
                }
            }

            $result['dataset1'] = $dataset1;
            $result['dataset2'] = $dataset2;
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadStudent($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $grade = !empty($request['grade']) ? $request['grade'] : '';

        try {
            if (empty($franchise_idx) || empty($grade)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentFeeInfo->loadStudent($franchise_idx, $grade);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $key + 1;
                    if (!empty($val['color_code'])) {
                        $result[$key]['user_name'] = $val['user_name'] . " <span style=\"color:" . $val['color_code'] . ";\"><i class=\"fa-solid fa-circle\"></i></span>";
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

        try {
            if (empty($franchise_idx) || empty($student_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentFeeInfo->loadStudentFeeList($franchise_idx, $student_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadInvoiceDetail($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $order_num = !empty($request['order_num']) ? $request['order_num'] : '';

        try {
            if (empty($franchise_idx) || empty($order_num)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->studentFeeInfo->loadInvoiceDetail($franchise_idx, $order_num);
            $tbl = '';
            $pay_amount = '0';
            if ($result) {
                foreach ($result as $key => $val) {
                    $tbl .= "<tr data-receipt-idx=\"" . $val['receipt_idx'] . "\">
                        <td>" . $val['receipt_name'] . "</td>
                        <td>" . $val['receipt_amount'] . "</td>
                        <td>" . $val['order_quantity'] . "</td>
                        <td>" . $val['order_money'] . "</td>
                        <td><button type=\"button\" class=\"btn btn-sm btn-outline-danger btnFeeCancel\">취소</button></td>
                        </tr>";
                    $pay_amount += $val['order_money'];
                }
                $result['tbl'] = $tbl;
                $result['pay_amount'] = $pay_amount;
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getLessonFeeInfo($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_idx = !empty($request['student_idx']) ? $request['student_idx'] : '';
        $paymonth = !empty($request['paymonth']) ? $request['paymonth'] : '';
        try {
            if (empty($franchise_idx) || empty($student_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentFeeInfo->getLessonFeeInfo($franchise_idx, $student_idx, $paymonth);
            $tbl = '';
            $amt = 0;
            if ($result) {
                foreach ($result as $key => $val) {
                    $tbl .= "<tr data-receipt-idx=\"" . $val['receipt_idx'] . "\">
                    <td>" . $val['receipt_name'] . "&#40;" . $val['code_name'] . "&#41;</td>
                    <td>" . $val['receipt_amount'] . "</td>
                    <td><input class='form-control tct' type='text' maxlength='2' value='" . $val['lesson_count'] . "'/ ></td>
                    <td><input class='form-control tamt' type='text' maxlength='7' value='" . $val['amount'] . "'/ ></td>
                    <td><button type=\"button\" class=\"btn btn-sm btn-outline-danger btnFeeCancel\">취소</button></td>
                    </tr>";
                    $amt += $val['amount'];
                }
                $result['tbl'] = $tbl;
                $result['amt'] = $amt;
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function payInvoiceCheck($request)
    {
        $order_num = !empty($request['order_num']) ? $request['order_num'] : '';
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        try {
            if (empty($franchise_idx) || empty($order_num)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentFeeInfo->payInvoiceCheck($order_num, $franchise_idx);
            if ($result) {
                if (date('Y-m-d', strtotime($result . '+7 Days')) < date('Y-m-d')) {
                    $return_data['msg'] = "수납 처리 후 7일이 경과되면 수정이 불가능합니다. 본사에 문의바랍니다.";
                }
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function payInvoiceInsert($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $teacher_idx = !empty($request['teacher_idx']) ? $request['teacher_idx'] : '';
        $student_idx = !empty($request['student_idx']) ? $request['student_idx'] : '';
        $paymonth = !empty($request['paymonth']) ? $request['paymonth'] : '';
        $paydate = !empty($request['paydate']) ? $request['paydate'] : '';
        $paystate = !empty($request['paystate']) ? $request['paystate'] : '';
        $paytype = !empty($request['paytype']) ? $request['paytype'] : '';
        $payetc = !empty($request['payetc']) ? $request['payetc'] : '';
        $payamount = !empty($request['payamount']) ? $request['payamount'] : '';
        $lists = !empty($request['lists']) ? $request['lists'] : '';

        try {
            if (empty($center_idx) || empty($teacher_idx) || empty($student_idx) || empty($paymonth) || empty($paystate) || empty($payamount) || empty($lists)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $order_num = "i" . date("YmdHis") . "_" . $center_idx . "_" . $student_idx;

            foreach ($lists as $key => $val) {
                $invoice_array = array(
                    "franchise_idx" => !empty($center_idx) ? $center_idx : '',
                    "teacher_idx" => !empty($teacher_idx) ? $teacher_idx : '',
                    "student_idx" => !empty($student_idx) ? $student_idx : '',
                    "order_num" => $order_num,
                    "receipt_idx" => $val[0],
                    "receipt_name" => $val[1],
                    "order_quantity" => $val[2],
                    "order_money" => $val[3],
                    "order_method" => !empty($paytype) ? $paytype : '',
                    "order_state" => !empty($paystate) ? $paystate : '',
                    "order_ym" => !empty($paymonth) ? $paymonth : '',
                    "order_date" => date("Y-m-d"),
                    "pay_date" => !empty($paydate) ? $paydate : '',
                    "pay_memo" => !empty($payetc) ? $payetc : '',
                );
                $this->studentFeeInfo->table_name = "invoiceM";
                $result = $this->studentFeeInfo->insert($invoice_array);
            }

            if ($result) {
                $return_data["msg"] = "원비가 수납되었습니다.";
            } else {
                $return_data["msg"] = "원비 수납에 실패했습니다.";
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function payInvoiceUpdate($request)
    {
        $order_num = !empty($request['order_num']) ? $request['order_num'] : '';
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $teacher_idx = !empty($request['teacher_idx']) ? $request['teacher_idx'] : '';
        $student_idx = !empty($request['student_idx']) ? $request['student_idx'] : '';
        $paystate = !empty($request['paystate']) ? $request['paystate'] : '';

        try {
            if (empty($order_num) || empty($center_idx) || empty($teacher_idx) || empty($student_idx) || empty($paystate)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $params = array(
                "order_state" => $paystate,
                "mod_date" =>  "getdate()"
            );
            $this->studentFeeInfo->table_name = 'invoiceM';
            $this->studentFeeInfo->where_qry = " order_num = '" . $order_num . "'";
            $result = $this->studentFeeInfo->update($params);
            if ($result) {
                $return_data['msg'] = '원비수납 결제상태가 수정 되었습니다.';
            } else {
                throw new Exception('원비수납 결제상태 수정에 실패하였습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getPayData($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_idx = !empty($request['student_idx']) ? $request['student_idx'] : '';
        $paymonth = !empty($request['months']) ? $request['months'] : '';
        try {
            if (empty($center_idx) || empty($student_idx) || empty($paymonth)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentFeeInfo->getPayData($center_idx, $student_idx, $paymonth);
            $result2 = $this->studentFeeInfo->getStudentEtc($center_idx, $student_idx);
            $tbl = "";
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $tbl .= "<tr>
                    <td>" . ($key + 1) . "</td>
                    <td>" . number_format($val['amount']) . "</td>
                    <td>" . $val['order_date'] . "</td>
                    <td>" . $val['code_name'] . "</td>
                    <td>" . $val['user_name'] . "</td>
                    <td class=\"text-start\">" . $val['pay_memo'] . "</td>
                    </tr>";
                }
            }
            if (!empty($result2)) {
                $return_data['color_tag'] = $result2['color_detail'];
                $return_data['memo'] = $result2['user_memo'];
            }
            $return_data['tbl'] = $tbl;
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function monthInvoiceListInsert($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $teacher_idx = !empty($request['teacher_idx']) ? $request['teacher_idx'] : '';
        $month = !empty($request['month']) ? $request['month'] : '';
        try {
            if (empty($center_idx) || empty($teacher_idx) || empty($month)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->studentFeeInfo->getMonthLessonFeeInfo($center_idx, $month);
            if (!empty($result)) {
                $sql = "";
                $cnt = count($result);
                $cnt1 = 0;
                $cnt2 = 0;
                foreach ($result as $key => $val) {
                    $order_num = 'i' . date('YmdHis') . '_' . $center_idx . '_' . $val['student_idx'];
                    $sql = "INSERT INTO invoiceM (franchise_idx, teacher_idx, student_idx, order_num, receipt_idx, receipt_name, order_quantity, order_money, order_state, order_date, order_ym)
                    VALUES ('{$center_idx}','{$teacher_idx}','{$val['student_idx']}','{$order_num}','{$val['receipt_idx']}','{$val['receipt_name']}'
                    ,'{$val['order_quantity']}','{$val['order_money']}','01','" . date('Y-m-d') . "','{$month}')";
                    $result2 = $this->studentFeeInfo->db->execute($sql);
                    if ($result2) $cnt1++;
                    else $cnt2++;
                }
                $return_data['msg'] = '조회된 ' . $cnt . '건의 수업정보를 통해 ' . $cnt1 . '건의 원비수납 정보가 생성되었고, ' . $cnt2 . '건의 원비수납 정보 생성에 실패했습니다.';
                return $return_data;
            } else {
                throw new Exception('수업 정보가 존재하지 않아 수납정보 생성에 실패했습니다.', 701);
            }
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$studentFeeController = new StudentFeeController();

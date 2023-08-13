<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/SalesTeacherInfo.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class SalesTeacherInfoController extends Controller
{
    private $saleTeacherInfo;

    function __construct()
    {
        $this->saleTeacherInfo = new SalesTeacherInfo();
    }

    public function getSalesData($request)
    {
        $franchise_idx  = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';
        $salesmonth = !empty($request['salesmonth']) ? $request['salesmonth'] : '';
        $lesson_class_type = !empty($request['lesson_class_type']) ? $request['lesson_class_type'] : '';
        try {
            if (empty($franchise_idx) || empty($user_idx) || empty($salesmonth) || empty($lesson_class_type)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->saleTeacherInfo->getSalesData($franchise_idx, $user_idx, $salesmonth, $lesson_class_type);
            if (!empty($result)) {
                $tbl = "";
                $totamt1 = 0;
                $totamt2 = 0;
                $stdcnt = 0;
                $tmpamt1 = 0;
                $tmpamt2 = 0;
                $tmp_stdcnt = 0;
                $tmp_idx = '';
                foreach ($result as $key => $val) {
                    if ($tmp_idx != $val['user_no'] && !empty($tmp_idx)) {
                        $tbl .= "<tr class=\"align-middle text-center\">
                        <td colspan=\"3\">합계</td>
                        <td class=\"text-end\">" . number_format($tmp_stdcnt) . " 명</td>
                        <td class=\"text-end\">" . number_format($tmpamt1) . " 원</td>
                        <td class=\"text-end\">" . number_format($tmpamt2) . " 원</td>";
                        if ($tmpamt1 > 0) {
                            $tbl .= "<td class=\"text-end\">" . sprintf("%.2f", $tmpamt2 / $tmpamt1 * 100) . " %</td></tr>";
                        } else {
                            $tbl .= "<td class=\"text-end\">0.00 %</td></tr>";
                        }
                        $tmpamt1 = 0;
                        $tmpamt2 = 0;
                        $tmp_stdcnt = 0;
                    }
                    $tbl .= "<tr class=\"align-middle text-center\">
                    <td>{$val['user_name']}</td>
                    <td>{$val['order_ym']}</td>
                    <td>{$val['lesson_type']}</td>
                    <td class=\"text-end\">" . number_format($val['teach_cnt']) . " 명</td>
                    <td class=\"text-end\">" . number_format($val['edu_fee']) . " 원</td>
                    <td class=\"text-end\">" . number_format($val['edu_fee2']) . " 원</td>
                    <td class=\"text-end\">" . sprintf("%.2f", $val['edu_fee2'] / $val['edu_fee'] * 100) . " %</td>
                    </tr>";
                    $tmp_idx = $val['user_no'];
                    $totamt1 += $val['edu_fee'];
                    $totamt2 += $val['edu_fee2'];
                    $stdcnt += $val['teach_cnt'];
                    $tmpamt1 += $val['edu_fee'];
                    $tmpamt2 += $val['edu_fee2'];
                    $tmp_stdcnt += $val['teach_cnt'];
                }
                $tbl .= "<tr class=\"align-middle text-center\">
                        <td colspan=\"3\">합계</td>
                        <td class=\"text-end\">" . number_format($tmp_stdcnt) . " 명</td>
                        <td class=\"text-end\">" . number_format($tmpamt1) . " 원</td>
                        <td class=\"text-end\">" . number_format($tmpamt2) . " 원</td>";
                if ($tmpamt1 > 0) {
                    $tbl .= "<td class=\"text-end\">" . sprintf("%.2f", $tmpamt2 / $tmpamt1 * 100) . " %</td></tr>";
                } else {
                    $tbl .= "<td class=\"text-end\">0.00 %</td></tr>";
                }
                $tbl .= "<tr class=\"align-middle text-center\">
                <td colspan=\"3\">총 합계</td>
                <td class=\"text-end\">" . number_format($stdcnt) . " 명</td>
                <td class=\"text-end\">" . number_format($totamt1) . " 원</td>
                <td class=\"text-end\">" . number_format($totamt2) . " 원</td>
                <td class=\"text-end\">" . sprintf("%.2f", $totamt2 / $totamt1 * 100) . " %</td></tr>";
                $result['tbl'] = $tbl;
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$salesTeacherInfoController = new SalesTeacherInfoController();
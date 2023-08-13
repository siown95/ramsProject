<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Invoice.php";

class InvoiceController extends Controller
{
    private $invoiceInfo;

    function __construct()
    {
        $this->invoiceInfo = new InvoiceInfo();
    }

    public function getSalesChartData($request)
    {
        $center_type = !empty($request['center_type']) ? $request['center_type'] : '';

        try {
            if (empty($center_type)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->invoiceInfo->getSalesChartData($center_type);

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

    public function getSalesInfoChartData($request)
    {
        $center_type = !empty($request['centertype']) ? $request['centertype'] : '';
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $year = !empty($request['year']) ? $request['year'] : '';

        try {
            if (empty($center_type) || empty($year)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->invoiceInfo->getSalesInfoChartData($center_type, $center_idx, $year);

            if (!empty($result)) {
                $temp_arr1 = array();
                $temp_arr2 = array();
                $temp_arr3 = array();
                $temp_arr4 = array();
                $temp_arr5 = array();

                $dataset1 = array();
                $dataset2 = array();
                $dataset3 = array();
                $dataset4 = array();
                $dataset5 = array();

                for ($i = 1; $i <= 12; $i++) {
                    $nowYearMonth = date('Y') . "-" . sprintf("%02d", $i);;

                    foreach ($result as $key => $val) {
                        if ($nowYearMonth == $val['order_ym']) {
                            $temp_arr1[$i] = $val['tot_edu_money'];
                            $temp_arr2[$i] = $val['real_edu_money'];
                            $temp_arr3[$i] = ($val['real_edu_money'] + $val['edu_book_money']);
                            $temp_arr4[$i] = $val['edu_book_money'];
                            $temp_arr5[$i] = round(($val['real_edu_money'] / $val['tot_edu_money'] * 100), 2);
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

                    if (!empty($temp_arr3[$i])) {
                        $dataset3[] = $temp_arr3[$i];
                    } else {
                        $dataset3[] = 0;
                    }

                    if (!empty($temp_arr4[$i])) {
                        $dataset4[] = $temp_arr4[$i];
                    } else {
                        $dataset4[] = 0;
                    }

                    if (!empty($temp_arr5[$i])) {
                        $dataset5[] = $temp_arr5[$i];
                    } else {
                        $dataset5[] = 0;
                    }
                }
            }

            $result['dataset1'] = $dataset1;
            $result['dataset2'] = $dataset2;
            $result['dataset3'] = $dataset3;
            $result['dataset4'] = $dataset4;
            $result['dataset5'] = $dataset5;
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getSalesCenterInfoChartData($request)
    {
        $center_type = !empty($request['centertype']) ? $request['centertype'] : '';
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $year = !empty($request['year']) ? $request['year'] : '';

        try {
            if (empty($center_type) || empty($year)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->invoiceInfo->getSalesCenterInfoChartData($center_type, $center_idx, $year);

            if (!empty($result)) {
                $temp_arr_center_name = array();
                $temp_arr1 = array();
                $temp_arr2 = array();
                $temp_arr3 = array();
                $temp_arr4 = array();
                $temp_arr5 = array();

                $dataset_center_name = array();
                $dataset1 = array();
                $dataset2 = array();
                $dataset3 = array();
                $dataset4 = array();
                $dataset5 = array();

                foreach ($result as $key => $val) {
                    $temp_arr_center_name[$key] = $val['center_name'];
                    $temp_arr1[$key] = $val['tot_edu_money'];
                    $temp_arr2[$key] = $val['real_edu_money'];
                    $temp_arr3[$key] = ($val['real_edu_money'] + $val['edu_book_money']);
                    $temp_arr4[$key] = $val['edu_book_money'];
                    $temp_arr5[$key] = round(($val['real_edu_money'] / $val['tot_edu_money'] * 100), 2);
                }
                for ($i = 0; $i < count($temp_arr_center_name); $i++) {
                    if (!empty($temp_arr_center_name)) {
                        $dataset_center_name[] = $temp_arr_center_name[$i];
                    } else {
                        $dataset_center_name[] = '';
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

                    if (!empty($temp_arr3[$i])) {
                        $dataset3[] = $temp_arr3[$i];
                    } else {
                        $dataset3[] = 0;
                    }

                    if (!empty($temp_arr4[$i])) {
                        $dataset4[] = $temp_arr4[$i];
                    } else {
                        $dataset4[] = 0;
                    }

                    if (!empty($temp_arr5[$i])) {
                        $dataset5[] = $temp_arr5[$i];
                    } else {
                        $dataset5[] = 0;
                    }
                }
            }
            $result['center'] = $dataset_center_name;
            $result['dataset1'] = $dataset1;
            $result['dataset2'] = $dataset2;
            $result['dataset3'] = $dataset3;
            $result['dataset4'] = $dataset4;
            $result['dataset5'] = $dataset5;
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getFranchiseData($request)
    {
        $center_type = !empty($request['centertype']) ? $request['centertype'] : '';
        try {
            if (empty($center_type)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->invoiceInfo->getFranchiseData($center_type);
            if (!empty($result)) {
                $return_data = '<option value="">전체</option>';
                foreach ($result as $key => $val) {
                    $return_data .= "<option value=\"{$val['franchise_idx']}\">{$val['center_name']}</option>";
                }
            }
            $result['options'] = $return_data;
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$invoiceController = new InvoiceController();

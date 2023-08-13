<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/SalesConfirm.php";

class SalesConfirmController extends Controller
{
    private $salesConfirmInfo;

    function __construct()
    {
        $this->salesConfirmInfo = new SalesConfirmInfo();
    }



    public function salesInfoLoad($request)
    {
        $months = !empty($request['months']) ? $request['months'] : '';

        try {
            if (empty($months)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->salesConfirmInfo->salesInfoLoad($months);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['no'] .= $key + 1;
                    $result[$key]['franchise_fee_money'] = ($val['franchise_fee_money'] != 0 && is_numeric($val['franchise_fee_money'])) ? number_format($val['franchise_fee_money']) : 0;
                    $result[$key]['refund_money'] = ($val['refund_money'] != 0 && is_numeric($val['refund_money'])) ? number_format($val['refund_money']) : 0;
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$salesConfirmController = new SalesConfirmController();

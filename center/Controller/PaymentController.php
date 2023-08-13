<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Payment.php";

class PaymentController extends Controller
{
    private $paymentInfo;

    function __construct()
    {
        $this->paymentInfo = new PaymentInfo();
    }

    //결제 키 불러오기
    public function getPaymentKey($request)
    {
        $order_num = !empty($request['order_num']) ? $request['order_num'] : '';
        try {
            if (empty($order_num)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->paymentInfo->getPaymentKey($order_num);
            if (!empty($result)) {
                $return_data['paymentKey'] = $result;
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$paymentController = new PaymentController();

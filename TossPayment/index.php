<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$paymentCmp = new paymentCmp();

$payment_data = $_REQUEST;
// if (empty($payment_data)) {
// }
// $payment_clientKey = $payment_data['clientKey'];
// $payment_clientKey = 'test_ck_jkYG57Eba3GNgnyOW5l8pWDOxmA1';
/* 
-- 주문번호 구분
 i -> 강의, o -> 물품, p -> 포인트 충전, f -> 계속가맹금
-- 결제 수단
'CARD' => '카드', // 일반결제 -> requestPayment / 자동결제 -> requestBillingAuth
'TRANSFER' => '계좌이체',
'MOBILE_PHONE' => '휴대폰',
'VIRTUAL_ACCOUNT' => '가상계좌',
'CULTURE_GIFT_CERTIFICATE' => '문화상품권',
'BOOK_GIFT_CERTIFICATE' => '도서문화상품권',
'GAME_GIFT_CERTIFICATE' => '게임문화상품권'
*/
$payment_order_type = !empty($payment_data['order_type']) ? $payment_data['order_type'] : '';
if ($payment_order_type == 'o' || $payment_order_type == 'p' || $payment_order_type == 'f') {
    $payment_clientKey = $paymentCmp->getClientKey('1');
} else if ($payment_order_type == 'i') {
    $payment_clientKey = $paymentCmp->getClientKey($payment_data['center_idx']);
} else {
}
$payment_orderId = !empty($payment_data['order_num']) ? $payment_data['order_num'] : $payment_order_type . date('YmdHis') . '_' . $payment_data['center_idx'];
$payment_orderName = $payment_data['order_name'];
$payment_method = $payment_data['pay_method'];
$payment_amount = !empty($payment_data['pay_amount']) ? $payment_data['pay_amount'] : 0;
$payment_taxfreeamount = !empty($payment_data['pay_tax_free_amount']) ? $payment_data['pay_tax_free_amount'] : 0;
$_SESSION['payment_order_type'] = $payment_order_type;
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <title></title>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <script src="https://js.tosspayments.com/v1"></script>
</head>
<script>
    const clientKey = '<?= $payment_clientKey ?>';
    const paymentMethod = '<?= $payment_method ?>';
    const paymentAmount = <?= $payment_amount ?>;
    const paymentTaxFreeAmount = <?= $payment_taxfreeamount ?>;
    const tossPayments = TossPayments(clientKey);
    var paymentData = {
        amount: paymentAmount,
        taxFreeAmount: paymentTaxFreeAmount,
        orderId: '<?= $payment_orderId ?>',
        orderName: '<?= $payment_orderName ?>',
        successUrl: window.location.origin + '/TossPayment/success.php',
        failUrl: window.location.origin + '/TossPayment/fail.php',
    }; // 일반결제

    window.addEventListener('DOMContentLoaded', (event) => {
        tossPayments
            .requestPayment(paymentMethod, paymentData) // 일반결제
            .catch(function(error) {
                alert("결제실패\n" + error.message + '\n\n에러코드 : ' + error.code);
                window.close();
            });
    });
</script>

</html>
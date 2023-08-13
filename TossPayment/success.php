<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$paymentCmp = new paymentCmp();
$paymentKey = $_GET['paymentKey'];
$orderId = $_GET['orderId'];
$amount = $_GET['amount'];
$payment_order_type = substr($_GET['orderId'], 0, 1);
if ($payment_order_type == 'o' || $payment_order_type == 'p' || $payment_order_type == 'f') { // 물품주문, 포인트 결제, 가맹금결제
    $secretKey = $paymentCmp->getSecretKey('1');
} else if ($payment_order_type == 'i') { // 원비결제 시 센터번호로
    $secretKey = $paymentCmp->getSecretKey($_SESSION['center_idx']);
} else {
}
// $secretKey = 'test_sk_5mBZ1gQ4YVXWOe9y6693l2KPoqNb';
$url = 'https://api.tosspayments.com/v1/payments/confirm';
$data = ['paymentKey' => $paymentKey, 'orderId' => $orderId, 'amount' => $amount];

$credential = base64_encode($secretKey . ':');
$curlHandle = curl_init($url);
curl_setopt_array($curlHandle, [
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_SSL_VERIFYPEER => FALSE,
    CURLOPT_HTTPHEADER => [
        'Authorization: Basic ' . $credential,
        'Content-Type: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($curlHandle);

$httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
$isSuccess = $httpCode == 200;
$responseJson = json_decode($response);

curl_close($curlHandle);
//결제 정보 처리
if ($isSuccess) {
    $db = new DBCmp();
    $sql = "INSERT INTO payment_logT (paymentKey, orderId, status, orderName, requestedAt, approvedAt, totalAmount, vat, taxFreeAmount, method) VALUES (
        '{$responseJson->paymentKey}', 
        '{$responseJson->orderId}', 
        '{$responseJson->status}', 
        '{$responseJson->orderName}', 
        '{$responseJson->requestedAt}', 
        '{$responseJson->approvedAt}', 
        '{$responseJson->totalAmount}', 
        '" . (empty($responseJson->vat) ? 0 : ($responseJson->vat)) . "', 
        '" . (empty($responseJson->taxFreeAmount) ? 0 : ($responseJson->taxFreeAmount)) . "', 
        '{$responseJson->method}')";
    $result = $db->execute($sql);
    // 결제 승인 처리 후 결제 정보 Update
    // 주문번호 구분 i -> 강의, o -> 물품, p -> 포인트 충전, f -> 계속가맹금
    $order_check = $_SESSION['payment_order_type'];
    unset($_SESSION['payment_order_type']);
    $order_method = $responseJson->method;
    $pay_date = date('Y-m-d');
    $sql = "";
    if ($order_method == '카드') {
        $order_method = '01';
    } else if ($order_method == '계좌이체') {
        $order_method = '02';
    }
    if ($order_check == 'i') {
        $sql = "UPDATE invoiceM SET order_method = '{$order_method}', order_state = '02', pay_date = '{$pay_date}', mod_date = GETDATE() WHERE order_num = '{$orderId}'";
    } else if ($order_check == 'o') {
        $sql = "UPDATE order_goodsT SET order_method = '{$order_method}', order_state = '02',  pay_date = '{$pay_date}' WHERE order_num = '{$orderId}'";
    } else if ($order_check == 'p') {
        $sql = "UPDATE franchiseM SET point = point + {$responseJson->totalAmount}, mod_date = GETDATE() WHERE franchise_idx = '{$_SESSION["center_idx"]}'";
        $result = $db->execute($sql);
        $sql = "INSERT INTO invoice_pointM (franchise_idx, user_no, user_name, order_num, order_money, order_method, order_state, order_date, pay_date) VALUES (
            '{$_SESSION["center_idx"]}',
            '{$_SESSION["logged_no"]}',
            '{$_SESSION["logged_name"]}',
            '{$responseJson->orderId}',
            '{$responseJson->totalAmount}',
            '{$order_method}',
            '02',
            '" . date('Y-m-d') . "',
            '" . date('Y-m-d') . "'
        )";
    } else if ($order_check == 'f') {
        $sql = "UPDATE franchise_feeM SET franchise_fee_method = '{$order_method}', franchise_fee_state = '02',  franchise_fee_date = '{$pay_date}', mod_date = GETDATE() WHERE order_num = '{$orderId}'";
    }
    $result = $db->execute($sql);
} else {
    $code = $responseJson->code;
    $message = $responseJson->message;
}
// 새 창 닫기
?>
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        <?php
        if ($isSuccess) {
            echo 'alert("결제되었습니다.");';
        } else {
            echo 'alert("' . $message . '\n에러코드 : ' . $code . '");';
        }
        ?>
        opener.location.reload();
        window.close();
    });
</script>
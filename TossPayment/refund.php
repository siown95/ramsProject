<?php
// 환불 결제 취소
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$paymentCmp = new paymentCmp();
$paymentKey = $_REQUEST['paymentKey'];
$cancelReason = $_REQUEST['cancelReason'];
$cancelFlag = $_REQUEST['cancelFlag'];
$cancelAmount = $_REQUEST['cancelAmount'];
if ($cancelFlag == 's') { // 원비환불 체크 - 원비 환불의 경우 센터 시크릿 키로 환불처리
    $secretKey = 'test_sk_5mBZ1gQ4YVXWOe9y6693l2KPoqNb';
    $secretKey = $paymentCmp->getSecretKey($_SESSION['center_idx']);
} else { // 물품, 가맹금, 포인트 환불 - 본사 시크릿 키로 환불처리
    $secretKey = 'test_sk_5mBZ1gQ4YVXWOe9y6693l2KPoqNb';
    $secretKey = $paymentCmp->getSecretKey('1');
}
$url = "https://api.tosspayments.com/v1/payments/" . $paymentKey . "/cancel";
if (empty($cancelAmount)) {
    $data = [
        'cancelReason' => $cancelReason
    ];
} else {
    $data = [
        'cancelReason' => $cancelReason,
        'cancelAmount' => $cancelAmount
    ];
}
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
    require_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
    $db = new DBCmp();
    $sql = "UPDATE payment_logT SET 
    status = '{$responseJson->status}',
    cancels_cancelReason = '{$responseJson->cancels->cancelReason}',
    cancels_cancelAmount = '{$responseJson->cancels->cancelAmount}',
    cancels_taxFreeAmount = '{$responseJson->cancels->taxFreeAmount}',
    cancels_refundableAmount = '{$responseJson->cancels->refundableAmount}',
    cancels_transactionKey = '{$responseJson->cancels->transactionKey}',
    cancels_canceledAt = '{$responseJson->cancels->canceledAt}'
    WHERE paymentKey = '{$paymentKey}'";
    $result = $db->execute($sql);
    // 결제 승인 처리 후 결제 정보 Update
    // 주문번호 구분 i -> 강의, o -> 물품, p -> 포인트 충전, f -> 계속가맹금
    $ori_amount = $responseJson->totalAmount; // 결제금액
    $cancel_amount = $responseJson->cancels->cancelAmount; // 환불금액
    $refund_date = date('Y-m-d'); // 환불일자
    $order_check = substr($responseJson->orderId, 0, 1); // 분기 처리 테이블 구분
    if ($cancel_amount == $ori_amount) { // 전체환불
        if ($cancelFlag == 's') {
            if ($order_check == 'i' || $order_check == 'f' || $order_check == 'p') {
                $order_state = '99';
            } else if ($order_check == 'o') {
                $order_state = '98';
            }
        } else if ($cancelFlag == 'h') {
            if ($order_check == 'i' || $order_check == 'f' || $order_check == 'p') {
                $order_state = '03';
            } else if ($order_check == 'o') {
                $order_state = '99';
            }
        }
    } else if ($cancel_amount < $ori_amount) { // 부분환불
        if ($cancelFlag == 's') {
            if ($order_check == 'i' || $order_check == 'f' || $order_check == 'p') {
                $order_state = '99';
            } else if ($order_check == 'o') {
                $order_state = '98';
            }
        } else if ($cancelFlag == 'h') {
            if ($order_check == 'i' || $order_check == 'f' || $order_check == 'p') {
                $order_state = '04';
            } else if ($order_check == 'o') {
                $order_state = '99';
            }
        }
    }
    if ($order_check == 'i') { // 원비
        $sql = "UPDATE invoiceM SET order_state = '{$order_state}', refund_date = '{$refund_date}', mod_date = GETDATE() WHERE order_num = '{$responseJson->orderId}'";
    } else if ($order_check == 'o') { // 물품
        $sql = "UPDATE order_goodsT SET order_state = '{$order_state}', refund_date = '{$refund_date}', refund_money = order_money WHERE order_num = '{$responseJson->orderId}'";
    } else if ($order_check == 'p') { // 포인트
        $sql = "UPDATE franchiseM SET point = point - {$responseJson->cancels->cancelAmount}, mod_date = GETDATE() WHERE franchise_idx = '{$_SESSION["center_idx"]}'";
        $result = $db->execute($sql);
        $sql = "UPDATE invoice_pointM SET order_state = '99' WHERE order_num = '{$responseJson->orderId}'";
    } else if ($order_check == 'f') { // 가맹금
        $sql = "UPDATE franchise_feeM SET franchise_fee_state = '02', refund_money = '{$responseJson->cancels->cancelAmount}', refund_date = '{$refund_date}', mod_date = GETDATE()
        WHERE order_num = '{$responseJson->orderId}'";
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
            if ($cancelFlag == 's') {
                echo 'alert("결제가 취소되었습니다.");';
            } else {
                echo 'alert("환불 처리되었습니다.");';
            }
        } else {
            echo 'alert("' . $message . '\n에러코드 : ' . $code . '");';
        }
        ?>
        opener.location.reload();
        window.close();
    });
</script>
<?php

header("Content-Type: application/json; charset=utf-8");
$response = array();
$count = rand(0, 10);

$ser = $_SERVER;

$response['count'] = $count;
if ($count > 0) {
    for ($i = 0; $i < $count; $i++) {
        $response['data'][] .= $i;
    }
    echo json_encode($response);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "조회된 값이 없습니다."));
}
$auth = explode(" ",$_SERVER['HTTP_AUTHORIZATION']);
echo end($auth);
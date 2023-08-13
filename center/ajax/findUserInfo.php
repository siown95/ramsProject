<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/Mail.php";

$status = false;

$jsonResult = array(
    'success' => $status,
    'msg'     => $return_data['msg']
);

echo json_encode($jsonResult);
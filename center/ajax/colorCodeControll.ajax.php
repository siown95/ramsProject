<?php
if(empty($_POST)){
    header('HTTP/2 403 Forbidden');
    exit;
}
require_once $_SERVER['DOCUMENT_ROOT']."/common/dbClass.php";
$db = new DBCmp();

include_once $_SERVER['DOCUMENT_ROOT']."/center/Controller/ColorCodeController.php";
$result = $colorCodeController->{$_POST['action']}($_POST);

$jsonResult = array (
    'success' => $result['error'] ? false : true,
    'data'    => !$result['error'] ? $result  : null,
    'msg'     => $result['msg']
);

echo json_encode($jsonResult);
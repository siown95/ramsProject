<?php
if(empty($_POST)){
    header('HTTP/2 403 Forbidden');
    exit;
}
include_once $_SERVER['DOCUMENT_ROOT']."/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT']."/center/Controller/MsgGroupController.php";
$result = $msgGroupController->{$_POST['action']}($_POST);

$jsonResult = array (
    'success' => $result['error'] ? false : true,
    'data'    => !$result['error'] ? $result  : null,
    'msg'     => $result['msg']
);

echo json_encode($jsonResult);
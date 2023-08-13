<?php
if(empty($_POST)){
    header('HTTP/2 403 Forbidden');
    exit;
}
include_once $_SERVER['DOCUMENT_ROOT']."/common/dbClass.php";
include_once $_SERVER['DOCUMENT_ROOT']."/center/student/Controller/BookController.php";

$db = new DBCmp();
$bookController = new BookController();

$category1 = !empty($_POST['category1']) ? $_POST['category1'] : '';
$category2 = !empty($_POST['category2']) ? $_POST['category2'] : '';
$position = !empty($_POST['position']) ? $_POST['position'] : '';
$select_option = "<option value=\"\">선택</option>";

if($position == '2'){
    $result = $bookController->bookCategory($position, $category1);
    if(!empty($result)){
        foreach($result as $key => $val){
            $select_option .= "<option value=\"".$val['code_num2']."\">".$val['code_name']."</option>";
        }
    }
}else{
    $result = $bookController->bookCategory($position, $category1, $category2);
    if(!empty($result)){
        foreach($result as $key => $val){
            $select_option .= "<option value=\"".$val['code_num3']."\">".$val['detail']."</option>";
        }
    }
}

$jsonResult = array(
    'success' => true,
    'data'    => $select_option
);

echo json_encode($jsonResult);
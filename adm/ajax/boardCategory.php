<?php
if(empty($_POST)){
    header('HTTP/2 403 Forbidden');
    exit;
}
include_once $_SERVER['DOCUMENT_ROOT']."/common/dbClass.php";
$db = new DBCmp();

$category1 = !empty($_POST['category1']) ? $_POST['category1'] : '';



$option = "<option value=''>선택</option>";
if(!empty($category1)){
    $sql =  "select code_num3, detail from codem where code_num1 = '71' and code_num2 = '".$category1."' and code_num3 <> ''";
    $category2 = $db->sqlRowArr($sql);

    if(!empty($category2)){
        foreach($category2 as $key => $val){
            $option .= "<option value=".$val['code_num3'].">".$val['detail']."</option>";
        }
    }
}

$result['data'] = $option;

echo json_encode($result);
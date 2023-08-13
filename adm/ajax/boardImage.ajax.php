<?php
if(empty($_POST)){
    header('HTTP/2 403 Forbidden');
    exit;
}
$uploadBase = $_SERVER['DOCUMENT_ROOT'] . "/files/" . $_POST['targetFolder'];
$urlBase = "files/" . $_POST['targetFolder'];

$result = array();

foreach ($_FILES['files']['name'] as $f => $name) {
    $name = $_FILES['files']['name'][$f];
    $exploded_file = strtolower(substr(strrchr($name, '.'), 1));

    //변경할 파일명(중복되지 않게 처리하기 위해 파일명을 변경해 줍니다.)
    $thisName = date("YmdHis", time()) . "." . $exploded_file;

    //업로드 파일(업로드 경로/변경할 이미지 파일명)
    $uploadFile = $uploadBase . "/" . $thisName;

    if (copy($_FILES['files']['tmp_name'][$f], $uploadFile)) {
        //파일을 업로드 폴더로 옮겨준후 $result 에 해당 경로를 넣어줍니다.
        array_push($result, "/" . $urlBase . "/" . $thisName);
    }
}
echo json_encode($result);
exit;
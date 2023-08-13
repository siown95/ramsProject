<?php
if(empty($_POST)){
    header('HTTP/2 403 Forbidden');
    exit;
}
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";

$db = new DBCmp();

include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/ReportController.php";
$reportController = new $reportController();

$user_no = !empty($_POST['user_no']) ? $_POST['user_no'] : '';
$report_idx = !empty($_POST['report_idx']) ? $_POST['report_idx'] : '';

if (empty($user_no) || empty($report_idx)) {
    $jsonResult = array(
        'success' => false,
        'msg' => '로드에 실패하였습니다.'
    );
    echo json_encode($jsonResult);
    exit;
}

$data['pre_data'] = '';
$data['report_data'] = '';

$report_data = $reportController->selectReportData($user_no, $report_idx);

if (!empty($report_data['pre_report_data'])) {
    for ($i = 1; $i <= count($report_data['pre_report_data']); $i++) {
        $data['pre_data'] .= "
            <div class=\"input-group\">
                <input type=\"text\" class=\"form-control\" value=\"" . $report_data['pre_report_data'][$i]["title" . $i . ""] . "\" disabled>
            </div>
            <div class=\"input-group mb-2\">
                <textarea id=\"txtLastContents" . $i . "\" class=\"form-control bg-white\" rows=\"4\" disabled>" . $report_data['pre_report_data'][$i]["content" . $i . ""] . "</textarea>
            </div>";
    }
}

if (!empty($report_data['report_data'])) {
    for ($i = 1; $i <= count($report_data['report_data']); $i++) {
        $data['report_data'] .= "
            <div class=\"input-group\">
                <input type=\"text\" class=\"form-control\" value=\"" . $report_data['report_data'][$i]["title" . $i . ""] . "\" disabled>
            </div>
            <div class=\"input-group mb-2\">
                <textarea id=\"txtLastContents" . $i . "\"  class=\"form-control bg-white\" name=\"origin_contents_val\" rows=\"4\">" . $report_data['report_data'][$i]["content" . $i . ""] . "</textarea>
            </div>";
    }
}

$jsonResult = array(
    'success' => true,
    'data'    => $data
);

echo json_encode($jsonResult);
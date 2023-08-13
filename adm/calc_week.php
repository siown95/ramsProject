<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
// 선택일 값
// $signupdate = '2024-01-01';
$signupdate = $_POST['week'];
$signupweek = sprintf("%d", date("W", strtotime($signupdate)));
$signupday = sprintf("%d", date("w", strtotime($signupdate)));
$year = date("Y", strtotime($signupdate));
if ($signupweek == 1 && $signupday == 1) {
    $year = $year + 1;
} else {
    $year = $year;
}
$sql = "SELECT COUNT(0) FROM weekT WHERE weekYear = '{$year}'";
$result = $db->sqlRowOne($sql);
if ($result > 1) {
	$data['msg'] = '이미 선택하신 년도의 주차가 존재합니다.';
	echo json_encode($data);
	exit;
}

$week_chk = true;
$temp_month = '';
$i = 1;
while (true) {
	$temp_month = date('m', strtotime($signupdate));
	$data[] = array(
		"year"  => date('Y', strtotime($signupdate)),
		"week"  => $signupweek,
		"weekcount"  => $i,
		"month"  => date('m', strtotime($signupdate)),
		"start_date" => date('Y-m-d', strtotime($signupdate)),
		"end_date" => date('Y-m-d', strtotime("+6 Days" . $signupdate)),
		"detail" => ''
	);
	$signupweek++;
	$signupdate = date("Y-m-d", strtotime("+7 Days" . $signupdate));
	$signupweek = sprintf("%d", date("W", strtotime($signupdate)));
	if ($signupweek == 1) {
		$data[] = array(
			"year"  => date('Y', strtotime("+1 Years" . $signupdate)),
			"week"  => $signupweek,
			"weekcount"  => '1',
			"month"  => date('m', strtotime("+1 Months" . $signupdate)),
			"start_date" => date('Y-m-d', strtotime($signupdate)),
			"end_date" => date('Y-m-d', strtotime("+6 Days" . $signupdate)),
			"detail" => ''
		);
		break;
	}
	$i++;
	if ($temp_month !== date('m', strtotime($signupdate))) {
		$i = 1;
	}
}

foreach ($data as $key => $val) {
	$sql = "INSERT INTO weekT (weekYear,weekMonth,weekCount,weekStartDate,weekEndDate,weekDetail) VALUES
	( '{$data[$key]["year"]}'
	, '{$data[$key]["month"]}'
	, '{$data[$key]["weekcount"]}'
	, '{$data[$key]["start_date"]}'
	, '{$data[$key]["end_date"]}'
	, '{$data[$key]["detail"]}')";

	$result = $db->execute($sql);
}

if ($result) {
	echo json_encode($data);
}

<?php
$grade = $_POST['grade'];
$flag = $_POST['flag'];
$answer1 = $_POST['answer1'];
$answer2 = $_POST['answer2'];
$answer3 = $_POST['answer3'];
$answer4 = $_POST['answer4'];
$answer5 = $_POST['answer5'];
$answer6 = $_POST['answer6'];
if (empty($grade) || empty($flag)) {
    header('HTTP/2 403 Forbidden');
    exit;
}
if ($flag == 'r') {
    if (empty($answer1) || empty($answer2) || empty($answer3) || empty($answer4) || empty($answer5) || empty($answer6)) {
        header('HTTP/2 403 Forbidden');
        exit;
    } else {
        $answer_arr = array($answer1, $answer2, $answer3, $answer4, $answer5, $answer6);
        $correct_arr = array();
        $result_txt = "";

        if ($grade == '1') {
            $correct_arr = array(2, 1, 3, 2, 1, 4);
        } else if ($grade == '2') {
            $correct_arr = array(2, 3, 3, 4, 1, 4);
        } else if ($grade == '3') {
            $correct_arr = array(4, 3, 1, 4, 2, 2);
        } else if ($grade == '4') {
            $correct_arr = array(2, 3, 3, 1, 3, 4);
        } else if ($grade == '5') {
            $correct_arr = array(3, 3, 3, 1, 1, 4);
        } else if ($grade == '6') {
            $correct_arr = array(1, 4, 2, 1, 2, 4);
        } else {
            header('HTTP/2 403 Forbidden');
            exit;
        }
        $cnt = 0;
        for ($i = 0; $i < 6; $i++) {
            if ($correct_arr[$i] == $answer_arr[$i]) {
                $cnt++;
            }
        }

        if ($cnt == 6) {
            $result_txt = "글을 읽고 이해하는 능력이 월등합니다.";
        } else if ($cnt == 5) {
            $result_txt = "글을 읽고 이해나는 능력이 우수합니다.";
        } else if ($cnt == 4) {
            $result_txt = "글을 읽고 이해하는 능력이 보통수준입니다.";
        } else if ($cnt == 3) {
            $result_txt = "글을 읽고 이해하는 능력이 부족합니다.";
        } else if ($cnt == 2) {
            $result_txt = "글을 읽고 이해하는 능력이 매우 부족합니다.";
        } else if ($cnt == 1) {
            $result_txt = "글을 읽고 이해하는 능력이 전혀 없습니다.";
        } else {
            $result_txt = "글의 주제에 맞는 글쓰기 역량이 매우 부족합니다.";
        }
        // echo "책읽기 진단평가 결과<br>6 문제중 " . $cnt . " 문제 정답! <br>" . $result_txt;
    }
} else if ($flag == 'w') {
    if (empty($answer1) || empty($answer2) || empty($answer3) || empty($answer4)) {
        header('HTTP/2 403 Forbidden');
        exit;
    } else {
        $answer_arr = array($answer1, $answer2, $answer3, $answer4);
        $correct_arr = array();
        $result_txt = "";

        if (($grade == '1' || $grade == '2')) {
            $correct_arr = array(3, 1, 1, 3);
        } else if (($grade == '3' || $grade == '4')) {
            $correct_arr = array(1, 1, 3, 1);
        } else if (($grade == '5' || $grade == '6')) {
            $correct_arr = array(3, 1, 3, 2);
        } else {
            header('HTTP/2 403 Forbidden');
            exit;
        }
        $cnt = 0;
        for ($i = 0; $i < 4; $i++) {
            if ($correct_arr[$i] == $answer_arr[$i]) {
                $cnt++;
            }
        }

        if ($cnt == 4) {
            $result_txt = "글의 주제에 맞는 글쓰기 역량이 매우 우수합니다.";
        } else if ($cnt == 3) {
            $result_txt = "글의 주제에 맞는 글쓰기 역량이 우수합니다.";
        } else if ($cnt == 2) {
            $result_txt = "글의 주제에 맞는 글쓰기 역량이 부족합니다.";
        } else if ($cnt == 1) {
            $result_txt = "글의 주제에 맞는 글쓰기 역량이 매우 부족합니다.";
        } else {
            $result_txt = "글의 주제에 맞는 글쓰기 역량이 매우 부족합니다.";
        }
        // echo $result_txt . "<br>" . $cnt . "/4";
    }
} else {
    header('HTTP/2 403 Forbidden');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 진단정답</title>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/css/styles.css" />
    <?php include_once $_SERVER["DOCUMENT_ROOT"] . "/common/common_script2.php" ?>
</head>

<body>
    <div class="container">
        <div class="card border-left-primary shadow mt-2">
            <div class="card-header">
                <h6><?php echo $flag == 'r' ? '책읽기' : '글쓰기';  ?> 진단평가 결과</h6>
            </div>
            <div class="card-body">
                <?php echo $flag == 'r' ? '6' : '4';  ?> 문제중 <?= $cnt ?> 문제 정답!<br>
                <h6><?= $result_txt ?></h6><br>
                <p><?php
                    echo $flag == 'r' ? '책읽기와 글쓰기 전문 리딩엠에서 정독/지속독/다양독/다량독을 할 수 있도록 해주시면 우리 아이의 변화를 눈으로 확인할 수 있습니다.' : '책읽기와 글쓰기 전문 리딩엠에서 자신의 생각을 글로 충분히 드러내는 변화를 눈으로 확인할 수 있습니다.';
                    ?></p>
            </div>
            <div class="input-group justify-content-end mb-2">
                <div class="form-inline form-check-inline me-2">
                    <a href="/" class="btn btn-outline-primary">처음으로</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
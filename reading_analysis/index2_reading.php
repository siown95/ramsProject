<?php
$grade = $_GET['grade'];
if (empty($grade)) {
    header("Location:https://" . $_SERVER['HTTP_HOST']);
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

    <title>리딩엠 RAMS - 책읽기 진단</title>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/css/styles.css" />
    <?php include_once $_SERVER["DOCUMENT_ROOT"] . "/common/common_script2.php" ?>
</head>

<body>
    <div class="container">
        <div class="card border-left-primary shadow mt-2">
            <div class="card-header">
                <h6>책읽기진단 - 초<?= $grade ?></h6>
            </div>
            <div class="card-body">
                <?php
                include_once $_SERVER['DOCUMENT_ROOT'] . "/reading_analysis/reading_question.php";
                echo $form;
                ?>
            </div>
        </div>
    </div>
</body>

</html>
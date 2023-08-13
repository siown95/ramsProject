<?php
include_once $_SERVER['DOCUMENT_ROOT']."/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT']."/common/commonClass.php";

$infoClassCmp = new infoClassCmp();
$centerList = $infoClassCmp->getCenterList();
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS</title>
    <link rel="icon" href="/favicon.ico?v=1" type="image/x-icon" />
    <!-- css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" integrity="sha512-GQGU0fMMi238uA+a/bdWJfpUGKUkBdgfFdgBm72SUQ6BeyWjoY/ton0tEjH+OSH9iP4Dfh+7HM0I9f5eR0L/4w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/css/styles.css" />
    <link rel="stylesheet" href="/css/main_styles.css" />
    <!-- script -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js" integrity="sha512-pax4MlgXjHEPfCwcJLQhigY7+N8rt6bVvWLFyUMuxShv170X53TRzGPmPkZmGBhk+jikR8WBM4yl7A9WMHHqvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body class="bg-main-background">
    <div class="container-fluid">
        <div class="container mt-3">
            <main>
                <div class="row">
                    <?php
                    foreach ($centerList as $key => $val) {
                    ?>
                        <div class="col-3">
                            <div class="card border-left-primary shadow text-center mt-2">
                                <div class="card-header">
                                    <img src="img/<?= $val['center_eng_name'] ?>.png" class="img-thumbnail" alt="">
                                </div>
                                <div class="card-body">
                                    <a href="<?= "/" . $val['center_eng_name']  ?>/login.html" class="btn btn-outline-primary me-2">로그인</a>
                                    <a href="<?= "/" . $val['center_eng_name']  ?>/register.html" class="btn btn-outline-primary">회원가입</a>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS - 모니터링</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <?php
    $stat = "adm";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/common/common_script.php";
    ?>
</head>

<body class="sb-nav-fixed size-14">
    <!-- header navbar -->
    <?php include_once('navbar.html'); ?>
    <div id="layoutSidenav">
        <!-- sidebar -->
        <?php include_once('sidebar.html'); ?>

        <div id="Maincontent">
            <main id="monitor">

            </main>
            <!-- footer -->
            <?php
            include_once('footer.html');
            ?>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#monitor").load("/common/monitor.php");

            // let interval = 10000;
            // let timer = setTimeout(function tick() {
            //     $("#monitor").empty();
            //     $("#monitor").load("/common/monitor.php");
            //     timer = setTimeout(tick, interval);
            // }, interval);
        });
    </script>
</body>

</html>
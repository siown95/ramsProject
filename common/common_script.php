<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/_config/adm_check.php";

if (!isset($db)) {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
    $db = new DBCmp();
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$menuClassCmp = new menuClassCmp();

$request_uri = $_SERVER['REQUEST_URI'];
$og_info = $menuClassCmp->getMetaInfo($request_uri);

$mobile = preg_match('/iPhone|iPod|BlackBerry|Android|Windows CE|LG|MOT|SAMSUNG|SonyEricsson/', $_SERVER['HTTP_USER_AGENT']) ? '0.35' : '1';
?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=<?= $mobile ?>, shrink-to-fit=no">
<meta name="robots" content="none" />
<meta property='og:type' content='website' />
<meta property='og:image' content='<?= $og_info['og_image'] ?>' />
<meta property='og:url' content='<?= $_SERVER['HTTP_HOST'] ?>' />
<meta property='og:title' content='<?= $og_info['og_title'] ?>' />

<title>리딩엠 RAMS<?= !empty($og_info['og_title']) ? " - " . $og_info['og_title'] : '' ?></title>
<link rel="icon" href="img/favicon.ico" type="image/x-icon" />
<?php if ($stat == 'adm') { ?>
    <!-- css -->
    <link rel="stylesheet" href="/css/styles.css?v=<?= date("YmdHis") ?>" />
    <!-- script -->
    <script type="text/javascript" src="/adm/js/scripts.js?v=<?= date("YmdHis") ?>" type="text/javascript"></script>
<?php } else { ?>
    <!-- css -->
    <link rel="stylesheet" href="/center/css/styles.css?v=<?= date("YmdHis") ?>" />
    <!-- script -->
<?php } ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/1.13.4/dataTables.bootstrap5.min.css" integrity="sha512-zY8EbjNubt5sVVeNIxLQuU6lrDn0zYpaxCtS6mBBaqQREH1ZNQLdUxhHZjPaZhrw1CbEZkNdShEbIInJxzs9dQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.css" integrity="sha512-YdYyWQf8AS4WSB0WWdc3FbQ3Ypdm0QCWD2k4hgfqbQbRCJBEgX0iAegkl2S1Evma5ImaVXLBeUkIlP6hQ1eYKQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" integrity="sha512-ZbehZMIlGA8CTIOtdE+M81uj3mrcgyrh6ZFeG33A4FHECakGrOsTPlPQ8ijjLkxgImrdmSVUHn1j+ApjodYZow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js" integrity="sha512-RCgrAvvoLpP7KVgTkTctrUdv7C6t7Un3p1iaoPr1++3pybCyCsCZZN7QEHMZTcJTmcJ7jzexTO+eFpHk4OCFAg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js" integrity="sha512-i9cEfJwUwViEPFKdC1enz4ZRGBj8YQo6QByFTF92YXHi7waCqyexvRD75S5NVTsSiTv7rKWqG9Y5eFxmRsOn0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.3.0/chart.umd.min.js" integrity="sha512-TJ7U6JRJx5IpyvvO9atNnBzwJIoZDaQnQhb0Wmw32Rj5BQHAmJG16WzaJbDns2Wk5VG6gMt4MytZApZG47rCdg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net/1.13.4/jquery.dataTables.min.js" integrity="sha512-8mDDicdS5IeRnHI+un7skH0RJoB2QMMJmORCHizlxul5jgf9tSWJwpE1eSl/2a9xFQYGOwXLr0Lh5SJPnL4IOw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-bs5/1.13.4/dataTables.bootstrap5.min.js" integrity="sha512-KFdmxVdAssPxrj4mZh1c01AbGXMAmXmBsO4Tc/GG5+kNLqitTfUBpDHicyDwF7CaFV+pN1r808IOK+vHzWB8gw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js" integrity="sha512-lVkQNgKabKsM1DA/qbhJRFQU8TuwkLF2vSN3iU/c7+iayKs08Y8GXqfFxxTZr1IcpMovXnf2N/ZZoMgmZep1YQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/lang/summernote-ko-KR.min.js" integrity="sha512-Zg4LEmUTxIodfMffiwHk5HUeapoVo2VTSGZS5q6ttOMseXr/ZbkiBgV2lyds3UQFPAX05AlF8RIpszT3l7BXKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php if ($stat != 'adm') { ?>
    <link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css" />
    <script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" integrity="sha512-+H4iLjY3JsKiF2V6N366in5IQHj2uEsGV7Pp/GRcm0fn76aPAk5V8xB6n8fQhhSonTqTXs/klFz4D0GIn6Br9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php } ?>
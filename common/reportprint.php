<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

$report_idx = !empty($_POST['report_idx']) ? $_POST['report_idx'] : '';
$user_no = !empty($_POST['user_no']) ? $_POST['user_no'] : '';
$division = !empty($_POST['division']) ? $_POST['division'] : '';
$franchise_idx = !empty($_POST['franchise_idx']) ? $_POST['franchise_idx'] : '';

if (empty($report_idx) || empty($division) || empty($user_no)) {
    echo "<script> alert('잘못된 접근입니다.'); window.close(); </script>";
    exit;
}

$reportCmp = new reportCmp();

$report_data = $reportCmp->getReport($report_idx, $division, $user_no, $franchise_idx);


$title = array();
$lastTitle = array();
$lastContents = array();
$Contents = array();

if (!empty($report_data)) {
    $writer = $report_data['report_data']['user_name'];
    $regdate = $report_data['report_data']['reg_date'];

    if (!empty($report_data['report_data'])) {
        for ($i = 1; $i <= 10; $i++) {
            if (!empty($report_data['report_data'])) {
                $title[] .= $report_data['report_data']["title" . $i . ""];
                $Contents[] .= $report_data['report_data']["content" . $i . ""];
            }
        }
    }

    if (!empty($report_data['pre_report_data'])) {
        for ($i = 1; $i <= 10; $i++) {
            if (!empty($report_data['pre_report_data'])) {
                $lastTitle[] .= $report_data['pre_report_data']["title" . $i . ""];
                $lastContents[] .= $report_data['pre_report_data']["content" . $i . ""];
            }
        }
    }
}

$lessonCount = 10;
$studentCount = 20;

$max = (count(array_filter($lastTitle)) >= count(array_filter($title))) ? count(array_filter($lastTitle)) : count(array_filter($title));
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    #printPage {
        page-break-before: always;
    }
</style>
<div id="printPage" class="container-fluid">
    <div class="container mt-3">
        <table class="table table-bordered">
            <thead class="align-middle text-center">
                <tr>
                    <th width="25%">담당</th>
                    <td width="25%"><?= $writer ?></td>
                    <th width="25%">작성일시</th>
                    <td width="25%"><?= $regdate ?></td>
                </tr>
                <?php if ($division == 'center') { ?>
                    <tr>
                        <th width="25%">수업시수</th>
                        <td width="25%"><?= $lessonCount ?></td>
                        <th width="25%">지도학생 수</th>
                        <td width="25%"><?= $studentCount ?></td>
                    </tr>
                <?php } ?>
            </thead>
        </table>
        <br>

        <table class="table table-bordered print-size">
            <thead class="align-middle text-center">
                <tr>
                    <td colspan="2" width="50%">이전 업무보고</td>
                    <td colspan="2" width="50%">업무계획</td>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < $max; $i++) {
                ?>
                    <tr style="font-size: 12px;">
                        <th width="10%"><?= $lastTitle[$i] ?></th>
                        <td width="40%"><?= $lastContents[$i] ?></td>
                        <th width="10%"><?= $title[$i] ?></th>
                        <td width="40%"><?= $Contents[$i] ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js" integrity="sha512-i9cEfJwUwViEPFKdC1enz4ZRGBj8YQo6QByFTF92YXHi7waCqyexvRD75S5NVTsSiTv7rKWqG9Y5eFxmRsOn0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.print();
    });

    var after = function() {
        window.close();
    }

    window.onafterprint = after;
</script>
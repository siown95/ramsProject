<?php
if (empty($_POST)) {
    header('HTTP/2 403 Forbidden');
    exit;
}
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER["DOCUMENT_ROOT"] . "/common/function.php";

$franchise_idx = !empty($_POST['txtpay_fidx']) ? $_POST['txtpay_fidx'] : '';
$student_idx = !empty($_POST['txtpay_sidx']) ? $_POST['txtpay_sidx'] : '';
$certificateYear = !empty($_POST['txtcYear']) ? $_POST['txtcYear'] : '';
$dlt = !empty($_POST['txtDayofLessonTime']) ? $_POST['txtDayofLessonTime'] : '';
$wlt = !empty($_POST['txtWeekofLessonTime']) ? $_POST['txtWeekofLessonTime'] : '';

$sql = "SELECT user_name, address FROM member_studentM WHERE franchise_idx = '" . $franchise_idx . "' AND user_no = '" . $student_idx . "'";

$requester = "";
$requester_no = "";
$requester_addr = "";
$centerName = "";
$center_addr = "";
$center_tel = "";
$ceoName = "";
$centerCode = "";

$result = $db->sqlRow($sql);

if (!empty($result)) {
    $requester = $result['user_name'];
    $requester_no = "";
    $requester_addr = $result['address'];
}

$sql = "SELECT center_name, address, tel_num, owner_name, franchise_type FROM franchiseM WHERE franchise_idx = '" . $franchise_idx . "'";
$result = $db->sqlRow($sql);

if (!empty($result)) {
    $centerName = $result['center_name'];
    $center_addr = $result['address'];
    $center_tel = $result['tel_num'];
    $ceoName = $result['owner_name'];
    $centerCode = $result['franchise_type'];
}

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    table {
        font-size: 11px !important;
    }

    #printPage {
        page-break-before: always;
    }
</style>

<div id="printPage" class="container-fluid">
    <div class="container" style="font-size: 10px !important;">
        <div class="text-center mb-3">
            <h5>학원교육비&#40;수강료&#41; 납입증명서</h5>
        </div>
        <table id="table1" class="table table-sm table-bordered border-secondary">
            <tr class="align-middle text-start">
                <th colspan="6">1&#46; 신청인</th>
            </tr>
            <tr class="align-middle text-center">
                <th width="15%">①성명</th>
                <th width="30%" colspan="2">&nbsp;</th>
                <th width="20%">②주민등록번호<br>&#40;납세번호&#41;</th>
                <th width="35%" colspan="2">&nbsp;</th>
            </tr>
            <tr class="align-middle text-center">
                <th style="min-height: 40px;">③주소</th>
                <th colspan="5" class="text-start">&nbsp;<br><br></th>
            </tr>
            <tr class="align-middle text-center">
                <th rowspan="2"><br>대상<br>학원생<br>&nbsp;</th>
                <th>④성명</th>
                <th width="20%"><?= $requester ?></th>
                <th>⑤주민등록번호</th>
                <th colspan="2">&nbsp;</th>
            </tr>
            <tr class="align-middle text-center">
                <th>⑥주소</th>
                <th class="text-start" colspan="2"><?= $requester_addr ?></th>
                <th width="18%">⑦소득자와의 관계</th>
                <th>자녀</th>
            </tr>
        </table>
        <table id="table2" class="table table-sm table-bordered">
            <tr class="align-middle text-start">
                <td colspan="6">2&#46; 수강학원</td>
            </tr>
            <tr class="align-middle text-start">
                <td>⑧학원명</td>
                <td class="ps-2" colspan="2"><?= $centerName ?></td>
                <td width="15%">⑨사업자등록번호</td>
                <td class="ps-2" colspan="2">123-12-12345</td>
            </tr>
            <tr class="align-middle text-start">
                <td>⑩소재지</td>
                <td class="ps-2" colspan="2"><?= $center_addr ?></td>
                <td width="15%">⑪전화번호</td>
                <td class="ps-2" colspan="2"><?= phoneFormat($center_tel, true) ?></td>
            </tr>
            <tr class="align-middle text-start">
                <td width="15%">⑫1일 수업시간</td>
                <td class="ps-2" width="35%" colspan="2"><?= $dlt ?></td>
                <td width="15%">⑬1주 수업시간</td>
                <td class="ps-2" width="35%" colspan="2"><?= $wlt ?></td>
            </tr>
        </table>
        <table id="table3" class="table table-sm table-bordered">
            <tr class="align-middle text-start">
                <td colspan="4">3&#46; 수강료 납입금액</td>
            </tr>
            <tr class="align-middle text-center">
                <td width="15%">⑭월별</td>
                <td width="35%">⑮납입금액</td>
                <td width="15%">⑭월별</td>
                <td width="35%">⑮납입금액</td>
            </tr>
            <?php
            $sql = "SELECT order_ym, (SUM(order_money) - SUM(refund_money)) amt FROM invoiceM
            WHERE franchise_idx = '" . $franchise_idx . "' AND student_idx = '" . $student_idx . "' AND
            order_ym BETWEEN '" . date('Y-m', strtotime($certificateYear . '-01')) . "' AND '" . date('Y-m', strtotime($certificateYear . '-12')) . "' 
            GROUP BY order_ym ORDER BY order_ym";

            $result = $db->sqlRowArr($sql);
            $amt = array();
            $cnt = 0;
            for ($i = 1; $i <= 12; $i++) {
                $amt[$i - 1]['month'] = $i < 10 ? ('0' . $i) : $i;
                foreach ($result as $key => $val) {
                    if ($amt[$i - 1]['month'] == substr($val['order_ym'], 5, 2)) {
                        $amt[$i - 1]['amt'] = $val['amt'];
                        break;
                    } else {
                        $amt[$i - 1]['amt'] = '0';
                    }
                }
                $cnt = ($cnt + (int)$amt[$i - 1]['amt']);
            }

            $j = 1;
            for ($i = 0; $i < 6; $i++) {
            ?>
                <tr class="align-middle text-center">
                    <td width="15%"><?= $j < 10 ? "0" . $j : $j ?>월</td>
                    <td width="35%" class="text-end pe-3"><?= !empty($amt[$j - 1]['amt']) ? number_format($amt[$j - 1]['amt']) : 0 ?></td>
                    <?php
                    $j++;
                    ?>
                    <td width="15%"><?= $j < 10 ? "0" . $j : $j ?>월</td>
                    <td width="35%" class="text-end pe-3"><?= !empty($amt[$j - 1]['amt']) ? number_format($amt[$j - 1]['amt']) : 0 ?></td>
                </tr>
            <?php
                $j++;
            }
            ?>
            <tr class="align-middle text-center">
                <td width="15%">연간합계액</td>
                <td width="35%" class="text-end pe-3"><?= !empty($cnt) ? number_format($cnt) : 0 ?></td>
                <td width="15%">용도</td>
                <td width="35%">연말정산용</td>
            </tr>
        </table>
        <div class="container border">
            <div class="text-start mt-2 mb-3">
                <p class="ms-4 me-4">소득세법 제 52조 및 소득세법시행령 제 113조 1항의 규정에 의하여 교육비 공제를 받고자 하니 위와 같이 학원교육비&#40;수강료&#41;를 납입하였음을 증명하여 주시기 바랍니다&#46;</p>
            </div>
            <div class="text-end mt-2">
                <span><?= date('Y') ?>년 <?= date('m') ?>월 <?= date('d') ?>일</span><span class="me-4">&nbsp;</span>
            </div>
            <div class="text-end fw-bold mt-5">
                <span class="me-3">신청인</span><span class="me-2">&nbsp;</span><span class="ms-5 me-5"></span><span>&#40;서명 또는 인&#41;</span><span class="ms-1 me-4"></span>
            </div>
            <br><br>
        </div>
        <div class="container border">
            <div class="text-center mt-3 mb-3">
                <p>위와 같이 학원교육비&#40;수강료&#41;를 납입하였음을 증명하였음을 확인합니다&#46;</p>
            </div>
            <div class="text-end mt-2 mb-4">
                <span><?= date('Y') ?>년 <?= date('m') ?>월 <?= date('d') ?>일</span><span class="me-4">&nbsp;</span>
            </div>
            <div class="row">
                <div class="col justify-content-end text-end">
                    <?php
                    if ($centerCode == '00' || $centerCode == '01') {
                    ?>
                        <div class="position-relative" style="float: right; margin-right: 80px;">
                            <span style="display:block;">
                                &#40;주&#41; 리딩엠 대표이사 황종일&nbsp;&nbsp;
                            </span>
                        <?php
                    } else {
                        ?>
                            <div class="position-relative" style="float: right; margin-right: 56px;">
                            <?php
                        }
                            ?>
                            <span style="display:block;">
                                책읽기와 글쓰기 리딩엠 <?= $centerName ?>&nbsp;&nbsp;
                            </span>
                            <span style="display:block;">
                                원장 <?= $ceoName ?>&nbsp;&nbsp;
                            </span>
                            <?php
                            if ($centerCode == '00' || $centerCode == '01') {
                            ?>
                                <div class="position-absolute top-50 start-100 translate-middle-y">
                                    <img src="/img/rm_stamp.png" width="80px" />
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="position-absolute top-50 start-100 translate-middle-y">
                                    <span class="fw-bold">&nbsp;&nbsp;&nbsp;&#40;인&#41;</span>
                                </div>
                            <?php
                            }
                            ?>
                            </div>
                            <br><br><br><br><br>
                        </div>
                </div>
            </div>
            <br>
            <div class="text-start">
                <p class="text-muted">&#42; 굵은선으로 표시된 칸과 아래 신청인 서명은 직접 작성하여 사용해주시기 바랍니다&#46;</p>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
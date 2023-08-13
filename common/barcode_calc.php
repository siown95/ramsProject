<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";
function getBarcode($center_idx, $startNo, $endNo)
{
    $db = new DBCmp();

    $start = $startNo;
    $end = $endNo;
    $data = '';
    $img = '<img style="width: 65%;" src="/img/barcode_logo.jpg" alt="barcode_logo" />';
    $table = '<table class="table">';
    $data = '';

    $sql = "SELECT F.center_name, F.tel_num, C1.code_name AS cate1, C2.code_name AS cate2, C3.detail AS cate3, BS.book_barcode FROM book_statusT BS
    LEFT OUTER JOIN bookM B ON B.book_idx = BS.book_idx
    LEFT OUTER JOIN franchiseM F ON BS.franchise_idx = F.franchise_idx
    LEFT OUTER JOIN codem C1 ON (B.book_category1 = C1.code_num1 AND C1.code_num2 = '' AND C1.code_num3 = '')
    LEFT OUTER JOIN codem C2 ON (B.book_category1 = C2.code_num1 AND B.book_category2 = C2.code_num2 AND C2.code_num3 = '')
    LEFT OUTER JOIN codem C3 ON (B.book_category1 = C3.code_num1 AND B.book_category2 = C3.code_num2 AND B.book_category3 = C3.code_num3)
    WHERE BS.franchise_idx = '{$center_idx}' AND BS.book_barcode BETWEEN '" . str_pad($start, 5, "0", STR_PAD_LEFT) . "' AND '" . str_pad($end, 5, "0", STR_PAD_LEFT) . "'
    ORDER BY BS.book_barcode";

    $result = $db->sqlRowArr($sql);

    if (!empty($result)) {
        foreach ($result as $key => $val) {
            if (($key + 1) % 2 == 1) {
                $table .= "<div class=\"row text-center align-middle\">";
            }
            $table .= "<div class=\"col-3 text-center justify-contents-center border\">
            <div><b>{$val['cate1']}</b></div>
            <div><b>{$val['cate2']}</b></div>
            <div><b>{$val['cate3']}</b></div>
            <div><b>{$val['book_barcode']}</b></div>
            <div class=\"bg-warning bg-gradient\"><b>리딩엠_{$val['center_name']}</b></div>
            </div>
            <div class=\"col-3 text-center justify-contents-center border\">
                <div>" . $img . "</div>
                <div id=\"barcode" . ($key + 1) . "\" class=\"ms-4 text-center align-self-center\"></div>
                <div></div>
                <div></div>
                <div class=\"text-center bg-warning bg-gradient\"><b class=\"text-center bg-warning bg-gradient\">" . phoneFormat($val['tel_num'], true) . "</b></div>
            </div>";
            if (($key + 1) % 2 == 0) {
                $table .= "</div>";
            }
            $data .= '$("#barcode' . ($key + 1) . '").barcode(' . '"' . $val['book_barcode'] . '"' . ', "code128", { barWidth: 1, fontSize: 14});';
        }
        $table .= '</table>';
    }
    $return_data['table'] = $table;
    $return_data['data'] = $data;
    return $return_data;
}

<?php
if (empty($_REQUEST)) {
    return false;
}

$searchType = !empty($_GET['searchType']) ? $_GET['searchType'] : '';
$searchValue = !empty($_GET['searchValue']) ? $_GET['searchValue'] : '';

include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
$params = $_REQUEST;

$columns = array(
    0 => 'b.book_isbn',
    1 => 'b.book_name',
    2 => 'b.book_publisher',
    3 => 'b.book_writer',
    4 => 'c.detail',
    5 => 'b.reg_date',
);

$where_arr = array();
$where_qry = "";
if (!empty($searchValue) && !empty($searchType)) {
    $replace_arr = array(
        "%" => "[%]",
        "_" => "[_]",
        "[" => "[[]",
        "'" => "''"
    );
    $searchValue = strtr($searchValue, $replace_arr);
    $where_arr = array(
        " b.book_isbn LIKE '%" . $searchValue . "%' ",
        " b.book_name LIKE '%" . $searchValue . "%' ",
        " b.book_publisher LIKE '%" . $searchValue . "%' ",
        " b.book_writer LIKE '%" . $searchValue . "%' ",
        " c.detail LIKE '%" . $searchValue . "%' ",
    );

    $where_qry = " WHERE " . $where_arr[$searchType];
}



$sql = "SELECT b.book_idx, b.book_isbn, b.book_name, b.book_writer, b.book_publisher, b.img_link, b.reg_date, c.detail book_category
FROM bookM b LEFT OUTER JOIN codem c
ON (b.book_category1 = c.code_num1 AND b.book_category2 = c.code_num2 AND b.book_category3 = c.code_num3) AND c.code_num2 <> ''
" . $where_qry;

$totResult = $db->sqlRowArr($sql);
$totRecords = count($totResult);

$sql .= "ORDER BY " . $columns[$params['order'][0]['column']] . ' ' . $params['order'][0]['dir'] . "
OFFSET " . $params['start'] . " ROWS
FETCH NEXT " . $params['length'] . " ROWS ONLY";

$result = $db->sqlRowArr($sql);


if (!empty($result)) {
    foreach ($result as $key => $val) {
        $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
    }
}

$json_data = array(
    "draw"            => intval($params['draw']),
    "recordsTotal"    => intval($totRecords),
    "recordsFiltered" => intval($totRecords),
    "data"            => $result
);

echo json_encode($json_data);

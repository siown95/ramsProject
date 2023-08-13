<?php
if (empty($_REQUEST)) {
    return false;
}

$center_idx = !empty($_GET['center_idx']) ? $_GET['center_idx'] : '';
$searchValue = !empty($_GET['searchValue']) ? $_GET['searchValue'] : '';

if (empty($center_idx)) {
    return false;
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
$params = $_REQUEST;

$columns = array(
    0 => 'b.book_isbn',
    1 => 'b.book_name',
    3 => 'b.book_writer',
    2 => 'b.book_publisher',
    4 => 'c.detail',
    5 => "(SELECT COUNT(0) FROM book_statusT s WHERE s.book_idx = b.book_idx AND s.franchise_idx = '" . $center_idx . "')",
    6 => 'b.reg_date',
);

$where_qry = " WHERE b.useYn = 'Y' AND c.code_num2 <> '' ";

if (!empty($searchValue)) {
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
    $where_qry .= " AND (" . implode(" OR ", $where_arr) . " ) ";
}

$sql = "SELECT b.book_idx, b.book_isbn, b.book_name, b.book_writer, b.book_publisher, b.reg_date, c.detail
        , (SELECT COUNT(0) FROM book_statusT s WHERE s.book_idx = b.book_idx AND s.franchise_idx = '" . $center_idx . "') amount
        FROM bookM b
        LEFT OUTER JOIN codem c
        ON (b.book_category1 = c.code_num1 AND b.book_category2 = c.code_num2 AND b.book_category3 = c.code_num3)
        " . $where_qry . "
        ORDER BY " . $columns[$params['order'][0]['column']] . ' ' . $params['order'][0]['dir'] . "
        OFFSET " . $params['start'] . " ROWS
        FETCH NEXT " . $params['length'] . " ROWS ONLY";

$result = $db->sqlRowArr($sql);

$totSql = "SELECT COUNT(b.book_idx)
           FROM bookM b 
           LEFT OUTER JOIN codem c ON (b.book_category1 = c.code_num1 AND b.book_category2 = c.code_num2 AND b.book_category3 = c.code_num3) AND c.code_num2 <> '' ";

if (!empty($where_arr)) {
    $totSql .= " WHERE " . implode(" OR ", $where_arr);
}

$totRecords = $db->sqlRowOne($totSql);

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

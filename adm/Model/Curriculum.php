<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class CurriculumInfo extends Model
{
    var $table_name = "curriculumm";

    function __construct()
    {
        parent::__construct();
    }

    public function curriculumLoad($params)
    {
        $months        = !empty($params['months']) ? $params['months'] : '';
        $grade         = !empty($params['grade']) ? $params['grade'] : '';
        $franchise_idx = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';

        $where = '';
        $where_arr = array();

        if (!empty($months)) {
            $where_arr[] .= " c.months = '" . $months . "' ";
        }

        if (!empty($grade)) {
            $where_arr[] .= " c.grade = '" . $grade . "' ";
        }

        if (!empty($franchise_idx)) {
            $where_arr[] .= " c.franchise_idx = '" . $franchise_idx . "' ";
        }

        if (!empty($where_arr)) {
            $where = "WHERE " . implode(" AND ", $where_arr);
        }

        $sql = "SELECT 
                  c.curriculum_idx
                , c.months
                , c1.code_name
                , c.orders
                , c.book_idx
                , b.book_name
                , b.book_writer
                , b.book_publisher  
                FROM {$this->table_name} c 
                LEFT OUTER JOIN bookm b 
                ON c.book_idx = b.book_idx
                LEFT OUTER JOIN codem c1
                ON (c.grade =  c1.code_num2 AND c1.code_num1 = '02')
                " . $where . "
                ORDER BY c.months, c.grade, c.orders";
        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function curriculumBatch($params)
    {
        $months        = !empty($params['months']) ? $params['months'] : '';
        $grade         = !empty($params['grade']) ? $params['grade'] : '';
        $franchise_idx = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';

        $where = '';
        $where_arr = array();
        $where_arr[] .= " franchise_idx = '1' ";

        if (!empty($months)) {
            $where_arr[] .= " months = '" . $months . "' ";
        }

        if (!empty($grade)) {
            $where_arr[] .= " grade = '" . $grade . "' ";
        }

        if (!empty($where_arr)) {
            $where = "WHERE " . implode(" AND ", $where_arr);
        }

        $sql = "INSERT INTO {$this->table_name} (book_idx, franchise_idx, orders, months, grade) SELECT book_idx, '" . $franchise_idx . "', orders, months, grade 
                FROM {$this->table_name} " . $where;
        $result = $this->db->execute($sql);

        return $result;
    }
}
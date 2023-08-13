<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class MagazineInfo extends Model
{
    var $table_name = 'magazinem';

    function __construct()
    {
        parent::__construct();
    }

    public function magazineLoad($season, $magazine_year)
    {
        $where_arr = array("magazine_year = '" . $magazine_year . "'");
        if(!empty($season)){
            $where_arr[] .= "season = '" . $season . "'";
        }

        $where_qry = "WHERE " . implode(" AND ", $where_arr);

        $sql = "SELECT M.magazine_idx, M.title, C.code_name season_name, M.magazine_year
                FROM magazinem M LEFT OUTER JOIN codem C ON C.code_num2 = M.season AND C.code_num1 = '31'" . $where_qry
                . " ORDER BY M.season";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function magazineSelect($magazine_idx)
    {
        $sql = "SELECT title, magazine_year, season, thumbnail_name, pdf_link, thumbnail_name
                FROM {$this->table_name} WHERE magazine_idx = '" . $magazine_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }
}
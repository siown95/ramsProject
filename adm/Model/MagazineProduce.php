<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class MagazineProduceInfo extends Model
{
    var $table_name = 'magazine_produceT';

    function __construct()
    {
        parent::__construct();
    }

    public function magazineProduceLoad($franchise_idx, $magazine_year, $season)
    {
        $sql = "SELECT produce_file_type, produce_origin_file_name, produce_file_name 
                FROM {$this->table_name} 
                WHERE franchise_idx = '" . $franchise_idx . "' AND produce_year = '" . $magazine_year . "' AND produce_season = '" . $season . "' 
                ORDER BY reg_date DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}

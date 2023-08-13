<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Model.php";

class MagazineInfo extends Model
{
    var $table_name = 'magazinem';

    function __construct()
    {
        parent::__construct();
    }

    public function magazineLoad($season, $magazine_year)
    {
        $sql = "SELECT title, thumbnail_name, pdf_link FROM {$this->table_name} WHERE magazine_year = '" . $magazine_year . "' AND season = '" . $season . "'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}
<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class BannerInfo extends Model
{
    var $table_name = "bannerT";

    function __construct()
    {
        parent::__construct();
    }

    public function loadBanner()
    {
        $sql = "SELECT banner_idx, from_date, to_date, banner_image, banner_link, orders, mainYn, banner_visible FROM {$this->table_name}";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}
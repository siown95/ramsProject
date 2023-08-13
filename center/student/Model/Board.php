<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Model.php";

class BoardInfo extends Model
{
    var $table_name = "boardM";

    function __construct()
    {
        parent::__construct();
    }

    public function loadFileBoard()
    {
        $sql = "SELECT b.board_idx, b.title, c1.code_name code_name1, c.detail code_name2, b.contents, b.files, convert(varchar(19), b.reg_date, 120) reg_date 
                FROM {$this->table_name} b
                LEFT OUTER JOIN codem c1 ON (b.category1 = c1.code_num2 AND c1.code_num1 = '71' AND c1.code_num3 = '')
                LEFT OUTER JOIN codem c ON (c.code_num1 = '71' AND b.category2 = c.code_num3 AND c.code_num2 = b.category1 )
                WHERE category1 != '' ORDER BY b.board_idx DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function loadBoard()
    {
        $sql = "SELECT board_idx, title,  contents, files, convert(varchar(19), reg_date, 120) reg_date
                FROM {$this->table_name}
                WHERE category1 = '' AND open_franchise = '' AND open_target = '' ORDER BY board_idx DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function boardSelect($board_idx){
        $sql = "SELECT board_idx, title, category1, category2, contents, files, origin_file, file_path, convert(varchar(19), reg_date, 120) reg_date 
                FROM {$this->table_name} WHERE board_idx = '".$board_idx."'" ;
        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function loadBoardMain()
    {
        $sql = "SELECT TOP(3) title, convert(varchar(19), reg_date, 120) reg_date
                FROM {$this->table_name}
                WHERE category1 = '' AND open_franchise = '' AND open_target = '' ORDER BY board_idx DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}
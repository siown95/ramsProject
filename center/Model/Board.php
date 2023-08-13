<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class BoardInfo extends Model
{
    var $table_name = "boardm";

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

    public function loadBoard($center_idx, $target_type)
    {
        $sql = "SELECT board_idx, title, contents, files, convert(varchar(19), reg_date, 120) reg_date 
        FROM {$this->table_name}
        WHERE category1 = ''  AND open_franchise IN ('', (SELECT franchise_type FROM franchiseM WHERE franchise_idx = '{$center_idx}'))
        AND open_target <= '{$target_type}'
        ORDER BY board_idx DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function loadBoardMain($center_idx, $target_type)
    {
        $sql = "SELECT TOP(5) board_idx, title, convert(varchar(19), reg_date, 120) reg_date 
        FROM {$this->table_name}
        WHERE category1 = ''  AND open_franchise IN ('', (SELECT franchise_type FROM franchiseM WHERE franchise_idx = '{$center_idx}'))
        AND open_target <= '{$target_type}'
        ORDER BY board_idx DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function boardSelect($board_idx)
    {
        $sql = "SELECT board_idx, title, category1, category2, contents, files, origin_file, file_path, convert(varchar(19), reg_date, 120) reg_date 
                FROM {$this->table_name} WHERE board_idx = '" . $board_idx . "'";
        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function fileBoardSelect($board_idx)
    {
        $sql = "SELECT B.board_idx, B.title, C1.code_name category1, C2.detail category2, B.contents, B.files, B.origin_file, B.file_path, B.reg_date
                FROM {$this->table_name} B
                LEFT OUTER JOIN codem C1 ON (B.category1 = C1.code_num2 AND C1.code_num1 = '71' AND C1.code_num3 = '')
                LEFT OUTER JOIN codem C2 ON (B.category2 = C2.code_num3 AND C2.code_num1 = '71' AND C2.code_num2 = B.category1 )
                WHERE board_idx = '" . $board_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }
}

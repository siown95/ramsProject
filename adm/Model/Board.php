<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

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
                WHERE b.category1 != ''  ORDER BY b.board_idx DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function loadBoard()
    {
        $sql = "SELECT B.board_idx
        , ISNULL(C1.code_name, '전체공개') AS open_franchise
        , ISNULL(C2.code_name, '전체공개') AS open_target
        , B.title, B.contents, B.files, convert(varchar(19), B.reg_date, 120) reg_date 
        FROM {$this->table_name} B
        LEFT OUTER JOIN codeM C1 ON B.open_franchise = C1.code_num2 AND C1.code_num1 = '03' AND C1.code_num2 <> ''
        LEFT OUTER JOIN codeM C2 ON B.open_target = C2.code_num2 AND C2.code_num1 = '20' AND C2.code_num2 <> ''
        WHERE category1 = '' ORDER BY board_idx DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function boardSelect($board_idx)
    {
        $sql = "SELECT board_idx, title, category1, category2, open_franchise, open_target, contents, files, origin_file, file_path, convert(varchar(19), reg_date, 120) reg_date 
                FROM {$this->table_name} WHERE board_idx = '" . $board_idx . "'";
        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function loadBoardMain()
    {
        $sql = "SELECT TOP (3) title, convert(varchar(19), reg_date, 120) reg_date
                FROM {$this->table_name}
                WHERE category1 = '' ORDER BY board_idx DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}

<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class NewsPaperInfo extends Model
{
    var $table_name = 'newspaper_columnt';

    function __construct()
    {
        parent::__construct();
    }

    public function newspaperLoad()
    {
        $sql = "SELECT 
                N.news_idx, N.newspaper_title, C.code_name news_company, C1.code_name news_subject, convert(varchar(19), N.news_date, 120) news_date
                , N.column_file, N.column_origin, convert(varchar(19), N.reg_date, 120) reg_date
                , N.teaching_file, N.teaching_origin, M.user_name, F.franchise_idx, F.center_name
                FROM {$this->table_name} N
                LEFT OUTER JOIN member_centerm M
                ON N.user_no = M.user_no
                LEFT OUTER JOIN franchisem F
                ON M.franchise_idx = F.franchise_idx
                LEFT OUTER JOIN codem C
                ON N.news_code = C.code_num2 AND C.code_num1 = '06'
                LEFT OUTER JOIN codem C1
                ON N.column_code = C1.code_num2 AND C1.code_num1 = '07'
                ORDER BY N.reg_date DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function newspaperSelect($news_idx)
    {
        $sql = "SELECT newspaper_title, news_code, column_code, news_date, column_file, column_origin, teaching_file, teaching_origin 
                FROM {$this->table_name} WHERE news_idx = '" . $news_idx . "'";
        $result = $this->db->sqlRow($sql);
        
        return $result;
    }
}
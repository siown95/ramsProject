<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class BoardTipInfo extends Model
{
    var $table_name = "board_lessontipt";
    var $comment_table = "board_lessontip_commentt";
    var $likes_table = "board_lessontip_likes";

    function __construct()
    {
        parent::__construct();
    }

    public function boardTipDelete($board_idx)
    {
        $sql = "DELETE FROM {$this->comment_table} WHERE board_idx = '" . $board_idx . "';";
        $sql .= "  DELETE FROM {$this->table_name} WHERE board_idx = '" . $board_idx . "'";

        $result = $this->db->multiExecute($sql);
        return $result;
    }

    public function boardTipNoticeLoad()
    {
        $sql = "SELECT TOP (3) 
                b.board_idx
                , b.title
                , c.code_name
                , '본사담당자' writer
                , (SELECT COUNT(0) FROM {$this->comment_table} WHERE board_idx = b.board_idx) cnt1
                , b.likes_cnt cnt2
                , b.notice_yn
                , convert(varchar(19), b.reg_date, 120) reg_date
                , b.file_name
                FROM {$this->table_name} b 
                LEFT OUTER JOIN codem c
                ON (b.board_kind = c.code_num2 AND c.code_num1 = '04' AND c.code_num2 <> '')
                LEFT OUTER JOIN member_employeem m
                ON b.user_no = m.user_no
                LEFT OUTER JOIN franchisem f
                ON m.franchise_idx = f.franchise_idx
                WHERE b.notice_yn = 'Y'
                ORDER BY b.board_idx DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function boardTipLoad()
    {
        $sql = "SELECT 
                b.board_idx
                , b.title
                , c.code_name
                , CASE WHEN b.head_write = 'Y' THEN '본사담당자'
                    ELSE m.user_name END 'user_name'
                , CASE WHEN b.head_write = 'Y' THEN ''
                    ELSE f.center_name END 'center_name'
                , (SELECT COUNT(0) FROM {$this->comment_table} WHERE board_idx = b.board_idx) cnt1
                , b.likes_cnt cnt2
                , b.notice_yn
                , convert(varchar(19), b.reg_date, 120) reg_date
                , b.file_name
                FROM {$this->table_name} b 
                LEFT OUTER JOIN codem c
                ON (b.board_kind = c.code_num2 AND c.code_num1 = '04' AND c.code_num2 <> '')
                LEFT OUTER JOIN member_centerm m
                ON b.user_no = m.user_no
                LEFT OUTER JOIN franchisem f
                ON m.franchise_idx = f.franchise_idx
                ORDER BY b.board_idx DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function boardTipSelect($board_idx)
    {
        $sql = "SELECT m.user_no, b.board_kind, b.title, b.contents, b.file_name, b.origin_name, b.file_path, b.notice_yn 
                FROM {$this->table_name} b
                LEFT OUTER JOIN member_centerm m
                ON b.user_no = m.user_no
                WHERE b.board_idx = '" . $board_idx . "'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }

    public function boardTipCmtInsert($params)
    {
        try {
            $this->table_name = $this->comment_table;
            $result = $this->insert($params);
        } catch (Exception $e) {
            new Exception($e);
        }
        return $result;
    }

    public function boardTipCmtLoad($board_idx)
    {
        $sql = "SELECT 
                c.comment_idx
                , CASE WHEN c.head_write = 'Y' THEN ''
                    ELSE m.user_no END 'cmt_user'
                , CASE WHEN c.head_write = 'Y' THEN '본사담당자'
                    ELSE m.user_name END 'user_name'
                , CASE WHEN c.head_write = 'Y' THEN '본사'
                    ELSE f.center_name END 'center_name'
                , c.comment
                , convert(varchar(19), c.reg_date, 120) reg_date
                FROM {$this->comment_table} c 
                LEFT OUTER JOIN member_centerm m 
                ON c.user_no = m.user_no
                LEFT OUTER JOIN franchisem f
                ON m.franchise_idx = f.franchise_idx
                WHERE c.board_idx = '" . $board_idx . "'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function getLikesCnt($board_idx)
    {
        $sql = "SELECT likes_cnt FROM {$this->table_name} WHERE board_idx = '" . $board_idx . "'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function boardTipCmtDelete($comment_idx)
    {
        $sql = "DELETE FROM {$this->comment_table} WHERE comment_idx = '" . $comment_idx . "' AND comment <> ''";
        $result = $this->db->execute($sql);

        return $result;
    }

    public function checkLikeCnt($board_idx, $user_no, $franchise_idx)
    {
        $sql = "SELECT likes_idx FROM {$this->likes_table} WHERE board_idx = '" . $board_idx . "' AND user_no = '" . $user_no . "' AND franchise_idx = '".$franchise_idx."'";
        $result = $this->db->sqlRow($sql);

        return $result;
    }

    public function likeInsert($params)
    {
        try {
            $this->table_name = $this->likes_table;
            $result = $this->insert($params);
        } catch (Exception $e) {
            new Exception($e);
        }
        return $result;
    }

    public function likeDelete($likes_idx)
    {
        $this->table_name = $this->likes_table;
        $this->where_qry = " likes_idx = '" . $likes_idx . "'";

        try {
            $result = $this->delete();
        } catch (Exception $e) {
            new Exception($e);
        }
        return $result;
    }
}

<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class ActivityErrorReportInfo extends Model
{
    var $table_name = "activity_error_reportt";

    function __construct()
    {
        parent::__construct();
    }

    public function activityErrorLoad()
    {
        $sql = "SELECT AE.error_report_idx, AE.title, M.user_name, F.center_name
                , convert(varchar(19), AE.reg_date, 120) reg_date, AE.state
                FROM {$this->table_name} AE
                LEFT OUTER JOIN member_centerm M
                ON AE.writer_no = M.user_no
                LEFT OUTER JOIN franchisem F
                ON M.franchise_idx = F.franchise_idx
                ORDER BY reg_date DESC";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function activityErrorSelect($error_idx)
    {
        $sql = "SELECT AE.error_report_idx, AE.title, AE.contents, M.user_name, F.center_name
                , convert(varchar(19), AE.reg_date, 120) reg_date, AE.state, AE.file_name, AE.origin_name, AE.comments
                FROM {$this->table_name} AE
                LEFT OUTER JOIN member_centerm M
                ON AE.writer_no = M.user_no
                LEFT OUTER JOIN franchisem F
                ON M.franchise_idx = F.franchise_idx
                WHERE AE.error_report_idx = '" . $error_idx . "'";

        $result = $this->db->sqlRow($sql);
        return $result;
    }
}
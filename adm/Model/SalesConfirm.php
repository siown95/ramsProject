<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class SalesConfirmInfo extends Model
{
    var $table_name = "franchise_feeM";

    function __construct()
    {
        parent::__construct();
    }

    public function salesInfoLoad($months)
    {
        $sql = "SELECT FF.franchise_fee_idx, F.center_name, FF.franchise_fee_ym, FF.franchise_fee_date, FF.franchise_fee_money
        , FF.refund_date, FF.refund_money, C.code_name AS franchise_fee_state FROM {$this->table_name} FF
        LEFT OUTER JOIN franchiseM F ON FF.franchise_idx = F.franchise_idx
        LEFT OUTER JOIN codeM C ON FF.franchise_fee_state = C.code_num2 AND C.code_num1 = '42'
        WHERE FF.franchise_fee_ym = '{$months}'";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }
}

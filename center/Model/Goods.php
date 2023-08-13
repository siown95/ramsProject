<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class GoodsInfo extends Model
{
    var $table_name = 'goodsm';

    function __construct()
    {
        parent::__construct();
    }

    public function goodsListLoad()
    {
        $sql = "SELECT goods_idx, goods_name, code_name goods_type, order_unit, sel_price, min_quantity, img_link
                FROM {$this->table_name} G LEFT OUTER JOIN codem C ON G.goods_type = C.code_num2 AND C.code_num1 = '43' WHERE G.useYn = 'Y'";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function goodsSelect($goods_idx)
    {
        $sql = "SELECT goods_name, goods_type, order_unit, cost_price, sel_price, min_quantity, memo, img_link
        FROM {$this->table_name} WHERE goods_idx = '" . $goods_idx . "'";

        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function goodsListAdd($goods_idxs)
    {
        $goods_idx = implode(',', $goods_idxs);
        $sql = "SELECT goods_idx, goods_name, order_unit, sel_price, min_quantity
        FROM {$this->table_name} WHERE goods_idx IN (" . $goods_idx . ")";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function loadOrderGoodsList($franchise_idx, $order_month)
    {
        $sql = "SELECT OG.order_num, FORMAT((SUM(OG.order_money * OG.order_quantity)), '#') order_money,
                FORMAT((SUM(GM.sel_price * OG.order_quantity) * 1.1), '#') pay_money
                , ISNULL((SELECT order_money FROM order_goodsT WHERE order_num = OG.order_num AND goods_idx = '237'),0) shipping_money
                , C1.code_name order_method, C2.code_name order_state, FORMAT(OG.order_date, 'yyyy-MM-dd') order_date, OG.pay_date
                FROM order_goodsT OG
                LEFT OUTER JOIN {$this->table_name} GM ON GM.goods_idx = OG.goods_idx
                LEFT OUTER JOIN codeM C1 ON C1.code_num1 = '41' AND C1.code_num2 = OG.order_method AND C1.code_num2 <> ''
                LEFT OUTER JOIN codeM C2 ON C2.code_num1 = '44' AND C2.code_num2 = OG.order_state AND C2.code_num2 <> ''
                WHERE OG.goods_idx <> '237' AND OG.order_date BETWEEN '" . $order_month . "-01' AND '" . date("Y-m-t", strtotime($order_month)) . "' AND OG.franchise_idx = '" . $franchise_idx . "'
                GROUP BY OG.order_num, C1.code_name, C2.code_name, FORMAT(OG.order_date, 'yyyy-MM-dd'), pay_date
                ORDER BY pay_date DESC, FORMAT(OG.order_date, 'yyyy-MM-dd') DESC";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function selectOrderInfo($order_num)
    {
        $sql = "SELECT CAST((SUM(OG.order_money * OG.order_quantity) * 1.1) as int) order_money, FORMAT(OG.order_date, 'yyyy-MM-dd') order_date, OG.order_state AS order_state_code
                , GS.shipping_date, GS.shipping_company, GS.shipping_address, GS.shipping_zipcode, GS.invoice_number, C.code_name AS order_state, GS.teacher_phone, GS.shipping_memo
                FROM order_goodsT OG
                LEFT OUTER JOIN goods_shippingT GS ON GS.order_num = OG.order_num
                LEFT OUTER JOIN codeM C ON C.code_num1 = '44' AND C.code_num2 = OG.order_state AND C.code_num2 <> ''
                WHERE OG.goods_idx <> '237' AND OG.order_num = '" . $order_num . "'
                GROUP BY OG.order_num, FORMAT(OG.order_date, 'yyyy-MM-dd'), OG.order_state, GS.shipping_date, GS.shipping_company, GS.shipping_address, GS.shipping_zipcode
                , GS.invoice_number, C.code_name, GS.teacher_phone, GS.shipping_memo";

        $result = $this->db->sqlRow($sql);

        return $result;
    }

    public function selectOrderList($order_num)
    {
        $sql = "SELECT OG.goods_idx, G.goods_name, G.min_quantity, G.sel_price, OG.order_money, G.order_unit, OG.order_quantity
                FROM order_goodsT OG
                LEFT OUTER JOIN goodsM G ON G.goods_idx = OG.goods_idx
                WHERE OG.order_num = '" . $order_num . "' AND OG.goods_idx <> '237'";

        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}

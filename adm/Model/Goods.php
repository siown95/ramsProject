<?php
include $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class GoodsInfo extends Model
{
    var $table_name = 'goodsm';

    function __construct()
    {
        parent::__construct();
    }

    public function goodsLoad()
    {
        $sql = "SELECT goods_idx, goods_name, code_name goods_type, order_unit, cost_price, sel_price, min_quantity, memo
                FROM {$this->table_name} G LEFT OUTER JOIN codem C ON G.goods_type = C.code_num2 AND C.code_num1 = '43'";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function goodsSelect($goods_idx)
    {
        $sql = "SELECT goods_name, goods_type, order_unit, cost_price, sel_price, min_quantity, memo, useYn, img_link
        FROM {$this->table_name} WHERE goods_idx = '" . $goods_idx . "'";

        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function loadOrderGoodsList($order_month, $order_state)
    {
        if ($order_state == 'All') {
            $where_qry = "";
        } else {
            $where_qry = " AND OG.order_state = '" . $order_state . "'";
        }

        $sql = "SELECT OG.order_num, FORMAT((SUM(OG.order_money * OG.order_quantity)), '#') AS order_money, F.center_name
                , FORMAT((SUM(GM.sel_price * OG.order_quantity) * 1.1), '#') pay_money
                , ISNULL((SELECT order_money FROM order_goodsT WHERE order_num = OG.order_num AND goods_idx = '237'),0) AS shipping_money
                , C1.code_name order_method, C2.code_name AS order_state, FORMAT(OG.order_date, 'yyyy-MM-dd') order_date
                FROM order_goodsT OG
                LEFT OUTER JOIN {$this->table_name} GM ON GM.goods_idx = OG.goods_idx
                LEFT OUTER JOIN codeM C1 ON C1.code_num1 = '41' AND C1.code_num2 = OG.order_method AND C1.code_num2 <> ''
                LEFT OUTER JOIN codeM C2 ON C2.code_num1 = '44' AND C2.code_num2 = OG.order_state AND C2.code_num2 <> ''
                LEFT OUTER JOIN franchiseM F ON OG.franchise_idx = F.franchise_idx
                WHERE OG.goods_idx <> '237' AND OG.order_date BETWEEN '" . $order_month . "-01' AND '" . date("Y-m-t", strtotime($order_month)) . "'" . $where_qry . "
                GROUP BY OG.order_num, C1.code_name, C2.code_name, FORMAT(OG.order_date, 'yyyy-MM-dd'), F.center_name
                ORDER BY FORMAT(OG.order_date, 'yyyy-MM-dd')";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function selectShippingInfo($order_num)
    {
        $sql = "SELECT S.shipping_idx, S.order_num, F.center_name, FORMAT(S.reg_date,'yyyy-MM-dd') reg_date, S.shipping_date, S.invoice_number, S.shipping_company
                , S.shipping_address, S.shipping_zipcode, S.shipping_memo, S.teacher_phone, S.shipping_state, OG.order_state, OG.refund_date AS cancel_date
                , ISNULL((SELECT order_money FROM order_goodsT WHERE order_num = '{$order_num}' AND goods_idx = '237'), 0) shipping_money
                , (SELECT CAST((SUM(order_money * order_quantity) * 1.1) as int) FROM order_goodsT WHERE order_num = '{$order_num}' AND goods_idx <> '237') order_money
                FROM goods_shippingT S 
                LEFT OUTER JOIN franchiseM F ON F.franchise_idx = S.franchise_idx
                LEFT OUTER JOIN order_goodsT OG ON S.order_num = OG.order_num
                WHERE S.order_num = '{$order_num}'";

        $result = $this->db->sqlRow($sql);
        return $result;
    }

    public function selectOrderList($order_num)
    {
        $sql = "SELECT OG.order_goods_idx, OG.goods_name, G.order_unit, G.sel_price, OG.order_money, OG.order_quantity, OG.goods_idx
                FROM order_goodsT OG
                LEFT OUTER JOIN goodsM G ON G.goods_idx = OG.goods_idx
                WHERE OG.order_num = '" . $order_num . "'";

        $result = $this->db->sqlRowArr($sql);
        return $result;
    }

    public function checkGoodsOrderState($order_num)
    {
        $sql = "SELECT TOP(1) order_state FROM order_goodsT WHERE order_num = '{$order_num}'";
        $result = $this->db->sqlRowOne($sql);
        return $result;
    }

    public function checkPayment($order_num)
    {
        $sql = "SELECT paymentKey FROM payment_logT WHERE orderId = '{$order_num}' AND cancels_cancelAmount > 0";
        $result = $this->db->sqlRowOne($sql);
    }
}

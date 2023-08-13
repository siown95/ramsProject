<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Goods.php";

class GoodsController extends Controller
{
    private $goodsInfo;

    function __construct()
    {
        $this->goodsInfo = new GoodsInfo();
    }

    public function goodsInsert($request)
    {
        $goods_name   = !empty($request['txtGoodsName']) ? $request['txtGoodsName'] : "";
        $goods_type   = !empty($request['selCategory']) ? $request['selCategory'] : "";
        $order_unit   = !empty($request['txtUnit']) ? $request['txtUnit'] : "";
        $cost_price   = !empty($request['txtCost']) ? $request['txtCost'] : "";
        $sel_price    = !empty($request['txtPrice']) ? $request['txtPrice'] : "";
        $min_quantity = !empty($request['txtMinQuantity']) ? $request['txtMinQuantity'] : "";
        $memo         = !empty($request['txtMemo']) ? $request['txtMemo'] : "";
        $useYn        = !empty($request['useYn']) ? $request['useYn'] : "";
        $img_link     = !empty($_FILES['fileGoodsImage']) ? $_FILES['fileGoodsImage'] : "";

        try {
            if (empty($goods_name)  || empty($goods_type) || empty($order_unit) || empty($cost_price) || empty($sel_price) || empty($min_quantity) || empty($useYn)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "goods_name"   => !empty($goods_name) ? $goods_name : '',
                "order_unit"   => !empty($order_unit) ? $order_unit : '',
                "goods_type"   => !empty($goods_type) ? $goods_type : '',
                "cost_price"   => !empty($cost_price) ? $cost_price : '',
                "sel_price"    => !empty($sel_price) ? $sel_price : '',
                "min_quantity" => !empty($min_quantity) ? $min_quantity : '',
                "memo"         => !empty($memo) ? $memo : '',
                "useYn"        => !empty($useYn) ? $useYn : ''
            );

            if (!empty($img_link)) {
                //썸네일
                $nameArr1 = explode(".", $img_link['name']);
                $extension1 = end($nameArr1);
                $file_name1 = "goods_" . date("YmdHis") . "." . $extension1;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/goods_image/";
                copy($img_link['tmp_name'], $path . $file_name1);
                $params['img_link'] = $file_name1;
            }

            $result = $this->goodsInfo->insert($params);

            if ($result) {
                $return_data['msg'] = '물품이 등록되었습니다.';
            } else {
                throw new Exception('물품 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function goodsUpdate($request)
    {
        $goods_idx        = !empty($request['goods_idx']) ? $request['goods_idx'] : "";
        $goods_name       = !empty($request['txtGoodsName']) ? $request['txtGoodsName'] : "";
        $goods_type       = !empty($request['selCategory']) ? $request['selCategory'] : "";
        $order_unit       = !empty($request['txtUnit']) ? $request['txtUnit'] : "";
        $cost_price       = !empty($request['txtCost']) ? $request['txtCost'] : "";
        $sel_price        = !empty($request['txtPrice']) ? $request['txtPrice'] : "";
        $min_quantity     = !empty($request['txtMinQuantity']) ? $request['txtMinQuantity'] : "";
        $memo             = !empty($request['txtMemo']) ? $request['txtMemo'] : "";
        $useYn            = !empty($request['useYn']) ? $request['useYn'] : "";
        $origin_file_name = !empty($request['origin_file_name']) ? $request['origin_file_name'] : "";
        $img_link         = !empty($_FILES['fileGoodsImage']) ? $_FILES['fileGoodsImage'] : "";

        try {
            if (empty($goods_name)  || empty($goods_type) || empty($order_unit) || empty($cost_price) || empty($sel_price) || empty($min_quantity) || empty($useYn)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "goods_name"   => !empty($goods_name) ? $goods_name : '',
                "order_unit"   => !empty($order_unit) ? $order_unit : '',
                "goods_type"   => !empty($goods_type) ? $goods_type : '',
                "cost_price"   => !empty($cost_price) ? $cost_price : '',
                "sel_price"    => !empty($sel_price) ? $sel_price : '',
                "min_quantity" => !empty($min_quantity) ? $min_quantity : '',
                "memo"         => !empty($memo) ? $memo : '',
                "useYn"        => !empty($useYn) ? $useYn : '',
                "mod_date"     => 'getdate()'
            );

            if (!empty($img_link)) {
                //썸네일
                $nameArr1 = explode(".", $img_link['name']);
                $extension1 = end($nameArr1);
                $file_name1 = "goods_" . date("YmdHis") . "." . $extension1;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/goods_image/";
                copy($img_link['tmp_name'], $path . $file_name1);
                $params['img_link'] = $file_name1;

                if (!empty($origin_file_name)) {
                    unlink($path . $origin_file_name);
                }
            }

            $this->goodsInfo->where_qry = " goods_idx = '" . $goods_idx . "' ";
            $result = $this->goodsInfo->update($params);

            if ($result) {
                $return_data['msg'] = '물품이 수정되었습니다.';
            } else {
                throw new Exception('물품 수정에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function goodsImageDelete($request)
    {
        $goods_idx        = !empty($request['goods_idx']) ? $request['goods_idx'] : '';
        $origin_file_name = !empty($request['origin_file_name']) ? $request['origin_file_name'] : '';

        try {
            if (empty($goods_idx) || empty($origin_file_name)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $path = $_SERVER['DOCUMENT_ROOT'] . "/files/goods_image/";
            unlink($path . $origin_file_name);

            $this->goodsInfo->where_qry = " goods_idx = '" . $goods_idx . "'";

            $params = array(
                "img_link" => '',
                "mod_date" => 'getdate()'
            );
            $result = $this->goodsInfo->update($params);

            if ($result) {
                $return_data['msg'] = "물품 이미지가 삭제되었습니다.";
            } else {
                throw new Exception('이미지가 삭제되지 않았습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function goodsLoad()
    {
        try {
            $result = $this->goodsInfo->goodsLoad();
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $key + 1;
                    $result[$key]['cost_price'] = number_format($val['cost_price']);
                    $result[$key]['sel_price'] = number_format($val['sel_price']);
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function goodsSelect($params)
    {
        $goods_idx = !empty($params['goods_idx']) ? $params['goods_idx'] : '';

        try {
            if (empty($goods_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->goodsInfo->goodsSelect($goods_idx);

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
    public function loadOrderGoodsList($request)
    {
        $order_month = !empty($request['order_month']) ? $request['order_month'] : '';
        $order_state = !empty($request['order_state']) ? $request['order_state'] : '';

        try {
            if (empty($order_month) || empty($order_state)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->goodsInfo->loadOrderGoodsList($order_month, $order_state);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['no'] .= $key + 1;
                    $result[$key]['pay_money'] = number_format($val['pay_money'] + $val['shipping_money']);
                    $result[$key]['order_money'] = number_format($val['order_money'] + $val['shipping_money']);
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function selectOrderGoodsList($request)
    {
        $order_num = !empty($request['order_num']) ? $request['order_num'] : '';

        try {
            if (empty($order_num)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $shipping_data = $this->goodsInfo->selectShippingInfo($order_num);
            $orderList_data = $this->goodsInfo->selectOrderList($order_num);

            $tbl = '';

            if (!empty($orderList_data)) {
                foreach ($orderList_data as $key => $val) {
                    if ($val['goods_idx'] == '237') {
                        $order_money = number_format($val['order_money'] * $val['order_quantity']);
                    } else {
                        $order_money = number_format(($val['order_money'] * $val['order_quantity']) * 1.1);
                    }
                    $tbl .= "
                    <tr class=\"align-middle text-center\" data-target-idx=\"{$val['order_goods_idx']}\">
                        <td>" . ($key + 1) . "</td>
                        <td class=\"text-start\">{$val['goods_name']}</td>
                        <td class=\"text-start\">{$val['order_unit']}</td>
                        <td>" . number_format($val['sel_price']) . "</td>
                        <td>{$val['order_quantity']}</td>
                        <td>" . $order_money . "</td>
                        <td><button type=\"button\" class=\"btn btn-sm btn-outline-danger\">취소</button></td>
                    </tr>";
                }
            }

            $shipping_data['order_money'] = $shipping_data['order_money'] + $shipping_data['shipping_money'];

            $return_data['shipping_data'] = $shipping_data;
            $return_data['order_list'] = $tbl;
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function checkGoodsOrderState($request)
    {
        $order_num = !empty($request['order_num']) ? $request['order_num'] : '';
        try {
            if (empty($order_num)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->goodsInfo->checkGoodsOrderState($order_num);
            if (!empty($result)) {
                $return_data['state'] = $result;
            } else {
                throw new Exception('잘못된 접근입니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function orderUpdate($request)
    {
        $order_num = !empty($request['order_num']) ? $request['order_num'] : '';
        $state = !empty($request['state']) ? $request['state'] : '';
        try {
            if (empty($order_num) || empty($state)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $payment_check = $this->goodsInfo->checkPayment($order_num);
            if ($state == '98' || $state == '99') {
                if (empty($payment_check)) {
                    throw new Exception('결제정보가 존재하지 않습니다.', 701);
                } else {
                    $return_data['paymentKey'] = $payment_check;
                    return $return_data;
                }
            } else {
                $sql = "UPDATE order_goodsT SET order_state = '{$state}' WHERE order_num = '{$order_num}'";
                $result = $this->goodsInfo->db->execute($sql);
            }

            if ($result) {
                $return_data['msg'] = "주문정보가 수정되었습니다.";
            } else {
                throw new Exception('주문정보 수정에 실패했습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$goodsController = new GoodsController();

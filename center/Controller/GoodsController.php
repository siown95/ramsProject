<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Goods.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class GoodsController extends Controller
{
    private $goodsInfo;

    function __construct()
    {
        $this->goodsInfo = new GoodsInfo();
    }

    public function goodsListLoad()
    {
        try {
            $result = $this->goodsInfo->goodsListLoad();
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['cost_price'] = !empty($val['cost_price']) ? number_format($val['cost_price']) : '0';
                    $result[$key]['sel_price'] = number_format($val['sel_price']);
                    $result[$key]['check_goods'] = "<input type=\"checkbox\" class=\"form-check-input\" name=\"goodsChk\" value=\"" . $val['goods_idx'] . "\"/>";
                    if (!empty($result[$key]['img_link'])) {
                        $result[$key]['img_link'] = "<a class=\"btn btn-sm btn-outline-info\" href=\"javascript:showImgWin('/files/goods_image/" . $val['img_link'] . "')\"><i class=\"fa-solid fa-image me-1\"></i>미리보기</a>";
                    }
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

    public function goodsListAdd($request)
    {
        $goods_idxs = !empty($request['goods_idxs']) ? $request['goods_idxs'] : '';
        try {
            if (empty($goods_idxs)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->goodsInfo->goodsListAdd($goods_idxs);
            $tbl = '';
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $tbl .= "<tr class=\"align-middle text-center\" data-goods-idx=\"" . $val['goods_idx'] . "\">
                            <td>" . ($key + 1) . "</td>
                            <td class=\"text-start\">" . $val['goods_name'] . "</td>
                            <td>" . $val['min_quantity'] . "</td>
                            <td><input type=\"text\" class=\"form-control text-end goodsNo\" value=\"" . $val['min_quantity'] . "\" /></td>
                            <td>" . $val['order_unit'] . "</td>
                            <td>" . number_format($val['sel_price']) . "</td>
                            <td>" . number_format($val['min_quantity'] * $val['sel_price'] * 1.1) . "</td>
                            <td><button type=\"button\" class=\"btn btn-sm btn-outline-danger btnOrderItemCancel\"><i class=\"fa-solid fa-xmark me-1\"></i>취소</button></td>
                            </tr>";
                }
                $result['tbl'] = $tbl;
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadOrderGoodsList($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $dtOrderMonth = !empty($request['dtOrderMonth']) ? $request['dtOrderMonth'] : '';

        try {
            if (empty($franchise_idx) || empty($dtOrderMonth)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->goodsInfo->loadOrderGoodsList($franchise_idx, $dtOrderMonth);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['pay_money'] = number_format($val['pay_money'] + $val['shipping_money']);
                    $result[$key]['order_money'] = number_format($val['order_money'] + $val['shipping_money']);
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function goodsOrderInsert($request)
    {
        $goods_idxs = !empty($request['goods_idxs']) ? $request['goods_idxs'] : '';
        $goods_quantitys = !empty($request['goods_quantitys']) ? $request['goods_quantitys'] : '';
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $teacher_idx = !empty($request['teacher_idx']) ? $request['teacher_idx'] : '';
        $teacher_name = !empty($request['teacher_name']) ? $request['teacher_name'] : '';
        $teacher_phone = !empty($request['teacher_phone']) ? $request['teacher_phone'] : '';
        $txtDeliveryFee = !empty($request['txtDeliveryFee']) ? $request['txtDeliveryFee'] : '';
        $txtOrderAddr = !empty($request['txtOrderAddr']) ? $request['txtOrderAddr'] : '';
        $txtOrderZipCode = !empty($request['txtOrderZipCode']) ? $request['txtOrderZipCode'] : '';
        $txtOrderEtc = !empty($request['txtOrderEtc']) ? $request['txtOrderEtc'] : '';

        try {
            if (
                empty($goods_idxs) || empty($goods_quantitys) || empty($franchise_idx) || empty($teacher_idx) || empty($teacher_name)
                || empty($teacher_phone) || empty($txtOrderAddr) || empty($txtOrderZipCode)
            ) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $order_num = "o" . date("YmdHis") . "_" . $franchise_idx;

            $shipping_params = array(
                "order_num" => $order_num,
                "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                "teacher_idx" => !empty($teacher_idx) ? $teacher_idx : '',
                "teacher_name" => !empty($teacher_name) ? $teacher_name : '',
                "teacher_phone" => !empty($teacher_phone) ? phoneFormat($teacher_phone) : '',
                "shipping_zipcode" => !empty($txtOrderZipCode) ? $txtOrderZipCode : '',
                "shipping_address" => !empty($txtOrderAddr) ? $txtOrderAddr : '',
                "shipping_state" => '02',
                "shipping_memo" => !empty($txtOrderEtc) ? $txtOrderEtc : '',
            );

            $this->goodsInfo->table_name = "goods_shippingT";
            $shipping_result = $this->goodsInfo->insert($shipping_params);

            if ($shipping_result) {
                $this->goodsInfo->table_name = "goodsM";
                $goodsListInfo = $this->goodsInfo->goodsListAdd($goods_idxs);

                foreach ($goodsListInfo as $key => $val) {
                    foreach ($goods_quantitys as $key2 => $val2) {
                        if ($val2['goods_idx'] == $val['goods_idx']) {
                            $order_array = array(
                                "order_num" => $order_num,
                                "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                                "teacher_idx" => !empty($teacher_idx) ? $teacher_idx : '',
                                "teacher_name" => !empty($teacher_name) ? $teacher_name : '',
                                "goods_idx" => $val['goods_idx'],
                                "goods_name" => $val['goods_name'],
                                "order_quantity" => $val2['goods_quantity'],
                                "order_money" => $val['sel_price'],
                                "order_method" => '',
                                "order_state" => '01',
                                "pay_date" => '',
                            );
                            $this->goodsInfo->table_name = "order_goodsT";
                            $result = $this->goodsInfo->insert($order_array);
                        }
                    }
                }

                if ($txtDeliveryFee > 0) {
                    $order_array = array(
                        "order_num" => $order_num,
                        "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                        "teacher_idx" => !empty($teacher_idx) ? $teacher_idx : '',
                        "teacher_name" => !empty($teacher_name) ? $teacher_name : '',
                        "goods_idx" => '237',
                        "goods_name" => '배송비',
                        "order_quantity" => '1',
                        "order_money" => '5000',
                        "order_method" => '01',
                        "order_state" => '01',
                        "pay_date" => date("Y-m-d"),
                    );
                    $this->goodsInfo->table_name = "order_goodsT";
                    $result = $this->goodsInfo->insert($order_array);
                }

                if ($result) {
                    $return_data['msg'] = "주문 정보가 등록되었습니다.\n결제로 진행하시려면 확인, 임시로 저장해두시려면 취소를 눌러주세요.";
                    $return_data['order_num'] = $order_num;
                } else {
                    throw new Exception('주문에 실패하였습니다.', 701);
                }
            } else {
                throw new Exception('배송정보 입력에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function selectOrderInfo($request)
    {
        $order_num = !empty($request['order_num']) ? $request['order_num'] : '';

        try {
            if (empty($order_num)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $order_info = $this->goodsInfo->selectOrderInfo($order_num);
            $order_list = $this->goodsInfo->selectOrderList($order_num);

            $tbl = '';
            if (!empty($order_list)) {
                foreach ($order_list as $key => $val) {
                    $tbl .= "<tr class=\"align-middle text-center\" data-goods-idx=\"" . $val['goods_idx'] . "\">
                            <td>" . ($key + 1) . "</td>
                            <td class=\"text-start\">" . $val['goods_name'] . "</td>
                            <td>" . $val['min_quantity'] . "</td>
                            <td><input type=\"text\" class=\"form-control text-end goodsNo\" value=\"" . $val['order_quantity'] . "\" /></td>
                            <td>" . $val['order_unit'] . "</td>
                            <td>" . number_format($val['sel_price']) . "</td>
                            <td>" . number_format(($val['order_money'] * $val['order_quantity']) * 1.1) . "</td>
                            <td><button type=\"button\" class=\"btn btn-sm btn-outline-danger btnOrderItemCancel\"><i class=\"fa-solid fa-xmark me-1\"></i>취소</button></td>
                            </tr>";
                }
            }
            
            if($order_info['order_money'] < 100000){
                $order_info['order_money'] = $order_info['order_money'] + 5000;
            }
            $return_data['order_info'] = $order_info;
            $return_data['order_list'] = $tbl;

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$goodsController = new GoodsController();

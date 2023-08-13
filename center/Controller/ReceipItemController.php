<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/ReceiptItem.php";

class ReceiptItemController extends Controller
{
    private $receiptItemInfo;

    function __construct()
    {
        $this->receiptItemInfo = new ReceiptItemtInfo();
    }

    public function receiptItemInsert($request)
    {
        $sel_center           = !empty($request['sel_center']) ? $request['sel_center'] : "";
        $selItem1             = !empty($request['selItem1']) ? $request['selItem1'] : "";
        $selClassType         = !empty($request['selClassType']) ? $request['selClassType'] : "";
        $selGrade             = !empty($request['selGrade']) ? $request['selGrade'] : "";
        $txtReceiptItemName   = !empty($request['txtReceiptItemName']) ? $request['txtReceiptItemName'] : "";
        $txtReceiptItemAmount = !empty($request['txtReceiptItemAmount']) ? $request['txtReceiptItemAmount'] : "";

        try {
            if (empty($sel_center) || empty($selItem1) || empty($txtReceiptItemName) || empty($txtReceiptItemAmount)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if ($selItem1 != '99') {
                if (empty($selClassType) || empty($selGrade)) {
                    throw new Exception('필수값이 누락되었습니다.', 701);
                }

                $receiptOverlap = $this->receiptItemInfo->receiptItemCheck($selItem1, $selClassType, $selGrade, $sel_center);

                if ($receiptOverlap > 0) {
                    throw new Exception('이미 등록된 항목입니다.', 701);
                }
            }

            $params = array(
                "franchise_idx" => !empty($sel_center) ? $sel_center : '',
                "receipt_type" => !empty($selItem1) ? $selItem1 : '',
                "receipt_lesson_type" => !empty($selClassType) ? $selClassType : '',
                "receipt_grade" => !empty($selGrade) ? $selGrade : '',
                "receipt_name" => !empty($txtReceiptItemName) ? trim($txtReceiptItemName) : '',
                "receipt_amount" => !empty($txtReceiptItemAmount) ? $txtReceiptItemAmount : '',
            );

            $result = $this->receiptItemInfo->insert($params);

            if ($result) {
                $return_data['msg'] = '수납항목이 등록되었습니다.';
            } else {
                throw new Exception('수납항목 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function receiptItemUpdate($request)
    {
        $targetReceipt        = !empty($request['targetReceipt']) ? $request['targetReceipt'] : "";
        $selItem1             = !empty($request['selItem1']) ? $request['selItem1'] : "";
        $selReceiptUse        = !empty($request['selReceiptUse']) ? $request['selReceiptUse'] : "";
        $selClassType         = !empty($request['selClassType']) ? $request['selClassType'] : "";
        $selGrade             = !empty($request['selGrade']) ? $request['selGrade'] : "";
        $txtReceiptItemName   = !empty($request['txtReceiptItemName']) ? $request['txtReceiptItemName'] : "";
        $txtReceiptItemAmount = !empty($request['txtReceiptItemAmount']) ? $request['txtReceiptItemAmount'] : "";

        try {
            if (empty($targetReceipt) || empty($selItem1) || empty($txtReceiptItemName) || empty($txtReceiptItemAmount)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if ($selItem1 != '99') {
                if (empty($selClassType) || empty($selGrade)) {
                    throw new Exception('필수값이 누락되었습니다.', 701);
                }

                $receiptUpdateData = $this->receiptItemInfo->receiptItemSelect($targetReceipt);

                if (!(($receiptUpdateData['receipt_type'] == $selItem1) && ($receiptUpdateData['receipt_lesson_type'] == $selClassType) && ($receiptUpdateData['receipt_grade'] == $selGrade))) {
                    $receiptOverlap = $this->receiptItemInfo->receiptItemCheck($selItem1, $selClassType, $selGrade, $receiptUpdateData['franchise_idx']);

                    if ($receiptOverlap > 0) {
                        throw new Exception('이미 등록된 항목입니다.', 701);
                    }
                }
            }

            $params = array(
                "receipt_type" => !empty($selItem1) ? $selItem1 : '',
                "receipt_lesson_type" => !empty($selClassType) ? $selClassType : '',
                "receipt_grade" => !empty($selGrade) ? $selGrade : '',
                "receipt_name" => !empty($txtReceiptItemName) ? trim($txtReceiptItemName) : '',
                "receipt_amount" => !empty($txtReceiptItemAmount) ? $txtReceiptItemAmount : '',
                "useYn" => !empty($selReceiptUse) ? $selReceiptUse : '',
                "mod_date" => "getdate()"
            );

            $this->receiptItemInfo->where_qry = "receipt_item_idx = '" . $targetReceipt . "'";
            $result = $this->receiptItemInfo->update($params);

            if ($result) {
                $return_data['msg'] = '수납항목이 수정되었습니다.';
            } else {
                throw new Exception('수납항목 수정에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function receiptItemLoad($request)
    {
        $sel_center = !empty($request['sel_center']) ? $request['sel_center'] : "";

        try {
            if (empty($sel_center)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->receiptItemInfo->receiptItemLoad($sel_center);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function receiptItemSelect($request)
    {
        $targetReceipt = !empty($request['targetReceipt']) ? $request['targetReceipt'] : "";

        try {
            if (empty($targetReceipt)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->receiptItemInfo->receiptItemSelect($targetReceipt);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function receiptItemBatch($request)
    {
        $sel_center = !empty($request['sel_center']) ? $request['sel_center'] : "";

        try {
            if (empty($sel_center)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }


            $this->receiptItemInfo->where_qry = " franchise_idx = '" . $sel_center . "' and headYn = 'Y' ";
            $delete_result = $this->receiptItemInfo->delete();

            if ($delete_result) {
                $result = $this->receiptItemInfo->receiptItemBatch($sel_center);

                if ($result) {
                    $return_data['msg'] = "수납항목이 배포되었습니다.";
                } else {
                    throw new Exception('수납항목 배포에 실패하였습니다.', 701);
                }
            } else {
                throw new Exception('수납항목 배포에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getReceiptList($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : "";
        $grade = !empty($request['grade']) ? $request['grade'] : "";
        try {
            if (empty($center_idx) || empty($grade)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->receiptItemInfo->getReceiptList($center_idx, $grade);

            $selectOption = "<option value=\"\">선택</option>";
            if(!empty($result)){
                foreach($result as $key => $val){
                    $selectOption .= "<option value=\"{$val['receipt_item_idx']}\">{$val['receipt_name']}</option>";
                }
            }
            $return_data['selOption'] = $selectOption;

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$receiptItemController = new ReceiptItemController();

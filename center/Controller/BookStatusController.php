<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/BookStatus.php";

class BookStatusController extends Controller
{
    private $bookStatusInfo;

    function __construct()
    {
        $this->bookStatusInfo = new BookStatusInfo();
    }

    public function bookStatusLoad($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';

        try {
            if (empty($franchise_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookStatusInfo->bookStatusLoad($franchise_idx);

            if (!empty($result)) {
                $num = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $num--;
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function statusSelect($request)
    {
        $status_idx = !empty($request['status_idx']) ? $request['status_idx'] : '';

        try {
            if (empty($status_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookStatusInfo->statusSelect($status_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function statusUpdate($request)
    {
        $status_idx = !empty($request['status_idx']) ? $request['status_idx'] : '';
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $book_barcode = !empty($request['book_barcode']) ? sprintf("%05d", $request['book_barcode']) : '';

        try {
            if (empty($status_idx) || empty($book_barcode)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $barcodeCheck = $this->bookStatusInfo->barcodeCheck($franchise_idx, $book_barcode);

            if ($barcodeCheck) {
                throw new Exception('중복된 관리번호입니다.', 701);
            }

            $params = array(
                "book_barcode" => !empty($book_barcode) ? $book_barcode : '',
                "mod_date" => 'getdate()'
            );

            $this->bookStatusInfo->where_qry = " status_idx = '" . $status_idx . "'";
            $result = $this->bookStatusInfo->update($params);

            if ($result) {
                $return_data['msg'] = "정보가 수정되었습니다.";
            } else {
                throw new Exception('정보 수정에 실패하였습니다..', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function BookBarcodeCreate($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        try {
            if (empty($franchise_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $last_barcode = $this->bookStatusInfo->lastBarcode($franchise_idx);
            $empty_barcode_list = $this->bookStatusInfo->getEmptyBarcode($franchise_idx);
            $barcode = (int)$last_barcode;
            $chk = false;
            foreach ($empty_barcode_list as $key => $val) {
                $barcode++;
                $barcode_set = sprintf("%05d", $barcode);
                $sql = "UPDATE book_statusT SET book_barcode = '{$barcode_set}', mod_date = GETDATE() WHERE franchise_idx = '{$franchise_idx}' AND status_idx = '{$val['status_idx']}'";
                $result = $this->bookStatusInfo->db->execute($sql);
                if (!$result) {
                    throw new Exception('바코드 정보 생성에 실패하였습니다.', 701);
                } else {
                    $chk = true;
                }
            }
            if ($chk) {
                $return_data['msg'] = '바코드 정보가 자동 생성되었습니다.';
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$bookStatusController = new BookStatusController();

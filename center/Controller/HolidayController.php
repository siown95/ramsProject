<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Holiday.php";

class HolidayController extends Controller
{
    private $holidayInfo;

    function __construct()
    {
        $this->holidayInfo = new HolidayInfo();
    }

    public function holidayLoad($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        try {
            if (empty($franchise_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->holidayInfo->holidayLoad($franchise_idx);

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['btnDelete'] = "<button type=\"button\" class=\"btn btn-sm btn-outline-danger btnholidaydel\"><i class=\"fas fa-trash me-1\"></i>삭제</button>";
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function holidayCount($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $holiday_date = !empty($request['holiday_date']) ? $request['holiday_date'] : '';

        try {
            if (empty($franchise_idx) || empty($holiday_date)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->holidayInfo->holidayCount($franchise_idx, $holiday_date);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function holidayInsert($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';
        $holiday_date = !empty($request['holiday_date']) ? $request['holiday_date'] : '';
        $holiday_memo = !empty($request['memo']) ? $request['memo'] : '';

        try {
            if (empty($franchise_idx) || empty($user_idx) || empty($holiday_date)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $chk = $this->holidayCount($request);
            if ($chk > 0) {
                throw new Exception('이미 휴일로 등록되어 있습니다.', 701);
            }

            $params = array(
                "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                "reg_user_idx"  => !empty($user_idx) ? $user_idx : '',
                "holiday_date" => !empty($holiday_date) ? $holiday_date : '',
                "holiday_memo" => !empty($holiday_memo) ? $holiday_memo : ''
            );

            $result = $this->holidayInfo->insert($params);

            if ($result) {
                $return_data['msg'] = '휴일이 등록되었습니다.';
            } else {
                throw new Exception('휴일 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function holidayDelete($request)
    {
        $holiday_idx = !empty($request['holiday_idx']) ? $request['holiday_idx'] : '';
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_idx = !empty($request['user_idx']) ? $request['user_idx'] : '';

        try {
            if (empty($holiday_idx) || empty($franchise_idx) || empty($user_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "del_user_idx"  => !empty($user_idx) ? $user_idx : '',
                "useYn" => '',
                "mod_date" => 'getdate()'
            );

            $this->holidayInfo->where_qry = " holiday_idx = '" . $holiday_idx . "' AND franchise_idx = '" . $franchise_idx . "'";
            $result = $this->holidayInfo->update($params);

            if ($result) {
                $return_data['msg'] = '휴일이 삭제되었습니다.';
            } else {
                throw new Exception('휴일 삭제에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$holidayController = new HolidayController();

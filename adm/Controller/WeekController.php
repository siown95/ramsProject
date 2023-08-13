<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Week.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class WeekController extends Controller
{
    private $weekInfo;

    function __construct()
    {
        $this->weekInfo = new WeekInfo();
    }

    public function getWeekYearData($request)
    {
        $result = $this->weekInfo->getWeekYearData();
        if (!empty($result)) {
            $options = "";
            foreach ($result as $key => $val) {
                $options .= "<option value=\"" . $val['weekYear'] . "\">{$val['weekYear']}</option>";
            }
            $return_data['data'] = $options;
        } else {
            $return_data['msg'] = '주차 데이터가 없습니다.';
        }
        return $return_data;
    }

    public function getWeekData($request)
    {
        $year = !empty($request['year']) ? $request['year'] : '';

        try {
            if (empty($year)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->weekInfo->getWeekData($year);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function selectWeekData($request)
    {
        $week_idx = !empty($request['week_idx']) ? $request['week_idx'] : '';
        try {
            if (empty($week_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->weekInfo->selectWeekData($week_idx);
            if ($result) {
                $return_data['week_data'] = $result;
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function insertWeekData($request)
    {
        $weekyear = !empty($request['weekyear']) ? $request['weekyear'] : '';
        $weekmonth = !empty($request['weekmonth']) ? $request['weekmonth'] : '';
        $weekcount = !empty($request['weekcount']) ? $request['weekcount'] : '';
        $weekstartdate = !empty($request['weekstartdate']) ? $request['weekstartdate'] : '';
        $weekenddate = !empty($request['weekenddate']) ? $request['weekenddate'] : '';
        $weekdetail = !empty($request['weekdetail']) ? $request['weekdetail'] : '';

        if (empty($weekyear) || empty($weekmonth) || empty($weekcount) || empty($weekstartdate) || empty($weekenddate)) {
            throw new Exception('필수값이 누락되었습니다.', 701);
        }

        try {
            if (empty($week_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $params = array(
                "weekYear" => $weekyear,
                "weekMonth" => $weekmonth,
                "weekCount" => $weekcount,
                "weekStartDate" => $weekstartdate,
                "weekEndDate" => $weekenddate,
                "weekDetail" => $weekdetail
            );

            $result = $this->weekInfo->insert($params);
            if ($result) {
                $return_data['msg'] = "주차 정보가 생성되었습니다.";
            } else {
                throw new Exception('주차 정보 생성에 실패했습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function updateWeekData($request)
    {
        $week_idx = !empty($request['week_idx']) ? $request['week_idx'] : '';
        $weekyear = !empty($request['weekyear']) ? $request['weekyear'] : '';
        $weekmonth = !empty($request['weekmonth']) ? $request['weekmonth'] : '';
        $weekcount = !empty($request['weekcount']) ? $request['weekcount'] : '';
        $weekstartdate = !empty($request['weekstartdate']) ? $request['weekstartdate'] : '';
        $weekenddate = !empty($request['weekenddate']) ? $request['weekenddate'] : '';
        $weekdetail = !empty($request['weekdetail']) ? $request['weekdetail'] : '';

        try {
            if (empty($week_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $params = array(
                "weekYear" => $weekyear,
                "weekMonth" => $weekmonth,
                "weekCount" => $weekcount,
                "weekStartDate" => $weekstartdate,
                "weekEndDate" => $weekenddate,
                "weekDetail" => $weekdetail
            );

            $this->weekInfo->where_qry = " week_idx = " . $week_idx;
            $result = $this->weekInfo->update($params);
            if ($result) {
                $return_data['msg'] = "주차 정보가 수정되었습니다.";
            } else {
                throw new Exception('주차 정보 수정에 실패했습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function deleteWeekData($request)
    {
        $week_idx = !empty($request['week_idx']) ? $request['week_idx'] : '';
        try {
            if (empty($week_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $this->weekInfo->where_qry = " week_idx = " . $week_idx;
            $result = $this->weekInfo->delete();
            if ($result) {
                $return_data['msg'] = "주차 정보가 삭제되었습니다.";
            } else {
                throw new Exception('주차 정보 삭제에 실패했습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$weekController = new WeekController();

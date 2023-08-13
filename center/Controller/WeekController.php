<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Week.php";

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
}

$weekController = new WeekController();

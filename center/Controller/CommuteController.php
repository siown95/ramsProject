<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Commute.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class CommuteController extends Controller
{
    private $commuteInfo;

    function __construct()
    {
        $this->commuteInfo = new CommuteInfo();
    }

    public function selectEmployee($franchise_idx, $user_no = null)
    {
        $franchise_idx = !empty($franchise_idx) ? $franchise_idx : $_SESSION['center_idx'];

        try {
            $result = $this->commuteInfo->selectEmployee($franchise_idx, $user_no);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function insertCommuteLog($request)
    {
        $user_no       = !empty($request['user_no']) ? $request['user_no'] : '';
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $state         = !empty($request['state']) ? $request['state'] : '';

        try {
            if (empty($user_no) || empty($franchise_idx) || empty($state)) {
                throw new Exception('필수값이 누락되었습니다.', 501);
            }

            $params = array(
                "user_no"       => !empty($user_no) ? $user_no : '',
                "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                "state"         => !empty($state) ? $state : ''
            );

            $cnt = $this->commuteInfo->checkTodayLog($user_no, $franchise_idx, $state);
            if ($cnt > 0) {
                throw new Exception("이미 오늘 " . $state . "을 등록하셨습니다.", 501);
            } else {
                $result = $this->commuteInfo->insert($params);
                if ($result) {
                    $return_data['msg'] = $state . "처리가 완료되었습니다.";
                } else {
                    throw new Exception('잠시 후 다시후 다시 시도해주세요.', 501);
                }
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function commuteSelect($params)
    {
        $user_no = !empty($params['user_no']) ? $params['user_no'] : '';
        $franchise_idx = !empty($params['center_idx']) ? $params['center_idx'] : '';
        $now_date = !empty($params['now_date']) ? $params['now_date'] : date("Y-m");

        $date_list = $this->getMonthArray($now_date);

        try {
            $result = $this->commuteInfo->commuteSelect($user_no, $franchise_idx, $now_date);

            foreach ($date_list as $key => $date_val) {
                $date_list[$key]['in_time']        = '';
                $date_list[$key]['out_time']       = '';
                $date_list[$key]['work_time']      = '';
                $date_list[$key]['break_time']     = '';
                $date_list[$key]['break_day']      = '';

                $date_w = date('w', strtotime($date_val['date'])) + 1; //요일 확인 (1:일, 2:월, 3:화, 4:수, 5:목, 6:금, 7:토)
                $date_arr = array();
                foreach ($result as $key2 => $val) {
                    if ($date_w == $val['paid_holiday']) {
                        $date_list[$key]['break_day'] = '유급휴일';
                    } else if ($date_w == $val['unpaid_holiday']) {
                        $date_list[$key]['break_day'] = '무급휴일';
                    }

                    if ($date_val['date'] == $val['date']) {
                        $date_arr[] .= $val['reg_date'];

                        $in_time = $date_arr[0];
                        $out_time = $date_arr[1];

                        $work_min = !empty($out_time) ? sprintf("%.1f", (strtotime($out_time) - strtotime($in_time)) / 60) : '';
                        $work_hour = !empty($work_min) ? sprintf("%.1f", $work_min / 60) : '';
                        $work_time = '';
                        $break_time = '';

                        if (!empty($in_time) && !empty($out_time)) {
                            $work_min = !empty($out_time) ? sprintf("%d", (strtotime($out_time) - strtotime($in_time)) / 60) : '';

                            if ($work_min > 0 && $work_min < 540) {
                                $break_time = 30;
                            } else if ($work_min >= 540) {
                                $break_time = 60;
                            } else {
                                $break_time = 0;
                            }

                            $work_min = $work_min - $break_time;
                            $work_hour = !empty($work_min) ? sprintf("%.1f", $work_min / 60) : '';
                            $work_time = sprintf("%.1f", $work_hour) . " / " . $work_min;

                            $work_time = ($work_time < 0) ? '0' : $work_time;
                        }

                        $date_list[$key]['in_time']    = $in_time;
                        $date_list[$key]['out_time']   = $out_time;
                        $date_list[$key]['work_time']  = $work_time;
                        $date_list[$key]['break_time'] = $break_time;
                    }
                }
            }
            foreach ($date_list as $key => $val) {
                $date_list[$key]['date'] = $val['date'] . getWeekday($val['date']);
            }
            return $date_list;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}
$commuteController = new CommuteController();

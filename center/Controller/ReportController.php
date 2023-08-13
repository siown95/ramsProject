<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Report.php";

class ReportController extends Controller
{
    private $reportInfo;

    function __construct()
    {
        $this->reportInfo = new ReportInfo();
    }

    public function settingCheck($params)
    {
        $user_no = !empty($params['user_no']) ? $params['user_no'] : '';
        $franchise_idx = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';

        try {
            if (empty($user_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->reportInfo->settingCheck($user_no, $franchise_idx);
            if($result > 0){
                return true;
            }else{
                throw new Exception('보고서 양식이 설정되지 않았습니다.', 701);
            }
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //보고서 불러오기
    public function reportLoad($params)
    {
        try {
            $result = $this->reportInfo->reportLoad($params);
            if(!empty($result)){
                $no = count($result);
                foreach($result as $key => $val){
                    $result[$key]['no'] = $no--;
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //보고서 양식 설정(insert, update)
    public function reportSettingInsert($request)
    {
        $user_no = !empty($request['user_no']) ? $request['user_no'] : '';
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $title_array = array();

        $title_array[] .= !empty($request['title1']) ? $request['title1'] : '';
        $title_array[] .= !empty($request['title2']) ? $request['title2'] : '';
        $title_array[] .= !empty($request['title3']) ? $request['title3'] : '';
        $title_array[] .= !empty($request['title4']) ? $request['title4'] : '';
        $title_array[] .= !empty($request['title5']) ? $request['title5'] : '';
        $title_array[] .= !empty($request['title6']) ? $request['title6'] : '';
        $title_array[] .= !empty($request['title7']) ? $request['title7'] : '';
        $title_array[] .= !empty($request['title8']) ? $request['title8'] : '';
        $title_array[] .= !empty($request['title9']) ? $request['title9'] : '';
        $title_array[] .= !empty($request['title10']) ? $request['title10'] : '';

        $title_array = array_filter($title_array);

        try {
            if (empty($user_no) || empty($franchise_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $check_data = $this->reportInfo->reportSettingLoad($user_no);


            $i = 0;
            foreach($check_data as $data){
                if(!empty($data)){
                    $i++;
                }
            }
            
            $params = array(
                "user_no" => !empty($user_no) ? $user_no : '',
                "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                "title_array" => !empty($title_array) ? $title_array : ''
            );

            if (!empty($check_data)) {
                $params['title_cnt'] = $i;
                $result = $this->reportInfo->reportSettingUpdate($params);
            } else {
                $result = $this->reportInfo->reportSettingInsert($params);
            }

            if ($result) {
                $return_data['msg'] = '양식이 설정되었습니다.';
            } else {
                throw new Exception('양식이 설정에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //보고서 양식 로드
    public function reportSettingLoad($user_no)
    {
        try {
            if (empty($user_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->reportInfo->reportSettingLoad($user_no);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //보고서 등록
    public function reportInsert($request)
    {
        $user_no = !empty($request['user_no']) ? $request['user_no'] : '';
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $title_arr = !empty($request['title_arr']) ? $request['title_arr'] : '';
        $contents_arr = !empty($request['contents_arr']) ? $request['contents_arr'] : '';

        try {
            if (empty($user_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "user_no" => $user_no,
                "franchise_idx" => $franchise_idx,
                "months" => date("Y-m"),
                "title_arr" => $title_arr,
                "contents_arr" => $contents_arr
            );

            $result = $this->reportInfo->reportInsert($params);

            if ($result) {
                $return_data['msg'] = "보고서가 등록되었습니다.";
            } else {
                throw new Exception('보고서 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //보고서 수정
    public function reportUpdate($request)
    {
        $report_idx = !empty($request['report_idx']) ? $request['report_idx'] : '';
        $contents_arr = !empty($request['contents_arr']) ? $request['contents_arr'] : '';

        try {
            if (empty($report_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "report_idx" => $report_idx,
                "contents_arr" => $contents_arr
            );

            $result = $this->reportInfo->reportUpdate($params);

            if ($result) {
                $return_data['msg'] = "보고서가 수정되었습니다.";
            } else {
                throw new Exception('보고서 수정에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function reportDelete($request)
    {
        $report_idx = !empty($request['report_idx']) ? $request['report_idx'] : '';

        try {
            if (empty($report_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->reportInfo->reportDelete($report_idx);

            if ($result) {
                $return_data['msg'] = "보고서가 삭제되었습니다.";
            } else {
                throw new Exception('보고서 삭제에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //이전 작성내용 로드
    public function reportContentsLoad($user_no)
    {
        try {
            if (empty($user_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->reportInfo->reportContentsLoad($user_no);

            $result_array = array();
            
            if (!empty($result)) {
                for($i = 1; $i <= 10; $i++){
                    if(!empty($result["title".$i.""])){
                        $result_array[$i]["title".$i.""] = $result["title".$i.""];
                        $result_array[$i]["content".$i.""] = $result["content".$i.""];
                    }
                }
            }

            return $result_array;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //데이터 로드
    public function selectReportData($user_no, $report_idx)
    {
        try {
            if (empty($user_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->reportInfo->selectReportData($user_no, $report_idx);

            $report_data_array = array();
            
            if (!empty($result['report_data'])) {
                for($i = 1; $i <= 10; $i++){
                    if(!empty($result['report_data']["title".$i.""])){
                        $report_data_array['report_data'][$i]["title".$i.""] = $result['report_data']["title".$i.""];
                        $report_data_array['report_data'][$i]["content".$i.""] = $result['report_data']["content".$i.""];
                    }
                }
            }

            if (!empty($result['pre_report_data'])) {
                for($i = 1; $i <= 10; $i++){
                    if(!empty($result['pre_report_data']["title".$i.""])){
                        $report_data_array['pre_report_data'][$i]["title".$i.""] = $result['pre_report_data']["title".$i.""];
                        $report_data_array['pre_report_data'][$i]["content".$i.""] = $result['pre_report_data']["content".$i.""];
                    }
                }
            }

            return $report_data_array;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}


$reportController = new ReportController();
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center//student/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center//student/Model/Counsel.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class CounselController extends Controller
{
    private $counselInfo;

    function __construct()
    {
        $this->counselInfo = new CounselInfo();
    }

    public function counselLoad($request)
    {
        $student_no    = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($student_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->counselInfo->counselLoad($student_no);

            if (!empty($result)) {
                $counsel_kind_arr = array(
                    "1" => "정기상담",
                    "2" => "사안상담",
                    "9" => "퇴원상담"
                );

                $no = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $no--;
                    $result[$key]['counsel_kind'] = $counsel_kind_arr[$val['counsel_kind']];
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadRecentCounselMain($request)
    {
        $student_no    = !empty($request['student_no']) ? $request['student_no'] : '';
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        try {
            if (empty($student_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->counselInfo->loadRecentCounselMain($franchise_idx, $student_no);
            $tbl = '';
            if (!empty($result)) {
                $counsel_kind_arr = array(
                    "1" => "정기상담",
                    "2" => "사안상담",
                    "9" => "퇴원상담"
                );

                $no = count($result);
                foreach ($result as $key => $val) {
                    $tbl .= "<tr class=\"align-middle text-center\"><td>{$no}</td><td>{$counsel_kind_arr[$val['counsel_kind']]}</td><td>{$val['teacher_name']}</td><td>{$val['counsel_date']}</td></tr>";
                    $no--;
                }
                $result['tbl'] = $tbl;
            } else {
                $tbl = "<tr class=\"align-middle text-center\"><td colspan=\"4\">최근 상담내역이 없습니다.</td></tr>";
                $result['tbl'] = $tbl;
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function counselSelect($request)
    {
        $counsel_idx = !empty($request['counsel_idx']) ? $request['counsel_idx'] : '';

        try {
            if (empty($counsel_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->counselInfo->counselSelect($counsel_idx);
            if (!empty($result)) {

                $counsel_kind_arr = array(
                    "1" => "정기상담",
                    "2" => "사안상담",
                    "9" => "퇴원상담"
                );

                $result['counsel_kind'] = $counsel_kind_arr[$result['counsel_kind']];
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function counselUpdate($request)
    {
        $counsel_idx  = !empty($request['counsel_idx']) ? $request['counsel_idx'] : '';
        $counsel_request = !empty($request['counsel_request']) ? $request['counsel_request'] : '';

        try {
            if (empty($counsel_idx) || empty($counsel_request)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "counsel_request" => !empty($counsel_request) ? $counsel_request : '',
                "mod_date" => 'getdate()'
            );

            $this->counselInfo->where_qry = " counsel_idx = '" . $counsel_idx . "' ";
            $result = $this->counselInfo->update($params);

            if ($result) {
                $return_data['msg'] = "요청사항이 등록 되었습니다.";
            } else {
                throw new Exception('요청사항 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$counselController = new CounselController();

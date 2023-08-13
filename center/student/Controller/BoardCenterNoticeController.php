<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/BoardCenterNotice.php";

class BoardCenterNoticeController extends Controller
{
    private $boardCenterNoticeInfo;

    function __construct()
    {
        $this->boardCenterNoticeInfo = new BoardCenterNoticeInfo();
    }

    public function loadCenterNoticedMain($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($franchise_idx) || empty($student_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->boardCenterNoticeInfo->loadCenterNoticedMain($franchise_idx, $student_no);

            $tbl = '';

            if (!empty($result)) {
                $cnt = count($result);
                foreach ($result as $key => $val) {
                    $tbl .= "<tr>
                                 <td>" . $cnt-- . "</td>
                                 <td class=\"text-start\">" . $val['notice_title'] . "</td>
                                 <td>" . date("Y-m-d", strtotime($val['reg_date'])) . "</td>
                             </tr>";
                }
            } else {
                $tbl = "<td colspan='3'>등록된 알림이 없습니다</td>";
            }

            $result['tbl'] = $tbl;

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function centerNoticeLoad($request)
    {
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($franchise_idx) || empty($student_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->boardCenterNoticeInfo->centerNoticeLoad($franchise_idx, $student_no);

            if (!empty($result)) {
                $cnt = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $cnt--;
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function centerNoticeSelect($request)
    {
        $notice_idx = !empty($request['notice_idx']) ? $request['notice_idx'] : '';

        try {
            if (empty($notice_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->boardCenterNoticeInfo->centerNoticeSelect($notice_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$boardCenterNoticeController = new BoardCenterNoticeController();

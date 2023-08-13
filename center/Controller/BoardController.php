<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Board.php";

class BoardController extends Controller
{
    private $boardInfo;

    function __construct()
    {
        $this->boardInfo = new BoardInfo();
    }

    //공지사항 불러오기
    public function loadBoard($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $target_type = !empty($request['target_type']) ? $request['target_type'] : '';

        try {
            $result = $this->boardInfo->loadBoard($center_idx, $target_type);

            if (!empty($result)) {
                $no = count($result);
                foreach ($result as $key => $val) {
                    if (!empty($result[$key]['files'])) {
                        $result[$key]['file_link'] = "<a class=\"btn btn-outline-primary btn-sm\" href=\"/files/notice_file/" . $val['files'] . "\" download><i class=\"fa-solid fa-file-arrow-down\"></i> 다운로드</a>";
                    } else {
                        $result[$key]['file_link'] = '';
                    }
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                    $result[$key]['no'] = $no--;
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadBoardMain($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $target_type = !empty($request['target_type']) ? $request['target_type'] : '';

        try {
            $result = $this->boardInfo->loadBoardMain($center_idx, $target_type);

            if (!empty($result)) {
                $no = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                    $result[$key]['no'] = $no--;
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //자료실 리스트 불러오기
    public function loadFileBoard()
    {
        try {
            $result = $this->boardInfo->loadFileBoard();

            if (!empty($result)) {
                $no = count($result);
                foreach ($result as $key => $val) {
                    if (!empty($result[$key]['code_name2'])) {
                        $result[$key]['title'] = "&#91;" . $val['code_name2'] . "&#93; " . $val['title'];
                    }

                    if (!empty($result[$key]['files'])) {
                        $result[$key]['file_link'] = "<a class=\"btn btn-outline-primary btn-sm\" href=\"/files/board_file/" . $val['files'] . "\" download><i class=\"fa-solid fa-file-arrow-down\"></i> 다운로드</a>";
                    } else {
                        $result[$key]['file_link'] = '';
                    }
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                    $result[$key]['no'] = $no--;
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function boardSelect($params)
    {
        $board_idx = !empty($params['board_idx']) ? $params['board_idx'] : '';

        try {
            if (empty($board_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->boardInfo->boardSelect($board_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function fileBoardSelect($params)
    {
        $board_idx = !empty($params['board_idx']) ? $params['board_idx'] : '';

        try {
            if (empty($board_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->boardInfo->fileBoardSelect($board_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$boardController = new BoardController();
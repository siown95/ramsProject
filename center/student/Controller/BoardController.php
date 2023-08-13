<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Board.php";

class BoardController extends Controller
{
    private $boardInfo;

    function __construct()
    {
        $this->boardInfo = new BoardInfo();
    }

    //공지사항 불러오기
    public function loadBoard()
    {
        try {
            $result = $this->boardInfo->loadBoard();

            if (!empty($result)) {
                $no = count($result);
                foreach ($result as $key => $val) {
                    if (!empty($result[$key]['files'])) {
                        $result[$key]['file_link'] = "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/notice_file/" . $val['files'] . "\" download><i class=\"fa-solid fa-file-arrow-down\"></i> 다운로드</a>";
                    } else {
                        $result[$key]['file_link'] = '';
                    }
                    $result[$key]['no'] = $no--;
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //글 선택 공용함수
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

    public function loadBoardMain()
    {
        try {
            $result = $this->boardInfo->loadBoardMain();
            $tbl = '';
            if (!empty($result)) {
                $no = count($result);
                foreach ($result as $key => $val) {
                    $tbl .= "<tr>
                                 <th class=\"text-center\">" . $no-- . "</th>
                                 <td class=\"text-start\">" . $val['title'] . "</td>
                                 <td class=\"text-center\">" . date("Y-m-d", strtotime($val['reg_date'])) . "</td>
                             </tr>";
                }
            }
            $result['tbl'] = $tbl;

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$boardController = new BoardController();
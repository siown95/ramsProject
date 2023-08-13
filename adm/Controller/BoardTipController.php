<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/BoardTip.php";

class BoardTipController extends Controller
{
    private $boardTipInfo;

    function __construct()
    {
        $this->boardTipInfo = new BoardTipInfo();
    }

    public function boardTipLoad()
    {
        try {
            $result_notice = $this->boardTipInfo->boardTipNoticeLoad(); // 공지
            $result_board = $this->boardTipInfo->boardTipLoad(); // 일반

            $notice = '';
            if (!empty($result_notice)) {
                foreach ($result_notice as $key => $val) {
                    $head = '';
                    $down_icon = '';

                    $val['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));

                    if (!empty($val['code_name'])) {
                        $head = "[" . $val['code_name'] . "] ";
                    } else {
                        $head = "";
                    }

                    if (!empty($val['file_name'])) {
                        $down_icon = "<span class=\"badge rounded-pill bg-success ms-2\"><i class=\"fa-solid fa-paperclip\"></i></span>";
                    } else {
                        $down_icon = "";
                    }

                    $title = "[공지] " . $head . $val['title']
                        . "<span class=\"badge rounded-pill bg-primary ms-2\"><i class=\"fa-regular fa-comment me-1\"></i>"
                        . $val['cnt1'] . "<i class=\"fa-regular fa-thumbs-up ms-1 me-1\"></i>" . (!empty($val['cnt2']) ? $val['cnt2'] : '0') . "</span>" . $down_icon;


                    $notice .= "<tr class=\"table-warning tc align-middle text-center\" data-board-idx=\"" . $val['board_idx'] . "\">
                                  <th>공지</th>
                                  <td class='text-start'><strong>" . $title . "</strong></td>
                                  <th>" . $val['writer'] . "</th>
                                  <th>" . $val['reg_date'] . "</th>
                              </tr>";
                }

                $return_data['notice'] = $notice;
            }

            $board = '';
            if (!empty($result_board)) {
                $no = count($result_board);
                foreach ($result_board as $key => $val) {
                    $head = '';
                    $notice_head = '';

                    $val['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));

                    if (!empty($val['code_name'])) {
                        $head = "[" . $val['code_name'] . "] ";
                    } else {
                        $head = "";
                    }

                    if (!empty($val['file_name'])) {
                        $down_icon = "<span class=\"badge rounded-pill bg-success ms-2\"><i class=\"fa-solid fa-paperclip\"></i></span>";
                    } else {
                        $down_icon = "";
                    }

                    if (!empty($val['user_name'] && $val['center_name'])) {
                        $writer = $val['user_name'] . "(" . $val['center_name'] . ")";
                    } else {
                        $writer = $val['user_name'];
                    }

                    if ($result_board[$key]['notice_yn'] == 'Y') {
                        $writer = '본사담당자';
                        $notice_head = '[공지] ';
                    }

                    $title = $val['title']
                        . "<span class=\"badge rounded-pill bg-primary ms-2\"><i class=\"fa-regular fa-comment me-1\"></i>"
                        . $val['cnt1'] . "<i class=\"fa-regular fa-thumbs-up ms-1 me-1\"></i>" . (!empty($val['cnt2']) ? $val['cnt2'] : '0') . "</span>" . $down_icon;

                    $board .= "<tr class=\"tc align-middle text-center\" data-board-idx=\"" . $val['board_idx'] . "\">
                                   <td>" . $no-- . "</td>
                                   <td class='text-start'>" . $notice_head . $head . $title . "</td>
                                   <td>" . $writer . "</td>
                                   <td>" . $val['reg_date'] . "</td>
                               </tr>";
                }


                $return_data['board'] = $board;
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function boardTipInsert($request)
    {
        $user_no = !empty($request['user_no']) ? $request['user_no'] : $_SESSION['logged_no'];
        $title = !empty($request['title']) ? $request['title'] : '';
        $board_kind = !empty($request['board_kind']) ? $request['board_kind'] : '';
        $contents = !empty($request['contents']) ? str_replace("﻿", "", $request['contents'] ) : '';
        $notice_yn = !empty($request['notice_yn']) ? $request['notice_yn'] : '';

        try {
            if (empty($user_no) || empty($title) || empty($contents)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if (!empty($request['files'])) {
                $data = array();
                $file_name = array();
                $b_direc = date("YmdHis") . "_file";
                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/tip_file/" . $b_direc . "/";

                for ($i = 0; $i < count($request['files']); $i++) {
                    if (strpos($request['files'][$i], "base64")) {
                        $nameArr = explode(",", $request['files'][$i]);
                        $data[] .= base64_decode($nameArr[1]);
                        $file_name[] .= 'bfile_' . date("YmdHis") . "_" . $i . ".png";
                    } else {
                        $type = pathinfo($request['files'][$i], PATHINFO_EXTENSION);
                        $dataInfo = file_get_contents($request['files'][$i]);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataInfo);

                        $nameArr = explode(",", $base64);
                        $data[] .= base64_decode($nameArr[1]);
                        $file_name[] .= 'bfile_' . date("YmdHis") . "_" . $i . ".png";
                    }

                    if (!is_dir($path)) {
                        $dir = mkdir($path, 775);
                        if ($dir) {
                            if (file_put_contents($path . $file_name[$i], $data[$i])) {
                                $contents = str_replace($request['files'][$i], "/files/tip_file/" . $b_direc . "/" . $file_name[$i], $contents);
                            } else {
                                throw new Exception('게시글 저장이 실패하였습니다.123', 701);
                            }
                        }
                    } else {
                        if (file_put_contents($path . $file_name[$i], $data[$i])) {
                            $contents = str_replace($request['files'][$i], "/files/tip_file/" . $b_direc . "/" . $file_name[$i], $contents);
                        } else {
                            throw new Exception('게시글 저장이 실패하였습니다.', 701);
                        }
                    }
                }
            }

            $params = array(
                "user_no"    => !empty($user_no) ? $user_no : '',
                "title"      => !empty($title) ? $title : '',
                "board_kind" => !empty($board_kind) ? $board_kind : '',
                "contents "  => !empty($contents) ? $contents : '',
                "file_path "  => !empty($b_direc) ? $b_direc : '',
                "notice_yn "  => !empty($notice_yn) ? $notice_yn : '',
                "head_write" => "Y",
                "reg_date"   => 'getdate()'
            );

            if (!empty($_FILES['attachFiles'])) {
                $nameArr = explode(".", $_FILES['attachFiles']['name']);
                $extension = end($nameArr);
                $file_name = 'file_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/tip_file/";
                copy($_FILES['attachFiles']['tmp_name'], $path . $file_name);
                $params['file_name'] = $file_name;
                $params['origin_name'] = $_FILES['attachFiles']['name'];
            }

            $result = $this->boardTipInfo->insert($params);

            if ($result) {
                $return_data['msg'] = '게시글이 등록되었습니다.';
            } else {
                throw new Exception('게시글 저장이 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function boardTipUpdate($request)
    {
        $board_idx = !empty($request['board_idx']) ? $request['board_idx'] : '';
        $title = !empty($request['title']) ? $request['title'] : '';
        $contents = !empty($request['contents']) ? str_replace("﻿", "", $request['contents'] ) : '';
        $notice_yn = !empty($request['notice_yn']) ? $request['notice_yn'] : '';

        try {
            if (empty($board_idx) || empty($title) || empty($contents)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if (!empty($request['files'])) {
                $data = array();
                $file_name = array();
                $b_direc = date("YmdHis") . "_file";
                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/tip_file/" . $b_direc . "/";

                for ($i = 0; $i < count($request['files']); $i++) {
                    if (strpos($request['files'][$i], "base64")) {
                        $nameArr = explode(",", $request['files'][$i]);
                        $data[] .= base64_decode($nameArr[1]);
                        $file_name[] .= 'bfile_' . date("YmdHis") . "_" . $i . ".png";
                    } else {
                        $org_file_name = preg_replace("#https?:\/\/" . $_SERVER['HTTP_HOST'] . "#i", '',  $request['files'][$i]);
                        $type = pathinfo($request['files'][$i], PATHINFO_EXTENSION);
                        $dataInfo = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $org_file_name);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataInfo);

                        $nameArr = explode(",", $base64);
                        $data[] .= base64_decode($nameArr[1]);
                        $file_name[] .= 'bfile_' . date("YmdHis") . "_" . $i . ".png";
                    }
                }

                if (!empty($request['file_path'])) {
                    $dir = $_SERVER['DOCUMENT_ROOT'] . "/files/tip_file/" . $request['file_path'];
                    if (is_dir($dir)) {
                        $it = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
                        $it = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                        foreach ($it as $file) {
                            if ($file->isDir()) rmdir($file->getPathname());
                            else unlink($file->getPathname());
                        }
                        rmdir($dir);
                    }
                }

                for ($i = 0; $i < count($request['files']); $i++) {
                    $org_file_name = preg_replace("#https?:\/\/" . $_SERVER['HTTP_HOST'] . "#i", '',  $request['files'][$i]);
                    if (!is_dir($path)) {
                        $dir = mkdir($path, 775);
                        if ($dir) {
                            if (file_put_contents($path . $file_name[$i], $data[$i])) {
                                $contents = str_replace($org_file_name, "/files/tip_file/" . $b_direc . "/" . $file_name[$i], $contents);
                            } else {
                                throw new Exception('게시글 수정이 실패하였습니다.', 701);
                            }
                        }
                    } else {
                        if (file_put_contents($path . $file_name[$i], $data[$i])) {
                            $contents = str_replace($org_file_name, "/files/tip_file/" . $b_direc . "/" . $file_name[$i], $contents);
                        } else {
                            throw new Exception('게시글 수정이 실패하였습니다.', 701);
                        }
                    }
                }
            }

            $params = array(
                "title" => !empty($title) ? $title : '',
                "contents " => !empty($contents) ? $contents : '',
                "mod_date" => "getdate()",
                "file_path" => !empty($b_direc) ? $b_direc : '',
                "notice_yn" => !empty($notice_yn) ? $notice_yn : ''
            );

            if (!empty($_FILES['attachFiles'])) {
                if (!empty($request['file_name'])) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/files/tip_file/" . $request['file_name']);
                }

                $nameArr = explode(".", $_FILES['attachFiles']['name']);
                $extension = end($nameArr);
                $file_name = 'file_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/tip_file/";
                copy($_FILES['attachFiles']['tmp_name'], $path . $file_name);
                $params['file_name'] = $file_name;
                $params['origin_name'] = $_FILES['attachFiles']['name'];
            }

            $this->boardTipInfo->where_qry = " board_idx = " . $board_idx;
            $result = $this->boardTipInfo->update($params);

            if ($result) {
                $return_data['msg'] = '게시글이 수정되었습니다.';
            } else {
                throw new Exception('게시글 수정에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function boardTipDelete($request)
    {
        $board_idx = !empty($request['board_idx']) ? $request['board_idx'] : '';
        $file_name = !empty($request['file_name']) ? $request['file_name'] : '';
        $file_path = !empty($request['file_path']) ? $request['file_path'] : '';

        try {
            if (empty($board_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->boardTipInfo->boardTipDelete($board_idx);
            if ($result) {
                $path = "/files/tip_file/";

                if (!empty($file_name)) { //기존파일 삭제
                    unlink($_SERVER['DOCUMENT_ROOT'] . $path . $file_name);
                }

                if (!empty($file_path)) {
                    $dir = $_SERVER['DOCUMENT_ROOT'] . $path . $file_path;
                    if (is_dir($dir)) {
                        $it = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
                        $it = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                        foreach ($it as $file) {
                            if ($file->isDir()) rmdir($file->getPathname());
                            else unlink($file->getPathname());
                        }
                        rmdir($dir);
                    }
                }

                $return_data['msg'] = '게시글이 삭제되었습니다.';
            } else {
                throw new Exception('게시글 삭제에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function boardTipSelect($params)
    {
        $board_idx = !empty($params['board_idx']) ? $params['board_idx'] : '';

        try {
            if (empty($board_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->boardTipInfo->boardTipSelect($board_idx);

            if (!empty($result['file_name'])) {
                $result['file_link'] = "첨부파일<a class=\"text-info\" href=\"/files/tip_file/" . $result['file_name'] . "\" download><i class=\"fa-solid fa-file-arrow-down ms-1 me-1\"></i>" . $result['origin_name'] . "</a>";
            } else {
                $result['file_link'] = "";
            }

            $result['writer_no'] = $result['user_no'];

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function boardTipCmtInsert($request)
    {
        $board_idx = !empty($request['board_idx']) ? $request['board_idx'] : '';
        $user_no = !empty($request['user_no']) ? $request['user_no'] : $_SESSION['logged_no'];
        $comment = !empty($request['comment']) ? $request['comment'] : '';

        try {
            if (empty($likes)) {
                if (empty($board_idx) || empty($user_no) || empty($comment)) {
                    throw new Exception('필수값이 누락되었습니다.', 701);
                }
            }

            $params = array(
                "board_idx"  => !empty($board_idx) ? $board_idx : '',
                "franchise_idx"  => '1',
                "user_no"    => !empty($user_no) ? $user_no : '',
                "comment"    => !empty($comment) ? $comment : '',
                "head_write" => 'Y'
            );

            $result = $this->boardTipInfo->boardTipCmtInsert($params);

            if ($result) {
                $return_data['msg'] = "댓글이 등록되었습니다.";
            } else {
                throw new Exception('댓글 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function boardTipCmtLoad($params)
    {
        $board_idx = !empty($params['board_idx']) ? $params['board_idx'] : '';

        try {
            if (empty($board_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->boardTipInfo->boardTipCmtLoad($board_idx);
            $likesCnt = $this->boardTipInfo->getLikesCnt($board_idx);
            $cmtPage = "";

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $myCmt = '';
                    $del_btn = "<div class=\"col-auto\"><button type=\"button\" data-cmt-idx=\"" . $val['comment_idx'] . "\" class=\"btn btn-sm btn-outline-danger cmt_del\"><i class=\"fa-solid fa-trash\"></i>삭제</button></div>";

                    if (($params['writer_no'] == $val['cmt_user'])) {
                        $myCmt = '<i class="fa-solid fa-pen ms-2 text-primary"></i>';
                    }

                    if (empty($params['writer_no']) && empty($val['cmt_user'])) {
                        $myCmt = '<i class="fa-solid fa-pen ms-2 text-primary"></i>';
                    }

                    if (!empty($val['user_name'] && $val['center_name'])) {
                        $writer = $val['user_name'] . "(" . $val['center_name'] . ")" . $myCmt;
                    } else {
                        $writer = $val['user_name'] . $myCmt;
                    }

                    $cmtPage .= "<div class=\"card mb-2\">
                                     <div class=\"card-header\">
                                         <div class=\"row align-items-center\">
                                             <div class=\"col-auto\">" . $writer . "</div>
                                             <div class=\"col-auto ms-auto\">" . $val['reg_date'] . "</div>
                                             " . $del_btn . "
                                         </div>
                                     </div>
                                     <div class=\"card-body\">
                                         " . htmlspecialchars($val['comment']) . "
                                     </div>
                                 </div>";
                }
            }

            $return_data['cmt'] = $cmtPage;
            $return_data['cnt'] = !empty($likesCnt) ? $likesCnt : '0';

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function boardTipCmtDelete($params)
    {
        $comment_idx = !empty($params['comment_idx']) ? $params['comment_idx'] : '';

        try {
            if (empty($comment_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->boardTipInfo->boardTipCmtDelete($comment_idx);

            if ($result) {
                $return_data['msg'] = '댓글이 삭제되었습니다.';
            } else {
                throw new Exception('댓글 삭제에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function cmtLikeChange($request)
    {
        $board_idx = !empty($request['board_idx']) ? $request['board_idx'] : '';
        $user_no = !empty($request['user_no']) ? $request['user_no'] : $_SESSION['logged_no'];

        try {
            if (empty($board_idx) || empty($user_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $like_check = $this->boardTipInfo->checkLikeCnt($board_idx, $user_no);

            if (!empty($like_check)) {
                $likes_idx = !empty($like_check['likes_idx']) ? $like_check['likes_idx'] : '';

                $result = $this->boardTipInfo->likeDelete($likes_idx);
            } else {
                $params = array(
                    "board_idx" => !empty($board_idx) ? $board_idx : '',
                    "franchise_idx" => '1',
                    "user_no" => !empty($user_no) ? $user_no : ''
                );

                $result = $this->boardTipInfo->likeInsert($params);
            }

            if($result){
                $return_data['msg'] = 'success';
            }else{
                throw new Exception('다시 시도해주세요', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function deleteImage($request)
    {
        $board_idx = !empty($request['board_idx']) ? $request['board_idx'] : '';
        $file_name = !empty($request['file_name']) ? $request['file_name'] : '';

        try {
            if (empty($board_idx) || empty($file_name)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $params = array(
                "file_name" => "",
                "origin_name" => ""
            );

            $this->boardTipInfo->where_qry = " board_idx = " . $board_idx;
            $result = $this->boardTipInfo->update($params);

            if ($result) {
                $path = "/files/tip_file/";
                unlink($_SERVER['DOCUMENT_ROOT'] . $path . $file_name);
                $return_data['msg'] = '첨부파일이 삭제되었습니다.';
            } else {
                throw new Exception('첨부파일 삭제가 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$boardTipController = new BoardTipController();

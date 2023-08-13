<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Board.php";

class BoardController extends Controller
{
    private $boardInfo;

    function __construct()
    {
        $this->boardInfo = new BoardInfo();
    }

    // 공지사항 글 입력
    public function boardInsert($request)
    {
        $title = !empty($request['title']) ? $request['title'] : '';
        $contents = !empty($request['contents']) ? str_replace("﻿", "", $request['contents'] ) : '';
        $franchiseType = !empty($request['franchiseType']) ? $request['franchiseType'] : '';
        $targetType = !empty($request['targetType']) ? $request['targetType'] : '';
        // echo $contents;
        // exit;

        try {
            if (empty($title) || empty($contents)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if (!empty($request['files'])) {
                $data = array();
                $file_name = array();
                $b_direc = date("YmdHis") . "_file";
                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/notice_file/" . $b_direc . "/";

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
                                $contents = str_replace($request['files'][$i], "/files/notice_file/" . $b_direc . "/" . $file_name[$i], $contents);
                            } else {
                                throw new Exception('게시글 저장이 실패하였습니다.', 701);
                            }
                        }
                    } else {
                        if (file_put_contents($path . $file_name[$i], $data[$i])) {
                            $contents = str_replace($request['files'][$i], "/files/notice_file/" . $b_direc . "/" . $file_name[$i], $contents);
                        } else {
                            throw new Exception('게시글 저장이 실패하였습니다.', 701);
                        }
                    }
                }
            }

            $params = array(
                "title"     => !empty($title) ? $title : '',
                "contents "  => !empty($contents) ? $contents : '',
                "open_franchise " => !empty($franchiseType) ? $franchiseType : '',
                "open_target " => !empty($targetType) ? $targetType : '',
                "file_path"  => !empty($b_direc) ? $b_direc : ''
            );

            if (!empty($_FILES['attachFiles'])) {
                $nameArr = explode(".", $_FILES['attachFiles']['name']);
                $extension = end($nameArr);
                $file_name = 'file_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/notice_file/";
                copy($_FILES['attachFiles']['tmp_name'], $path . $file_name);
                $params['files'] = $file_name;
                $params['origin_file'] = $_FILES['attachFiles']['name'];
            }

            $result = $this->boardInfo->insert($params);

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

    //공지사항 글 수정
    public function boardUpdate($request)
    {
        $board_idx = !empty($request['board_idx']) ? $request['board_idx'] : '';
        $title = !empty($request['title']) ? $request['title'] : '';
        $contents = !empty($request['contents']) ? str_replace("﻿", "", $request['contents'] ) : '';
        $franchiseType = !empty($request['franchiseType']) ? $request['franchiseType'] : '';
        $targetType = !empty($request['targetType']) ? $request['targetType'] : '';
        try {
            if (empty($board_idx) || empty($title) || empty($contents)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if (!empty($request['files'])) {
                $data = array();
                $file_name = array();
                $b_direc = date("YmdHis") . "_file";
                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/notice_file/" . $b_direc . "/";

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
                    $dir = $_SERVER['DOCUMENT_ROOT'] . "/files/notice_file/" . $request['file_path'];
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
                                $contents = str_replace($org_file_name, "/files/notice_file/" . $b_direc . "/" . $file_name[$i], $contents);
                            } else {
                                throw new Exception('게시글 수정이 실패하였습니다.123', 701);
                            }
                        }
                    } else {
                        if (file_put_contents($path . $file_name[$i], $data[$i])) {
                            $contents = str_replace($org_file_name, "/files/notice_file/" . $b_direc . "/" . $file_name[$i], $contents);
                        } else {
                            throw new Exception('게시글 수정이 실패하였습니다.456', 701);
                        }
                    }
                }
            }

            $params = array(
                "title" => !empty($title) ? $title : '',
                "contents " => !empty($contents) ? $contents : '',
                "open_franchise " => !empty($franchiseType) ? $franchiseType : '',
                "open_target " => !empty($targetType) ? $targetType : '',
                "mod_date" => 'getdate()',
                "file_path" => !empty($b_direc) ? $b_direc : ''
            );

            if (!empty($_FILES['attachFiles'])) {
                if (!empty($request['file_name'])) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/files/notice_file/" . $request['file_name']);
                }

                $nameArr = explode(".", $_FILES['attachFiles']['name']);
                $extension = end($nameArr);
                $file_name = 'file_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/notice_file/";
                copy($_FILES['attachFiles']['tmp_name'], $path . $file_name);
                $params['files'] = $file_name;
                $params['origin_file'] = $_FILES['attachFiles']['name'];
            }

            $this->boardInfo->where_qry = " board_idx = " . $board_idx;
            $result = $this->boardInfo->update($params);

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

    //공지사항 불러오기
    public function loadBoard()
    {
        try {
            $result = $this->boardInfo->loadBoard();

            if (!empty($result)) {
                $no = count($result);
                foreach ($result as $key => $val) {
                    if (empty($result[$key]['open_franchise'])) {
                        $result[$key]['open_franchise'] = "전체공개";
                    }
                    if (empty($result[$key]['open_target'])) {
                        $result[$key]['open_target'] = "전체공개";
                    }
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

    //자료실 글 입력
    public function fileBoardInsert($request)
    {
        $title     = !empty($request['title']) ? $request['title'] : '';
        $category1 = !empty($request['category1']) ? $request['category1'] : '';
        $category2 = !empty($request['category2']) ? $request['category2'] : '';
        $contents  = !empty($request['contents']) ? $request['contents'] : '';

        try {
            if (empty($title) || empty($category1)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "title"     => !empty($title) ? $title : '',
                "category1" => !empty($category1) ? $category1 : '',
                "category2" => !empty($category2) ? $category2 : '',
                "contents" => !empty($contents) ? $contents : ''
            );

            if (!empty($_FILES['files'])) {
                $nameArr = explode(".", $_FILES['files']['name']);
                $extension = end($nameArr);
                $file_name = 'file_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/board_file/";
                copy($_FILES['files']['tmp_name'], $path . $file_name);
                $params['files'] = $file_name;
                $params['origin_file'] = $_FILES['files']['name'];
            }

            $result = $this->boardInfo->insert($params);

            if ($result) {
                $return_data['msg'] = '게시글이 등록되었습니다.';
            } else {
                throw new Exception('게시글 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //자료실 글 수정
    public function fileBoardUpdate($request)
    {
        $board_idx = !empty($request['board_idx']) ? $request['board_idx'] : '';
        $title     = !empty($request['title']) ? $request['title'] : '';
        $category1 = !empty($request['category1']) ? $request['category1'] : '';
        $category2 = !empty($request['category2']) ? $request['category2'] : '';
        $contents  = !empty($request['contents']) ? $request['contents'] : '';

        try {
            if (empty($board_idx) || empty($title) || empty($category1)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "title"     => !empty($title) ? $title : '',
                "category1" => !empty($category1) ? $category1 : '',
                "category2" => !empty($category2) ? $category2 : '',
                "contents" => !empty($contents) ? $contents : '',
                "mod_date" => 'getdate()'
            );

            if (!empty($_FILES['files'])) {
                if (!empty($request['file_name'])) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/files/board_file/" . $request['file_name']);
                }

                $nameArr = explode(".", $_FILES['files']['name']);
                $extension = end($nameArr);
                $file_name = 'file_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/board_file/";
                copy($_FILES['files']['tmp_name'], $path . $file_name);
                $params['files'] = $file_name;
                $params['origin_file'] = $_FILES['files']['name'];
            }

            $this->boardInfo->where_qry = " board_idx = " . $board_idx;
            $result = $this->boardInfo->update($params);

            if ($result) {
                $return_data['msg'] = '게시글 수정이 완료되었습니다.';
            } else {
                throw new Exception('게시글 수정에 실패하였습니다.', 701);
            }

            return $return_data;
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
                        $result[$key]['file_link'] = "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/board_file/" . $val['files'] . "\" download><i class=\"fa-solid fa-file-arrow-down\"></i> 다운로드</a>";
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

    //게시글 이미지만 삭제 (공용함수)
    public function deleteImage($request)
    {
        $board_idx = !empty($request['board_idx']) ? $request['board_idx'] : '';
        $file_name = !empty($request['file_name']) ? $request['file_name'] : '';
        $board_type = !empty($request['board_type']) ? $request['board_type'] : '';

        try {
            if (empty($board_idx) || empty($file_name) || empty($board_type)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $params = array(
                "files" => "",
                "origin_file" => ""
            );

            $this->boardInfo->where_qry = " board_idx = " . $board_idx;
            $result = $this->boardInfo->update($params);

            if ($result) {
                if ($board_type == '1') { //공지사항
                    $path = "/files/notice_file/";
                } else if ($board_type == '2') { //자료실
                    $path = "/files/board_file/";
                } else {
                    return false;
                }
                unlink($_SERVER['DOCUMENT_ROOT'] . $path . $file_name);
                $return_data['msg'] = '이미지가 삭제되었습니다.';
            } else {
                throw new Exception('이미지 삭제가 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //글 삭제 공용 함수
    public function deleteBoard($request)
    {
        $board_idx = !empty($request['board_idx']) ? $request['board_idx'] : '';
        $file_name = !empty($request['file_name']) ? $request['file_name'] : '';
        $file_path = !empty($request['file_path']) ? $request['file_path'] : '';
        $board_type = !empty($request['board_type']) ? $request['board_type'] : '';

        try {
            if (empty($board_idx) || empty($board_type)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $this->boardInfo->where_qry = " board_idx = " . $board_idx;
            $result = $this->boardInfo->delete();

            if ($result) {
                if ($board_type == '1') { //공지사항
                    $path = "/files/notice_file/";
                } else if ($board_type == '2') { //자료실
                    $path = "/files/board_file/";
                } else {
                    return false;
                }

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
                throw new Exception('게시글이 삭제되지 않았습니다.', 701);
            }

            return $return_data;
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
                                 <td>" . $val['title'] . "</td>
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

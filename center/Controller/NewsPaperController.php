<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/NewsPaper.php";

class NewsPaperContoller extends Controller
{
    private $newsPaperInfo;

    function __construct()
    {
        $this->newsPaperInfo = new NewsPaperInfo();
    }

    public function newsPaperInsert($request)
    {
        $user_no         = !empty($request['user_no']) ? $request['user_no'] : '';
        $franchise_idx   = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $newspaper_title = !empty($request['title']) ? $request['title'] : '';
        $news_code       = !empty($request['newscompany']) ? $request['newscompany'] : '';
        $column_code     = !empty($request['subject']) ? $request['subject'] : '';
        $news_date       = !empty($request['newsdate']) ? $request['newsdate'] : '';
        $column_file     = !empty($_FILES['column_file']) ? $_FILES['column_file'] : '';
        $teaching_file   = !empty($_FILES['teaching_file']) ? $_FILES['teaching_file'] : '';

        try {
            if (empty($user_no) || empty($franchise_idx) || empty($newspaper_title) || empty($news_code) || empty($column_code) || empty($news_date) || empty($column_file) || empty($teaching_file)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "user_no"         => !empty($user_no) ? $user_no : '',
                "franchise_idx"   => !empty($franchise_idx) ? $franchise_idx : '',
                "newspaper_title" => !empty($newspaper_title) ? $newspaper_title : '',
                "news_code"       => !empty($news_code) ? $news_code : '',
                "column_code"     => !empty($column_code) ? $column_code : '',
                "news_date"       => !empty($news_date) ? $news_date : ''
            );

            if (!empty($column_file)) {
                $nameArr = explode(".", $column_file['name']);
                $extension = end($nameArr);
                $file_name = 'column_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/newspaper/";
                copy($column_file['tmp_name'], $path . $file_name);
                $params['column_file'] = $file_name;
                $params['column_origin'] = $column_file['name'];
            }

            if (!empty($teaching_file)) {
                $nameArr = explode(".", $teaching_file['name']);
                $extension = end($nameArr);
                $file_name = 'teaching_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/newspaper/";
                copy($teaching_file['tmp_name'], $path . $file_name);
                $params['teaching_file'] = $file_name;
                $params['teaching_origin'] = $teaching_file['name'];
            }

            $result = $this->newsPaperInfo->insert($params);

            if ($result) {
                $return_data['msg'] = '신문칼럼이 등록되었습니다.';
            } else {
                throw new Exception('신문칼럼 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function newsPaperUpdate($request)
    {
        $news_idx        = !empty($request['news_idx']) ? $request['news_idx'] : '';
        $newspaper_title = !empty($request['title']) ? $request['title'] : '';
        $news_code       = !empty($request['newscompany']) ? $request['newscompany'] : '';
        $column_code     = !empty($request['subject']) ? $request['subject'] : '';
        $news_date       = !empty($request['newsdate']) ? $request['newsdate'] : '';
        $column_file     = !empty($_FILES['column_file']) ? $_FILES['column_file'] : '';
        $teaching_file   = !empty($_FILES['teaching_file']) ? $_FILES['teaching_file'] : '';
        $column_origin   = !empty($request['column_origin']) ? $request['column_origin'] : '';
        $teaching_origin = !empty($request['teaching_origin']) ? $request['teaching_origin'] : '';

        try {
            if (empty($news_idx) || empty($newspaper_title) || empty($news_code) || empty($column_code) || empty($news_date)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "newspaper_title" => !empty($newspaper_title) ? $newspaper_title : '',
                "news_code" => !empty($news_code) ? $news_code : '',
                "column_code" => !empty($column_code) ? $column_code : '',
                "news_date" => !empty($news_date) ? $news_date : '',
                "mod_date" => 'getdate()'
            );

            if (!empty($column_file)) {
                $nameArr = explode(".", $column_file['name']);
                $extension = end($nameArr);
                $file_name = 'column_' . date("YmdHis") . "." . $extension;

                $path = "/files/newspaper/";

                unlink($_SERVER['DOCUMENT_ROOT'] . $path . $column_origin);

                copy($column_file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $path . $file_name);
                $params['column_file'] = $file_name;
                $params['column_origin'] = $column_file['name'];
            }

            if (!empty($teaching_file)) {
                $nameArr = explode(".", $teaching_file['name']);
                $extension = end($nameArr);
                $file_name = 'teaching_' . date("YmdHis") . "." . $extension;

                $path = "/files/newspaper/";

                unlink($_SERVER['DOCUMENT_ROOT'] . $path . $teaching_origin);

                copy($teaching_file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $path . $file_name);
                $params['teaching_file'] = $file_name;
                $params['teaching_origin'] = $teaching_file['name'];
            }

            $this->newsPaperInfo->where_qry = " news_idx = '" . $news_idx . "' ";
            $result = $this->newsPaperInfo->update($params);

            if ($result) {
                $return_data['msg'] = '신문칼럼이 수정되었습니다.';
            } else {
                throw new Exception('신문칼럼 수정에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function newsPaperDelete($request)
    {
        $news_idx        = !empty($request['news_idx']) ? $request['news_idx'] : '';
        $column_origin   = !empty($request['column_origin']) ? $request['column_origin'] : '';
        $teaching_origin = !empty($request['teaching_origin']) ? $request['teaching_origin'] : '';

        try {
            if (empty($news_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if (!empty($column_origin)) {
                $path = "/files/newspaper/";
                unlink($_SERVER['DOCUMENT_ROOT'] . $path . $column_origin);
            }
            
            if (!empty($teaching_origin)) {
                $path = "/files/newspaper/";
                unlink($_SERVER['DOCUMENT_ROOT'] . $path . $teaching_origin);
            }

            $this->newsPaperInfo->where_qry = " news_idx = '" . $news_idx . "' ";
            $result = $this->newsPaperInfo->delete();

            if ($result) {
                $return_data['msg'] = '신문칼럼이 삭제되었습니다.';
            } else {
                throw new Exception('신문칼럼 삭제에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function newspaperLoad()
    {
        try {
            $result = $this->newsPaperInfo->newspaperLoad();

            if (!empty($result)) {
                $no = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['writer'] = $val['user_name'] . " (" . $val['center_name'] . ")";
                    if (!empty($val['column_file'])) {
                        $result[$key]['column_link'] = "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/newspaper/" . $val['column_file'] . "\" download><i class=\"fa-solid fa-file-arrow-down\"></i> 다운로드</a>";
                        $result[$key]['column_link'] .= "<br><a class=\"btn btn-sm btn-outline-primary\" href=\"/files/newspaper/" . $val['column_file'] . "\" target=\"_blank\"><i class=\"fa-regular fa-file-lines\"></i> 미리보기</a>";
                    } else {
                        $result[$key]['column_link'] = '';
                    }

                    if (!empty($val['teaching_file'])) {
                        $result[$key]['teaching_link'] = "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/newspaper/" . $val['teaching_file'] . "\" download><i class=\"fa-solid fa-file-arrow-down\"></i> 다운로드</a>";
                        $result[$key]['teaching_link'] .= "<br><a class=\"btn btn-sm btn-outline-primary\" href=\"/files/newspaper/" . $val['teaching_file'] . "\" target=\"_blank\"><i class=\"fa-regular fa-file-lines\"></i> 미리보기</a>";
                    } else {
                        $result[$key]['teaching_link'] = '';
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

    public function newspaperSelect($params)
    {
        $news_idx = !empty($params['news_idx']) ? $params['news_idx'] : '';

        try {
            if (empty($news_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->newsPaperInfo->newspaperSelect($news_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$newsPaperController = new NewsPaperContoller();

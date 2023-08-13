<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/ActivityPaper.php";

class ActivityPaperController extends Controller
{
    private $activityPaperInfo;

    function __construct()
    {
        $this->activityPaperInfo = new ActivityPaperInfo();
    }

    //활동지 목록 불러오기
    public function activityListLoad()
    {
        try {
            $result = $this->activityPaperInfo->activityListLoad();

            if (!empty($result)) {
                $no = count($result);
                foreach ($result as $key => $val) {
                    $downloadBtn = '';
                    $viewBtn = '';
                    $result[$key]['img_link'] = "<img src=\"" . $val['img_link'] . "\" class=\"img w-30\" />";

                    if (!empty($result[$key]['activity_student1'])) {
                        $downloadBtn .= "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $val['activity_student1'] . "\" download><i class=\"fa-regular fa-file-lines\"></i> 학생용1</a><br>";
                        $viewBtn .= "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $val['activity_student1'] . "\" target=\"_blank\"><i class=\"fa-regular fa-file-lines\"></i> 학생용1</a><br>";
                    }

                    if (!empty($result[$key]['activity_student2'])) {
                        $downloadBtn .= "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $val['activity_student2'] . "\" download><i class=\"fa-regular fa-file-lines\"></i> 학생용2</a><br>";
                        $viewBtn .= "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $val['activity_student2'] . "\" target=\"_blank\"><i class=\"fa-regular fa-file-lines\"></i> 학생용2</a><br>";
                    }

                    if (!empty($result[$key]['activity_teacher1'])) {
                        $downloadBtn .= "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $val['activity_teacher1'] . "\" download><i class=\"fa-regular fa-file-lines\"></i> 교사용1</a><br>";
                        $viewBtn .= "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $val['activity_teacher1'] . "\" target=\"_blank\"><i class=\"fa-regular fa-file-lines\"></i> 교사용1</a><br>";
                    }

                    if (!empty($result[$key]['activity_teacher2'])) {
                        $downloadBtn .= "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $val['activity_teacher2'] . "\" download><i class=\"fa-regular fa-file-lines\"></i> 교사용2</a>";
                        $viewBtn .= "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $val['activity_teacher2'] . "\" target=\"_blank\"><i class=\"fa-regular fa-file-lines\"></i> 교사용2</a>";
                    }

                    $result[$key]['no'] = $no--;
                    $result[$key]['download'] = $downloadBtn;
                    $result[$key]['view'] = $viewBtn;
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //활동지 선택
    public function activityListSelect($params)
    {
        $activitypaper_idx = !empty($params['activitypaper_idx']) ? $params['activitypaper_idx'] : '';

        try {
            if (empty($activitypaper_idx)) {
                return true;
            }

            $result = $this->activityPaperInfo->activityListSelect($activitypaper_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //활동지 추가
    public function activityListInsert($request)
    {
        $book_idx = !empty($request['book_idx']) ? $request['book_idx'] : '';

        try {
            if (empty($book_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "book_idx" => !empty($book_idx) ? $book_idx : ''
            );

            if (!empty($_FILES['activity_student1'])) {
                $nameArr = explode(".", $_FILES['activity_student1']['name']);
                $extension = end($nameArr);
                $file_name = 'student1_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/activitypaper/";
                copy($_FILES['activity_student1']['tmp_name'], $path . $file_name);
                $params['activity_student1'] = $file_name;
            }

            if (!empty($_FILES['activity_student2'])) {
                $nameArr = explode(".", $_FILES['activity_student2']['name']);
                $extension = end($nameArr);
                $file_name = 'student2_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/activitypaper/";
                copy($_FILES['activity_student2']['tmp_name'], $path . $file_name);
                $params['activity_student2'] = $file_name;
            }

            if (!empty($_FILES['activity_teacher1'])) {
                $nameArr = explode(".", $_FILES['activity_teacher1']['name']);
                $extension = end($nameArr);
                $file_name = 'teacher1_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/activitypaper/";
                copy($_FILES['activity_teacher1']['tmp_name'], $path . $file_name);
                $params['activity_teacher1'] = $file_name;
            }

            if (!empty($_FILES['activity_teacher2'])) {
                $nameArr = explode(".", $_FILES['activity_teacher2']['name']);
                $extension = end($nameArr);
                $file_name = 'teacher2_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/activitypaper/";
                copy($_FILES['activity_teacher2']['tmp_name'], $path . $file_name);
                $params['activity_teacher2'] = $file_name;
            }

            $result = $this->activityPaperInfo->insert($params);

            if ($result) {
                $return_data['msg'] = '활동지가 등록되었습니다.';
            } else {
                throw new Exception('활동지 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //활동지 업데이트
    public function activityListUpdate($request)
    {
        $activitypaper_idx = !empty($request['activitypaper_idx']) ? $request['activitypaper_idx'] : '';
        //기존 업로드 되었던 파일명
        $activity_student1_file = !empty($request['activity_student1_file']) ? $request['activity_student1_file'] : '';
        $activity_student2_file = !empty($request['activity_student2_file']) ? $request['activity_student2_file'] : '';
        $activity_teacher1_file = !empty($request['activity_teacher1_file']) ? $request['activity_teacher1_file'] : '';
        $activity_teacher2_file = !empty($request['activity_teacher2_file']) ? $request['activity_teacher2_file'] : '';

        try {
            if (empty($activitypaper_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if (empty($_FILES['activity_student1']) && empty($_FILES['activity_student2']) && empty($_FILES['activity_teacher1']) && empty($_FILES['activity_teacher2'])) {
                throw new Exception('변경사항이 없습니다.', 701);
            }

            $params = array(
                "mod_date" => "getdate()"
            );

            if (!empty($_FILES['activity_student1'])) {
                if(!empty($activity_student1_file)){
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/files/activitypaper/" . $activity_student1_file);
                }

                $nameArr = explode(".", $_FILES['activity_student1']['name']);
                $extension = end($nameArr);
                $file_name = 'student1_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/activitypaper/";
                copy($_FILES['activity_student1']['tmp_name'], $path . $file_name);
                $params['activity_student1'] = $file_name;
            }

            if (!empty($_FILES['activity_student2'])) {
                if(!empty($activity_student2_file)){
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/files/activitypaper/" . $activity_student2_file);
                }

                $nameArr = explode(".", $_FILES['activity_student2']['name']);
                $extension = end($nameArr);
                $file_name = 'student2_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/activitypaper/";
                copy($_FILES['activity_student2']['tmp_name'], $path . $file_name);
                $params['activity_student2'] = $file_name;
            }

            if (!empty($_FILES['activity_teacher1'])) {
                if(!empty($activity_teacher1_file)){
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/files/activitypaper/" . $activity_teacher1_file);
                }
                
                $nameArr = explode(".", $_FILES['activity_teacher1']['name']);
                $extension = end($nameArr);
                $file_name = 'teacher1_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/activitypaper/";
                copy($_FILES['activity_teacher1']['tmp_name'], $path . $file_name);
                $params['activity_teacher1'] = $file_name;
            }

            if (!empty($_FILES['activity_teacher2'])) {
                if(!empty($activity_teacher2_file)){
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/files/activitypaper/" . $activity_teacher2_file);
                }

                $nameArr = explode(".", $_FILES['activity_teacher2']['name']);
                $extension = end($nameArr);
                $file_name = 'teacher2_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/activitypaper/";
                copy($_FILES['activity_teacher2']['tmp_name'], $path . $file_name);
                $params['activity_teacher2'] = $file_name;
            }

            $this->activityPaperInfo->where_qry = " activitypaper_idx = '{$activitypaper_idx}' ";
            $result = $this->activityPaperInfo->update($params);

            if ($result) {
                $return_data['msg'] = '활동지가 업로드 되었습니다.';
            } else {
                throw new Exception('파일 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //파일 삭제
    public function fileDelete($request)
    {
        $activitypaper_idx = !empty($request['activitypaper_idx']) ? $request['activitypaper_idx'] : '';
        $file_name = !empty($request['file_name']) ? $request['file_name'] : '';
        $file_number = !empty($request['file_number']) ? $request['file_number'] : '';

        try {
            if ( empty($activitypaper_idx) || empty($file_name) || empty($file_number) ) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                $file_number => '',
                "mod_date" => "getdate()"
            );

            if(unlink($_SERVER['DOCUMENT_ROOT'] . "/files/activitypaper/" . $file_name)){
                $this->activityPaperInfo->where_qry = " activitypaper_idx = '{$activitypaper_idx}' ";
                $result = $this->activityPaperInfo->update($params);            
    
                if ($result) {
                    $return_data['msg'] = '파일이 삭제 되었습니다.';
                } else {
                    throw new Exception('파일 삭제에 실패하였습니다.', 701);
                }
            }else{
                throw new Exception('파일 삭제에 실패하였습니다.', 701);
            }            

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$activityPaperController = new ActivityPaperController();
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/ActivityPaper.php";

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
                    $viewBtn = '';
                    $result[$key]['img_link'] = "<img src=\"" . $val['img_link'] . "\" class=\"img w-30\" />";

                    if (!empty($result[$key]['activity_student1'])) {
                        $viewBtn .= "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $val['activity_student1'] . "\" target=\"_blank\"><i class=\"fa-regular fa-file-lines\"></i> 학생용1</a><br>";
                    }

                    if (!empty($result[$key]['activity_student2'])) {
                        $viewBtn .= "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $val['activity_student2'] . "\" target=\"_blank\"><i class=\"fa-regular fa-file-lines\"></i> 학생용2</a><br>";
                    }

                    if (!empty($result[$key]['activity_teacher1'])) {
                        $viewBtn .= "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $val['activity_teacher1'] . "\" target=\"_blank\"><i class=\"fa-regular fa-file-lines\"></i> 교사용1</a><br>";
                    }

                    if (!empty($result[$key]['activity_teacher2'])) {
                        $viewBtn .= "<a class=\"btn btn-sm btn-outline-primary\" href=\"/files/activitypaper/" . $val['activity_teacher2'] . "\" target=\"_blank\"><i class=\"fa-regular fa-file-lines\"></i> 교사용2</a>";
                    }

                    $result[$key]['no'] = $no--;
                    $result[$key]['view'] = $viewBtn;
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$activityPaperController = new ActivityPaperController();
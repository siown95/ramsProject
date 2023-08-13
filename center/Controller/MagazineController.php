<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Magazine.php";

class MagazineController extends Controller
{
    private $magazineInfo;

    function __construct()
    {
        $this->magazineInfo = new MagazineInfo();
    }

    public function magazineLoad($request)
    {
        $season = !empty($request['season']) ? $request['season'] : '';
        $magazine_year = !empty($request['magazine_year']) ? $request['magazine_year'] : '';

        try {
            $result = $this->magazineInfo->magazineLoad($season, $magazine_year);
            $magazineList = '';

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $magazineList .= "<div class=\"col-3\">
                                          <div class=\"card border-left-primary shadow mt-2\">
                                              <img src=\"/files/magazine_file/" . $val['thumbnail_name'] . "\" class=\"card-img-top mt-4 w-50 align-self-center\" alt=\"매거진 샘플\" />
                                              <div class=\"card-body\">
                                                  <h6 class=\"card-title\">" . $val['title'] . "</h6>
                                                  <a class=\"btn btn-outline-primary\" href=\"" . htmlspecialchars($val['pdf_link']) . "\" target=\"_blank\">보기</a>
                                              </div>
                                          </div>
                                      </div>";                                      
                }
            }
            $result['list'] = $magazineList;

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$magazineController = new MagazineController();
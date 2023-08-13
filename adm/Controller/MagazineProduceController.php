<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/MagazineProduce.php";

class MagazineProduceController extends Controller
{
    private $magazineProduceInfo;

    function __construct()
    {
        $this->magazineProduceInfo = new MagazineProduceInfo();
    }

    public function magazineProduceLoad($request)
    {
        $center_idx     = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $produce_year   = !empty($request['selMagazineProduceYears']) ? $request['selMagazineProduceYears'] : '';
        $produce_season = !empty($request['selMagazineProduceSeason']) ? $request['selMagazineProduceSeason'] : '';

        try {
            if (empty($center_idx) || empty($produce_year) || empty($produce_season)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->magazineProduceInfo->magazineProduceLoad($center_idx, $produce_year, $produce_season);

            $produce_file_arr = array();
            $path = "/files/magazine_produce_file/";

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $produce_file_arr[$val['produce_file_type']][] .= "<a class=\"link-info me-2 mt-1 mb-1\" href=\"" . $path . $val['produce_file_name'] . "\" download><i class=\"fa-solid fa-file-arrow-down\"></i>" . $val['produce_origin_file_name'] . "</a>";
                }
            }

            $uf_type = array(
                "00" => "표지사진&amp;수업사진",
                "01" => "수업안내&amp;설명회안내",
                "02" => "초등글쓰기",
                "03" => "초등칼럼",
                "04" => "중등글쓰기",
                "05" => "중등칼럼",
                "06" => "교사기고",
                "07" => "인터뷰",
                "08" => "원장기고",
                "09" => "대회원고",
                "10" => "수상진학현황",
                "11" => "특별섹션",
                "12" => "기타원고",
            );

            $magazineProduceTable = '';
            for ($i = 0; $i < count($uf_type); $i++) {

                if (!empty($produce_file_arr[sprintf("%02d", $i)])) {
                    $fileList = implode("<br>", $produce_file_arr[sprintf("%02d", $i)]);
                } else {
                    $fileList = '';
                }

                $magazineProduceTable .= "
                <tr>
                    <td>" . $uf_type[sprintf("%02d", $i)] . "</td>
                    <td class=\"align-middle align-self-center\">
                        " . $fileList . "
                    </td>
                </tr>";
            }

            $return_data['produceTable'] = $magazineProduceTable;

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$magazineProduceController = new MagazineProduceController();

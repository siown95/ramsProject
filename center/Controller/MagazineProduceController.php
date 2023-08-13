<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/MagazineProduce.php";

class MagazineProduceController extends Controller
{
    private $magazineProduceInfo;

    function __construct()
    {
        $this->magazineProduceInfo = new MagazineProduceInfo();
    }

    public function magazineProduceUpload($request)
    {
        $center_idx            = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $produce_type          = !empty($request['produce_type']) ? $request['produce_type'] : '';
        $produce_year          = !empty($request['selMagazineProduceYears']) ? $request['selMagazineProduceYears'] : '';
        $produce_season        = !empty($request['selMagazineProduceSeason']) ? $request['selMagazineProduceSeason'] : '';
        $magazine_produce_file = !empty($_FILES['magazine_produce_file']) ? $_FILES['magazine_produce_file'] : '';

        try {
            if (empty($center_idx) || empty($produce_type) || empty($produce_year) || empty($produce_season) || empty($magazine_produce_file)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "franchise_idx"     => !empty($center_idx) ? $center_idx : '',
                "produce_year"      => !empty($produce_year) ? $produce_year : '',
                "produce_season"    => !empty($produce_season) ? $produce_season : '',
                "produce_file_type" => !empty($produce_type) ? $produce_type : '',
            );

            if (!empty($magazine_produce_file)) {
                $nameArr = explode(".", $magazine_produce_file['name']);
                $extension = end($nameArr);
                $file_name = 'file_' . date("YmdHis") . "." . $extension;

                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/magazine_produce_file/";
                copy($magazine_produce_file['tmp_name'], $path . $file_name);
                $params['produce_file_name'] = $file_name;
                $params['produce_origin_file_name'] = $magazine_produce_file['name'];
            }

            $result = $this->magazineProduceInfo->insert($params);

            if ($result) {
                $return_data['msg'] = '파일이 등록되었습니다.';
            } else {
                throw new Exception('파일 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function magazineProduceDelete($request)
    {
        $produce_idx = !empty($request['produce_idx']) ? $request['produce_idx'] : '';

        try {
            if (empty($produce_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $produce_file_name = $this->magazineProduceInfo->getProduceFileName($produce_idx);

            if (!empty($produce_file_name)) {
                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/magazine_produce_file/";
                unlink($path . $produce_file_name);
            }

            $this->magazineProduceInfo->where_qry = " produce_idx = '" . $produce_idx . "' ";
            $result = $this->magazineProduceInfo->delete();

            if ($result) {
                $return_data['msg'] = "파일이 삭제되었습니다.";
            } else {
                throw new Exception('파일삭제에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
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
                    $produce_file_arr[$val['produce_file_type']][] .= "<a class=\"link-info me-2 mt-1 mb-1\" href=\"" . $path . $val['produce_file_name'] . "\" download><i class=\"fa-solid fa-file-arrow-down\"></i>" . $val['produce_origin_file_name'] . "</a>
                    <a class=\"btn btn-sm btn-outline-danger mt-1 mb-1 produce-file-del\" data-produce-idx=\"" . $val['produce_idx'] . "\"><i class=\"fa-solid fa-trash\"></i> 삭제</a>";
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
                    <td>
                        <input type=\"file\" name=\"magazineProduceFile\" class=\"form-control d-none\" style=\"display:none;\">
                        <button type=\"button\" class=\"btn btn-sm btn-success magazine-pfile-upload\" data-produce-type=\"" . sprintf("%02d", $i) . "\">업로드</button>
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

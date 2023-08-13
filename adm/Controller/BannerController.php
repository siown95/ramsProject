<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Banner.php";

class BannerController extends Controller
{
    private $bannerInfo;

    function __construct()
    {
        $this->bannerInfo = new BannerInfo();
    }

    //배너목록 불러오기
    public function loadBanner()
    {
        try {
            $result = $this->bannerInfo->loadBanner();

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['del_btn'] = "<button type=\"button\" class=\"btn btn-sm btn-outline-danger delbanner\"><i class=\"fas fa-trash me-1\"></i>삭제</button>";
                    $result[$key]['banner_image'] = "<img class='w-100' src='/files/banner_file/" . $val['banner_image'] . "' data-img-name='" . $val['banner_image'] . "'/>";

                    if (!empty($result[$key]['banner_visible'])) {
                        $result[$key]['edit_btn'] = "<button type=\"button\" class=\"btn btn-sm btn-outline-success editbanner\"><i class=\"fa-solid fa-eye me-1\"></i>표시</button>";
                    } else {
                        $result[$key]['edit_btn'] = "<button type=\"button\" class=\"btn btn-sm btn-outline-success editbanner\"><i class=\"fa-solid fa-eye-slash me-1\"></i>숨김</button>";
                    }
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //배너 추가
    public function bannerInsert($request)
    {
        $banner_link = !empty($request['banner_link']) ? htmlspecialchars($request['banner_link']) : '';
        $from_date = !empty($request['from_date']) ? $request['from_date'] : '';
        $to_date = !empty($request['to_date']) ? $request['to_date'] : '';
        $orders = !empty($request['orders']) ? $request['orders'] : '';
        $mainYn = !empty($request['mainYn']) ? $request['mainYn'] : '';
        $visible = !empty($request['visible']) ? $request['visible'] : '';

        try {
            if (empty($from_date) || empty($_FILES['file1'])) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "banner_link" => !empty($banner_link) ? $banner_link : '',
                "from_date" => !empty($from_date) ? $from_date : '',
                "to_date" => !empty($to_date) ? $to_date : '',
                "orders" => !empty($orders) ? $orders : '',
                "mainYn" => !empty($mainYn) ? $mainYn : '',
                "banner_visible" => !empty($visible) ? $visible : ''
            );

            //썸네일
            $nameArr1 = explode(".", $_FILES['file1']['name']);
            $extension1 = end($nameArr1);
            $file_name1 = date('YmdHis') . "." . $extension1;

            $path = $_SERVER['DOCUMENT_ROOT'] . "/files/banner_file/";
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            copy($_FILES['file1']['tmp_name'], $path . $file_name1);
            $params['banner_image'] = $file_name1;

            $result = $this->bannerInfo->insert($params);

            if ($result) {
                $return_data['msg'] = '배너가 등록되었습니다.';
            } else {
                throw new Exception('배너 등록에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bannerDelete($request)
    {
        $banner_idx = !empty($request['banner_idx']) ? $request['banner_idx'] : '';
        $txtImageFileName = !empty($request['file_name']) ? $request['file_name'] : '';

        try {
            if (empty($banner_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            if (!empty($txtImageFileName)) {
                $path = $_SERVER['DOCUMENT_ROOT'] . "/files/banner_file/" . $txtImageFileName;
                unlink($path);
            }

            $this->bannerInfo->where_qry = "banner_idx = '" . $banner_idx . "'";
            $result = $this->bannerInfo->delete();

            if ($result) {
                $return_data['msg'] = "배너 정보가 삭제되었습니다.";
            } else {
                throw new Exception('배너 정보가 삭제되지않았습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function bannerUpdate($request)
    {
        $banner_idx = !empty($request['banner_idx']) ? $request['banner_idx'] : '';
        $flag = !empty($request['flag']) ? '' : 'Y';
        try {
            $params = array(
                "banner_visible" => !empty($flag) ? $flag : '',
                "mod_date" => 'getdate()'
            );
            $this->bannerInfo->where_qry = " banner_idx = " . $banner_idx;
            $result = $this->bannerInfo->update($params);

            if ($result) {
                $return_data['msg'] = '배너가 수정되었습니다.';
            } else {
                throw new Exception('배너 수정에 실패하였습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$bannerController = new BannerController();

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/ColorCode.php";

class ColorCodeController extends Controller
{
    private $colorCodeInfo;

    function __construct()
    {
        $this->colorCodeInfo = new ColorCodeInfo();
    }

    public function colorCodeLoad($params)
    {
        $franchise_idx = !empty($params['centerIdx']) ? $params['centerIdx'] : '';

        try {
            if (empty($franchise_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->colorCodeInfo->colorCodeLoad($franchise_idx);

            if (!empty($result)) {
                $num = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $num--;
                    $result[$key]['color_code'] = "<span id=\"lblColor\" style=\"color:" . $val['color_code'] . ";\"><i class=\"fa-solid fa-circle\"></i></span>";
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function colorCodeSelect($params)
    {
        $color_idx = !empty($params['colorIdx']) ? $params['colorIdx'] : '';

        try {
            if (empty($color_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->colorCodeInfo->colorCodeSelect($color_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function colorCodeInsert($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $color_detail  = !empty($request['txtDetail']) ? $request['txtDetail'] : '';
        $color_code    = !empty($request['selColor']) ? $request['selColor'] : '';

        try {
            if (empty($franchise_idx) || empty($color_detail) || empty($color_code)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                "color_detail" => !empty($color_detail) ? $color_detail : '',
                "color_code" => !empty($color_code) ? $color_code : ''
            );

            $result = $this->colorCodeInfo->insert($params);

            if ($result) {
                $return_data['msg'] = "색깔코드가 등록되었습니다.";
            } else {
                throw new Exception('색깔코드가 등록되지않았습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function colorCodeUpdate($request)
    {
        $color_idx    = !empty($request['colorIdx']) ? $request['colorIdx'] : '';
        $color_detail = !empty($request['txtDetail']) ? $request['txtDetail'] : '';
        $color_code   = !empty($request['selColor']) ? $request['selColor'] : '';

        try {
            if (empty($color_idx) || empty($color_detail) || empty($color_code)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "color_detail" => !empty($color_detail) ? $color_detail : '',
                "color_code" => !empty($color_code) ? $color_code : '',
                "mod_date" => 'getdate()'
            );

            $this->colorCodeInfo->where_qry = " color_idx = '" . $color_idx . "' ";
            $result = $this->colorCodeInfo->update($params);

            if ($result) {
                $return_data['msg'] = "색깔코드가 수정되었습니다.";
            } else {
                throw new Exception('색깔코드가 수정되지않았습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function colorCodeDelete($request)
    {
        $color_idx = !empty($request['colorIdx']) ? $request['colorIdx'] : '';

        try {
            if (empty($color_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $this->colorCodeInfo->where_qry = " color_idx = '" . $color_idx . "' ";
            $result = $this->colorCodeInfo->delete();

            if ($result) {
                $return_data['msg'] = "색깔코드가 삭제되었습니다.";
            } else {
                throw new Exception('색깔코드가 삭제되지않았습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}


$colorCodeController = new ColorCodeController();
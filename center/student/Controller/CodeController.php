<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Code.php";

class CodeController extends Controller
{
    private $codeInfo;

    function __construct()
    {
        $this->codeInfo = new CodeInfo();
    }

    //코드 입력
    public function codeInsert($request)
    {
        $degree    = !empty($request['degree']) ? $request['degree'] : '';          //차수
        $code_num1 = !empty($request['code_num1']) ? $request['code_num1'] : '';    //1차 코드
        $code_num2 = !empty($request['code_num2']) ? $request['code_num2'] : '';    //2차 코드
        $code_num3 = !empty($request['code_num3']) ? $request['code_num3'] : '';    //3차 코드
        $code_name = !empty($request['code_name']) ? $request['code_name'] : '';    //코드명
        $detail    = !empty($request['detail']) ? $request['detail'] : '';          //3차 코드명

        try {
            if (!empty($degree)) {
                if ($degree == '1') {
                    if (empty($degree) || empty($code_num1) || empty($code_name)) {
                        throw new Exception('잘못된 접근입니다. (필수 값 누락)', 401);
                    }
                } else if ($degree == '2') {
                    if (empty($degree) || empty($code_num1) || empty($code_num2) || empty($code_name)) {
                        throw new Exception('잘못된 접근입니다. (필수 값 누락)', 402);
                    }
                } else {
                    if (empty($degree) || empty($code_num1) || empty($code_num2) || empty($code_num3) || empty($detail)) {
                        throw new Exception('잘못된 접근입니다. (필수 값 누락)', 403);
                    }
                }
            } else {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 403);
            }

            $overlap_cnt = $this->codeInfo->codeCheck($request); // 중복코드 검증

            if ($overlap_cnt > 0) {
                throw new Exception('이미 사용중인 코드입니다.', 501);
            } else {
                $params = array(
                    "code_num1" => !empty($request['code_num1']) ? $request['code_num1'] : '',
                    "code_num2" => !empty($request['code_num2']) ? $request['code_num2'] : '',
                    "code_num3" => !empty($request['code_num3']) ? $request['code_num3'] : '',
                    "code_name" => !empty($request['code_name']) ? $request['code_name'] : '',
                    "detail"    => !empty($request['detail']) ? $request['detail'] : ''
                );

                $result = $this->codeInfo->insert($params);

                if ($result) {
                    $return_data['msg']   = $degree . "차 코드가 생성되었습니다.";
                } else {
                    throw new Exception('코드 생성에 실패하였습니다.', 502);
                }
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //코드 수정
    public function codeUpdate($request)
    {
        $degree    = !empty($request['degree']) ? $request['degree'] : ''; //차수
        $code_num1 = !empty($request['code_num1']) ? $request['code_num1'] : ''; //1차 코드
        $code_num2 = !empty($request['code_num2']) ? $request['code_num2'] : ''; //2차 코드
        $code_num3 = !empty($request['code_num3']) ? $request['code_num3'] : ''; //3차 코드
        $code_name = !empty($request['code_name']) ? $request['code_name'] : ''; //코드명
        $detail    = !empty($request['detail']) ? $request['detail'] : ''; //3차 코드명

        try {
            if (!empty($degree)) {
                if ($degree == '1') {
                    if (empty($code_num1) || empty($code_name)) {
                        throw new Exception('잘못된 접근입니다. (필수 값 누락)', 403);
                    }
                } else if ($degree == '2') {
                    if (empty($code_num1) || empty($code_num2) || empty($code_name)) {
                        throw new Exception('잘못된 접근입니다. (필수 값 누락)', 404);
                    }
                } else {
                    if (empty($code_num1) || empty($code_num2) || empty($code_num3) || empty($code_name)) {
                        throw new Exception('잘못된 접근입니다. (필수 값 누락)', 404);
                    }
                }
            } else {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 404);
            }

            if ($degree == '1') {
                $this->codeInfo->where_qry = " code_num1 = '" . $code_num1 . "' and code_num2 = '' and code_num3 = '' ";


                $params = array(
                    "code_name" => !empty($code_name) ? $code_name : ''
                );
            } else if ($degree == '2') {

                $params = array(
                    "code_name" => !empty($code_name) ? $code_name : ''
                );
                $this->codeInfo->where_qry = " code_num1 = '" . $code_num1 . "' and code_num2 = '" . $code_num2 . "'";
            } else {

                $params = array(
                    "detail" => !empty($detail) ? $detail : ''
                );
                $this->codeInfo->where_qry = " code_num1 = '" . $code_num1 . "' and code_num2 = '" . $code_num2 . "' and code_num3 = '" . $code_num3 . "'";
            }


            $result = $this->codeInfo->update($params);

            if ($result) {
                $return_data['msg']   = "코드가 수정되었습니다.";
            } else {
                throw new Exception('코드 수정에 실패하였습니다.', 502);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //코드 삭제
    public function codeDelete($request)
    {
        $degree    = !empty($request['degree']) ? $request['degree'] : ''; //차수
        $code_num1 = !empty($request['code_num1']) ? $request['code_num1'] : ''; //1차 코드
        $code_num2 = !empty($request['code_num2']) ? $request['code_num2'] : ''; //2차 코드
        $code_num3 = !empty($request['code_num3']) ? $request['code_num3'] : ''; //3차 코드

        if (!empty($degree)) {
            if ($degree == '1') {
                if (empty($code_num1)) {
                    throw new Exception('잘못된 접근입니다. (필수 값 누락)', 405);
                }
                $this->codeInfo->where_qry = "code_num1 = '" . $code_num1 . "' and code_num2 = '' and code_num3 = ''";
            } else if ($degree == '2') {
                if (empty($code_num1) || empty($code_num2)) {
                    throw new Exception('잘못된 접근입니다. (필수 값 누락)', 405);
                }
                $this->codeInfo->where_qry = "code_num1 = '" . $code_num1 . "' and code_num2 = '" . $code_num2 . "'";
            } else {
                if (empty($code_num1) || empty($code_num2) || empty($code_num3)) {
                    throw new Exception('잘못된 접근입니다. (필수 값 누락)', 405);
                }
                $this->codeInfo->where_qry = "code_num1 = '" . $code_num1 . "' and code_num2 = '" . $code_num2 . "' and code_num3 = '" . $code_num3 . "'";
            }
        }

        try {
            $params = array(
                "code_use" => 'N'
            );

            
            $result = $this->codeInfo->update($params);

            if ($result) {
                $return_data['msg']   = "코드가 삭제되었습니다.";
            } else {
                throw new Exception('코드 삭제에 실패하였습니다.', 503);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //리스트 호출
    public function codeLoad($params)
    {
        $degree    = !empty($params['degree']) ? $params['degree'] : ''; //차수

        try {
            if (empty($degree)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 401);
            }
            
            $result = $this->codeInfo->codeLoad($params);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function codeLoadName($code_num1)
    {
        if (empty($code_num1)) {
            throw new Exception('잘못된 접근입니다. (필수 값 누락)', 601);
        }

        try {
            $result = $this->codeInfo->codeLoadName($code_num1);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function codethirdLoadName($code_num1)
    {
        if (empty($code_num1)) {
            throw new Exception('잘못된 접근입니다. (필수 값 누락)', 601);
        }

        try {
            $result = $this->codeInfo->codethirdLoadName($code_num1);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$codeController = new CodeController();
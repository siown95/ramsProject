<?php
include $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Model.php";

class CodeInfo extends Model
{
    var $table_name = 'codem';

    function __construct()
    {
        parent::__construct();
    }

    //코드 중복체크
    public function codeCheck($params)
    {
        $degree    = !empty($params['degree']) ? $params['degree'] : ''; //차수
        $code_num1 = !empty($params['code_num1']) ? $params['code_num1'] : ''; //1차 코드
        $code_num2 = !empty($params['code_num2']) ? $params['code_num2'] : ''; //2차 코드
        $code_num3 = !empty($params['code_num3']) ? $params['code_num3'] : ''; //3차 코드
        $code_name = !empty($params['code_name']) ? $params['code_name'] : ''; //코드명
        $where     = ''; //조건

        if ($degree == 1) {
            if (empty($degree) || empty($code_num1) || empty($code_name)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 401);
            }
        } else if ($degree == 2) {
            if (empty($degree) || empty($code_num1) || empty($code_num2) || empty($code_name)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 402);
            }
            $where = " and code_num2 = '" . $code_num2 . "'";
        } else {
            if (empty($degree) || empty($code_num1) || empty($code_num2) || empty($code_num3) || empty($code_name)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 402);
            }
            $where = " and code_num2 = '" . $code_num2 . "' and code_num3 = '" . $code_num3 . "'";
        }

        $sql = "select count(0) from {$this->table_name}
            where code_num1 = '" . $code_num1 . "'
            " . $where;

        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function codeLoad($params)
    {
        $degree   = !empty($params['degree'])   ? $params['degree']   : ''; //차수
        $code_num1 = !empty($params['code_num1']) ? $params['code_num1'] : ''; //1차코드
        $code_num2 = !empty($params['code_num2']) ? $params['code_num2'] : ''; //2차코드

        if ($degree == 1) {
            #1차 코드 불러오는 함수
            $degree_first_sql = "SELECT code_num1, code_name, is_necessary FROM {$this->table_name} WHERE code_num2 = '' AND code_use = 'Y'";

            $degree_first_result = $this->db->sqlRowArr($degree_first_sql);

            if (!empty($degree_first_result)) {
                foreach ($degree_first_result as $key => $val) {
                    $return_data[] = array(
                        "code_num"  => $val['code_num1'],
                        "code_name" => $val['code_name'],
                        "necessary" => $val['is_necessary'],
                        "code_num2" => '',
                        "detail" => ''
                    );
                }
            }
        } else if ($degree == 2) {
            #2차 코드 불러오는 함수
            $degree_second_sql = "SELECT code_num2, code_name, is_necessary FROM {$this->table_name} 
            WHERE code_num1 = " . $code_num1 . " AND code_num2 <> '' AND code_num3 = '' AND code_use = 'Y'";

            $degree_second_result = $this->db->sqlRowArr($degree_second_sql);

            if (!empty($degree_second_result)) {
                foreach ($degree_second_result as $key => $val) {
                    $return_data[] = array(
                        "code_num"  => $val['code_num2'],
                        "code_name" => $val['code_name'],
                        "necessary" => $val['is_necessary']
                    );
                }
            }
        } else {
            $degree_third_sql = "SELECT code_num3, detail, is_necessary FROM {$this->table_name} 
                                 WHERE code_num1 = '" . $code_num1 . "' AND code_num2 = '" . $code_num2 . "' AND code_num3 <> '' AND code_use = 'Y'";

            $degree_third_result = $this->db->sqlRowArr($degree_third_sql);

            if (!empty($degree_third_result)) {
                foreach ($degree_third_result as $key => $val) {
                    $return_data[] = array(
                        "code_num"  => $val['code_num3'],
                        "code_name" => $val['detail'],
                        "necessary" => $val['is_necessary']
                    );
                }
            }
        }

        return $return_data;
    }

    public function codeLoadName($code_num1)
    {
        $sql = "SELECT code_num1, code_num2, code_name FROM codem WHERE code_num1 = '" . $code_num1 . "' AND code_num2 <> ''";

        $data = $this->db->sqlRowArr($sql);

        if (!empty($data)) {
            return array(
                'count'      => count($data),
                'data'       => !empty($data) ? $data : null
            );
        }
    }

    public function codethirdLoadName($code_num1)
    {
        $sql = "SELECT code_num1, code_num2, code_name FROM codem WHERE code_num1 = '" . $code_num1 . "' AND code_num2 <> '' AND code_num3 = ''";
        $data = $this->db->sqlRowArr($sql);

        if (!empty($data)) {
            return array(
                'count'      => count($data),
                'data'       => !empty($data) ? $data : null
            );
        }
    }
}
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";

class Model
{
    var $db;

    function __construct()
    {
        global $db;
        $this->db = $db;
    }

    //전화번호 설정
    public function phoneFormat($phone, $division = false)
    {
        $phone = preg_replace("/[^0-9]/", "", $phone);  // 숫자 이외 제거

        if ($division == true) {
            if (substr($phone, 0, 2) == '02') {    // 지역번호 - 서울
                $phone = preg_replace("/([0-9]{2})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $phone);
            } else if (strlen($phone) == '8' && (substr($phone, 0, 2) == '15' || substr($phone, 0, 2) == '16' || substr($phone, 0, 2) == '18')) {    // 지능망 번호
                $phone = preg_replace("/([0-9]{4})([0-9]{4})$/", "\\1-\\2", $phone);
            } else {
                $phone = preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $phone);
            }
        }

        return $phone;
    }

    public function insert($list)
    {
        $key_arr = array();
        $val_arr = array();
        $sql = "INSERT INTO {$this->table_name} ";
        foreach ($list as $key => $val) {
            $key_arr[] .= $key;
            
            if ($val === 'getdate()') {
                $val_arr[] .= "getdate()";
            } else if (is_array($val)) {
                $val_arr[] .= "'" . json_encode($val) . "'";
            } else {
                $val_arr[] .= "'" . str_replace("'", "''", $val) . "'";
            }
        }
        $key_str = implode(",", $key_arr);
        $val_str = implode(",", $val_arr);

        $sql = $sql . "(" . $key_str . ") VALUES (" . $val_str . ")";

        try {
            $result = $this->db->execute($sql);
        } catch (Exception $e) {
            new Exception($e);
        }

        $this->last_sql = $sql;
        return $result;
    }

    public function update($list)
    {
        $sql = "UPDATE {$this->table_name} SET ";
        foreach ($list as $key => $val) {
            if ($val === 'getdate()') {
                $sql .= "{$key}=getdate(),";
            } else if ($val === 'NULL') {
                $sql .= "{$key}=NULL,";
            } else {
                $sql .= "{$key}='" . str_replace("'", "''", $val) . "',";
            }
        }

        $sql = substr($sql, 0, -1);

        if (!empty($this->where_qry)) {
            $sql .= " WHERE ";
            if (is_array($this->where_qry)) {
                foreach ($this->where_qry as $key => $val) {
                    $sql .= "{$key} = " . (is_numeric($val) ? $val : "'" . $val . "'");
                    $sql .= ($key < count($this->where_qry) - 1) ? " AND " : '';
                }
            } else {
                $sql .= $this->where_qry;
            }
        }

        try {
            $result = $this->db->execute($sql);
        } catch (Exception $e) {
            throw new Exception('DB Update Error ' . $e->getMessage(), $e->getCode());
        }

        $this->last_sql = $sql;
        return $result;
    }

    public function delete()
    {
        if (is_null($this->where_qry) || is_null($this->table_name)) {
            return false;
        }

        $sql = "DELETE FROM {$this->table_name} WHERE " . $this->where_qry;
        $this->last_sql = $sql;

        return $this->db->execute($sql);
    }
}

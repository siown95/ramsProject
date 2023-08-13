<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/Model.php";

class StudentInfo extends Model
{
    var $table_name = 'member_studentM';

    function __construct()
    {
        parent::__construct();
    }

    public function studentUpdate($params)
    {
        $user_no           = !empty($params['user_no']) ? $params['user_no'] : '';
        $user_name         = !empty($params['user_name']) ? $params['user_name'] : '';
        $password          = !empty($params['password']) ? $params['password'] : '';
        $gender            = !empty($params['gender']) ? $params['gender'] : '';
        $birth             = !empty($params['birth']) ? $params['birth'] : '';
        $user_phone        = !empty($params['user_phone']) ? $params['user_phone'] : '';
        $email             = !empty($params['email']) ? $params['email'] : '';
        $school            = !empty($params['school']) ? $params['school'] : '';
        $zipcode           = !empty($params['zipcode']) ? $params['zipcode'] : '';
        $address           = !empty($params['address']) ? $params['address'] : '';
        $pass_update = '';

        if (!empty($password)) {
            $pass_update = ", password = '" . $password . "'";
        }

        $sql = "UPDATE {$this->table_name} SET 
                user_name = '" . $user_name . "'
                , gender = '" . $gender . "'
                , birth = '" . $birth . "'
                , user_phone = '" . $user_phone . "'
                , email = '" . $email . "'
                , school_name = '" . $school . "'
                , zipcode = '" . $zipcode . "'
                , address = '" . $address . "'
                , mod_date = getdate()
                " . $pass_update . "
            WHERE user_no = '" . $user_no . "'";
        $result = $this->db->execute($sql);
        return $result;
    }

    public function loadStudent($state, $franchise_idx)
    {
        $sql = "SELECT 
            e.user_no
            , e.user_name
            , e.position
            , c.code_name
            , e.user_phone 
        FROM {$this->table_name} e
        LEFT OUTER JOIN codem c
            ON c.code_num1 = '08' AND e.position = c.code_num2 AND c.code_num2 <> ''
        WHERE e.show_yn = 'Y'
              AND e.state = '" . $state . "'
              AND e.franchise_idx = '" . $franchise_idx . "'
              AND e.user_id <> 'admin'";

        $result = $this->db->sqlRowArr($sql);

        if (!empty($result)) {
            foreach ($result as $key => $val) {
                $return_data[] = array(
                    "user_no"     => $val['user_no'],
                    "user_name"   => $val['user_name'],
                    "position"    => $val['code_name'],
                    "user_phone"  => $val['user_phone'],
                    "position_no" => $val['position']
                );
            }
        }

        return $return_data;
    }

    public function idCheck($user_id)
    {
        $sql = "SELECT COUNT(0) FROM {$this->table_name}
                WHERE user_id = '" . $user_id . "'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function findUserId($params)
    {
        $user_name         = !empty($params['user_name']) ? $params['user_name'] : '';
        $email             = !empty($params['email']) ? $params['email'] : '';

        $sql = "SELECT m.user_no, m.user_id, m.user_name, m.email, f.center_name
                FROM {$this->table_name} m
                LEFT OUTER JOIN franchisem f
                  ON m.franchise_idx = f.franchise_idx
                WHERE m.email = '" . addslashes($email) . "' AND m.user_name = '" . $user_name . "'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function myInfoUpdate($params)
    {
        $user_no           = !empty($params['user_no']) ? $params['user_no'] : '';
        $user_name         = !empty($params['user_name']) ? $params['user_name'] : '';
        $password          = !empty($params['password']) ? hash("sha256", $params['password']) : '';
        $gender            = !empty($params['gender']) ? $params['gender'] : '';
        $birth             = !empty($params['birth']) ? $params['birth'] : '';
        $user_phone        = !empty($params['user_phone']) ? $params['user_phone'] : '';
        $email             = !empty($params['email']) ? $params['email'] : '';
        $school            = !empty($params['school']) ? $params['school'] : '';
        $zipcode           = !empty($params['zipcode']) ? $params['zipcode'] : '';
        $address           = !empty($params['address']) ? $params['address'] : '';
        $pass_update = '';

        if (!empty($password)) {
            $pass_update = ", password = '" . $password . "'";
        }

        $sql = "UPDATE {$this->table_name} SET 
                user_name = '" . $user_name . "'
                , gender = '" . $gender . "'
                , birth = '" . $birth . "'
                , user_phone = '" . $user_phone . "'
                , email = '" . $email . "'
                , school_name = '" . $school . "'
                , zipcode = '" . $zipcode . "'
                , address = '" . $address . "'
                , mod_date = getdate()
                " . $pass_update . "
            WHERE user_no = '" . $user_no . "'";

        $result = $this->db->execute($sql);
        return $result;
    }

    public function searchStudent()
    {
        $sql = "SELECT user_no, user_name FROM {$this->table_name} WHERE state = '00' AND user_id <> 'admin'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function searchId($user_name)
    {
        if (empty($user_name)) {
            throw new Exception('잘못된 접근입니다. (필수 값 누락)', 602);
        }

        $sql = "SELECT user_name, user_phone, user_id FROM {$this->table_name} WHERE user_name LIKE '%" . $user_name . "%' AND franchise_idx != '1' ";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}
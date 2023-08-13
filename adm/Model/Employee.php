<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Model.php";

class EmployeeInfo extends Model
{
    var $table_name = 'member_employeem';
    var $commute_table = 'employee_commutem';

    function __construct()
    {
        parent::__construct();
    }

    public function employeeUpdate($params)
    {
        $franchise_idx     = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';
        $user_no           = !empty($params['user_no']) ? $params['user_no'] : '';
        $user_name         = !empty($params['user_name']) ? $params['user_name'] : '';
        $password          = !empty($params['password']) ? $params['password'] : '';
        $gender            = !empty($params['gender']) ? $params['gender'] : '';
        $state             = !empty($params['state']) ? $params['state'] : '';
        $position          = !empty($params['position']) ? $params['position'] : '';
        $birth             = !empty($params['birth']) ? $params['birth'] : '';
        $user_phone        = !empty($params['user_phone']) ? $params['user_phone'] : '';
        $emergencyhp       = !empty($params['emergencyhp']) ? $params['emergencyhp'] : '';
        $email             = !empty($params['email']) ? $params['email'] : '';
        $school            = !empty($params['school']) ? $params['school'] : '';
        $graduation_months = !empty($params['graduation_months']) ? $params['graduation_months'] : '';
        $major             = !empty($params['major']) ? $params['major'] : '';
        $degree_number     = !empty($params['degree_number']) ? $params['degree_number'] : '';
        $career            = !empty($params['career']) ? $params['career'] : '';
        $career_year       = !empty($params['career_year']) ? $params['career_year'] : '';
        $certificate       = !empty($params['certificate']) ? $params['certificate'] : '';
        $bank_name         = !empty($params['bank_name']) ? $params['bank_name'] : '';
        $account_number    = !empty($params['account_number']) ? "ENCRYPTBYPASSPHRASE('" . ACCOUNT_HASH . "', N'" . $params['account_number'] . "')" : '';
        $zipcode           = !empty($params['zipcode']) ? $params['zipcode'] : '';
        $address           = !empty($params['address']) ? $params['address'] : '';
        $menu_group        = !empty($params['menu_group']) ? $params['menu_group'] : '';
        $hire_date         = !empty($params['hire_date']) ? $params['hire_date'] : '';
        $resign_date       = !empty($params['resign_date']) ? $params['resign_date'] : '';
        $pass_update = '';

        if (!empty($password)) {
            $pass_update = ", password = '" . $password . "'";
        }

        if (!empty($account_number)) {
            $account_update = ", account_number = " . $account_number;
        } else {
            $account_update = ", account_number = CAST('' AS VARBINARY)";
        }

        $member_table = '';

        if ($franchise_idx == '1') {
            $member_table = 'member_employeem';
        } else {
            $member_table = 'member_centerm';
        }

        $sql = "UPDATE {$member_table} SET 
                user_name = '" . $user_name . "'
                , gender = '" . $gender . "'
                , birth = '" . $birth . "'
                , user_phone = '" . $user_phone . "'
                , emergencyhp = '" . $emergencyhp . "'
                , email = '" . $email . "'
                , state = '" . $state . "'
                , position = '" . $position . "'
                , hire_date = '" . $hire_date . "'
                , resign_date = '" . $resign_date . "'
                , school = '" . $school . "'
                , graduation_months = '" . $graduation_months . "'
                , major = '" . $major . "'
                , degree_number = '" . $degree_number . "'
                , career = '" . $career . "'
                , career_year = '" . $career_year . "'
                , certificate = '" . $certificate . "'
                , bank_name = '" . $bank_name . "'
                , zipcode = '" . $zipcode . "'
                , address = '" . $address . "'
                , menu_group = '" . $menu_group . "'
                , mod_date = getdate()
                " . $account_update . "
                " . $pass_update . "
            WHERE user_no = '" . $user_no . "'";

        $result = $this->db->execute($sql);
        return $result;
    }

    public function loadEmployee($franchise_idx, $state)
    {
        $member_table = '';
        $code_num = '';

        if ($franchise_idx == '1') {
            $member_table = 'member_employeem';
            $code_num = '08';
        } else {
            $member_table = 'member_centerm';
            $code_num = '20';
        }

        $sql = "SELECT 
                m.user_no
                , m.user_name
                , m.position
                , c.code_name
                , m.user_phone 
                FROM {$member_table} m
                LEFT OUTER JOIN codem c
                ON c.code_num1 = '" . $code_num . "' AND m.position = c.code_num2 AND c.code_num2 <> ''
                WHERE m.show_yn = 'Y'
                AND m.state = '" . $state . "'
                AND m.franchise_idx = '" . $franchise_idx . "'
                AND m.user_id <> 'admin'";

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

    public function selectEmployee($user_no, $franchise_idx)
    {
        $member_table = '';

        if ($franchise_idx == '1') {
            $member_table = 'member_employeem';
        } else {
            $member_table = 'member_centerm';
        }

        $sql = "SELECT 
                m.user_id
                , m.user_name
                , m.user_phone
                , m.emergencyhp
                , m.birth
                , m.gender
                , m.state
                , m.position
                , m.email
                , m.zipcode
                , m.address 
                , m.school
                , m.graduation_months
                , m.major
                , m.degree_number
                , m.career
                , m.career_year
                , m.certificate
                , m.bank_name
                , m.hire_date
                , m.resign_date
                , CAST(DECRYPTBYPASSPHRASE('" . ACCOUNT_HASH . "',account_number) AS nvarchar(2000)) account_number
                , m.menu_group
                , c.from_time
                , c.to_time
                , c.paid_holiday
                , c.unpaid_holiday
                FROM {$member_table} m
                LEFT OUTER JOIN {$this->commute_table} c
                ON m.user_no = c.user_no AND m.franchise_idx = c.franchise_idx
                WHERE m.user_no = '" . $user_no . "'";
        $result = $this->db->sqlRow($sql);

        if ($result) {
            $result['user_phone'] =  $this->phoneFormat($result['user_phone']);
            $result['emergencyhp'] =  $this->phoneFormat($result['emergencyhp']);
            $result['from_time_array'] = !empty($result['from_time']) ? explode(",", $result['from_time']) : '';
            $result['to_time_array'] = !empty($result['to_time']) ? explode(",", $result['to_time']) : '';
            $result['menu_group_array'] = !empty($result['menu_group']) ? explode(",", $result['menu_group']) : '';
        }

        return $result;
    }

    public function idCheck($user_id)
    {
        $sql = "SELECT COUNT(0) FROM {$this->table_name} WHERE user_id = '" . $user_id . "'";
        $result = $this->db->sqlRowOne($sql);

        return $result;
    }

    public function commuteCheck($user_no, $franchise_idx)
    {
        $sql = "SELECT COUNT(0) FROM {$this->commute_table} WHERE user_no = '" . $user_no . "' AND franchise_idx = '" . $franchise_idx . "'";
        $cnt = $this->db->sqlRowOne($sql);

        return $cnt;
    }

    public function commuteInsert($params)
    {
        $franchise_idx  = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';
        $user_no        = !empty($params['user_no']) ? $params['user_no'] : '';
        $paid_holiday   = !empty($params['paid_holiday']) ? $params['paid_holiday'] : '';
        $unpaid_holiday = !empty($params['unpaid_holiday']) ? $params['unpaid_holiday'] : '';
        $from_time      = !empty($params['from_time']) ? $params['from_time'] : '';
        $to_time        = !empty($params['to_time']) ? $params['to_time'] : '';

        $key_arr = array("user_no", "franchise_idx", "from_time", "to_time", "paid_holiday", "unpaid_holiday");
        $key_str = implode(",", $key_arr);

        if (!empty($user_no)) {
            $sql = "INSERT INTO {$this->commute_table} (" . $key_str . ") VALUES
                      ('" . $user_no . "'
                    , '" . $franchise_idx . "'
                    , '" . $from_time . "'
                    , '" . $to_time . "'
                    , '" . $paid_holiday . "'
                    , '" . $unpaid_holiday . "')";
            $result = $this->db->execute($sql);
        }
        return $result;
    }

    public function commuteUpdate($params)
    {
        $franchise_idx  = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';
        $user_no        = !empty($params['user_no']) ? $params['user_no'] : '';
        $paid_holiday   = !empty($params['paid_holiday']) ? $params['paid_holiday'] : '';
        $unpaid_holiday = !empty($params['unpaid_holiday']) ? $params['unpaid_holiday'] : '';
        $from_time      = !empty($params['from_time']) ? $params['from_time'] : '';
        $to_time        = !empty($params['to_time']) ? $params['to_time'] : '';

        if (!empty($user_no)) {
            $sql = "UPDATE {$this->commute_table} SET
                  from_time = '" . $from_time . "'
                , to_time = '" . $to_time . "'
                , paid_holiday = '" . $paid_holiday . "'
                , unpaid_holiday = '" . $unpaid_holiday . "'
            WHERE user_no = '" . $user_no . "' AND franchise_idx = '" . $franchise_idx . "'";
            $result = $this->db->execute($sql);
        }

        return $result;
    }

    public function findUserId($params)
    {
        $user_name = !empty($params['user_name']) ? $params['user_name'] : '';
        $email     = !empty($params['email']) ? $params['email'] : '';

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
        $emergencyhp       = !empty($params['emergencyhp']) ? $params['emergencyhp'] : '';
        $email             = !empty($params['email']) ? $params['email'] : '';
        $school            = !empty($params['school']) ? $params['school'] : '';
        $graduation_months = !empty($params['graduation_months']) ? $params['graduation_months'] : '';
        $major             = !empty($params['major']) ? $params['major'] : '';
        $degree_number     = !empty($params['degree_number']) ? $params['degree_number'] : '';
        $career            = !empty($params['career']) ? $params['career'] : '';
        $career_year       = !empty($params['career_year']) ? $params['career_year'] : '';
        $certificate       = !empty($params['certificate']) ? $params['certificate'] : '';
        $bank_name         = !empty($params['bank_name']) ? $params['bank_name'] : '';
        $account_number    = !empty($params['account_number']) ? "ENCRYPTBYPASSPHRASE('" . ACCOUNT_HASH . "', N'" . $params['account_number'] . "')" : '';
        $zipcode           = !empty($params['zipcode']) ? $params['zipcode'] : '';
        $address           = !empty($params['address']) ? $params['address'] : '';
        $pass_update = '';

        if (!empty($password)) {
            $pass_update = ", password = '" . $password . "'";
        }

        if (!empty($account_number)) {
            $account_update = ", account_number = " . $account_number;
        } else {
            $account_update = ", account_number = CAST('' AS VARBINARY)";
        }

        $sql = "UPDATE {$this->table_name} SET 
                user_name = '" . $user_name . "'
                , gender = '" . $gender . "'
                , birth = '" . $birth . "'
                , user_phone = '" . $user_phone . "'
                , emergencyhp = '" . $emergencyhp . "'
                , email = '" . $email . "'
                , school = '" . $school . "'
                , graduation_months = '" . $graduation_months . "'
                , major = '" . $major . "'
                , degree_number = '" . $degree_number . "'
                , career = '" . $career . "'
                , career_year = '" . $career_year . "'
                , certificate = '" . $certificate . "'
                , bank_name = '" . $bank_name . "'
                , zipcode = '" . $zipcode . "'
                , address = '" . $address . "'
                , mod_date = getdate()
                " . $account_update . "
                " . $pass_update . "
            WHERE user_no = '" . $user_no . "'";
        $result = $this->db->execute($sql);
        return $result;
    }

    public function searchEmployee()
    {
        $sql = "SELECT user_no, user_name FROM {$this->table_name} WHERE state = '00' AND user_id <> 'admin'";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function searchId($user_name, $franchise_idx)
    {
        if (empty($user_name) || empty($franchise_idx)) {
            throw new Exception('잘못된 접근입니다. (필수 값 누락)', 602);
        }

        $sql = "SELECT user_name, user_phone, user_id FROM member_centerm WHERE user_name LIKE '%" . $user_name . "%' AND franchise_idx = '{$franchise_idx}' ";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }

    public function loadState($franchise_idx)
    {
        $code_num1 = '';

        if ($franchise_idx == 1) {
            $code_num1 = '08';
        } else {
            $code_num1 = '20';
        }

        $sql = "SELECT code_num2, code_name FROM codem WHERE code_num1 = '" . $code_num1 . "' AND code_num2 <> ''";
        $result = $this->db->sqlRowArr($sql);

        return $result;
    }
}

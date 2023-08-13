<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Employee.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class EmployeeController extends Controller
{
    private $employeeInfo;

    function __construct()
    {
        $this->employeeInfo = new EmployeeInfo();
    }

    //직원 리스트 불러오기
    public function loadEmployee($params)
    {
        $state = !empty($params['state']) ? $params['state'] : '00';
        $franchise_idx = !empty($params['center_idx']) ? $params['center_idx'] : $_SESSION['center_idx'];

        try {
            $result = $this->employeeInfo->loadEmployee($state, $franchise_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //직원 선택하여 정보 확인
    public function selectEmployee($params)
    {
        $user_no = !empty($params['user_no']) ? $params['user_no'] : '';

        if (empty($user_no)) {
            throw new Exception('잘못된 접근입니다. (필수 값 누락)', 600);
        }

        try {
            $result = $this->employeeInfo->selectEmployee($user_no);

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //아이디 중복 확인
    public function idCheck($params)
    {
        $user_id = !empty($params['user_id']) ? $params['user_id'] : '';

        if (empty($user_id)) {
            throw new Exception('잘못된 접근입니다. (필수 값 누락)', 602);
        }

        try {
            $id_cnt = $this->employeeInfo->idCheck($user_id);

            if ($id_cnt > 0) {
                throw new Exception('사용중인 아이디입니다.', 603);
            } else {
                $result['msg'] = '사용 가능한 아이디입니다.';
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //직원 등록
    public function employeeInsert($request)
    {
        $user_id        = !empty($request['user_id']) ? $request['user_id'] : '';
        $password       = !empty($request['password']) ? $request['password'] : '';
        $user_name      = !empty($request['user_name']) ? $request['user_name'] : '';
        $user_phone     = !empty($request['user_phone']) ? $request['user_phone'] : '';
        $birth          = !empty($request['birth']) ? $request['birth'] : '';
        $gender         = !empty($request['gender']) ? $request['gender'] : '';
        $email          = !empty($request['email']) ? $request['email'] : '';
        $zipcode        = !empty($request['zipcode']) ? $request['zipcode'] : '';
        $address        = !empty($request['address']) ? $request['address'] : '';
        $franchise_idx  = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';

        try {

            if (
                empty($user_id) || empty($password) || empty($user_name) || empty($user_phone) || empty($birth) || empty($gender) || empty($email) || empty($zipcode)
                || empty($address) || empty($franchise_idx)
            ) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 404);
            }

            $params = array(
                "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                "user_id" => !empty($user_id) ? $user_id : '',
                "password" => !empty($password) ? hash("sha256", $password) : '',
                "user_name" => !empty($user_name) ? $user_name : '',
                "user_phone" => !empty($user_phone) ? phoneFormat($user_phone, true) : '',
                "birth" => !empty($birth) ? $birth : '',
                "position" => !empty($position) ? $position : '',
                "gender" => !empty($gender) ? $gender : '',
                "email" => !empty($email) ? $email : '',
                "zipcode" => !empty($zipcode) ? $zipcode : '',
                "address" => !empty($address) ? $address : '',
            );

            $result = $this->employeeInfo->insert($params);
            if ($result) {
                $return_data['msg'] = '정상적으로 등록되었습니다.';
            } else {
                throw new Exception('직원 등록에 실패하였습니다.', 501);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //직원 수정
    public function employeeUpdate($request)
    {
        $franchise_idx     = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $user_no           = !empty($request['user_no']) ? $request['user_no'] : '';
        $password          = !empty($request['password']) ? $request['password'] : '';
        $user_name         = !empty($request['user_name']) ? $request['user_name'] : '';
        $user_phone        = !empty($request['user_phone']) ? $request['user_phone'] : '';
        $emergencyhp       = !empty($request['emergencyhp']) ? $request['emergencyhp'] : '';
        $birth             = !empty($request['birth']) ? $request['birth'] : '';
        $gender            = !empty($request['gender']) ? $request['gender'] : '';
        $state             = !empty($request['state']) ? $request['state'] : '';
        $position          = !empty($request['position']) ? $request['position'] : '';
        $email             = !empty($request['email']) ? $request['email'] : '';
        $zipcode           = !empty($request['zipcode']) ? $request['zipcode'] : '';
        $address           = !empty($request['address']) ? $request['address'] : '';
        $school            = !empty($request['school']) ? $request['school'] : '';
        $graduation_months = !empty($request['graduation_months']) ? $request['graduation_months'] : '';
        $major             = !empty($request['major']) ? $request['major'] : '';
        $degree_number     = !empty($request['degree_number']) ? $request['degree_number'] : '';
        $career            = !empty($request['career']) ? $request['career'] : '';
        $career_year       = !empty($request['career_year']) ? $request['career_year'] : '';
        $certificate       = !empty($request['certificate']) ? $request['certificate'] : '';
        $bank_name         = !empty($request['bank_name']) ? $request['bank_name'] : '';
        $account_number    = !empty($request['account_number']) ? $request['account_number'] : '';
        $hire_date         = !empty($request['hire_date']) ? $request['hire_date'] : '';
        $resign_date       = !empty($request['resign_date']) ? $request['resign_date'] : '';
        $menu_group        = !empty($request['menu_group_arr']) ? implode(",", $request['menu_group_arr']) : '';

        $paid_holiday      = !empty($request['paid_holiday']) ? $request['paid_holiday'] : '';
        $unpaid_holiday    = !empty($request['unpaid_holiday']) ? $request['unpaid_holiday'] : '';
        $from_time         = !empty($request['from_time']) ? $request['from_time'] : '';
        $to_time           = !empty($request['to_time']) ? $request['to_time'] : '';

        try {
            if (
                empty($franchise_idx) || empty($user_no) || empty($user_name) || empty($user_phone) || empty($emergencyhp) || empty($birth) || empty($gender) || empty($state) || empty($position) || empty($email) || empty($zipcode) || empty($address) || empty($paid_holiday) || empty($unpaid_holiday)
                || empty($from_time) || empty($to_time)
            ) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 607);
            }

            $params = array(
                "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                "user_no" => !empty($user_no) ? $user_no : '',
                "user_name" => !empty($user_name) ? $user_name : '',
                "user_phone" => !empty($user_phone) ? phoneFormat($user_phone, true) : '',
                "emergencyhp" => !empty($emergencyhp) ? phoneFormat($emergencyhp, true) : '',
                "birth" => !empty($birth) ? $birth : '',
                "gender" => !empty($gender) ? $gender : '',
                "state" => !empty($state) ? $state : '',
                "position" => !empty($position) ? $position : '',
                "email" => !empty($email) ? $email : '',
                "zipcode" => !empty($zipcode) ? $zipcode : '',
                "address" => !empty($address) ? $address : '',
                "school" => !empty($school) ? $school : '',
                "graduation_months" => !empty($graduation_months) ? $graduation_months : '',
                "major" => !empty($major) ? $major : '',
                "degree_number" => !empty($degree_number) ? $degree_number : '',
                "career" => !empty($career) ? $career : '',
                "career_year" => !empty($career_year) ? $career_year : '',
                "certificate" => !empty($certificate) ? $certificate : '',
                "bank_name" => !empty($bank_name) ? $bank_name : '',
                "account_number" => !empty($account_number) ? $account_number : '',
                "menu_group" => !empty($menu_group) ? $menu_group : '',
                "hire_date" => !empty($hire_date) ? $hire_date : '',
                "resign_date" => !empty($resign_date) ? $resign_date : '',
                "mod_date" => 'getdate()'
            );

            if (!empty($password)) {
                $params['password'] = hash("sha256", $password);
            }

            $result = $this->employeeInfo->employeeUpdate($params);

            if ($result) {
                $chkCnt = $this->employeeInfo->commuteCheck($user_no, $franchise_idx);
                $params2 = array(
                    "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                    "user_no" => !empty($user_no) ? $user_no : '',
                    "from_time" => !empty($from_time) ? $from_time : '',
                    "to_time" => !empty($to_time) ? $to_time : '',
                    "paid_holiday" => !empty($paid_holiday) ? $paid_holiday : '',
                    "unpaid_holiday" => !empty($unpaid_holiday) ? $unpaid_holiday : ''
                );

                if ($chkCnt < 1) {
                    $result2 = $this->employeeInfo->commuteInsert($params2);
                } else {
                    $result2 = $this->employeeInfo->commuteUpdate($params2);
                }

                if ($result2) {
                    $return_data['msg'] = "직원 수정에 성공하였습니다.";
                } else {
                    $return_data['msg'] = "출퇴근 정보가 수정되지 않았습니다.";
                }
            } else {
                throw new Exception('직원 수정에 실패하였습니다.', 609);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //아이디 찾기
    public function findUserId($request)
    {
        $user_name = !empty($request['user_name']) ? $request['user_name'] : '';
        $email = !empty($request['user_email']) ? $request['user_email'] : '';

        if (empty($email)) {
            throw new Exception('잘못된 접근입니다. (필수 값 누락)', 602);
        }

        try {
            $params = array(
                "user_name" => !empty($user_name) ? $user_name : '',
                "email" => !empty($email) ? $email : ''
            );

            $result = $this->employeeInfo->findUserId($params);
            $table = '';
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $user_id = setWildcardId($val['user_id']);
                    $table .= "<div class=\"input-group mb-1\">
                                   <div class=\"form-check-inline\">
                                       <input type=\"radio\" id=\"rdoMember" . $key . "\" class=\"form-check-input\" name=\"rdoMember\" value=\"" . $val['user_no'] . "\">
                                       <label class=\"form-check-label\" for=\"rdoMember" . $key . "\">" . $user_id . "<span class=\"ms-2\">(" . $val['center_name'] . ")</span></label>
                                   </div>
                               </div>";
                }
            } else {
                throw new Exception('해당 정보로 등록된 정보가 존재하지 않습니다.', 602);
            }
            $result['table'] = $table;

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    //개인 회원정보 수정
    public function myInfoUpdate($request)
    {
        $user_no           = !empty($request['user_no']) ? $request['user_no'] : '';
        $user_name         = !empty($request['user_name']) ? $request['user_name'] : '';
        $password          = !empty($request['password']) ? $request['password'] : '';
        $gender            = !empty($request['gender']) ? $request['gender'] : '';
        $birth             = !empty($request['birth']) ? $request['birth'] : '';
        $user_phone        = !empty($request['user_phone']) ? $request['user_phone'] : '';
        $emergencyhp       = !empty($request['emergencyhp']) ? $request['emergencyhp'] : '';
        $email             = !empty($request['email']) ? $request['email'] : '';
        $school            = !empty($request['school']) ? $request['school'] : '';
        $graduation_months = !empty($request['graduation_months']) ? $request['graduation_months'] : '';
        $major             = !empty($request['major']) ? $request['major'] : '';
        $degree_number     = !empty($request['degree_number']) ? $request['degree_number'] : '';
        $career            = !empty($request['career']) ? $request['career'] : '';
        $career_year       = !empty($request['career_year']) ? $request['career_year'] : '';
        $certificate       = !empty($request['certificate']) ? $request['certificate'] : '';
        $bank_name         = !empty($request['bank_name']) ? $request['bank_name'] : '';
        $account_number    = !empty($request['account_number']) ? $request['account_number'] : '';
        $zipcode           = !empty($request['zipcode']) ? $request['zipcode'] : '';
        $address           = !empty($request['address']) ? $request['address'] : '';

        try {
            if (empty($user_no) || empty($user_name) || empty($gender) || empty($birth) || empty($user_phone) || empty($emergencyhp) || empty($email) || empty($zipcode) || empty($address)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 602);
            }

            $params = array(
                "user_no" => $user_no,
                "user_name" => !empty($user_name) ? $user_name : '',
                "password" => !empty($password) ? $password : '',
                "gender" => !empty($gender) ? $gender : '',
                "birth" => !empty($birth) ? $birth : '',
                "user_phone" => !empty($user_phone) ? phoneFormat($user_phone, true) : '',
                "emergencyhp" => !empty($emergencyhp) ? phoneFormat($emergencyhp, true) : '',
                "email" => !empty($email) ? $email : '',
                "school" => !empty($school) ? $school : '',
                "graduation_months" => !empty($graduation_months) ? $graduation_months : '',
                "major" => !empty($major) ? $major : '',
                "degree_number" => !empty($degree_number) ? $degree_number : '',
                "career" => !empty($career) ? $career : '',
                "career_year" => !empty($career_year) ? $career_year : '',
                "certificate" => !empty($certificate) ? $certificate : '',
                "bank_name" => !empty($bank_name) ? $bank_name : '',
                "account_number" => !empty($account_number) ? $account_number : '',
                "zipcode" => !empty($zipcode) ? $zipcode : '',
                "address" => !empty($address) ? $address : '',
            );
            $result = $this->employeeInfo->myInfoUpdate($params);

            if ($result) {
                $return_data['msg'] = "개인정보가 수정되었습니다.";
            } else {
                throw new Exception('개인정보 수정에 실패하였습니다.', 602);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function searchEmployee()
    {
        try {
            $result = $this->employeeInfo->searchEmployee();
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function searchId($params)
    {
        $user_name = !empty($params['user_name']) ? $params['user_name'] : '';

        try {
            $result = $this->employeeInfo->searchId($user_name);
            $table = '';

            if (!empty($result)) {
                foreach ($result as  $key => $val) {
                    $table .= "<tr class=\"idc\">
                                    <td>" . $val['user_name'] . "</td>
                                    <td>" . $val['user_phone'] . "</td>
                                    <td>" . $val['user_id'] . "</td>
                                </tr>";
                }
                $result['data'] = $table;
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function msgInfoSelect($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $state = !empty($request['state']) ? $request['state'] : '';

        try {
            if (empty($franchise_idx) || empty($state)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->employeeInfo->msgInfoSelect($franchise_idx, $state);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['chkmsg'] = "<input type=\"checkbox\" class=\"form-check-input chkMsg\" name=\"chkNo[]\" />";
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$employeeController = new EmployeeController();

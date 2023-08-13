<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Student.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class StudentController extends Controller
{
    private $studentInfo;

    function __construct()
    {
        $this->studentInfo = new StudentInfo();
    }

    public function loadStudent($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $state = !empty($request['state']) ? $request['state'] : '';

        try {
            if (empty($franchise_idx) || empty($state)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentInfo->loadStudent($franchise_idx, $state);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $key + 1;

                    $result[$key]['age'] = getGrade($val['age']);

                    if (!empty($val['color_code'])) {
                        $result[$key]['user_name'] = $val['user_name'] . " <span style=\"color:" . $val['color_code'] . ";\"><i class=\"fa-solid fa-circle\"></i></span>";
                    }
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadStudentDetail($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $state         = !empty($request['state']) ? $request['state'] : '';
        $grade         = !empty($request['grade']) ? $request['grade'] : '';
        $teacher_no    = !empty($request['teacher_no']) ? $request['teacher_no'] : '';

        try {
            if (empty($franchise_idx) || empty($state)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentInfo->loadStudentDetail($franchise_idx, $state, getAge($grade), $teacher_no);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $key + 1;

                    if (!empty($val['color_code'])) {
                        $result[$key]['user_name'] = $val['user_name'] . " <span style=\"color:" . $val['color_code'] . ";\"><i class=\"fa-solid fa-circle\"></i></span>";
                    }

                    $result[$key]['age'] = getGrade($val['age']);
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function selectStudent($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_no = !empty($request['user_no']) ? $request['user_no'] : '';

        try {
            if (empty($franchise_idx) || empty($user_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentInfo->selectStudent($franchise_idx, $user_no);
            if (!empty($result)) {

                $now = date("Y");
                $birth = date("Y", strtotime($result['birth']));
                $age = $now - $birth + 1;

                $result['grade'] = getGrade($age);

                if ($result['gender'] == 'M') {
                    $result['gender'] = '남';
                } else {
                    $result['gender'] = '여';
                }

                $result['user_phone'] = $this->studentInfo->phoneFormat($result['user_phone']);

                $result['reg_date'] = date("Y-m-d", strtotime($result['reg_date']));
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function updateStudent($request)
    {
        $user_no        = !empty($request['user_no']) ? $request['user_no'] : '';
        $user_name      = !empty($request['user_name']) ? $request['user_name'] : '';
        $birth          = !empty($request['birth']) ? $request['birth'] : '';
        $user_phone     = !empty($request['user_phone']) ? $request['user_phone'] : '';
        $email          = !empty($request['email']) ? $request['email'] : '';
        $school_name    = !empty($request['school_name']) ? $request['school_name'] : '';
        $teacher        = !empty($request['teacher']) ? $request['teacher'] : '';
        $state          = !empty($request['state']) ? $request['state'] : '';
        $address        = !empty($request['address']) ? $request['address'] : '';
        $zipcode        = !empty($request['zipcode']) ? $request['zipcode'] : '';
        $memo           = !empty($request['memo']) ? $request['memo'] : '';
        $color_tag      = !empty($request['color_tag']) ? $request['color_tag'] : '';

        try {
            if (empty($user_no) || empty($user_name) || empty($birth) || empty($user_phone) || empty($email) || empty($school_name) || empty($state) || empty($address) || empty($zipcode)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "user_name" => !empty($user_name) ? $user_name : '',
                "birth" => !empty($birth) ? $birth : '',
                "user_phone" => !empty($user_phone) ? phoneFormat($user_phone, true) : '',
                "email" => !empty($email) ? $email : '',
                "school_name" => !empty($school_name) ? $school_name : '',
                "teacher_no" => !empty($teacher) ? $teacher : '0',
                "state" => !empty($state) ? $state : '',
                "address" => !empty($address) ? $address : '',
                "zipcode" => !empty($zipcode) ? $zipcode : '',
                "user_memo" => !empty($memo) ? $memo : '',
                "color_tag" => !empty($color_tag) ? $color_tag : ''
            );

            $this->studentInfo->where_qry = " user_no = '" . $user_no . "' ";
            $result = $this->studentInfo->update($params);

            if ($result) {
                $return_data['msg'] = "원생 정보가 수정되었습니다.";
            } else {
                throw new Exception('원생 정보가 수정되지않았습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getStudentData($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';

        try {
            if (empty($franchise_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentInfo->getStudentData($franchise_idx);
            if (!empty($result)) {
                $temp_arr1 = array();
                $temp_arr2 = array();

                $dataset1 = array();
                $dataset2 = array();

                for ($i = 1; $i <= 12; $i++) {
                    $lastYearMonth = date('Y', strtotime("-1 year")) . "-" . sprintf("%02d", $i);
                    $nowYearMonth = date('Y') . "-" . sprintf("%02d", $i);

                    foreach ($result as $key => $val) {
                        if ($lastYearMonth == $val['ym']) {
                            $temp_arr1[$i] = $val['cnt'];
                        }

                        if ($nowYearMonth == $val['ym']) {
                            $temp_arr2[$i] = $val['cnt'];
                        }
                    }

                    if (!empty($temp_arr1[$i])) {
                        $dataset1[] = $temp_arr1[$i];
                    } else {
                        $dataset1[] = 0;
                    }

                    if (!empty($temp_arr2[$i])) {
                        $dataset2[] = $temp_arr2[$i];
                    } else {
                        $dataset2[] = 0;
                    }
                }
            }

            $result['dataset1'] = $dataset1;
            $result['dataset2'] = $dataset2;
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getStudentProfile($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_no       = !empty($request['user_no']) ? $request['user_no'] : '';

        try {
            if (empty($franchise_idx) || empty($user_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentInfo->selectStudent($franchise_idx, $user_no);

            if (!empty($result)) {
                if ($result['gender'] == 'M') {
                    $gender = '남';
                } else {
                    $gender = '여';
                }

                if (!empty($result['color_code'])) {
                    $color_tag = "<i class=\"fa-solid fa-circle me-1\" style=\"color:" . $result['color_code'] . "\"></i>" . $result['color_detail'];
                } else {
                    $color_tag = "";
                }

                $table = "<tr>
                              <td>이름</td>
                              <td>" . $result['user_name'] . "</td>
                              <td>성별</td>
                              <td>" . $gender . "</td>
                          </tr>
                          <tr>
                              <td>생년월일</td>
                              <td>" . $result['birth'] . "</td>
                              <td>학교명</td>
                              <td>" . $result['school_name'] . "</td>
                          </tr>
                          <tr>
                              <td>학부모명</td>
                              <td>홍길동동</td>
                              <td>연락처</td>
                              <td>" . $result['user_phone'] . "</td>
                          </tr>
                          <tr>
                              <td>첫수업일</td>
                              <td>" . $result['first_lesson_date'] . "</td>
                              <td>등록일</td>
                              <td>" . date("Y-m-d", strtotime($result['reg_date'])) . "</td>
                          </tr>
                          <tr>
                              <td>아이디</td>
                              <td>" . $result['user_id'] . "</td>
                              <td>색깔태그</td>
                              <td>" . $color_tag . "</td>
                          </tr>
                          <tr>
                              <td>주소</td>
                              <td>" . $result['address'] . "</td>
                              <td>우편번호</td>
                              <td>" . $result['zipcode'] . "</td>
                          </tr>
                          <tr>
                              <td>수업정보</td>
                              <td colspan=\"3\">" . $result['lesson_info'] . "</td>
                          </tr>
                          <tr>
                                <td>비고</td>
                                <td class=\"text-start\" colspan=\"3\">" . $result['user_memo'] . "</td>
                          </tr>";
            }
            $return_data['table'] = $table;

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function msgInfoSelect($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $teacher_no = !empty($request['teacher_idx']) ? $request['teacher_idx'] : '';
        $grade = !empty($request['grade']) ? $request['grade'] : '';
        $state = !empty($request['state']) ? $request['state'] : '';
        $months = !empty($request['months']) ? $request['months'] : '';

        try {
            if (empty($franchise_idx) || empty($state)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->studentInfo->msgInfoSelect($franchise_idx, $teacher_no, getAge($grade), $state, $months);
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

$studentController = new StudentController();

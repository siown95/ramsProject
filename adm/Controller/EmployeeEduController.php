<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/EmployeeEdu.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class EmployeeEduController extends Controller
{
    private $employeeEduInfo;

    function __construct()
    {
        $this->employeeEduInfo = new EmployeeEduInfo();
    }

    public function eduInfoInsert($request)
    {
        $edu_name = !empty($request['edu_name']) ? $request['edu_name'] : '';
        $edu_type = !empty($request['edu_type']) ? $request['edu_type'] : '';
        $edu_target = !empty($request['edu_target']) ? $request['edu_target'] : '';
        $edu_way = !empty($request['edu_way']) ? $request['edu_way'] : '';

        try {
            if (empty($edu_name) || empty($edu_type) || empty($edu_target) || empty($edu_way)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 602);
            }

            $params = array(
                "edu_name" => !empty($edu_name) ? $edu_name : '',
                "edu_type" => !empty($edu_type) ? $edu_type : '',
                "edu_target" => !empty($edu_target) ? $edu_target : '',
                "edu_way" => !empty($edu_way) ? $edu_way : ''
            );
            $result = $this->employeeEduInfo->insert($params);

            if ($result) {
                $return_data['msg'] = "교육정보가 등록되었습니다.";
            } else {
                throw new Exception('교육정보 등록에 실패하였습니다.', 602);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function eduInfoUpdate($request)
    {
        $edu_idx = !empty($request['edu_idx']) ? $request['edu_idx'] : '';
        $edu_name = !empty($request['edu_name']) ? $request['edu_name'] : '';
        $edu_type = !empty($request['edu_type']) ? $request['edu_type'] : '';
        $edu_target = !empty($request['edu_target']) ? $request['edu_target'] : '';
        $edu_way = !empty($request['edu_way']) ? $request['edu_way'] : '';

        try {
            if (empty($edu_idx) || empty($edu_name) || empty($edu_type) || empty($edu_target) || empty($edu_way)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 602);
            }

            $params = array(
                "edu_name" => !empty($edu_name) ? $edu_name : '',
                "edu_type" => !empty($edu_type) ? $edu_type : '',
                "edu_target" => !empty($edu_target) ? $edu_target : '',
                "edu_way" => !empty($edu_way) ? $edu_way : '',
                "mod_date" => 'getdate()'
            );
            $this->employeeEduInfo->where_qry = " edu_idx = '" . $edu_idx . "'";
            $result = $this->employeeEduInfo->update($params);

            if ($result) {
                $return_data['msg'] = "교육정보가 수정되었습니다.";
            } else {
                throw new Exception('교육정보 수정에 실패하였습니다.', 602);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function eduInfoDelete($parmas)
    {
        $edu_idx = !empty($parmas['edu_idx']) ? $parmas['edu_idx'] : '';

        try {
            if (empty($edu_idx)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 602);
            }

            $this->employeeEduInfo->where_qry = " edu_idx = '" . $edu_idx . "'";
            $result = $this->employeeEduInfo->delete();

            if ($result) {
                $return_data['msg'] = "교육정보가 삭제되었습니다.";
            } else {
                throw new Exception('교육정보 삭제에 실패하였습니다.', 602);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function eduInfoSelect($params)
    {
        $edu_idx = !empty($params['edu_idx']) ? $params['edu_idx'] : '';

        try {
            if (empty($edu_idx)) {
                return false;
            }

            $result = $this->employeeEduInfo->eduInfoSelect($edu_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function eduCenterSelect($params)
    {
        $franchise_idx = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';

        try {
            $result = $this->employeeEduInfo->eduCenterSelect($franchise_idx);
            $table = '';
            if ($result) {
                foreach ($result as $key => $val) {
                    $table .= "<tr class=\"align-middle text-center\">
                                <td><input type=\"checkbox\" class=\"form-check-input chk\" name=\"chkNo\" value=\"" . $val['user_no'] . "\"/></td>
                                <td>" . $val['user_name'] . "</td>
                            </tr>";
                }
                $return_data['data'] = $table;
            } else {
                throw new Exception('조회결과가 없습니다.', 602);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function eduScheduleInsert($request)
    {
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $edu_idx = !empty($request['edu_idx']) ? $request['edu_idx'] : '';
        $from_time = !empty($request['from_time']) ? $request['from_time'] : '';
        $to_time = !empty($request['to_time']) ? $request['to_time'] : '';
        $user_list = !empty($request['user_list']) ? $request['user_list'] : '';

        try {
            if (empty($franchise_idx) || empty($edu_idx) || empty($from_time) || empty($to_time) || empty($user_list)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 602);
            }

            $params = array(
                "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                "edu_idx" => !empty($edu_idx) ? $edu_idx : '',
                "from_time" => !empty($from_time) ? $from_time : '',
                "to_time" => !empty($to_time) ? $to_time : '',
                "user_list" => !empty($user_list) ? implode(",", $user_list) : ''
            );

            $result = $this->employeeEduInfo->eduScheduleInsert($params);

            if ($result) {
                $result2 = $this->employeeEduInfo->eduEmployeeInsert();
                if ($result2) {
                    $return_data['msg'] = "교육 일정이 등록되었습니다.";
                }
            } else {
                throw new Exception('교육 일정이 등록되지 않았습니다.', 602);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function eduCenterScheduleSelect($params)
    {
        $franchise_idx = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';

        try {
            $result = $this->employeeEduInfo->eduCenterScheduleSelect($franchise_idx);
            $table = '';
            if ($result) {
                foreach ($result as $key => $val) {
                    $table .= "<tr class=\"tc\" data-edu-schedule-idx=\"" . $val['eduschedule_idx'] . "\">
                                <td>" . $val['edu_name'] . "</td>
                                <td>" . $val['edu_type'] . "</td>
                                <td>" . $val['edu_time'] . "</td>
                                <td>" . count(explode(",", $val['user_list'])) . "</td>
                                <td>" . $val['cnt'] . "</td>
                               </tr>";
                }
                $return_data['data'] = $table;
            } else {
                throw new Exception('조회결과가 없습니다.', 602);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function eduScheduleSelect($params)
    {
        $eduschedule_idx = !empty($params['eduschedule_idx']) ? $params['eduschedule_idx'] : '';
        $franchise_idx = !empty($params['franchise_idx']) ? $params['franchise_idx'] : '';

        try {
            if (empty($eduschedule_idx)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 602);
            }

            $result = $this->employeeEduInfo->eduScheduleSelect($eduschedule_idx, $franchise_idx);
            $table = '';
            if ($result) {
                foreach ($result as $key => $val) {
                    if (!empty($val['file_name'])) {
                        $down_btn = "<a class=\"btn btn-sm btn-outline-info\" href=\"/files/edu_file/" . $val['file_name'] . "\" download>다운로드</a>";
                        $down_btn .= "<button type=\"button\" class=\"btn btn-sm btn-outline-success file-upload\" data-fn=\"" . $val['file_name'] . "\" >업로드</button>";
                    } else {
                        $down_btn = "<button type=\"button\" class=\"btn btn-sm btn-outline-success file-upload\">업로드</button>";
                    }
                    $table .= "<tr data-eduschedule-idx=\"" . $val['eduschedule_idx'] . "\" data-franchise-idx=\"" . $val['franchise_idx'] . "\" data-user-no=\"" . $val['user_no'] . "\">
                                <td>" . ($key + 1) . "</td>
                                <td>" . $val['user_name'] . "</td>
                                <td>" . $val['edu_complete_date'] . "</td>
                                <td>" . $down_btn . " <input type=\"file\" class=\"form-control d-none\" name=\"fileAttach\"></td>
                            </tr>";
                }
                $return_data['table'] = $table;
                $return_data['data'] = $this->employeeEduInfo->eduScheduleSelectOne($eduschedule_idx);
            } else {
                throw new Exception('조회결과가 없습니다.', 602);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function eduScheduleDelete($request)
    {
        $eduschedule_idx = !empty($request['eduschedule_idx']) ? $request['eduschedule_idx'] : '';

        try {
            if (empty($eduschedule_idx)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 602);
            }

            $result = $this->employeeEduInfo->eduScheduleDelete($eduschedule_idx);
            if ($result) {
                $return_data['msg'] = '교육 일정이 삭제되었습니다.';
            } else {
                throw new Exception('교육일정이 삭제되지 않았습니다.', 602);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function certificatesUpload($request)
    {
        $employee_edu_idx = !empty($request['employee_edu_idx']) ? $request['employee_edu_idx'] : '';
        $eduschedule_idx = !empty($request['eduschedule_idx']) ? $request['eduschedule_idx'] : '';
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $user_no = !empty($request['user_no']) ? $request['user_no'] : '';
        $origin_file_name = !empty($request['origin_file_name']) ? $request['origin_file_name'] : '';
        $file_name = !empty($_FILES['file_name']['name']) ? $_FILES['file_name']['name'] : '';

        try {
            if (empty($employee_edu_idx) || empty($eduschedule_idx)  || empty($franchise_idx) || empty($user_no) || empty($file_name)) {
                throw new Exception('잘못된 접근입니다. (필수 값 누락)', 602);
            }

            if (!empty($origin_file_name)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . "/files/edu_file/" . $origin_file_name);
            }

            $nameArr = explode(".", $_FILES['file_name']['name']);
            $extension = end($nameArr);
            $file_name = 'file_' . date("YmdHis") . "." . $extension;

            $path = $_SERVER['DOCUMENT_ROOT'] . "/files/edu_file/";
            if (copy($_FILES['file_name']['tmp_name'], $path . $file_name)) {
                $params = array(
                    "file_name" => !empty($file_name) ? $file_name : '',
                    "employee_edu_idx" => !empty($employee_edu_idx) ? $employee_edu_idx : '',
                    "eduschedule_idx" => !empty($eduschedule_idx) ? $eduschedule_idx : '',
                    "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                    "user_no" => !empty($user_no) ? $user_no : ''
                );

                $result = $this->employeeEduInfo->certificatesUpload($params);
                if ($result) {
                    $return_data['msg'] = '파일이 업로드되었습니다.';
                } else {
                    throw new Exception('파일업로드에 실패하였습니다2.', 602);
                }
            } else {
                throw new Exception('파일업로드에 실패하였습니다1.', 602);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$employeeEduController = new EmployeeEduController();
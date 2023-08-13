<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/Counsel.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class CounselController extends Controller
{
    private $counselInfo;

    function __construct()
    {
        $this->counselInfo = new CounselInfo();
    }

    //정규상담 함수 목록
    public function studentSearch($request)
    {
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $student_name  = !empty($request['student_name']) ? $request['student_name'] : '';

        try {
            if (empty($franchise_idx) || empty($student_name)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->counselInfo->studentSearch($franchise_idx, $student_name);

            $table = '';
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $table .= "<tr class=\"tc text-center align-middel\" data-student-no=\"" . $val['user_no'] . "\">
                                   <td>" . $val['user_name'] . "</td>
                                   <td>" . getGrade($val['user_age']) . "</td>
                                   <td>" . $val['school_name'] . "</td>
                               </tr>";
                }
            } else {
                $return_data['msg'] = "해당 정보의 학생은 존재하지 않습니다.";
            }
            $return_data['table'] = $table;

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function counselLoad($request)
    {
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $counsel_month = !empty($request['counsel_month']) ? $request['counsel_month'] : '';

        try {
            if (empty($franchise_idx) || empty($counsel_month)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->counselInfo->counselLoad($franchise_idx, $counsel_month);

            if (!empty($result)) {
                $no = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $no--;
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function counselSelect($request)
    {
        $counsel_idx = !empty($request['counsel_idx']) ? $request['counsel_idx'] : '';

        try {
            if (empty($counsel_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->counselInfo->counselSelect($counsel_idx);
            $result['student_age'] = getGrade($result['student_age']);

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function counselInsert($request)
    {
        $franchise_idx              = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $writer_no                  = !empty($request['writer_no']) ? $request['writer_no'] : '';
        $student_no                 = !empty($request['student_no']) ? $request['student_no'] : '';
        $counsel_date               = !empty($request['dtCounselDate']) ? $request['dtCounselDate'] : '';
        $counsel_kind               = !empty($request['selCounselKind']) ? $request['selCounselKind'] : '';
        $counsel_method             = !empty($request['selCounselMethod']) ? $request['selCounselMethod'] : '';
        $counsel_followup           = !empty($request['txtFollowup']) ? $request['txtFollowup'] : '';
        $counsel_discharge_reason   = !empty($request['selDischargeKind']) ? $request['selDischargeKind'] : '';
        $counsel_discharge_contents = !empty($request['txtDischarge']) ? $request['txtDischarge'] : '';
        $counsel_contents           = !empty($request['txtCounselContents']) ? $request['txtCounselContents'] : '';
        $counsel_open               = !empty($request['chkCounselOpen']) ? $request['chkCounselOpen'] : '';

        try {
            if (empty($franchise_idx) || empty($writer_no) || empty($student_no) || empty($counsel_date) || empty($counsel_contents) || empty($counsel_open)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "franchise_idx"              => !empty($franchise_idx) ? $franchise_idx : '',
                "writer_no"                  => !empty($writer_no) ? $writer_no : '',
                "student_no"                 => !empty($student_no) ? $student_no : '',
                "counsel_date"               => !empty($counsel_date) ? $counsel_date : '',
                "counsel_kind"               => !empty($counsel_kind) ? $counsel_kind : '',
                "counsel_method"             => !empty($counsel_method) ? $counsel_method : '',
                "counsel_followup"           => !empty($counsel_followup) ? $counsel_followup : '',
                "counsel_discharge_reason"   => !empty($counsel_discharge_reason) ? $counsel_discharge_reason : '',
                "counsel_discharge_contents" => !empty($counsel_discharge_contents) ? $counsel_discharge_contents : '',
                "counsel_contents"           => !empty($counsel_contents) ? $counsel_contents : '',
                "counsel_open"               => !empty($counsel_open) ? $counsel_open : '',
            );

            $this->counselInfo->table_name = $this->counselInfo->counsel_table_name;
            $result = $this->counselInfo->insert($params);

            if ($result) {
                $return_data['msg'] = "상담내용이 등록되었습니다.";
            } else {
                throw new Exception('상담내용이 등록되지 않았습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function counselUpdate($request)
    {
        $counsel_idx                = !empty($request['counsel_idx']) ? $request['counsel_idx'] : '';
        $counsel_date               = !empty($request['dtCounselDate']) ? $request['dtCounselDate'] : '';
        $counsel_kind               = !empty($request['selCounselKind']) ? $request['selCounselKind'] : '';
        $counsel_method             = !empty($request['selCounselMethod']) ? $request['selCounselMethod'] : '';
        $counsel_followup           = !empty($request['txtFollowup']) ? $request['txtFollowup'] : '';
        $counsel_discharge_reason   = !empty($request['selDischargeKind']) ? $request['selDischargeKind'] : '';
        $counsel_discharge_contents = !empty($request['txtDischarge']) ? $request['txtDischarge'] : '';
        $counsel_contents           = !empty($request['txtCounselContents']) ? $request['txtCounselContents'] : '';
        $counsel_open               = !empty($request['chkCounselOpen']) ? $request['chkCounselOpen'] : '';

        try {
            if (empty($counsel_idx) || empty($counsel_date) || empty($counsel_contents) || empty($counsel_open)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "counsel_date"               => !empty($counsel_date) ? $counsel_date : '',
                "counsel_kind"               => !empty($counsel_kind) ? $counsel_kind : '',
                "counsel_method"             => !empty($counsel_method) ? $counsel_method : '',
                "counsel_followup"           => !empty($counsel_followup) ? $counsel_followup : '',
                "counsel_discharge_reason"   => !empty($counsel_discharge_reason) ? $counsel_discharge_reason : '',
                "counsel_discharge_contents" => !empty($counsel_discharge_contents) ? $counsel_discharge_contents : '',
                "counsel_contents"           => !empty($counsel_contents) ? $counsel_contents : '',
                "counsel_open"               => !empty($counsel_open) ? $counsel_open : '',
                "mod_date"                   => 'getdate()',
            );

            $this->counselInfo->table_name = $this->counselInfo->counsel_table_name;
            $this->counselInfo->where_qry = " counsel_idx = '" . $counsel_idx . "' ";

            $result = $this->counselInfo->update($params);

            if ($result) {
                $return_data['msg'] = "상담내용이 수정되었습니다.";
            } else {
                throw new Exception('상담내용이 수정되지 않았습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function counselDelete($request)
    {
        $counsel_idx = !empty($request['counsel_idx']) ? $request['counsel_idx'] : '';

        try {
            if (empty($counsel_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->counselInfo->counselDelete($counsel_idx);

            if ($result) {
                $return_data['msg'] = "상담내용이 삭제되었습니다.";
            } else {
                throw new Exception('상담내용이 삭제되지 않았습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
    //정규상담 함수 종료

    //신규상담 함수 목록
    public function counselNewLoad($request)
    {
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $counsel_month = !empty($request['counsel_month']) ? $request['counsel_month'] : '';

        try {
            if (empty($franchise_idx) || empty($counsel_month)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->counselInfo->counselNewLoad($franchise_idx, $counsel_month);

            if (!empty($result)) {
                $no = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $no--;
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function counselNewInsert($request)
    {
        $franchise_idx         = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $writer_no             = !empty($request['writer_no']) ? $request['writer_no'] : '';
        $dtNewCounselDate      = !empty($request['dtNewCounselDate']) ? $request['dtNewCounselDate'] : '';
        $txtNewName            = !empty($request['txtNewName']) ? $request['txtNewName'] : '';
        $rdoGender             = !empty($request['rdoGender']) ? $request['rdoGender'] : '';
        $selGrade              = !empty($request['selGrade']) ? $request['selGrade'] : '';
        $txtSchool             = !empty($request['txtSchool']) ? $request['txtSchool'] : '';
        $selCounselTeacher     = !empty($request['selCounselTeacher']) ? $request['selCounselTeacher'] : '';
        $txtParentTel          = !empty($request['txtParentTel']) ? $request['txtParentTel'] : '';
        $txtCounselResult      = !empty($request['txtCounselResult']) ? $request['txtCounselResult'] : '';
        $selRegisterRate       = !empty($request['selRegisterRate']) ? $request['selRegisterRate'] : '';
        $selHopeClass          = !empty($request['selHopeClass']) ? $request['selHopeClass'] : '';
        $selKnownPath          = !empty($request['selKnownPath']) ? $request['selKnownPath'] : '';
        $txtNewCounselContents = !empty($request['txtNewCounselContents']) ? $request['txtNewCounselContents'] : '';

        try {
            if (empty($franchise_idx) || empty($writer_no) || empty($dtNewCounselDate) || empty($txtNewName) || empty($selGrade) || empty($txtParentTel) || empty($txtNewCounselContents)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                "writer_no" => !empty($writer_no) ? $writer_no : '',
                "counsel_date" => !empty($dtNewCounselDate) ? $dtNewCounselDate : '',
                "counselee_name" => !empty($txtNewName) ? $txtNewName : '',
                "gender" => !empty($rdoGender) ? $rdoGender : '',
                "counsel_grade" => !empty($selGrade) ? $selGrade : '',
                "school_name" => !empty($txtSchool) ? $txtSchool : '',
                "counsel_teacher_no" => !empty($selCounselTeacher) ? $selCounselTeacher : '0',
                "counsel_phone" => !empty($txtParentTel) ? phoneFormat($txtParentTel, true) : '',
                "counsel_result" => !empty($txtCounselResult) ? $txtCounselResult : '',
                "counsel_regist" => !empty($selRegisterRate) ? $selRegisterRate : '',
                "counsel_class" => !empty($selHopeClass) ? $selHopeClass : '',
                "counsel_know" => !empty($selKnownPath) ? $selKnownPath : '',
                "counsel_contents" => !empty($txtNewCounselContents) ? $txtNewCounselContents : ''
            );

            $result = $this->counselInfo->counselNewInsert($params);

            if ($result) {
                $return_data['msg'] = "신규상담내용이 등록되었습니다.";
            } else {
                throw new Exception('상담내용이 등록되지 않았습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function counselNewUpdate($request)
    {
        $counsel_idx           = !empty($request['counsel_idx']) ? $request['counsel_idx'] : '';
        $dtNewCounselDate      = !empty($request['dtNewCounselDate']) ? $request['dtNewCounselDate'] : '';
        $txtNewName            = !empty($request['txtNewName']) ? $request['txtNewName'] : '';
        $rdoGender             = !empty($request['rdoGender']) ? $request['rdoGender'] : '';
        $selGrade              = !empty($request['selGrade']) ? $request['selGrade'] : '';
        $txtSchool             = !empty($request['txtSchool']) ? $request['txtSchool'] : '';
        $selCounselTeacher     = !empty($request['selCounselTeacher']) ? $request['selCounselTeacher'] : '';
        $txtParentTel          = !empty($request['txtParentTel']) ? $request['txtParentTel'] : '';
        $txtCounselResult      = !empty($request['txtCounselResult']) ? $request['txtCounselResult'] : '';
        $selRegisterRate       = !empty($request['selRegisterRate']) ? $request['selRegisterRate'] : '';
        $selHopeClass          = !empty($request['selHopeClass']) ? $request['selHopeClass'] : '';
        $selKnownPath          = !empty($request['selKnownPath']) ? $request['selKnownPath'] : '';
        $txtNewCounselContents = !empty($request['txtNewCounselContents']) ? $request['txtNewCounselContents'] : '';

        try {
            if (empty($counsel_idx) || empty($dtNewCounselDate) || empty($txtNewName) || empty($selGrade) || empty($txtNewCounselContents)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "counsel_date" => !empty($dtNewCounselDate) ? $dtNewCounselDate : '',
                "counselee_name" => !empty($txtNewName) ? $txtNewName : '',
                "gender" => !empty($rdoGender) ? $rdoGender : '',
                "counsel_grade" => !empty($selGrade) ? $selGrade : '',
                "school_name" => !empty($txtSchool) ? $txtSchool : '',
                "counsel_teacher_no" => !empty($selCounselTeacher) ? $selCounselTeacher : '0',
                "counsel_phone" => !empty($txtParentTel) ? phoneFormat($txtParentTel, true) : '',
                "counsel_result" => !empty($txtCounselResult) ? $txtCounselResult : '',
                "counsel_regist" => !empty($selRegisterRate) ? $selRegisterRate : '',
                "counsel_class" => !empty($selHopeClass) ? $selHopeClass : '',
                "counsel_know" => !empty($selKnownPath) ? $selKnownPath : '',
                "counsel_contents" => !empty($txtNewCounselContents) ? $txtNewCounselContents : '',
                "mod_date" => 'getdate()'
            );
            $this->counselInfo->table_name = $this->counselInfo->counsel_new_table_name;
            $this->counselInfo->where_qry = " counsel_idx = '" . $counsel_idx . "'";

            $result = $this->counselInfo->update($params);

            if ($result) {
                $return_data['msg'] = "상담내용이 수정되었습니다.";
            } else {
                throw new Exception('상담내용이 수정되지 않았습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function counselNewDelete($request)
    {
        $counsel_idx = !empty($request['counsel_idx']) ? $request['counsel_idx'] : '';

        try {
            if (empty($counsel_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->counselInfo->counselNewDelete($counsel_idx);

            if ($result) {
                $return_data['msg'] = "상담내용이 삭제되었습니다.";
            } else {
                throw new Exception('상담내용이 삭제되지 않았습니다.', 701);
            }

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function counselNewSelect($request)
    {
        $counsel_idx = !empty($request['counsel_idx']) ? $request['counsel_idx'] : '';

        try {
            if (empty($counsel_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->counselInfo->counselNewSelect($counsel_idx);
            $result['counsel_phone'] = phoneFormat($result['counsel_phone']);

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function msgInfoSelect($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $months = !empty($request['months']) ? $request['months'] : '';
        $flag = !empty($request['flag']) ? $request['flag'] : '';

        try {
            if (empty($franchise_idx) || empty($flag)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->counselInfo->msgInfoSelect($franchise_idx, $months, $flag);
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

$counselController = new CounselController();

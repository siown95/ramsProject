<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/StudentEvaluation.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class StudentEvaluationController extends Controller
{
    private $studenEvaluationtInfo;

    function __construct()
    {
        $this->studenEvaluationtInfo = new StudentEvaluationInfo();
    }

    public function studentSearch($request)
    {
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $student_name  = !empty($request['student_name']) ? $request['student_name'] : '';

        try {
            if (empty($franchise_idx) || empty($student_name)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studenEvaluationtInfo->studentSearch($franchise_idx, $student_name);

            $table = '';
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $table .= "<tr class=\"tc text-center align-middle\" data-student-no=\"" . $val['user_no'] . "\">
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

    public function evaluationLoad($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $EvalYear      = !empty($request['selEvalYear']) ? $request['selEvalYear'] : '';
        $SemiAnnual    = !empty($request['selSemiAnnual']) ? $request['selSemiAnnual'] : '';
        $grade         = !empty($request['grade']) ? $request['grade'] : '';
        $student_no    = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($franchise_idx) || empty($EvalYear) || empty($SemiAnnual)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studenEvaluationtInfo->evaluationLoad($franchise_idx, $EvalYear, $SemiAnnual, getAge($grade), $student_no);

            if (!empty($result)) {
                $no = count($result);
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $no--;
                    $result[$key]['grade'] = getGrade($val['grade']);
                    $result[$key]['reg_date'] = date("Y-m-d", strtotime($val['reg_date']));
                }
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function selectEvaluation($params)
    {
        $eval_idx = !empty($params['eval_idx']) ? $params['eval_idx'] : '';

        try {
            if (empty($eval_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->studenEvaluationtInfo->selectEvaluation($eval_idx);

            if (!empty($result)) {
                $result['grade'] = getGrade($result['grade']);
            }

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function evaluationInsert($request)
    {
        $franchise_idx      = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no         = !empty($request['selStudent']) ? $request['selStudent'] : '';
        $writer_no          = !empty($request['writer_no']) ? $request['writer_no'] : '';
        $selEvalYear       = !empty($request['selEvalYear']) ? $request['selEvalYear'] : '';
        $selSemiAnnual       = !empty($request['selSemiAnnual']) ? $request['selSemiAnnual'] : '';
        $eval_content       = !empty($request['txtSynthesis']) ? $request['txtSynthesis'] : '';
        $read_score         = !empty($request['txtReadScore']) ? $request['txtReadScore'] : '';
        $read_content       = !empty($request['txtRead']) ? $request['txtRead'] : '';
        $debate_score       = !empty($request['txtDebateScore']) ? $request['txtDebateScore'] : '';
        $debate_content     = !empty($request['txtDebate']) ? $request['txtDebate'] : '';
        $write_score        = !empty($request['txtWriteScore']) ? $request['txtWriteScore'] : '';
        $write_content      = !empty($request['txtWrite']) ? $request['txtWrite'] : '';
        $lead_score         = !empty($request['txtTenacityScore']) ? $request['txtTenacityScore'] : '';
        $lead_content       = !empty($request['txtTenacity']) ? $request['txtTenacity'] : '';
        $guide_content      = !empty($request['txtGuide']) ? $request['txtGuide'] : '';
        $next_guide_content = !empty($request['txtNextGuide']) ? $request['txtNextGuide'] : '';
        $parent_request     = !empty($request['txtRequests']) ? $request['txtRequests'] : '';

        try {
            if (empty($franchise_idx) || empty($student_no)  || empty($writer_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "franchise_idx"      => !empty($franchise_idx) ? $franchise_idx : '',
                "student_no"         => !empty($student_no) ? $student_no : '',
                "writer_no"          => !empty($writer_no) ? $writer_no : '',
                "eval_year"       => !empty($selEvalYear) ? $selEvalYear : '',
                "eval_semiannual"       => !empty($selSemiAnnual) ? $selSemiAnnual : '',
                "eval_content"       => !empty($eval_content) ? $eval_content : '',
                "read_score"         => !empty($read_score) ? $read_score : '',
                "read_content"       => !empty($read_content) ? $read_content : '',
                "debate_score"       => !empty($debate_score) ? $debate_score : '',
                "debate_content"     => !empty($debate_content) ? $debate_content : '',
                "write_score"        => !empty($write_score) ? $write_score : '',
                "write_content"      => !empty($write_content) ? $write_content : '',
                "lead_score"         => !empty($lead_score) ? $lead_score : '',
                "lead_content"       => !empty($lead_content) ? $lead_content : '',
                "guide_content"      => !empty($guide_content) ? $guide_content : '',
                "next_guide_content" => !empty($next_guide_content) ? $next_guide_content : '',
                "parent_request"     => !empty($parent_request) ? $parent_request : '',
            );

            $result = $this->studenEvaluationtInfo->insert($params);

            if ($result) {
                $return_data['msg'] = "종합평가가 등록되었습니다.";
            } else {
                throw new Exception('종합평가가 등록되지 않았습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function evaluationUpdate($request)
    {
        $eval_idx           = !empty($request['eval_idx']) ? $request['eval_idx'] : '';
        $eval_content       = !empty($request['txtSynthesis']) ? $request['txtSynthesis'] : '';
        $read_score         = !empty($request['txtReadScore']) ? $request['txtReadScore'] : '';
        $read_content       = !empty($request['txtRead']) ? $request['txtRead'] : '';
        $debate_score       = !empty($request['txtDebateScore']) ? $request['txtDebateScore'] : '';
        $debate_content     = !empty($request['txtDebate']) ? $request['txtDebate'] : '';
        $write_score        = !empty($request['txtWriteScore']) ? $request['txtWriteScore'] : '';
        $write_content      = !empty($request['txtWrite']) ? $request['txtWrite'] : '';
        $lead_score         = !empty($request['txtTenacityScore']) ? $request['txtTenacityScore'] : '';
        $lead_content       = !empty($request['txtTenacity']) ? $request['txtTenacity'] : '';
        $guide_content      = !empty($request['txtGuide']) ? $request['txtGuide'] : '';
        $next_guide_content = !empty($request['txtNextGuide']) ? $request['txtNextGuide'] : '';
        $parent_request     = !empty($request['txtRequests']) ? $request['txtRequests'] : '';

        try {
            if (empty($eval_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $params = array(
                "eval_content"       => !empty($eval_content) ? $eval_content : '',
                "read_score"         => !empty($read_score) ? $read_score : '',
                "read_content"       => !empty($read_content) ? $read_content : '',
                "debate_score"       => !empty($debate_score) ? $debate_score : '',
                "debate_content"     => !empty($debate_content) ? $debate_content : '',
                "write_score"        => !empty($write_score) ? $write_score : '',
                "write_content"      => !empty($write_content) ? $write_content : '',
                "lead_score"         => !empty($lead_score) ? $lead_score : '',
                "lead_content"       => !empty($lead_content) ? $lead_content : '',
                "guide_content"      => !empty($guide_content) ? $guide_content : '',
                "next_guide_content" => !empty($next_guide_content) ? $next_guide_content : '',
                "parent_request"     => !empty($parent_request) ? $parent_request : '',
                "mod_date"           => 'getdate()'
            );

            $this->studenEvaluationtInfo->where_qry = " eval_idx = '" . $eval_idx . "'";
            $result = $this->studenEvaluationtInfo->update($params);

            if ($result) {
                $return_data['msg'] = "종합평가가 수정되었습니다.";
            } else {
                throw new Exception('종합평가가 수정되지 않았습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function evaluationDelete($request)
    {
        $eval_idx = !empty($request['eval_idx']) ? $request['eval_idx'] : '';

        try {
            if (empty($eval_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $this->studenEvaluationtInfo->where_qry = " eval_idx = '" . $eval_idx . "'";
            $result = $this->studenEvaluationtInfo->delete();

            if ($result) {
                $return_data['msg'] = "종합평가가 삭제되었습니다.";
            } else {
                throw new Exception('종합평가가 삭제되지 않았습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getStudentList($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $grade = !empty($request['grade']) ? $request['grade'] : '';

        try {
            if (empty($franchise_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $studentSelect = "<option value=\"all\">전체</option>";
            $result = $this->studenEvaluationtInfo->getStudentList($franchise_idx, getAge($grade));

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $studentSelect .= "<option value=\"" . $val['user_no'] . "\">" . $val['user_name'] . "</option>";
                }
            }

            $return_data['studentSelect'] = $studentSelect;

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getStudentScore($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_idx   = !empty($request['student_idx']) ? $request['student_idx'] : '';
        $evalyear   = !empty($request['selEvalYear']) ? $request['selEvalYear'] : '';
        $semiannual   = !empty($request['selSemiAnnual']) ? $request['selSemiAnnual'] : '';

        try {
            if (empty($franchise_idx) || empty($student_idx) || empty($evalyear) || empty($semiannual)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studenEvaluationtInfo->getStudentScore($franchise_idx, $student_idx, $evalyear, $semiannual);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }

    }
}


$studentEvaluationController = new StudentEvaluationController();

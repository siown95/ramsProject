<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/LessonEvaluation.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class LessonEvaluationController extends Controller
{
    private $lessonEvaluationInfo;

    function __construct()
    {
        $this->lessonEvaluationInfo = new LessonEvaluationInfo();
    }

    public function weekEvaluationSelect($request)
    {
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $student_idx = !empty($request['student_idx']) ? $request['student_idx'] : '';
        $eval_month = !empty($request['eval_month']) ? $request['eval_month'] : '';

        try {
            if (empty($franchise_idx) || empty($student_idx) || empty($eval_month)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonEvaluationInfo->weekEvalSelect($franchise_idx, $student_idx, $eval_month);

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function semiAnnualEvaluationSelect($request)
    {
        $franchise_idx = !empty($request['franchise_idx']) ? $request['franchise_idx'] : '';
        $student_idx = !empty($request['student_idx']) ? $request['student_idx'] : '';
        $eval_year = !empty($request['eval_year']) ? $request['eval_year'] : '';
        $eval_semiannual = !empty($request['eval_semiannual']) ? $request['eval_semiannual'] : '';

        try {
            if (empty($franchise_idx) || empty($student_idx) || empty($eval_year) || empty($eval_semiannual)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonEvaluationInfo->semiAnnualEvalSelect($franchise_idx, $student_idx, $eval_year, $eval_semiannual);

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadSemiAnnualEvaluationMain($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_idx = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($franchise_idx) || empty($student_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->lessonEvaluationInfo->loadSemiAnnualEvaluationMain($franchise_idx, $student_idx);
            $tbl = '';
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $tbl .= "<tr><td>{$val['eval_year']}</td><td>{$val['eval_semiannual']}</td><td>{$val['read_score']}</td><td>{$val['debate_score']}</td><td>{$val['write_score']}</td><td>{$val['lead_score']}</td></tr>";
                }
                $result['tbl'] = $tbl;
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function parentRequestSave($request)
    {
        $franchise_idx = !empty($request["franchise_idx"]) ? $request["franchise_idx"] : "";
        $student_idx = !empty($request["student_idx"]) ? $request["student_idx"] : "";
        $eval_idx = !empty($request["eval_idx"]) ? $request["eval_idx"] : "";
        $contents = !empty($request["contents"]) ? $request["contents"] : "";

        try {
            if (empty($franchise_idx) || empty($student_idx) || empty($eval_idx) || empty($contents)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $params = array(
                'parent_request' => !empty($contents) ? $contents : '',
                'mod_date'       => 'getdate()'
            );

            $this->lessonEvaluationInfo->where_qry = "eval_idx = '" . $eval_idx . "' AND franchise_idx = '" . $franchise_idx . "' AND student_no = '" . $student_idx . "'";
            $result = $this->lessonEvaluationInfo->update($params);

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$lessonEvaluationController = new LessonEvaluationController();

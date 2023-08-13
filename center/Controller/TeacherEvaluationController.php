<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/Model/TeacherEvaluation.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class TeacherEvaluationController extends Controller
{
    private $teacherEvaluationtInfo;

    function __construct()
    {
        $this->teacherEvaluationtInfo = new TeacherEvaluationInfo();
    }

    public function teacherEvalLoad($request)
    {
        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $months        = !empty($request['months']) ? $request['months'] : '';
        $teacher_no    = !empty($request['teacher_no']) ? $request['teacher_no'] : '';

        try {
            if (empty($franchise_idx) || empty($months)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->teacherEvaluationtInfo->teacherEvalLoad($franchise_idx, $teacher_no, $months);

            $table = "";
            if (!empty($result)) {
                $no = count($result);

                foreach ($result as $key => $val) {
                    $total_score = 0;
                    for ($i = 1; $i <= 10; $i++) {
                        $total_score += $result[$key]['teval_score' . $i];
                    }

                    $table .= "<tr class=\"text-center align-middle tc\" data-teval-idx=\"" . $val['teval_idx'] . "\">
                                   <td>" . $no-- . "</td>
                                   <td>" . $val['user_name'] . "</td>
                                   <td></td>
                                   <td>" . getScoreGrade($val['teval_score1']) . "</td>
                                   <td>" . getScoreGrade($val['teval_score2']) . "</td>
                                   <td>" . getScoreGrade($val['teval_score3']) . "</td>
                                   <td>" . getScoreGrade($val['teval_score4']) . "</td>
                                   <td>" . getScoreGrade($val['teval_score5']) . "</td>
                                   <td>" . getScoreGrade($val['teval_score6']) . "</td>
                                   <td>" . getScoreGrade($val['teval_score7']) . "</td>
                                   <td>" . getScoreGrade($val['teval_score8']) . "</td>
                                   <td>" . getScoreGrade($val['teval_score9']) . "</td>
                                   <td>" . getScoreGrade($val['teval_score10']) . "</td>
                                   <td>" . getScoreGrade($total_score / 10) . "</td>
                               </tr>";
                }
            } else {
                $table = "<tr class=\"text-center align-middle tc\">
                              <td colspan=\"14\">데이터가 존재하지 않습니다.</td>
                          </tr>";
            }

            $return_data['tbl'] = $table;

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function teacherEvalSelect($params)
    {
        $teval_idx = !empty($params['teval_idx']) ? $params['teval_idx'] : '';

        try {
            if (empty($teval_idx)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->teacherEvaluationtInfo->teacherEvalSelect($teval_idx);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function teacherEvaluationInsert($request)
    {

        $franchise_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $user_no       = !empty($request['user_no']) ? $request['user_no'] : '';
        $teval_sub1    = !empty($request['txtTEval1']) ? $request['txtTEval1'] : '';
        $teval_score1  = !empty($request['selTEval1']) ? $request['selTEval1'] : '';
        $teval_sub2    = !empty($request['txtTEval2']) ? $request['txtTEval2'] : '';
        $teval_score2  = !empty($request['selTEval2']) ? $request['selTEval2'] : '';
        $teval_sub3    = !empty($request['txtTEval3']) ? $request['txtTEval3'] : '';
        $teval_score3  = !empty($request['selTEval3']) ? $request['selTEval3'] : '';
        $teval_sub4    = !empty($request['txtTEval4']) ? $request['txtTEval4'] : '';
        $teval_score4  = !empty($request['selTEval4']) ? $request['selTEval4'] : '';
        $teval_sub5    = !empty($request['txtTEval5']) ? $request['txtTEval5'] : '';
        $teval_score5  = !empty($request['selTEval5']) ? $request['selTEval5'] : '';
        $teval_sub6    = !empty($request['txtTEval6']) ? $request['txtTEval6'] : '';
        $teval_score6  = !empty($request['selTEval6']) ? $request['selTEval6'] : '';
        $teval_sub7    = !empty($request['txtTEval7']) ? $request['txtTEval7'] : '';
        $teval_score7  = !empty($request['selTEval7']) ? $request['selTEval7'] : '';
        $teval_sub8    = !empty($request['txtTEval8']) ? $request['txtTEval8'] : '';
        $teval_score8  = !empty($request['selTEval8']) ? $request['selTEval8'] : '';
        $teval_sub9    = !empty($request['txtTEval9']) ? $request['txtTEval9'] : '';
        $teval_score9  = !empty($request['selTEval9']) ? $request['selTEval9'] : '';
        $teval_sub10   = !empty($request['txtTEval10']) ? $request['txtTEval10'] : '';
        $teval_score10 = !empty($request['selTEval10']) ? $request['selTEval10'] : '';

        try {
            if (empty($franchise_idx) || empty($user_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $checkEvaluation = $this->teacherEvaluationtInfo->checkEvaluation($franchise_idx, $user_no);

            if ($checkEvaluation) {
                throw new Exception('해당 직원의 이번달 근무평가를 입력하셨습니다.', 701);
            }

            $params = array(
                "franchise_idx" => !empty($franchise_idx) ? $franchise_idx : '',
                "user_no" => !empty($user_no) ? $user_no : '',
                "teval_sub1" => !empty($teval_sub1) ? $teval_sub1 : '',
                "teval_score1" => !empty($teval_score1) ? $teval_score1 : '',
                "teval_sub2" => !empty($teval_sub2) ? $teval_sub2 : '',
                "teval_score2" => !empty($teval_score2) ? $teval_score2 : '',
                "teval_sub3" => !empty($teval_sub3) ? $teval_sub3 : '',
                "teval_score3" => !empty($teval_score3) ? $teval_score3 : '',
                "teval_sub4" => !empty($teval_sub4) ? $teval_sub4 : '',
                "teval_score4" => !empty($teval_score4) ? $teval_score4 : '',
                "teval_sub5" => !empty($teval_sub5) ? $teval_sub5 : '',
                "teval_score5" => !empty($teval_score5) ? $teval_score5 : '',
                "teval_sub6" => !empty($teval_sub6) ? $teval_sub6 : '',
                "teval_score6" => !empty($teval_score6) ? $teval_score6 : '',
                "teval_sub7" => !empty($teval_sub7) ? $teval_sub7 : '',
                "teval_score7" => !empty($teval_score7) ? $teval_score7 : '',
                "teval_sub8" => !empty($teval_sub8) ? $teval_sub8 : '',
                "teval_score8" => !empty($teval_score8) ? $teval_score8 : '',
                "teval_sub9" => !empty($teval_sub9) ? $teval_sub9 : '',
                "teval_score9" => !empty($teval_score9) ? $teval_score9 : '',
                "teval_sub10" => !empty($teval_sub10) ? $teval_sub10 : '',
                "teval_score10" => !empty($teval_score10) ? $teval_score10 : ''
            );

            $result = $this->teacherEvaluationtInfo->insert($params);

            if ($result) {
                $return_data['msg'] = "근무평가가 등록되었습니다.";
            } else {
                throw new Exception('근무평가가 등록되지 않았습니다.', 701);
            }
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$teacherEvaluationController = new TeacherEvaluationController();
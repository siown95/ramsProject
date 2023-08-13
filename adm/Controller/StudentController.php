<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/adm/Model/Student.php";

class StudentController extends Controller
{
    private $studentInfo;

    function __construct()
    {
        $this->studentInfo = new StudentInfo();
    }

    public function getStudentData($request)
    {
        $center_type = !empty($request['center_type']) ? $request['center_type'] : '';

        try {
            if (empty($center_type)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentInfo->getStudentData($center_type);
            if (!empty($result)) {
                $temp_arr1 = array();
                $temp_arr2 = array();

                $dataset1 = array();
                $dataset2 = array();

                for ($i = 1; $i <= 12; $i++) {
                    $lastYearMonth = date('Y', strtotime("-1 year")) . "-" . sprintf("%02d", $i);
                    $nowYearMonth = date('Y') . "-" . sprintf("%02d", $i);;

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

    public function getFranchiseList($request)
    {
        $selFranchiseType = !empty($request['selFranchiseType']) ? $request['selFranchiseType'] : '';

        try {
            if (empty($selFranchiseType)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $selOptionTxt = "<option value='all'>전체</option>";

            if ($selFranchiseType != 'all') {
                $result = $this->studentInfo->getFranchiseList($selFranchiseType);
            }

            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $selOptionTxt .= "<option value='" . $val['franchise_idx'] . "'>" . $val['center_name'] . "</option>";
                }
            }

            $return_data['selOptionTxt'] = $selOptionTxt;

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getAnalysisStudentData1($request)
    {
        $from_date = !empty($request['dtformDate']) ? $request['dtformDate'] : '';
        $center_type = !empty($request['center_type']) ? $request['center_type'] : '';
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';

        try {
            if (empty($from_date) || empty($center_type)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentInfo->getAnalysisStudentData1($from_date, $center_type, $center_idx);
            $dataset1 = array();
            $dataset2 = array();
            $dataset3 = array();
            $dataset4 = array();
            $dataset5 = array();
            $dataset6 = array();

            $gradeArr = array(
                "유아",
                "초0",
                "초1",
                "초2",
                "초3",
                "초4",
                "초5",
                "초6",
                "중1",
                "중2",
                "중3",
                "고1",
                "고2",
                "고3",
            );

            $temp_arr = array();
            $temp_arr2 = array(); //정규
            $temp_arr3 = array(); //특강
            $temp_arr4 = array(); //목적
            $temp_arr5 = array(); //내신
            $temp_arr6 = array(); //JT

            for ($i = 0; $i < count($gradeArr); $i++) {
                foreach ($result as $key => $val) {
                    if ($gradeArr[$i] == $val['grade']) {
                        $temp_arr[$i] = $val['totcnt'];

                        if ($val['lesson_type'] == '01') {
                            $temp_arr2[$i] = $val['cnt'];
                        } else if ($val['lesson_type'] == '02') {
                            $temp_arr3[$i] = $val['cnt'];
                        } else if ($val['lesson_type'] == '03') {
                            $temp_arr4[$i] = $val['cnt'];
                        } else if ($val['lesson_type'] == '04') {
                            $temp_arr5[$i] = $val['cnt'];
                        } else if ($val['lesson_type'] == '05') {
                            $temp_arr6[$i] = $val['cnt'];
                        }
                    }
                }

                if (!empty($temp_arr[$i])) {
                    $dataset1[] = $temp_arr[$i];
                } else {
                    $dataset1[] = 0;
                }

                if (!empty($temp_arr2[$i])) {
                    $dataset2[] = $temp_arr2[$i];
                } else {
                    $dataset2[] = 0;
                }

                if (!empty($temp_arr3[$i])) {
                    $dataset3[] = $temp_arr3[$i];
                } else {
                    $dataset3[] = 0;
                }

                if (!empty($temp_arr4[$i])) {
                    $dataset4[] = $temp_arr4[$i];
                } else {
                    $dataset4[] = 0;
                }

                if (!empty($temp_arr5[$i])) {
                    $dataset5[] = $temp_arr5[$i];
                } else {
                    $dataset5[] = 0;
                }

                if (!empty($temp_arr6[$i])) {
                    $dataset6[] = $temp_arr6[$i];
                } else {
                    $dataset6[] = 0;
                }
            }
            $return_data['dataset1'] = $dataset1;
            $return_data['dataset2'] = $dataset2;
            $return_data['dataset3'] = $dataset3;
            $return_data['dataset4'] = $dataset4;
            $return_data['dataset5'] = $dataset5;
            $return_data['dataset6'] = $dataset6;
            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getAnalysisStudentData2($request)
    {
        $from_date = !empty($request['dtformDate']) ? $request['dtformDate'] : '';
        $center_type = !empty($request['center_type']) ? $request['center_type'] : '';
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';

        try {
            if (empty($from_date) || empty($center_type)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->studentInfo->getAnalysisStudentData2($from_date, $center_type, $center_idx);
            $dataset1 = array();
            $dataset2 = array();

            $gradeArr = array(
                "초0",
                "초1",
                "초2",
                "초3",
                "초4",
                "초5",
                "초6",
                "중1",
                "중2",
                "중3",
                "고1",
                "고2",
                "고3",
            );

            $temp_arr = array();
            $temp_arr2 = array(); //정규

            for ($i = 0; $i < count($gradeArr); $i++) {
                foreach ($result as $key => $val) {
                    if ($gradeArr[$i] == $val['grade']) {
                        $temp_arr[$i] = $val['totcnt'];
                        $temp_arr2[$i] = $val['cnt'];
                    }
                }

                if (!empty($temp_arr[$i])) {
                    $dataset1[] = $temp_arr[$i];
                } else {
                    $dataset1[] = 0;
                }

                if (!empty($temp_arr2[$i])) {
                    $dataset2[] = $temp_arr2[$i];
                } else {
                    $dataset2[] = 0;
                }
            }

            $gradeSum1 = $dataset1[0] + $dataset1[1] + $dataset1[2] + $dataset1[3] + $dataset1[4] + $dataset1[5] + $dataset1[6]; //초등합
            $gradeSum2 = $dataset1[7] + $dataset1[8] + $dataset1[9]; //중등합
            $gradeSum3 = $dataset1[10] + $dataset1[11] + $dataset1[12]; //고등합
            $gradeSumTot = $gradeSum1 + $gradeSum2 + $gradeSum3;

            $lessonSum1 = $dataset2[0] + $dataset2[1] + $dataset2[2] + $dataset2[3] + $dataset2[4] + $dataset2[5] + $dataset2[6]; //초등 정규합
            $lessonSum2 = $dataset2[7] + $dataset2[8] + $dataset2[9]; //중등 정규합
            $lessonSum3 = $dataset2[10] + $dataset2[11] + $dataset2[12]; //고등 정규합
            $lessonSumTot = $lessonSum1 + $lessonSum2 + $lessonSum3;

            $resultSum1 = array($gradeSum1, $gradeSum2, $gradeSum3, $gradeSumTot);
            $resultSum2 = array($lessonSum1, $lessonSum2, $lessonSum3, $lessonSumTot);

            $return_data['dataset1'] = $resultSum1;
            $return_data['dataset2'] = $resultSum2;

            return $return_data;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$studentController = new StudentController();

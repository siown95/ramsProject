<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Controller/Controller.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/center/student/Model/BookDiagnosis.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

class BookDiagnosisController extends Controller
{
    private $bookDiagnosisInfo;

    function __construct()
    {
        $this->bookDiagnosisInfo = new BookDiagnosisInfo();
    }

    public function loadDiagnosis1($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($center_idx) || empty($student_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookDiagnosisInfo->loadDiagnosis1($center_idx, $student_no);

            $tbl = '';
            $resultText = '';
            if (!empty($result)) {
                $allUserCnt = $this->bookDiagnosisInfo->getAllUserCnt();
                $maleUserCnt = $this->bookDiagnosisInfo->getMaleUserCnt();
                $femaleUserCnt = $this->bookDiagnosisInfo->getFemaleUserCnt();

                $tdcolor1 = $result['cnt_user_l'] > $result['total_l'] / $allUserCnt ? 'table-info' : 'table-danger';
                $tdcolor2 = $result['cnt_user_nl'] > $result['total_nl'] / $allUserCnt ? 'table-info' : 'table-danger';
                $tdcolor3 = ($result['cnt_user_l'] + $result['cnt_user_nl']) > (($result['total_l'] + $result['total_nl']) / $allUserCnt) / $allUserCnt ? 'table-info' : 'table-danger';

                $tbl = "<tr>
                            <th>문학</th>
                            <td class=" . $tdcolor1 . ">" . $result['cnt_user_l'] . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_lm'] / $maleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_lf'] / $femaleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", $result['total_l'] / $allUserCnt) . "</td>
                        </tr>
                        <tr>
                            <th>비문학</th>
                            <td class=" . $tdcolor2 . ">" . $result['cnt_user_nl'] . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_nlm'] / $maleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_nlf'] / $femaleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", $result['total_nl'] / $allUserCnt) . "</td>
                        </tr>
                        <tr>
                            <th>합계권수</th>
                            <td class=" . $tdcolor3 . ">" . ($result['cnt_user_l'] + $result['cnt_user_nl']) . "</td>
                            <td>" . sprintf("%.2f", ((($result['cnt_lm'] + $result['cnt_nlm']) / $maleUserCnt))) . "</td>
                            <td>" . sprintf("%.2f", ((($result['cnt_lf'] + $result['cnt_nlf']) / $femaleUserCnt)))  . "</td>
                            <td>" . sprintf("%.2f", ((($result['total_l'] + $result['total_nl']) / $allUserCnt))) . "</td>
                        </tr>";

                $result['avg_male_li'] = sprintf("%.2f", $result['cnt_lm'] / $maleUserCnt);
                $result['avg_male_nonli'] = sprintf("%.2f", $result['cnt_nlm'] / $maleUserCnt);
                $result['avg_female_li'] = sprintf("%.2f", $result['cnt_lf'] / $femaleUserCnt);
                $result['avg_female_nonli'] = sprintf("%.2f", $result['cnt_nlf'] / $femaleUserCnt);

                if ($result['cnt_user_l']) {
                    if ($result['cnt_user_l'] < ($result['total_l'] / $allUserCnt)) {
                        $resultText .= '<li>문학분야에서 평균보다 독서량이 부족합니다. 다양한 분야의 문학 도서를 읽어보시길 바랍니다.</li>';
                    } else {
                        $resultText .= '<li>문학분야에서 평균보다 독서량이 우수합니다.</li>';
                    }
                } else {
                    $resultText .= '<li>문학분야에서 평균보다 독서량이 부족합니다. 다양한 분야의 문학 도서를 읽어보시길 바랍니다.</li>';
                }

                if ($result['cnt_user_nl']) {
                    if ($result['cnt_user_nl'] < ($result['total_nl'] / $allUserCnt)) {
                        $resultText .= '<li>비문학분야에서 평균보다 독서량이 부족합니다. 다양한 분야의 비문학 도서를 읽어보시길 바랍니다.</li><br><br>';
                    } else {
                        $resultText .= '<li>비문학분야에서 평균보다 독서량이 우수합니다.</li><br><br>';
                    }
                } else {
                    $resultText .= '<li>비문학분야에서 평균보다 독서량이 부족합니다. 다양한 분야의 비문학 도서를 읽어보시길 바랍니다.</li><br><br>';
                }
            }
            $result['tbl'] = $tbl;
            $result['txtResult'] = $resultText;

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function loadDiagnosis2($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($center_idx) || empty($student_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }

            $result = $this->bookDiagnosisInfo->loadDiagnosis2($center_idx, $student_no);

            $tbl = '';
            $resultText = '';
            if (!empty($result)) {
                $allUserCnt = $this->bookDiagnosisInfo->getAllUserCnt();
                $maleUserCnt = $this->bookDiagnosisInfo->getMaleUserCnt();
                $femaleUserCnt = $this->bookDiagnosisInfo->getFemaleUserCnt();

                $allCount = ($result['cnt_c1m'] + $result['cnt_c2m'] + $result['cnt_c3m'] + $result['cnt_c4m'] + $result['cnt_c5m']) + ($result['cnt_c1f'] + $result['cnt_c2f'] + $result['cnt_c3f'] + $result['cnt_c4f'] + $result['cnt_c5f']);

                $tdcolor0 = $result['cnt_user_c0'] > ($result['cnt_c0m'] + $result['cnt_c0f']) / $allUserCnt ? 'table-info' : 'table-danger';
                $tdcolor1 = $result['cnt_user_c1'] > ($result['cnt_c1m'] + $result['cnt_c1f']) / $allUserCnt ? 'table-info' : 'table-danger';
                $tdcolor2 = $result['cnt_user_c2'] > ($result['cnt_c2m'] + $result['cnt_c2f']) / $allUserCnt ? 'table-info' : 'table-danger';
                $tdcolor3 = $result['cnt_user_c3'] > ($result['cnt_c3m'] + $result['cnt_c3f']) / $allUserCnt ? 'table-info' : 'table-danger';
                $tdcolor4 = $result['cnt_user_c4'] > ($result['cnt_c4m'] + $result['cnt_c4f']) / $allUserCnt ? 'table-info' : 'table-danger';
                $tdcolor5 = $result['cnt_user_c5'] > ($result['cnt_c5m'] + $result['cnt_c5f']) / $allUserCnt ? 'table-info' : 'table-danger';
                $tdcolor7 = ($result['cnt_user_c0'] + $result['cnt_user_c1'] + $result['cnt_user_c2'] + $result['cnt_user_c3'] + $result['cnt_user_c4'] + $result['cnt_user_c5'] + $result['cnt_user_c6']) > ($allCount / $allUserCnt) ? 'table-info' : 'table-danger';

                $tbl = "<tr>
                            <th>문학</th>
                            <td class=" . $tdcolor0 . ">" . $result['cnt_user_c0'] . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_c0m'] / $maleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_c0f'] / $femaleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", ($result['cnt_c0m'] + $result['cnt_c0f']) / $allUserCnt) . "</td>
                        </tr>
                        <tr>
                            <th>인문</th>
                            <td class=" . $tdcolor1 . ">" . $result['cnt_user_c1'] . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_c1m'] / $maleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_c1f'] / $femaleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", ($result['cnt_c1m'] + $result['cnt_c1f']) / $allUserCnt) . "</td>
                        </tr>
                        <tr>
                            <th>사회</th>
                            <td class=" . $tdcolor2 . ">" . $result['cnt_user_c2'] . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_c2m'] / $maleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_c2f'] / $femaleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", ($result['cnt_c2m'] + $result['cnt_c2f']) / $allUserCnt) . "</td>
                        </tr>
                        <tr>
                            <th>과학수학</th>
                            <td class=" . $tdcolor3 . ">" . $result['cnt_user_c3'] . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_c3m'] / $maleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_c3f'] / $femaleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", ($result['cnt_c3m'] + $result['cnt_c3f']) / $allUserCnt) . "</td>
                        </tr>
                        <tr>
                            <th>체육예술</th>
                            <td class=" . $tdcolor4 . ">" . $result['cnt_user_c4'] . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_c4m'] / $maleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_c4f'] / $femaleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", ($result['cnt_c4m'] + $result['cnt_c4f']) / $allUserCnt) . "</td>
                        </tr>
                        <tr>
                            <th>진로</th>
                            <td class=" . $tdcolor5 . ">" . $result['cnt_user_c5'] . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_c5m'] / $maleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_c5f'] / $femaleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", ($result['cnt_c5m'] + $result['cnt_c5f']) / $allUserCnt) . "</td>
                        </tr>
                        <tr>
                            <th>기타</th>
                            <td>" . $result['cnt_user_c6'] . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_c6m'] / $maleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", $result['cnt_c6f'] / $femaleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", ($result['cnt_c6m'] + $result['cnt_c6f']) / $allUserCnt) . "</td>
                        </tr>
                        <tr>
                            <th>합계권수</th>
                            <td class=" . $tdcolor7 . ">" . ($result['cnt_user_c0'] + $result['cnt_user_c1'] + $result['cnt_user_c2'] + $result['cnt_user_c3'] + $result['cnt_user_c4'] + $result['cnt_user_c5'] + $result['cnt_user_c6']) . "</td>
                            <td>" . sprintf("%.2f", ($result['cnt_c0m'] + $result['cnt_c1m'] + $result['cnt_c2m'] + $result['cnt_c3m'] + $result['cnt_c4m'] + $result['cnt_c5m'] + $result['cnt_c6m']) / $maleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", ($result['cnt_c0f'] + $result['cnt_c1f'] + $result['cnt_c2f'] + $result['cnt_c3f'] + $result['cnt_c4f'] + $result['cnt_c5f'] + $result['cnt_c6f']) / $femaleUserCnt) . "</td>
                            <td>" . sprintf("%.2f", $allCount / $allUserCnt) . "</td>
                        </tr>";
                $result['avg_male_0'] = sprintf("%.2f", $result['cnt_c0m'] / $maleUserCnt);
                $result['avg_female_0'] = sprintf("%.2f", $result['cnt_c0f'] / $femaleUserCnt);
                $result['avg_male_1'] = sprintf("%.2f", $result['cnt_c1m'] / $maleUserCnt);
                $result['avg_female_1'] = sprintf("%.2f", $result['cnt_c1f'] / $femaleUserCnt);
                $result['avg_male_2'] = sprintf("%.2f", $result['cnt_c2m'] / $maleUserCnt);
                $result['avg_female_2'] = sprintf("%.2f", $result['cnt_c2f'] / $femaleUserCnt);
                $result['avg_male_3'] = sprintf("%.2f", $result['cnt_c3m'] / $maleUserCnt);
                $result['avg_female_3'] = sprintf("%.2f", $result['cnt_c3f'] / $femaleUserCnt);
                $result['avg_male_4'] = sprintf("%.2f", $result['cnt_c4m'] / $maleUserCnt);
                $result['avg_female_4'] = sprintf("%.2f", $result['cnt_c4f'] / $femaleUserCnt);
                $result['avg_male_5'] = sprintf("%.2f", $result['cnt_c5m'] / $maleUserCnt);
                $result['avg_female_5'] = sprintf("%.2f", $result['cnt_c5f'] / $femaleUserCnt);
                $result['avg_male_6'] = sprintf("%.2f", $result['cnt_c6m'] / $maleUserCnt);
                $result['avg_female_6'] = sprintf("%.2f", $result['cnt_c6f'] / $femaleUserCnt);

                $cnt = 0;
                if ($result['cnt_user_c0']) {
                    $cnt++;
                    //문학
                    if ($result['cnt_user_c0'] < (($result['cnt_c0m'] + $result['cnt_c0f']) / $allUserCnt)) {
                        $resultText .= "<li>문학 분야에서 평균보다 독서량이 부족합니다.</li>";
                    } else {
                        $resultText .= "<li>문학 분야에서 평균보다 독서량이 우수합니다.</li>";
                    }
                } else {
                    $resultText .= "<li>문학 분야에서 평균보다 독서량이 부족합니다.</li>";
                }

                if ($result['cnt_user_c1']) {
                    $cnt++;
                    //인문
                    if ($result['cnt_user_c1'] < (($result['cnt_c1m'] + $result['cnt_c1f']) / $allUserCnt)) {
                        $resultText .= "<li>인문 분야에서 평균보다 독서량이 부족합니다.</li>";
                    } else {
                        $resultText .= "<li>인문 분야에서 평균보다 독서량이 우수합니다.</li>";
                    }
                } else {
                    $resultText .= "<li>인문 분야에서 평균보다 독서량이 부족합니다.</li>";
                }

                if ($result['cnt_user_c2']) {
                    $cnt++;
                    //사회
                    if ($result['cnt_user_c2'] < (($result['cnt_c2m'] + $result['cnt_c2f']) / $allUserCnt)) {
                        $resultText .= "<li>사회 분야에서 평균보다 독서량이 부족합니다.</li>";
                    } else {
                        $resultText .= "<li>사회 분야에서 평균보다 독서량이 우수합니다.</li>";
                    }
                } else {
                    $resultText .= "<li>사회 분야에서 평균보다 독서량이 부족합니다.</li>";
                }

                if ($result['cnt_user_c3']) {
                    $cnt++;
                    //과학수학
                    if ($result['cnt_user_c3'] < (($result['cnt_c3m'] + $result['cnt_c3f']) / $allUserCnt)) {
                        $resultText .= "<li>과학수학 분야에서 평균보다 독서량이 부족합니다.</li>";
                    } else {
                        $resultText .= "<li>과학수학 분야에서 평균보다 독서량이 우수합니다.</li>";
                    }
                } else {
                    $resultText .= "<li>과학수학 분야에서 평균보다 독서량이 부족합니다.</li>";
                }

                if ($result['cnt_user_c4']) {
                    $cnt++;
                    //체육예술
                    if ($result['cnt_user_c4'] < (($result['cnt_c4m'] + $result['cnt_c4f']) / $allUserCnt)) {
                        $resultText .= "<li>체육예술 분야에서 평균보다 독서량이 부족합니다.</li>";
                    } else {
                        $resultText .= "<li>체육예술 분야에서 평균보다 독서량이 우수합니다.</li>";
                    }
                } else {
                    $resultText .= "<li>체육예술 분야에서 평균보다 독서량이 부족합니다.</li>";
                }

                if ($result['cnt_user_c5']) {
                    $cnt++;
                    //진로
                    if ($result['cnt_user_c5'] < (($result['cnt_c5m'] + $result['cnt_c5f']) / $allUserCnt)) {
                        $resultText .= "<li>진로 분야에서 평균보다 독서량이 부족합니다.</li>";
                    } else {
                        $resultText .= "<li>진로 분야에서 평균보다 독서량이 우수합니다.</li>";
                    }
                } else {
                    $resultText .= "<li>진로 분야에서 평균보다 독서량이 부족합니다.</li>";
                }

                //전체
                if ($cnt > 4) {
                    $resultText .= '<p>여러 분야의 책을 두루 독서하고 있습니다.</p>';
                } else {
                    $resultText .= '<p>여러 분야의 책을 독서할 수 있도록 노력하시기 바랍니다.</p>';
                }
                // 편독 확인 / 표준편차
                // 순서 : 문학 > 인문 > 사회 > 과학수학 > 체육예술 > 진로
                $sd_arr = array();
                $sd_arr[] .= $result['cnt_user_c0'];
                $sd_arr[] .= $result['cnt_user_c1'];
                $sd_arr[] .= $result['cnt_user_c2'];
                $sd_arr[] .= $result['cnt_user_c3'];
                $sd_arr[] .= $result['cnt_user_c4'];
                $sd_arr[] .= $result['cnt_user_c5'];
                $cnt_sd = count($sd_arr);
                $sum_sd = array_sum($sd_arr);
                $avg_sd = $sum_sd / $cnt_sd;
                $s = 0;
                for ($i = 0, $s = 0; $i < $cnt; $i++) {
                    $s += ($sd_arr[$i] - $avg_sd) * ($sd_arr[$i] - $avg_sd);
                }
                $std = sprintf('%0.4f', sqrt($s / $cnt_sd));
                $c0_avg_sd = $result['cnt_user_c0'] - $std;
                $c1_avg_sd = $result['cnt_user_c1'] - $std;
                $c2_avg_sd = $result['cnt_user_c2'] - $std;
                $c3_avg_sd = $result['cnt_user_c3'] - $std;
                $c4_avg_sd = $result['cnt_user_c4'] - $std;
                $c5_avg_sd = $result['cnt_user_c5'] - $std;

                if ($c0_avg_sd > 1.0000) {
                    $resultText .= '<p>문학 분야에 독서가 치중되어 있습니다.</p>';
                }
                if ($c1_avg_sd > 1.0000) {
                    $resultText .= '<p>인문 분야에 독서가 치중되어 있습니다.</p>';
                }
                if ($c2_avg_sd > 1.0000) {
                    $resultText .= '<p>사회 분야에 독서가 치중되어 있습니다.</p>';
                }
                if ($c3_avg_sd > 1.0000) {
                    $resultText .= '<p>과학수학 분야에 독서가 치중되어 있습니다.</p>';
                }
                if ($c4_avg_sd > 1.0000) {
                    $resultText .= '<p>체육예술 분야에 독서가 치중되어 있습니다.</p>';
                }
                if ($c5_avg_sd > 1.0000) {
                    $resultText .= '<p>진로 분야에 독서가 치중되어 있습니다.</p>';
                }
            }
            $result['tbl'] = $tbl;
            $result['txtResult'] = $resultText;

            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getReadBookCategoryDetail($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($center_idx) || empty($student_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->bookDiagnosisInfo->getReadBookCategoryDetail($center_idx, $student_no);
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }

    public function getRecommendBookList($request)
    {
        $center_idx = !empty($request['center_idx']) ? $request['center_idx'] : '';
        $student_no = !empty($request['student_no']) ? $request['student_no'] : '';

        try {
            if (empty($center_idx) || empty($student_no)) {
                throw new Exception('필수값이 누락되었습니다.', 701);
            }
            $result = $this->bookDiagnosisInfo->getRecommendBookList($center_idx, $student_no);
            if (!empty($result)) {
                foreach ($result as $key => $val) {
                    $result[$key]['no'] = $key + 1;
                }
            }
            return $result;
        } catch (Exception $e) {
            return $this->getMsgException($e);
        }
    }
}

$bookDiagnosisController = new BookDiagnosisController();

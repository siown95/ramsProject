<?php
$StudentName = '홍길동';
$score1 = array(
    "0" => array(
        "key" => "우수",
        "val" => "더욱 노력하기 바랍니다."
    ),
    "1" => array(
        "key" => "보통",
        "val" => "대책이 필요합니다."
    ),
    "2" => array(
        "key" => "미흡",
        "val" => "관심과 대책이 필요합니다."
    )
);

$k = random_int(0, 2);

for ($i = 1; $i <= 9; $i++) {
    for ($j = 1; $j <= 5; $j++) {
        if ($j == 1) {
            $result1[$i][$j] = $score1[$k]['key'] . '<br>' . $score1[$k]['val'];
        } else {
            $result1[$i][$j] = ((rand() % 10000) / 100);
        }
    }
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" integrity="sha512-GQGU0fMMi238uA+a/bdWJfpUGKUkBdgfFdgBm72SUQ6BeyWjoY/ton0tEjH+OSH9iP4Dfh+7HM0I9f5eR0L/4w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="container-fluid">
    <div id="Result1" class="card border-left-primary shadow mt-3" style="page-break-before: always;">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 id="lblTitle" class="text-primary">독서이력진단 결과</h5>
                </div>
                <div>
                    <h6 id="lblSubTitle" class="text-muted">종합결과 진단일 : <?= date('Y-m-d') ?> <?= $StudentName ?>님</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-warning align-middle text-center">
                    <th>진단영역</th>
                    <th>진단요소</th>
                    <th>발달수준</th>
                    <th>백분위 점수</th>
                    <th>전체 평균</th>
                    <th>남학생 평균</th>
                    <th>여학생 평균</th>
                    <th>진단내용</th>
                </thead>
                <tbody class="align-middle text-center">
                    <tr>
                        <th class="table-warning" rowspan="3">A 도서선택이력<br>관리영역</th>
                        <td class="table-secondary">도서선택능력</td>
                        <td class="text-start"><?= $result1[1][1] ?></td>
                        <td><?= $result1[1][2] ?></td>
                        <td><?= $result1[1][3] ?></td>
                        <td><?= $result1[1][4] ?></td>
                        <td><?= $result1[1][5] ?></td>
                        <td class="text-start">도서 선택 준비도<br />도서 선택시 자기 주도 능력 의존도<br>전략적 독서상태</td>
                    </tr>
                    <tr>
                        <td class="table-secondary">독서관리<br>활용능력</td>
                        <td class="text-start"><?= $result1[2][1] ?></td>
                        <td><?= $result1[2][2] ?></td>
                        <td><?= $result1[2][3] ?></td>
                        <td><?= $result1[2][4] ?></td>
                        <td><?= $result1[2][5] ?></td>
                        <td class="text-start">독서 전 활동 상태<br>행동, 흥미, 배경지식, 상호작용</td>
                    </tr>
                    <tr>
                        <td class="table-secondary">소계</td>
                        <td class="text-start"><?= $result1[3][1] ?></td>
                        <td><?= $result1[3][2] ?></td>
                        <td><?= $result1[3][3] ?></td>
                        <td><?= $result1[3][4] ?></td>
                        <td><?= $result1[3][5] ?></td>
                        <td class="text-start">도서 선택 준비도<br />도서 선택시 자기 주도 능력 의존도<br>전략적 독서상태</td>
                    </tr>
                    <tr>
                        <th class="table-warning" rowspan="4">B 독서활동영역</th>
                        <td class="table-secondary">독서 전 활동</td>
                        <td class="text-start"><?= $result1[4][1] ?></td>
                        <td><?= $result1[4][2] ?></td>
                        <td><?= $result1[4][3] ?></td>
                        <td><?= $result1[4][4] ?></td>
                        <td><?= $result1[4][5] ?></td>
                        <td class="text-start">독서 전 활동 상태<br>행동, 흥미, 배경지식, 상호작용</td>
                    </tr>
                    <tr>
                        <td class="table-secondary">독서 중 활동</td>
                        <td class="text-start"><?= $result1[5][1] ?></td>
                        <td><?= $result1[5][2] ?></td>
                        <td><?= $result1[5][3] ?></td>
                        <td><?= $result1[5][4] ?></td>
                        <td><?= $result1[5][5] ?></td>
                        <td class="text-start">독서 중 활동 상태<br>행동, 흥미, 배경지식, 상호작용</td>
                    </tr>
                    <tr>
                        <td class="table-secondary">독서 후 활동</td>
                        <td class="text-start"><?= $result1[6][1] ?></td>
                        <td><?= $result1[6][2] ?></td>
                        <td><?= $result1[6][3] ?></td>
                        <td><?= $result1[6][4] ?></td>
                        <td><?= $result1[6][5] ?></td>
                        <td class="text-start">독서 후 활동 상태<br>행동, 흥미, 배경지식, 상호작용</td>
                    </tr>
                    <tr>
                        <td class="table-secondary">소계</td>
                        <td class="text-start"><?= $result1[7][1] ?></td>
                        <td><?= $result1[7][2] ?></td>
                        <td><?= $result1[7][3] ?></td>
                        <td><?= $result1[7][4] ?></td>
                        <td><?= $result1[7][5] ?></td>
                        <td class="text-start">독서 전 활동 상태<br>행동, 흥미, 배경지식, 상호작용</td>
                    </tr>
                    <tr>
                        <th class="table-warning">C 과거독서이력영역</th>
                        <td class="table-secondary">독서량<br>다양독<br>편중독</td>
                        <td class="text-start"><?= $result1[8][1] ?></td>
                        <td><?= $result1[8][2] ?></td>
                        <td><?= $result1[8][3] ?></td>
                        <td><?= $result1[8][4] ?></td>
                        <td><?= $result1[8][5] ?></td>
                        <td class="text-start">도서 선택 준비도<br />도서 선택시 자기 주도 능력 의존도</td>
                    </tr>
                    <tr>
                        <th class="table-warning">D 현재독서이력<br>분야분량영역</th>
                        <td class="table-secondary">독서량<br>다양독<br>편중독</td>
                        <td class="text-start"><?= $result1[9][1] ?></td>
                        <td><?= $result1[9][2] ?></td>
                        <td><?= $result1[9][3] ?></td>
                        <td><?= $result1[9][4] ?></td>
                        <td><?= $result1[9][5] ?></td>
                        <td class="text-start">도서 선택 준비도<br />도서 선택시 자기 주도 능력 의존도</td>
                    </tr>
                    <tr>
                        <td class="text-start" colspan="8"><small class="text-muted"> * 지표의 퍼센트가 높을수록 검사인원 중 우수함을 의미합니다.</small></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    $result2 = array();
    for ($i = 1; $i <= 4; $i++) {
        for ($j = 1; $j <= 4; $j++) {
            $result2[$i][$j] = ((rand() % 10000) / 100);
        }
    }
    ?>
    <div id="Result2" class="card border-left-primary shadow mt-3" style="page-break-before: always;">
        <div class="card-header">
            <h5 class="text-primary">결과분석 &#40;문학, 비문학&#41; / 독서이력지수 &#40;RQ&#41;</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <table class="table table-bordered">
                        <thead class="align-middle text-center">
                            <th>진단요소</th>
                            <th>점수</th>
                            <th>전체평균</th>
                            <th>남학생평균</th>
                            <th>여학생평균</th>
                        </thead>
                        <tbody class="align-middle text-center">
                            <tr>
                                <th>문학</th>
                                <td><?= $result2[1][1] ?></td>
                                <td><?= $result2[1][2] ?></td>
                                <td><?= $result2[1][3] ?></td>
                                <td><?= $result2[1][4] ?></td>
                            </tr>
                            <tr>
                                <th>비문학</th>
                                <td><?= $result2[2][1] ?></td>
                                <td><?= $result2[2][2] ?></td>
                                <td><?= $result2[2][3] ?></td>
                                <td><?= $result2[2][4] ?></td>
                            </tr>
                            <tr>
                                <th>합계</th>
                                <td><?= $result2[3][1] ?></td>
                                <td><?= $result2[3][2] ?></td>
                                <td><?= $result2[3][3] ?></td>
                                <td><?= $result2[3][4] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <canvas id="result2_1"></canvas>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6">
                    <table class="table table-bordered">
                        <thead class="align-middle text-center">
                            <th>진단요소</th>
                            <th>점수</th>
                            <th>전체평균</th>
                            <th>남학생평균</th>
                            <th>여학생평균</th>
                        </thead>
                        <tbody class="align-middle text-center">
                            <tr>
                                <th>독서이력지수</th>
                                <td><?= $result2[4][1] ?></td>
                                <td><?= $result2[4][2] ?></td>
                                <td><?= $result2[4][3] ?></td>
                                <td><?= $result2[4][4] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <canvas id="result2_2"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php
    $result3 = array();
    for ($i = 1; $i <= 3; $i++) {
        for ($j = 1; $j <= 4; $j++) {
            $result3[$i][$j] = ((rand() % 10000) / 100);
        }
    }

    ?>
    <div id="Result3" class="card border-left-primary shadow mt-3" style="page-break-before: always;">
        <div class="card-header">
            <h5 class="text-primary">A 도서선택 이력관리 결과</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="align-middle text-center">
                    <th>진단요소</th>
                    <th>발달수준</th>
                    <th>점수</th>
                    <th>전체평균</th>
                    <th>남학생평균</th>
                    <th>여학생평균</th>
                </thead>
                <tbody class="align-middle text-center">
                    <tr>
                        <th>도서선택능력</th>
                        <td class="text-start"><?= $result1[1][1] ?></td>
                        <td><?= $result3[1][1] ?></td>
                        <td><?= $result3[1][2] ?></td>
                        <td><?= $result3[1][3] ?></td>
                        <td><?= $result3[1][4] ?></td>
                    </tr>
                    <tr>
                        <th>도서관리활용능력</th>
                        <td class="text-start"><?= $result1[1][1] ?></td>
                        <td><?= $result3[2][1] ?></td>
                        <td><?= $result3[2][2] ?></td>
                        <td><?= $result3[2][3] ?></td>
                        <td><?= $result3[2][4] ?></td>
                    </tr>
                    <tr>
                        <th>합계</th>
                        <td></td>
                        <td><?= $result3[3][1] ?></td>
                        <td><?= $result3[3][2] ?></td>
                        <td><?= $result3[3][3] ?></td>
                        <td><?= $result3[3][4] ?></td>
                    </tr>
                </tbody>
            </table>
            <canvas id="result3"></canvas>
            <table class="table table-bordered">
                <thead class="align-middle text-center">
                    <tr>
                        <th colspan="3">진단결과 분석 및 개선방안</th>
                    </tr>
                    <tr>
                        <th>구분</th>
                        <th>도서선택능력</th>
                        <th>독서관리 활용능력</th>
                    </tr>
                </thead>
                <tbody class="align-middle text-center">
                    <tr>
                        <th>결과</th>
                        <td class="text-start"></td>
                        <td class="text-start"></td>
                    </tr>
                    <tr>
                        <th>특징</th>
                        <td class="text-start"></td>
                        <td class="text-start"></td>
                    </tr>
                    <tr>
                        <th>개선방향</th>
                        <td class="text-start"></td>
                        <td class="text-start"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    $result4_1 = array();
    for ($i = 1; $i <= 3; $i++) {
        for ($j = 1; $j <= 4; $j++) {
            $result4_1[$i][$j] = ((rand() % 10000) / 100);
        }
    }
    $result4_1[4][1] = ($result4_1[1][1] + $result4_1[2][1] + $result4_1[3][1]) / 3;
    $result4_1[4][2] = ($result4_1[1][2] + $result4_1[2][2] + $result4_1[3][2]) / 3;
    $result4_1[4][3] = ($result4_1[1][3] + $result4_1[2][3] + $result4_1[3][3]) / 3;
    $result4_1[4][4] = ($result4_1[1][4] + $result4_1[2][4] + $result4_1[3][4]) / 3;

    ?>

    <div id="Result4" class="card border-left-primary shadow mt-3" style="page-break-before: always;">
        <div class="card-header">
            <h5 class="text-primary">B 독서 전중후 활동영역</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="align-middle text-center">
                    <tr>
                        <th>진단요소</th>
                        <th>발달수준</th>
                        <th>점수</th>
                        <th>전체평균</th>
                        <th>남학생평균</th>
                        <th>여학생평균</th>
                    </tr>
                </thead>
                <tbody class="align-middle text-center">
                    <tr>
                        <th>독서 전</th>
                        <td class="text-start"><?= $result1[1][1] ?></td>
                        <td><?= $result4_1[1][1] ?></td>
                        <td><?= $result4_1[1][2] ?></td>
                        <td><?= $result4_1[1][3] ?></td>
                        <td><?= $result4_1[1][4] ?></td>
                    </tr>
                    <tr>
                        <th>독서 중</th>
                        <td class="text-start"><?= $result1[1][1] ?></td>
                        <td><?= $result4_1[2][1] ?></td>
                        <td><?= $result4_1[2][2] ?></td>
                        <td><?= $result4_1[2][3] ?></td>
                        <td><?= $result4_1[2][4] ?></td>
                    </tr>
                    <tr>
                        <th>독서 후</th>
                        <td class="text-start"><?= $result1[1][1] ?></td>
                        <td><?= $result4_1[3][1] ?></td>
                        <td><?= $result4_1[3][2] ?></td>
                        <td><?= $result4_1[3][3] ?></td>
                        <td><?= $result4_1[3][4] ?></td>
                    </tr>
                </tbody>
            </table>

            <canvas id="result4_2"></canvas>

            <table class="table table-bordered">
                <thead class="align-middle text-center">
                    <tr>
                        <th colspan="4">진단결과 분석 및 개선방안</th>
                    </tr>
                    <tr>
                        <th width="10%">구분</th>
                        <th width="30%">독서 전</th>
                        <th width="30%">독서 중</th>
                        <th width="30%">독서 후</th>
                    </tr>
                </thead>
                <tbody class="align-middle text-start">
                    <tr>
                        <th class="text-center">결과</th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="text-center">특징</th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="text-center">개선방향</th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

    <?php
    $result5_1 = array();
    for ($i = 1; $i <= 4; $i++) {
        $result5_1[$i] = ((rand() % 10000) / 100);
    }

    ?>

    <div id="Result5" class="card border-left-primary shadow mt-3" style="page-break-before: always;">
        <div class="card-header">
            <h5 class="text-primary">C 과거 독서이력</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="align-middle text-center">
                    <th>진단요소</th>
                    <th>발달수준</th>
                    <th>점수</th>
                    <th>전체평균</th>
                    <th>남학생평균</th>
                    <th>여학생평균</th>
                </thead>
                <tbody class="align-middle text-center">
                    <tr>
                        <th>독서량<br>다양독<br>편중독</th>
                        <td class="text-start"><?= $result1[1][1] ?></td>
                        <td><?= $result5_1[1] ?></td>
                        <td><?= $result5_1[2] ?></td>
                        <td><?= $result5_1[3] ?></td>
                        <td><?= $result5_1[4] ?></td>
                    </tr>
                </tbody>
            </table>

            <canvas id="result5_2"></canvas>

            <table class="table table-bordered">
                <thead class="align-middle text-center">
                    <tr>
                        <th colspan="4">진단결과 분석 및 개선방안</th>
                    </tr>
                    <tr>
                        <th>구분</th>
                        <th>과거 독서이력</th>
                    </tr>
                </thead>
                <tbody class="align-middle text-start">
                    <tr>
                        <th class="text-center">결과</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="text-center">특징</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="text-center">개선방향</th>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    $result6_1 = array();
    for ($i = 1; $i <= 4; $i++) {
        $result6_1[$i] = ((rand() % 10000) / 100);
    }

    $result6_2 = array();
    for ($i = 1; $i <= 2; $i++) {
        for ($j = 1; $j <= 6; $j++) {
            $result6_2[$i][$j] = ((rand() % 1000) / 100);
        }
    }

    $result6_3 = array();
    for ($i = 1; $i <= 2; $i++) {
        for ($j = 1; $j <= 4; $j++) {
            $result6_3[$i][$j] = ((rand() % 1000) / 100);
        }
    }

    ?>

    <div id="Result6" class="card border-left-primary shadow mt-3" style="page-break-before: always;">
        <div class="card-header">
            <h5 class="text-primary">D 현재 독서이력</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="align-middle text-center">
                    <th>진단요소</th>
                    <th>발달수준</th>
                    <th>점수</th>
                    <th>전체평균</th>
                    <th>남학생평균</th>
                    <th>여학생평균</th>
                </thead>
                <tbody class="align-middle text-center">
                    <tr>
                        <th>독서량<br>다양독<br>편중독</th>
                        <td class="text-start"><?= $result1[1][1] ?></td>
                        <td><?= $result6_1[1] ?></td>
                        <td><?= $result6_1[2] ?></td>
                        <td><?= $result6_1[3] ?></td>
                        <td><?= $result6_1[4] ?></td>
                    </tr>
                </tbody>
            </table>

            <canvas id="result6_2"></canvas>

            <table class="table table-bordered">
                <thead class="align-middle text-center">
                    <tr>
                        <th colspan="4">진단결과 분석 및 개선방안</th>
                    </tr>
                    <tr>
                        <th>구분</th>
                        <th>현재 독서이력</th>
                    </tr>
                </thead>
                <tbody class="align-middle text-start">
                    <tr>
                        <th class="text-center">결과</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="text-center">특징</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="text-center">개선방향</th>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <div class="row text-center">
                <div class="col-6"><canvas id="result6_3"></canvas></div>
                <div class="col-6"><canvas id="result6_4"></canvas></div>
            </div>
        </div>
    </div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.0/chart.min.js" integrity="sha512-R60W3LgKdvvfwbGbqKusRu/434Snuvr9/Flhtoq9cj1LQ9P4HFKParULqOCAisHk/J4zyaEWWjiWIMuP13vXEg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js" integrity="sha512-pax4MlgXjHEPfCwcJLQhigY7+N8rt6bVvWLFyUMuxShv170X53TRzGPmPkZmGBhk+jikR8WBM4yl7A9WMHHqvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        result2Load();
        result3Load();
        result4Load();
        result5Load();
        result6Load();
    });

    function result2Load() {
        const ctx1 = document.getElementById("result2_1");
        const ctx2 = document.getElementById("result2_2");

        const chart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['문학', '비문학', '합계'],
                datasets: [{
                    label: '<?= $StudentName ?>님 점수',
                    data: [<?= $result2[1][1] ?>, <?= $result2[2][1] ?>, <?= $result2[3][1] ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }, {
                    label: '전체평균',
                    data: [<?= $result2[1][2] ?>, <?= $result2[2][2] ?>, <?= $result2[3][2] ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }, {
                    label: '남학생평균',
                    data: [<?= $result2[1][3] ?>, <?= $result2[2][3] ?>, <?= $result2[3][3] ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }, {
                    label: '여학생평균',
                    data: [<?= $result2[1][4] ?>, <?= $result2[2][4] ?>, <?= $result2[3][4] ?>],
                    backgroundColor: [
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        const chart2 = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['점수', '전체평균', '남학생평균', '여학생평균'],
                datasets: [{
                    data: [<?= $result2[4][1] ?>, <?= $result2[4][2] ?>, <?= $result2[4][3] ?>, <?= $result2[4][4] ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: '독서이력지수'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }

    function result3Load() {
        const ctx3 = document.getElementById("result3");
        const chart3 = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: ['도서선택능력', '독서관리능력', '합계'],
                datasets: [{
                    label: '<?= $StudentName ?>님 점수',
                    data: [<?= $result3[1][1] ?>, <?= $result3[2][1] ?>, <?= $result3[3][1] ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }, {
                    label: '전체평균',
                    data: [<?= $result3[1][2] ?>, <?= $result3[2][2] ?>, <?= $result3[3][2] ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }, {
                    label: '남학생평균',
                    data: [<?= $result3[1][3] ?>, <?= $result3[2][3] ?>, <?= $result3[3][3] ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }, {
                    label: '여학생평균',
                    data: [<?= $result3[1][4] ?>, <?= $result3[2][4] ?>, <?= $result3[3][4] ?>],
                    backgroundColor: [
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }

    function result4Load() {
        const ctx1 = document.getElementById("result4_2");
        const chart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['독서 전', '독서 중', '독서 후', '평균'],
                datasets: [{
                    label: '<?= $StudentName ?>님 점수',
                    data: [<?= $result4_1[1][1] ?>, <?= $result4_1[2][1] ?>, <?= $result4_1[3][1] ?>, <?= $result4_1[4][1] ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }, {
                    label: '전체평균',
                    data: [<?= $result4_1[1][2] ?>, <?= $result4_1[2][2] ?>, <?= $result4_1[3][2] ?>, <?= $result4_1[4][2] ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }, {
                    label: '남학생평균',
                    data: [<?= $result4_1[1][3] ?>, <?= $result4_1[2][3] ?>, <?= $result4_1[3][3] ?>, <?= $result4_1[4][3] ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }, {
                    label: '여학생평균',
                    data: [<?= $result4_1[1][4] ?>, <?= $result4_1[2][4] ?>, <?= $result4_1[3][4] ?>, <?= $result4_1[4][4] ?>],
                    backgroundColor: [
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }

    function result5Load() {
        const ctx1 = document.getElementById("result5_2");
        const chart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['점수', '전체평균', '남학생평균', '여학생평균'],
                datasets: [{
                    data: [<?= $result5_1[1] ?>, <?= $result5_1[2] ?>, <?= $result5_1[3] ?>, <?= $result5_1[4] ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }

    function result6Load() {
        const ctx1 = document.getElementById("result6_2");
        const ctx2 = document.getElementById("result6_3");
        const ctx3 = document.getElementById("result6_4");
        const chart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['점수', '전체평균', '남학생평균', '여학생평균'],
                datasets: [{
                    data: [<?= $result6_1[1] ?>, <?= $result6_1[2] ?>, <?= $result6_1[3] ?>, <?= $result6_1[4] ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        const chart2 = new Chart(ctx2, {
            type: 'radar',
            data: {
                labels: ['세계명작', '전래동화', '그림동화', '창작동화', '동시', '위인전'],
                datasets: [{
                    data: [<?= $result6_2[1][1] ?>, <?= $result6_2[1][2] ?>, <?= $result6_2[1][3] ?>, <?= $result6_2[1][4] ?>, <?= $result6_2[1][5] ?>, <?= $result6_2[1][6] ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }, {
                    data: [<?= $result6_2[2][1] ?>, <?= $result6_2[2][2] ?>, <?= $result6_2[2][3] ?>, <?= $result6_2[2][4] ?>, <?= $result6_2[2][5] ?>, <?= $result6_2[2][6] ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: '문학'
                    }

                }
            }
        });

        const chart3 = new Chart(ctx3, {
            type: 'radar',
            data: {
                labels: ['사회문화', '과학환경', '논리철학', '수학흥미'],
                datasets: [{
                    data: [<?= $result6_3[1][1] ?>, <?= $result6_3[1][2] ?>, <?= $result6_3[1][3] ?>, <?= $result6_3[1][4] ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }, {
                    data: [<?= $result6_2[2][1] ?>, <?= $result6_2[2][2] ?>, <?= $result6_2[2][3] ?>, <?= $result6_2[2][4] ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: '비문학'
                    }

                }
            }
        });
    }
</script>
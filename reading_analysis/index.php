<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>리딩엠 RAMS</title>
    <link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/css/styles.css" />
    <?php include_once $_SERVER["DOCUMENT_ROOT"] . "/common/common_script2.php" ?>
</head>

<body class="size-14 bg-dark">
    <div class="container-fluid">
        <div class="container mt-2">
            <div id="reading_quiz_carousel" class="carousel slide" data-bs-interval="false" data-bs-pause="true">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="card">
                            <div class="card-header">독서이력진단 검사</div>
                            <div class="card-body">
                                <h5 class="card-title">독서이력진단 검사 안내</h5>
                                <p class="card-text">&#40;주&#41;리딩엠이 국내 최초로 개발하고 특허를 획득한 독서이력진단 방법과 그 시스템에 의한 독서이력진단 검사 프로그램입니다.
                                    <br>학생의 독서이력과 함께 독서방법, 습관, 독서 전, 중, 후 활동 등에 대한 정확한 진단과 처방을 통해 차별화된 독서활동 매니지먼트가 이뤄질 수 있도록 하는데 유용하고도 특별한 도구가 됩니다.
                                    <br>
                                <h6>독서이력진단 개요</h6>
                                진단 대상 : 초등학교 1~6학년 (초등 1학년~2학년은 학부모 대상) (초등 3학년~6학년은 학생 대상)
                                <br>진단 방법 : 응답자 기입법에 따른 설문지 조사방법 (리커트 척도) (설문지법 : 5점 척도 사용)
                                <br>진단 시기 : 매년 최소 1회 또는 2회 주기적 실시
                                <br><br>
                                <h6>독서이력진단 내용</h6>
                                <table class="table table-sm table-bordered">
                                    <thead class="align-middle text-center">
                                        <th>영역구분</th>
                                        <th>요소</th>
                                        <th>진단항목 내용</th>
                                        <th>결과표시</th>
                                    </thead>
                                    <tbody class="align-middle">
                                        <tr>
                                            <td class="text-center" rowspan="2">도서선택 이력관리 영역</td>
                                            <td class="text-center">도서선택</td>
                                            <td rowspan="2">도서선택의 준비도<br>도서선택 시 자기 주도 능력(의존도)<br>전략적 독서상태<br>도서이력 관리 능력<br>도서이력에 대한 이해도</td>
                                            <td rowspan="9">
                                                &lt;점수&gt;<br>
                                                규준점수<br>
                                                원점수<br>
                                                백분위점수<br>
                                                &lt;비교&gt;<br>
                                                평균 (전체, 개인, 남여)<br>
                                                RQ (독서이력지수)<br>
                                                &lt;그래프&gt;<br>
                                                막대 그래프 (전체/개인)<br>
                                                점분포 그래프<br>
                                                시계열 그래프<br>
                                                방사형 그래프 (전국평균/지역평균/개인점수)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">이력관리</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center" rowspan="3">독서활동 영역</td>
                                            <td class="text-center">독서 전 활동</td>
                                            <td>독서 전 활동 상태 : 행동, 흥미, 배경지식, 상호작용</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">독서 중 활동</td>
                                            <td>독서 중 활동 상태 : 행동, 방법, 문식성, 이해도, 상호작용</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">독서 후 활동</td>
                                            <td>독서 후 활동 상태 : 행동, 정리능력, 적용능력, 표현능력</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center" rowspan="2">과거 독서 이력 영역</td>
                                            <td class="text-center">도서선택</td>
                                            <td rowspan="2">문식성, 독서량, 다양독, 편중독<br>현재 시점 이력관리 기준 과거 1~3년동안 독서이력 파악</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">이력관리</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center" rowspan="2">현재 독서 분야분량 영역</td>
                                            <td class="text-center">도서선택</td>
                                            <td rowspan="2">문식성, 독서량, 다양독, 편중독<br>조사시점 기준 전월 (최근 1개월)의 분야별, 주제별 독서량 파악</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">이력관리</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h6>독서이력진단 결과분석의 활용</h6>
                                <table class="table table-sm table-bordered">
                                    <tbody class="align-middle">
                                        <tr>
                                            <th class="text-center">지도계획 도출 및 학부모 상담</th>
                                            <td rowspan="3">독서이력진단 결과는 지금까지 학생의 독서활동의 행태적, 시계열적 분석을 통해<br>
                                                리딩엠에서 강조하는 정독, 다양독, 지속독, 잠재독, 흥미독이 얼마나 이뤄지는지 파악한 후 지도계획을 도출해<br>
                                                교사가 학생에게 도서를 추천하는 등 전문적이고 체계적인 독서활동에 활용됩니다.</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">교사 추천도서 선정</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">정독, 지속독, 다양독, 잠재독</th>
                                        </tr>
                                    </tbody>
                                </table>
                                </p>
                                <div class="text-end">
                                    <a href="#" class="btn btn-primary" data-bs-target="#reading_quiz_carousel" data-bs-slide="next">다음</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="card">
                            <div class="card-header">독서이력진단 검사</div>
                            <div class="card-body">
                                <h5 class="card-title">독서이력진단 검사 이용에 대한 안내</h5>
                                <p class="card-text">
                                </p>
                                <div class="row justify-content-between">
                                    <div class="col">
                                        <a href="#" class="btn btn-primary" data-bs-target="#reading_quiz_carousel" data-bs-slide="prev">이전</a>
                                    </div>
                                    <div class="col-auto">
                                        <a href="#" class="btn btn-primary" data-bs-target="#reading_quiz_carousel" data-bs-slide="next">다음</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item"></div>
                    <div class="carousel-item"></div>
                    <div class="carousel-item"></div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";

$db = new DBCmp();

include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";

?>
<!DOCTYPE html>
<html>

<head>
    <?php
    $stat = "adm";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/common/common_script.php";
    ?>
    <script src="/adm/js/magazine_produce.js?v=<?= date('YmdHis') ?>"></script>
</head>

<body class="sb-nav-fixed size-14">
    <!-- header navbar -->
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/adm/navbar.html'; ?>
    <div id="layoutSidenav">
        <!-- sidebar -->
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/adm/sidebar.html'; ?>

        <div id="Maincontent">
            <main>
                <div class="container-fluid px-4 mt-3">
                    <div class="row mt-2">
                        <div class="col-4">
                            <div class="card border-left-primary shadow mt-2">
                                <div class="card-header">
                                    <h6>매거진 제작정보</h6>
                                </div>
                                <div class="card-body">

                                    <table class="table table-bordered table-hover">
                                        <thead class="align-middle text-center">
                                            <th>꼭지</th>
                                            <th>필요수량</th>
                                        </thead>
                                        <tbody class="align-middle text-center">
                                            <tr>
                                                <td>표지사진&amp;수업사진</td>
                                                <td>가변수량</td>
                                            </tr>
                                            <tr>
                                                <td>수업안내&amp;설명회안내</td>
                                                <td>1</td>
                                            </tr>
                                            <tr>
                                                <td>초등글쓰기</td>
                                                <td>10</td>
                                            </tr>
                                            <tr>
                                                <td>초등칼럼</td>
                                                <td>10</td>
                                            </tr>
                                            <tr>
                                                <td>중등글쓰기</td>
                                                <td>10</td>
                                            </tr>
                                            <tr>
                                                <td>중등칼럼</td>
                                                <td>10</td>
                                            </tr>
                                            <tr>
                                                <td>교사기고</td>
                                                <td>1</td>
                                            </tr>
                                            <tr>
                                                <td>인터뷰</td>
                                                <td>1</td>
                                            </tr>
                                            <tr>
                                                <td>원장기고</td>
                                                <td>1</td>
                                            </tr>
                                            <tr>
                                                <td>대회원고</td>
                                                <td>10</td>
                                            </tr>
                                            <tr>
                                                <td>수상진학현황</td>
                                                <td>10</td>
                                            </tr>
                                            <tr>
                                                <td>특별섹션</td>
                                                <td>10</td>
                                            </tr>
                                            <tr>
                                                <td>기타원고</td>
                                                <td>1</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p class="text-muted">파일이 여러 개일 때는 압축하여 업로드하시기 바랍니다&#46;</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="card border-left-primary shadow mt-2">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col align-self-center">
                                            <h6>매거진 사진원고 제출</h6>
                                        </div>
                                        <div class="col-auto">
                                            <div class="input-group">
                                                <div class="form-inline me-2">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="selMagazineProduceYears" onchange="magazineLoad()">
                                                            <?php
                                                            $i = date("Y", strtotime("+ 1 years"));
                                                            for ($i; $i >= '2021'; $i--) {
                                                                if ($i == date("Y")) {
                                                                    $selectedOption = 'selected';
                                                                } else {
                                                                    $selectedOption = '';
                                                                }
                                                                echo "<option value='" . $i . "' " . $selectedOption . " >" . $i . "년</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                        <label for="selMagazineProduceYears">연도</label>
                                                    </div>
                                                </div>
                                                <div class="form-inline me-2">
                                                    <div class="form-floating">
                                                        <select id="selMagazineProduceSeason" class="form-select">
                                                            <option value="01">봄</option>
                                                            <option value="02">여름</option>
                                                            <option value="03">가을</option>
                                                            <option value="04">겨울</option>
                                                        </select>
                                                        <label for="selMagazineProduceSeason">계절호</label>
                                                    </div>
                                                </div>
                                                <div class="form-inline">
                                                    <div class="form-floating">
                                                        <select id="selCenter" class="form-select">
                                                            <?php
                                                            $centerList = $infoClassCmp->searchFranchisee(1);
                                                            if (!empty($centerList)) {
                                                                foreach ($centerList as $key => $val) {
                                                                    echo "<option value=\"" . $val['franchise_idx'] . "\">" . $val['center_name'] . "</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <label for="selCenter">센터</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-hover table-bordered">
                                        <thead class="align-middle text-center">
                                            <th width="25%">꼭지</th>
                                            <th width="75%">파일</th>
                                        </thead>
                                        <tbody class="align-middle text-center" id="magazineFileList">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/adm/footer.html" ?>
        </div>
    </div>
</body>

</html>
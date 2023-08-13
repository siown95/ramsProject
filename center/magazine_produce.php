<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";

$infoClassCmp = new infoClassCmp();

$franchiseeDetail = $infoClassCmp->getFranchiseeDetail($_SESSION['center_idx']);

$engName = explode('/', $_SERVER['REQUEST_URI']);
?>
<script src="/<?= $engName[1] ?>/js/magazine_produce.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
</script>
<div class="row mt-2">
    <div class="col-4">
        <div class="card border-left-primary shadow mt-2">
            <div class="card-header">
                <h6>편집제작</h6>
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
                        <h6>사진원고</h6>
                    </div>
                    <div class="col-auto">
                        <div class="input-group mb-2">
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <select id="selMagazineProduceYears" class="form-select" style="display: none;">
                                        <?php
                                        $i = date("Y", strtotime("+ 1 years"));
                                        for ($i; $i >= date("Y", strtotime("- 1 years")); $i--) {
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
                            <div class="form-inline">
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <table class="table table-sm table-hover table-bordered">
                    <thead class="align-middle text-center">
                        <th width="15%">꼭지</th>
                        <th width="70%">파일</th>
                        <th width="15%">&nbsp;</th>
                    </thead>
                    <tbody class="align-middle text-center" id="magazineFileList">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
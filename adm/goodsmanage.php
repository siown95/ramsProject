<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$codeInfoCmp = new codeInfoCmp();

$typeSelect = $codeInfoCmp->getCodeInfo('43'); //물품 분류
?>
<!DOCTYPE html>
<html>

<head>
    <?php
    $stat = 'adm';
    include_once($_SERVER['DOCUMENT_ROOT'] . '/common/common_script.php');
    ?>
    <script src="/adm/js/goodsmanage.js?v=<?= date('YmdHis') ?>"></script>
    <script src="/js/number_format.js"></script>
</head>

<body class="sb-nav-fixed size-14">
    <!-- header navbar -->
    <?php include_once('navbar.html'); ?>
    <div id="layoutSidenav">
        <!-- sidebar -->
        <?php include_once('sidebar.html'); ?>

        <div id="Maincontent">
            <main>
                <div class="container-fluid px-4 mt-3">
                    <!-- 콘텐츠 -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="card border-left-primary shadow h-100">
                                <div class="card-body">
                                    <h6>물품목록</h6>
                                    <table id="goodsTable" class="table table-sm table-bordered table-hover">
                                        <thead class="text-center">
                                            <th>번호</th>
                                            <th>품목명</th>
                                            <th>분류</th>
                                            <!-- <th>세부분류</th> -->
                                            <th>원가</th>
                                            <th>판매단가</th>
                                            <th>단위</th>
                                            <th>최소주문</th>
                                            <th>비고</th>
                                            <!-- <th>사용유무</th> -->
                                        </thead>
                                        <tbody id="goodsList">
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card border-left-primary shadow h-100">
                                <div class="card-body">
                                    <div class="text-end">
                                        <button type="button" id="btnClear" class="btn btn-sm btn-secondary mr-1"><span class="icon mr-1"><i class="fas fa-redo"></i></span>초기화</button>
                                        <button type="button" id="btnSave" class="btn btn-sm btn-primary"><span class="icon mr-1"><i class="fas fa-save"></i></span>저장</button>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-8">
                                            <form id="goodsForm">
                                                <input type="hidden" id="editGoods">
                                                <div class="input-group mb-2">
                                                    <!-- <div class="form-floating">
                                                        <input type="text" id="txtGoodsName" class="form-control" placeholder="물품명" maxlength="100">
                                                        <label for="txtGoodsName">물품명</label>
                                                    </div> -->
                                                    <div class="form-inline me-2">
                                                        <div class="form-floating">
                                                            <input type="text" id="txtGoodsName" class="form-control" placeholder="물품명">
                                                            <label for="txtGoodsName">물품명</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-inline align-self-center">
                                                        <div class="form-check form-switch">
                                                            <input id="chkActive" class="form-check-input" type="checkbox" role="switch" checked>
                                                            <label id="lblActive" class="form-check-label" for="chkActive">사용</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="input-group mb-2">
                                                    <div class="form-inline me-2">
                                                        <div class="form-floating">
                                                            <select id="selCategory" class="form-select">
                                                                <option value="">선택</option>
                                                                <?php
                                                                if (!empty($typeSelect)) {
                                                                    foreach ($typeSelect as $key => $val) {
                                                                ?>
                                                                        <option value="<?= $val['code_num2'] ?>"><?= $val['code_name'] ?></option>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <label for="selCategory">분류</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-inline me-2">
                                                        <div class="form-floating">
                                                            <input type="text" id="txtUnit" class="form-control" placeholder="단위" maxlength="20">
                                                            <label class="form-label">단위</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-inline me-2">
                                                        <div class="form-floating">
                                                            <input type="text" id="txtCost" class="form-control" placeholder="원가">
                                                            <label class="form-label">원가</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-inline">
                                                        <div class="form-floating">
                                                            <input type="text" id="txtPrice" class="form-control" placeholder="판매단가">
                                                            <label class="form-label">판매단가</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="input-group mb-2">
                                                    <div class="form-inline me-2">
                                                        <div class="form-floating">
                                                            <input type="text" id="txtMinQuantity" class="form-control" placeholder="최소주문수량">
                                                            <label for="txtMinQuantity">최소주문수량</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-check form-check-inline align-self-center">
                                                        <input type="checkbox" id="chkStock" class="form-check-input">
                                                        <label class="form-check-label" for="chkStock">재고관리</label>
                                                    </div>
                                                </div>
                                                <div class="input-group mb-2">
                                                    <div class="col me-2">
                                                        <div class="form-floating">
                                                            <input type="text" id="txtMemo" class="form-control" placeholder="비고">
                                                            <label for="txtMemo">비고</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <div class="form-floating">
                                                            <input type="text" id="txtImageName" class="form-control bg-white" placeholder="파일을 선택해주세요" readonly>
                                                            <label for="txtImageName">사진</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto align-self-center">
                                                        <input type="file" id="fileGoodsImage" class="form-control d-none" name="fileGoodsImage">
                                                        <button type="button" id="btnGoodsImageUpload" class="btn btn-outline-secondary me-2"><i class="fa-solid fa-file-arrow-up"></i> 파일선택</button>
                                                    </div>
                                                </div>
                                                <div class="form-inline">
                                                    <a class="link-info me-2 d-none" href="" id="origin_file" download></a>
                                                    <input type="hidden" class="file-name" id="origin_file_name">
                                                    <a class="btn btn-sm btn-outline-danger file-del d-none" href="javascript:void(0)" id="imgdel"><i class="fa-solid fa-trash"></i> 삭제</a>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-4">
                                            <img id="imgGoodsThumbnail" class="img img-thumbnail" src="/img/noImg_view.png" alt="상품 샘플" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
            <!-- footer -->
            <?php
            include_once('footer.html');
            ?>
        </div>
    </div>
</body>

</html>
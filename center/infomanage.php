<?php
include_once $_SERVER['DOCUMENT_ROOT']."/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT']."/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT']."/common/commonClass.php";

$infoClassCmp = new infoClassCmp();

$franchiseeDetail = $infoClassCmp->getFranchiseeDetail($_SESSION['center_idx']);

$engName = explode('/', $_SERVER['REQUEST_URI']);
?>
<script src="/<?=$engName[1]?>/js/infomanage.js?v=<?=date("YmdHis")?>"></script>
<script>
    var franchise_idx = '<?=$_SESSION['center_idx']?>';
</script>
<!-- 콘텐츠 -->
<div class="mt-2">
    <div class="card border-left-primary shadow">
        <div class="card-body">
            <div class="row mb-3">
                <div class="justify-content-start">
                    <h6>정보관리<small class="ms-2 text-muted">입력한 정보는 페이지에서 사용하는 항목 및 하단 페이지 정보에 표시됩니다.</small></h6>
                </div>
                <div class="text-end">
                    <button type="button" id="btnSaveInfomanage" class="btn btn-sm btn-primary"><span class="icon mr-1"><i class="fas fa-save"></i></span>저장</button>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-1 col-form-label">업체명</label>
                <div class="col-auto">
                    <input type="text" id="txtCompanyName" class="form-control" value="<?=$franchiseeDetail['center_name']?>" maxlength="20" />
                </div>
                <label class="col-auto col-form-label">대표자명</label>
                <div class="col-auto">
                    <input type="text" id="txtCeoName" class="form-control" value="<?=$franchiseeDetail['owner_name']?>" maxlength="20" />
                </div>
                <label class="col-auto col-form-label">사업자등록번호</label>
                <div class="col-auto">
                    <input type="text" id="txtCompanyNo" class="form-control" value="<?=$franchiseeDetail['biz_no']?>" maxlength="15" />
                </div>
                <label class="col-auto col-form-label">학원등록번호</label>
                <div class="col-auto">
                    <input type="text" id="txtCenterNo" class="form-control" value="<?=$franchiseeDetail['center_no']?>" maxlength="10" />
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-1 col-form-label">주소</label>
                <div class="col-9">
                    <input type="text" id="txtAddr" class="form-control" value="<?=$franchiseeDetail['address']?>" maxlength="100" />
                </div>
                <div class="col-auto">
                    <button id="btnaddr" class="btn btn-outline-success" type="button"><span class="icon mr-1"><i class="fas fa-search-location"></i></span>주소찾기</button>
                    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
                    <script>
                        $('#btnaddr').click(function() {
                            new daum.Postcode({
                                oncomplete: function(data) {
                                    $('#txtAddr').val(data.roadAddress);
                                    $('#txtZipCode').val(data.zonecode);
                                    $('#txtAddr').focus();
                                }
                            }).open();
                        });
                    </script>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-1 col-form-label">전화번호</label>
                <div class="col-auto">
                    <input type="text" id="txtTel" class="form-control" value="<?=$franchiseeDetail['tel_num']?>" maxlength="15" />
                </div>
                <label class="col-auto col-form-label">FAX</label>
                <div class="col-auto">
                    <input type="text" id="txtFax" class="form-control" value="<?=$franchiseeDetail['fax_num']?>" maxlength="15" />
                </div>
                <label class="col-auto col-form-label">이메일</label>
                <div class="col-auto">
                    <input type="text" id="txtEmail" class="form-control" value="<?=$franchiseeDetail['email']?>" maxlength="50" />
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-1 col-form-label">가맹시작일</label>
                <div class="col-auto">
                    <input type="text" class="form-control" value="<?=$franchiseeDetail['franchisee_start']?>" maxlength="10" disabled />
                </div>
                <label class="col-auto col-form-label">가맹종료일</label>
                <div class="col-auto">
                    <input type="text" class="form-control" value="<?=$franchiseeDetail['franchisee_end']?>" maxlength="10" disabled />
                </div>
            </div>
        </div>
    </div>
</div>
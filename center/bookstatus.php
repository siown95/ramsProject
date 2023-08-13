<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";
?>
<script src="/center/js/book_status.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var userInfo = {
        user_no: '<?= $_SESSION['logged_no'] ?>',
        user_id: '<?= $_SESSION['logged_id'] ?>',
        user_name: '<?= $_SESSION['logged_name'] ?>',
        user_phone: '<?= phoneFormat($_SESSION['logged_phone']) ?>',
        user_email: '<?= $_SESSION['logged_email'] ?>'
    }
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
</script>
<div class="card border-left-primary shadow mt-2">
    <div class="card-header">
        <h6>도서현황</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-3">
                <div class="form-floating mb-2">
                    <input type="text" id="txtBookStatusNo" class="form-control" placeholder="도서관리번호&#40;바코드&#41;" maxlength="5">
                    <label for="txtBookStatusNo">도서관리번호&#40;바코드&#41;</label>
                </div>
                <div class="form-floating mb-2">
                    <input type="text" id="txtBookStatusName" class="form-control bg-white" placeholder="도서명" readonly>
                    <label for="txtBookStatusName">도서명</label>
                </div>
                <div class="text-end mb-2">
                    <input type="hidden" id="statusIdx">
                    <button type="button" id="btnBookStatusSave" class="btn btn-sm btn-primary" style="display: none;"><i class="fa-solid fa-floppy-disk me-1"></i>저장</button>
                </div>
                <form id="barcodeForm" method="post" action="/common/barcode.php" target="_blank">
                    <div class="input-group mb-2">
                        <input type="hidden" id="centerCode" name="center_idx" value="<?= $_SESSION['center_idx'] ?>" />
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="text" id="txtBarcodeStart" class="form-control" name="startNo" />
                                <label for="txtBarcodeStart">바코드번호&#40;시작&#41;</label>
                            </div>
                        </div>
                        <div class="form-inline me-2">
                            <div class="form-floating">
                                <input type="text" id="txtBarcodeEnd" class="form-control" name="endNo" />
                                <label for="txtBarcodeEnd">바코드번호&#40;끝&#41;</label>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="text-end">
                    <button type="button" id="btnBookBarcodeCreate" class="btn btn-sm btn-success"><i class="fa-solid fa-list-ol me-1"></i>바코드 자동부여</button>
                    <button type="button" id="btnBookBarcodePrint" class="btn btn-sm btn-success"><i class="fa-solid fa-print me-1"></i>바코드 인쇄</button>
                </div>
            </div>
            <div class="col-9">
                <table id="bookStatusTable" class="table table-sm table-bordered table-hover">
                    <thead class="text-center align-middle">
                        <th>번호</th>
                        <th>바코드</th>
                        <th>도서명</th>
                        <th>대출자</th>
                        <th>최근반납자</th>
                        <th>최근대출일자</th>
                        <th>최근반납일자</th>
                    </thead>
                    <tbody id="bookStatusList">
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
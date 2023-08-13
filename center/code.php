<script src="/center/js/code.js?v=<?=date("YmdHis")?>"></script>
<div class="row mt-2 size-14">
    <!-- 1차 코드 목록 -->
    <div class="col-4">
        <div class="card border-left-primary shadow h-100">
            <div class="card-body">
                <div class="container">
                    <h6>1차 코드 목록</h6>
                    <table id="codeTable1" class="table table-bordered table-hover">
                        <thead class="text-center align-middle">
                            <th>코드</th>
                            <th>코드명</th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                        </thead>
                        <tbody class="text-center" id="degree1">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- 2차 코드 목록 -->
    <div class="col-4">
        <div class="card border-left-primary shadow h-100">
            <div class="card-body">
                <div class="container">
                    <h6>2차 코드 목록</h6>
                    <input type="hidden" id="codeNumber1">
                    <table id="codeTable2" class="table table-bordered table-hover">
                        <thead class="text-center align-middle">
                            <th>2차 코드</th>
                            <th>2차 코드명</th>
                        </thead>
                        <tbody class="text-center" id="degree2">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- 3차 코드 목록 -->
    <div class="col-4">
        <div class="card border-left-primary shadow h-100">
            <div class="card-body">
                <div class="container">
                    <h6>3차 코드 목록</h6>
                    <table id="codeTable3" class="table table-bordered table-hover">
                        <thead class="text-center">
                            <th>3차 코드</th>
                            <th>3차 코드명</th>
                        </thead>
                        <tbody class="text-center" id="degree3">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
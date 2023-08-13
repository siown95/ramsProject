<div class="card border-left-primary shadow mt-2">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col align-items-start align-self-center">
                <h6>매거진</h6>
            </div>
            <div class="col-auto align-items-end">
                <div class="form-floating">
                    <select class="form-select" id="selYear" onchange="magazineLoad()">
                        <?php
                        for ($i = date('Y'); $i >= '2013'; $i--) {
                        ?>
                            <option value="<?= $i ?>"><?= $i ?>년</option>
                        <?php
                        }
                        ?>
                    </select>
                    <label for="selYear">연도</label>
                </div>
            </div>
            <div class="col-auto align-items-end">
                <div class="form-floating">
                    <select class="form-select" id="selSeason" onchange="magazineLoad()">
                        <option value="01">봄</option>
                        <option value="02">여름</option>
                        <option value="03">가을</option>
                        <option value="04">겨울</option>
                    </select>
                    <label for="selSeason">계절</label>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2 mb-2" id="magazineList">

    </div>

</div>

<script src="/center/js/magazine.js?v=<?= date("YmdHis") ?>"></script>
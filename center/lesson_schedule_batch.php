<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";

$codeInfoCmp = new codeInfoCmp();
$gradeList = $codeInfoCmp->getCodeInfo('02');
?>
<script src="/center/js/lesson_schedule_batch.js?v=<?=date("YmdHis")?>"></script>
<script>
    var center_idx = '<?=$_SESSION['center_idx']?>';
</script>
<div class="card border-left-primary shadow mt-2 size-14">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <h6>수업도서배치</h6>
            </div>
            <div class="col-auto align-items-end">
                <button type="button" id="btnBatchLessonBook" class="btn btn-sm btn-outline-info"><i class="fa-solid fa-book me-1"></i>자동배치</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="input-group mb-2">
            <div class="form-inline me-1">
                <div class="form-floating">
                    <input type="month" id="dtYearMonth" class="form-control" value="<?= date('Y-m') ?>">
                    <label for="dtYearMonth">년월</label>
                </div>
            </div>
            <div class="form-inline">
                <div class="form-floating">
                    <select id="selGrade" class="form-select">
                        <?php
                        if(!empty($gradeList)){
                            foreach($gradeList as $key => $val){
                                echo "<option value=\"".$val['code_num2']."\">".$val['code_name']."</option>";
                            }
                        }
                        ?>
                    </select>
                    <label for="selGrade">학년</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <table class="table table-sm table-bordered table-hover" id="lessonBookTable">
                    <thead class="align-middle text-center">
                        <th width="10%">번호</th>
                        <th width="50%">도서명</th>
                        <th width="10%">보유권수</th>
                        <th width="10%">대출권수</th>
                        <th width="10%">부족권수</th>
                        <th width="10%">예상권수</th>
                    </thead>
                    <tbody class="align-middle text-center">
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-sm table-bordered table-hover" id="lessonBatchTable">
                    <thead class="align-middle text-center">
                        <th width="7%">학년</th>
                        <th width="15%">수업일</th>
                        <th width="10%">시간</th>
                        <th width="10%">담당</th>
                        <th width="29%">도서</th>
                        <th width="8%">학생수</th>
                        <th width="7%">재량</th>
                        <th width="8%">온라인</th>
                    </thead>
                    <tbody class="align-middle text-center">
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
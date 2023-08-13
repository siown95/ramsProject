<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";

$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";

$infoClassCmp = new infoClassCmp();
$codeInfoCmp = new codeInfoCmp();
$teacherSelect = $infoClassCmp->teacherList($_SESSION['center_idx']);
$TevalList = $infoClassCmp->teacherEvalList();
$evalCode = $codeInfoCmp->getCodeInfo('54');

$evalSelect = '';
if (!empty($evalCode)) {
    foreach ($evalCode as $key => $val) {
        $evalSelect .= "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
    }
}
?>
<script src="/center/js/teacher_evaluation.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
</script>
<div class="card border-left-primary shadow mt-2 size-14">
    <div class="card-header">
        <div class="row">
            <div class="col align-self-center">
                <h6>종합평가</h6>
            </div>
            <div class="col-auto align-items-end">
                <button type="button" id="btnInfo" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#TeacherEvaluationinfoModal"><i class="fa-regular fa-circle-question"></i> 도움말</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 mb-2">
                <div class="input-group">
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <input type="month" id="dtMonths" class="form-control" value="<?= date("Y-m") ?>" onchange="teacherEvalLoad()" />
                            <label for="dtMonths">년월</label>
                        </div>
                    </div>
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <select id="selTeacher" class="form-select" onchange="teacherEvalLoad()">
                                <?php
                                if (!empty($teacherSelect)) {
                                    foreach ($teacherSelect as $key => $val) {
                                ?>
                                        <option value="<?= $val['user_no'] ?>"><?= $val['user_name'] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            <label for="selTeacher">이름</label>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" id="btnTEvalSave" class="btn btn-sm btn-primary"><i class="fas fa-save me-1"></i>저장</button>
                </div>
            </div>
            <div class="col-12 mb-2">
                <table id="TevaluationTable" class="table table-sm table-bordered table-hover">
                    <thead class="text-center align-middle">
                        <th>번호</th>
                        <th>이름</th>
                        <th>RAMS점수</th>
                        <?php
                        $count = count($TevalList);
                        foreach ($TevalList as $key => $val) {
                            echo "<th>" . $val['code_name'] . "</th>";
                        }
                        ?>
                        <th>점수</th>
                    </thead>
                    <tbody id="TevaluationList"></tbody>
                </table>
            </div>

            <div class="col-12">
                <table class="table table-sm table-responsive">
                    <thead>
                        <tr class="align-middle text-center">
                            <th width="10%">평가항목</th>
                            <th width="10%">평가점수 &#40;D&#126;S&#41;</th>
                            <th width="80%">평가근거 &#40;100자&#41;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($TevalList)) {
                            foreach ($TevalList as $key => $val) {
                        ?>
                                <tr class="align-middle text-center">
                                    <td><?= $val['code_name'] ?></td>
                                    <td>
                                        <select id="selTEval<?= ($key + 1) ?>" class="form-select">
                                            <?= $evalSelect ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="txtTEval<?= ($key + 1) ?>" class="form-control" maxlength="100" placeholder="평가근거 <?= ($key + 1) ?>" />
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="TeacherEvaluationinfoModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">근무평가 도움말</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body size-14">
                <span>각 평가항목별로 세부적인 사항은 아래를 참조하면 됩니다&#46;</span><br>
                <span>S &#58; 10점&#44; A &#58; 9점&#44; B &#58; 8점&#44; C &#58; 7점&#44; D &#58; 6점 이하</span><br>
                <table class="table table-sm table-bordered">
                    <thead class="align-middle text-center">
                        <th width="15%">평가항목</th>
                        <th width="85%">설명</th>
                    </thead>
                    <tbody class="align-middle text-center">
                        <tr>
                            <th><?= $TevalList[0]['code_name'] ?></th>
                            <td class="text-start">회사의 지향점&#44; 리딩엠 교육의 목표와 방향&#44; 원장이 강조하는 가치 등을 잘 수용하고 실천하려고 하는지를 평가한다&#46;<br>
                                &#45; 리딩엠 본사, 총괄원장의 강조점을 잘 수용하고 실천하는가&#63; &#40;2점&#41;<br>
                                &#45; 수업이외의 독서활동관리&#44; 환경유지관리 등도 본인의 직무라고 생각하는가&#63; &#40;2점&#41;<br>
                                &#45; 지침&#40;매뉴얼&#41;에 맞춘 직무수행의 중요성을 인식하고 있는가&#63; &#40;2점&#41;<br>
                                &#45; 원장이 센터운영을 위해 중요하게 생각하는 강조점을 잘 수용하고 실천하는가&#63; &#40;4점&#41;</td>
                        </tr>
                        <tr>
                            <th><?= $TevalList[1]['code_name'] ?></th>
                            <td class="text-start">대표&#44; 총괄원장과 각 센터 원장을 비롯한 운영진의 지시사항을 잘 이행하고 있는가&#63;</td>
                        </tr>
                        <tr>
                            <th><?= $TevalList[2]['code_name'] ?></th>
                            <td class="text-start">출퇴근<br>수업준비&#44; 학생관리 등을 철저하게 잘 하고 있는가&#63;<br>첨삭&#44; 도서대출 등 잘 진행하는가&#63;</td>
                        </tr>
                        <tr>
                            <th><?= $TevalList[3]['code_name'] ?></th>
                            <td class="text-start">자신이 맡은 수업 진행을 잘하고 있는가&#63;</td>
                        </tr>
                        <tr>
                            <th><?= $TevalList[4]['code_name'] ?></th>
                            <td class="text-start">카운터 임무를 잘 수행하는가&#63;<br>원장&#40;운영진&#41;의 요청에 협력적인가&#63;<br>공통업무에 협력적인가&#63;</td>
                        </tr>
                        <tr>
                            <th><?= $TevalList[5]['code_name'] ?></th>
                            <td class="text-start">원장&#40;운영진&#41;이 중요하게 강조하거나 지시하는 바에 적극 호응하는가&#63;<br>
                                재원생이 퇴원하지 않도록 선제적으로 대응하는가&#63;<br>
                                궂은 일&#44; 남들이 하고 싶지 않은 일을 적극적으로 맡을려고 하는가&#63;</td>
                        </tr>
                        <tr>
                            <th><?= $TevalList[6]['code_name'] ?></th>
                            <td class="text-start">
                                카카오 워크에 보고를 철저히 잘 하고 있는가&#63;<br>
                                중요사안에 대해 원장에게 보고를 잘 하는가&#63;<br>
                                램스에 입력을 잘하는가&#63;
                            </td>
                        </tr>
                        <tr>
                            <th><?= $TevalList[7]['code_name'] ?></th>
                            <td class="text-start">
                                원장&#40;운영진&#41;과 잘 소통하는가&#63;<br>
                                학생과 잘 소통하는가&#63;<br>
                                학부모와 잘 소통하는가&#63;
                            </td>
                        </tr>
                        <tr>
                            <th><?= $TevalList[8]['code_name'] ?></th>
                            <td class="text-start">
                                교내대회 또는 교외대회 성과가 있는가&#63;<br>
                                2건 이상일 경우 S&#44; 1건 있을 경우 A&#44; 없을 경우 B<br>
                                기본점수가 &#39;B&#39;임
                            </td>
                        </tr>
                        <tr>
                            <th><?= $TevalList[9]['code_name'] ?></th>
                            <td class="text-start">
                                2건 이상일 경우 S&#44; 1건 있을 경우 A&#44; 없을 경우 B<br>
                                기본점수가 &#39;B&#39;임<br>
                                주간회의, 미팅, 원장과의 면담 등 어떤 경로든 상관없이<br>
                                &#45; 정규수업 관련해 아이디어를 제출해 채택되었는가&#63;<br>
                                &#45; 특강 관련해 아이디어를 제출해 채택되었는가&#63;<br>
                                &#45; 리딩엠 관련 아이디어를 제출해 채택되었는가&#63;
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();

include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/function.php";
$codeInfoCmp = new codeInfoCmp();

$grade_data = $codeInfoCmp->getCodeInfo('02'); //학년
$grade_option = '';

if (!empty($grade_data)) {
    $grade_option .= "<option value='00'>전체</option>";
    foreach ($grade_data as $key => $val) {
        $grade_option .= "<option value='" . $val['code_num2'] . "'>" . $val['code_name'] . "</option>";
    }
}
?>
<script src="/center/js/student_evaluation.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var center_idx = '<?= $_SESSION['center_idx'] ?>';
    var userInfo = {
        user_no: '<?= $_SESSION['logged_no'] ?>',
        user_id: '<?= $_SESSION['logged_id'] ?>',
        user_name: '<?= $_SESSION['logged_name'] ?>',
        user_phone: '<?= phoneFormat($_SESSION['logged_phone']) ?>',
        user_email: '<?= $_SESSION['logged_email'] ?>'
    }
</script>
<div class="card border-left-primary shadow mt-2 size-14">
    <div class="card-header">
        <div class="row">
            <div class="col">
                <h6>종합평가</h6>
            </div>
            <div class="col-auto align-items-end">
                <button type="button" id="btnInfo" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#StudentEvaluationinfoModal"><i class="fa-regular fa-circle-question"></i> 도움말</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-3">
                <div class="input-group mb-2">
                    <div class="form-inline w-15 me-2">
                        <div class="form-floating">
                            <input id="selEvalYear1" class="form-control" value="<?= date('Y') ?>" readonly />
                            <label for="selEvalYear1">년도</label>
                        </div>
                    </div>
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <select id="selSemiAnnual1" class="form-select">
                                <?php
                                if ((int)(date('m')) >= 7) {
                                    echo "<option value=\"1\">상반기</option><option value=\"2\" selected>하반기</option>";
                                } else {
                                    echo "<option value=\"1\" selected>상반기</option><option value=\"2\">하반기</option>";
                                }
                                ?>
                            </select>
                            <label for="selSemiAnnual1">반기</label>
                        </div>
                    </div>
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <select id="selGrade" class="form-select" onchange="getStudentList()">
                                <?= $grade_option ?>
                            </select>
                            <label for="selGrade">학년</label>
                        </div>
                    </div>
                    <div class="form-inline">
                        <div class="form-floating">
                            <select id="selStudent" class="form-select" onchange="evaluationLoad()">
                                <option value="all">전체</option>
                            </select>
                            <label for="selStudent">이름</label>
                        </div>
                    </div>
                </div>
                <table id="evaluationTable" class="table table-sm table-bordered table-hover">
                    <thead class="text-center align-middle">
                        <th>번호</th>
                        <th>학년</th>
                        <th>이름</th>
                        <th>작성자</th>
                        <th>작성일자</th>
                    </thead>
                    <tbody id="evaluationList">
                        <tr class="text-center align-middle tc">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-9">
                <div class="input-group mb-2">
                    <div class="form-inline w-5 me-2">
                        <div class="form-floating">
                            <input id="selEvalYear2" class="form-control" value="<?= date('Y') ?>" readonly />
                            <label for="selEvalYear2">년도</label>
                        </div>
                    </div>
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <select id="selSemiAnnual2" class="form-select">
                                <?php
                                if ((int)(date('m')) >= 7) {
                                    echo "<option value=\"1\">상반기</option><option value=\"2\" selected>하반기</option>";
                                } else {
                                    echo "<option value=\"1\" selected>상반기</option><option value=\"2\">하반기</option>";
                                }
                                ?>
                            </select>
                            <label for="selSemiAnnual2">반기</label>
                        </div>
                    </div>
                    <div class="form-inline me-2">
                        <div class="form-floating">
                            <input type="hidden" id="targetStudentNo">
                            <input type="hidden" id="studentEvalutionIdx">
                            <input type="text" id="txtEvaluationStudentName" class="form-control bg-white" placeholder="원생이름" readonly>
                            <label for="txtEvaluationStudentName">원생이름</label>
                        </div>
                    </div>
                    <div class="input-group-append align-self-center ms-2">
                        <button type="button" id="btnEvaluationSearch" class="btn btn-sm btn-outline-secondary">원생찾기</button>
                    </div>
                    <div class="input-group-append align-self-center ms-2">
                        <label id="lblEvaluationGrade" class="form-label"></label>
                    </div>
                    <div class="input-group-append align-self-center ms-2">
                        <label id="lblEvaluationSchool" class="form-label"></label>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label for="txtSynthesis">종합관찰내용</label>
                        <textarea id="txtSynthesis" class="form-control" placeholder="종합관찰내용" rows="4" maxlength="500"></textarea>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-auto w-15 align-self-center">
                        <div class="input-group">
                            <div class="form-inline">
                                <div class="form-floating">
                                    <input type="text" id="txtReadScore" class="form-control" readonly />
                                    <label for="txtReadScore">책읽기</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <textarea id="txtRead" class="form-control" placeholder="책읽기" rows="4" maxlength="500"></textarea>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-auto w-15 align-self-center">
                        <div class="input-group">
                            <div class="form-inline">
                                <div class="form-floating">
                                    <input type="text" id="txtDebateScore" class="form-control" readonly />
                                    <label for="txtDebateScore">토론참여</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <textarea id="txtDebate" class="form-control" placeholder="토론참여" rows="4" maxlength="500"></textarea>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-auto w-15 align-self-center">
                        <div class="input-group">
                            <div class="form-inline">
                                <div class="form-floating">
                                    <input type="text" id="txtWriteScore" class="form-control" readonly />
                                    <label for="txtWriteScore">글쓰기</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <textarea id="txtWrite" class="form-control" placeholder="글쓰기" rows="4" maxlength="500"></textarea>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-auto w-15 align-self-center">
                        <div class="input-group">
                            <div class="form-inline">
                                <div class="form-floating">
                                    <input type="text" id="txtTenacityScore" class="form-control" readonly />
                                    <label for="txtTenacityScore">주도&#47;인성</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <textarea id="txtTenacity" class="form-control" placeholder="주도/인성" rows="4" maxlength="500"></textarea>
                    </div>
                </div>

                <div class="col mb-2">
                    <label for="txtGuide">지도한내용</label>
                    <textarea id="txtGuide" class="form-control" placeholder="지도한내용" rows="4" maxlength="500"></textarea>
                </div>
                <div class="col mb-2">
                    <label for="txtRequests">학부모님 요청사항</label>
                    <textarea id="txtRequests" class="form-control bg-light" placeholder="학부모님 요청사항" rows="4" maxlength="500" readonly></textarea>
                </div>
                <div class="col mb-2">
                    <label for="txtNextGuide">앞으로 지도방향</label>
                    <textarea id="txtNextGuide" class="form-control" placeholder="앞으로 지도방향" rows="4" maxlength="500"></textarea>
                </div>
                <div class="text-end">
                    <button type="button" id="btnEvalDelete" class="btn btn-sm btn-danger" style="display: none;"><i class="fa-solid fa-trash me-1"></i>삭제</button>
                    <button type="button" id="btnEvalUpdate" class="btn btn-sm btn-success" style="display: none;"><i class="fa-solid fa-trash me-1"></i>수정</button>
                    <button type="button" id="btnEvalSave" class="btn btn-sm btn-primary"><i class="fas fa-save me-1"></i>저장</button>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="StudentEvaluationinfoModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">작성요령</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body size-14">
                <span class="size-12">
                    &lt;취지 및 효과&gt;<br>
                    1&#46; 리딩엠 교육센터에서 매년 학생종합평가서 작성해 학부모님께 발송하면 학부모님의 반응이 좋았고&#44; 어떤 학부보님은 학부모님란에 고맙다는 말씀 등을 적어 보내오는 경우가 있음<br>
                    2&#46; 리딩엠에서 이뤄지는 교육이 교과평가 등과는 달리 관찰내용&#44; 지도내용&#44; 앞으로 지도계획&#44; 학부모님 요청사항&#40;적어보내주세요&#41; 등의 항목에 담당 선생님이 직접 작성하므로 글로 전달하는 효과가 있음<br>
                    3&#46; 담당 지도교사의 경우&#44; 학생에 대해 좀 더 고민하는 계기가 되기도 함<br>
                </span><br>
                <span>수업후 지도교사가 학생에 대한 평가등급 부여 기준입니다.<br>
                    S &#58; 10점&#44; A &#58; 9점&#44; B &#58; 8점&#44; C &#58; 7점&#44; D &#58; 6점 이하<br>
                    책읽기, 토론참여, 글쓰기는 매주 수업후 지도교사가 체크한 결과의 평균값으로 환산된 등급이 표시됩니다&#46;<br>
                    주도/인성 평가는 종합평가를 할 때 직접 입력해야 합니다&#46;
                </span><br>
                <table class="table table-bordered">
                    <thead class="text-center align-middle">
                        <th>평가항목</th>
                        <th>설명</th>
                    </thead>
                    <tbody class="text-center align-middle">
                        <tr>
                            <td>책읽기</td>
                            <td class="text-start">
                                1&#46; 자기주도적 독서를 하는지 평가해주세요&#46;<br>
                                2&#46; 정독&#44; 지속독&#44; 다양독&#44; 다량독을 기준으로 평가해주세요&#46;<br>
                                3&#46; 램스에서 도서대출이력을 체크해 평가해주세요&#46;
                            </td>
                        </tr>
                        <tr>
                            <td>토론참여</td>
                            <td class="text-start">
                                1&#46; 자신의 생각을 적극적으로 표현하는지 평가해주세요&#46;<br>
                                2&#46; 상대방의 얘기를 들어주는지 평가해주세요&#46;<br>
                                3&#46; 사회자의 안내에 잘 따라주는지 평가해주세요&#46;
                            </td>
                        </tr>
                        <tr>
                            <td>글쓰기</td>
                            <td class="text-start">
                                1&#46; 맞춤법&#44; 뜨어쓰기&#44; 어법 등을 평가해주세요&#46;<br>
                                2&#46; 문단을 나눠서 글을 쓸 줄 아는지 평가해주세요&#46;<br>
                                3&#46; 중심문장과 뒷받침문장이 잘 연결되는지 평가해주세요&#46;<br>
                                4&#46; 글을 논리적&#44; 비판적&#44; 창의적 관점에서 평가해주세요&#46;
                            </td>
                        </tr>
                        <tr>
                            <td>주도&#47;인성</td>
                            <td class="text-start">
                                1&#46; 자기주도적으로 수업준비&#44; 수업참여 등을 하는지 평가해주세요&#46;<br>
                                2&#46; 모둠학생들과 잘 소통하고 배려하는지 평가해주세요&#46;<br>
                                3&#46; 지도교사의 말을 잘 따르는지 평가해주세요&#46;
                            </td>
                        </tr>
                        <tr>
                            <td>지도한내용</td>
                            <td class="text-start">
                                1&#46; 학생의 개별특성에 맞게 지도한 내용을 적어주세요&#46;<br>
                                2&#46; 단계적으로 부족한 부분을 어떻게 지도해 보완했는지 적어주세요&#46;<br>
                                3&#46; 독서&#44; 글쓰기&#44; 말하기 등에 대한 지도내용을 적어주세요&#46;
                            </td>
                        </tr>
                        <tr>
                            <td>앞으로 지도방향</td>
                            <td class="text-start">
                                1&#46; 학년에 맞게 앞으로 중점적으로 지도할 내용을 적어주세요&#46;<br>
                                2&#46; 중점지도를 할 경우&#44; 예상되는 변화내용을 적어주세요&#46;
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="SearchEvaluationModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">학생검색</h5>
                <button type="button" id="btnClose" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-2">
                    <input type="text" id="txtSearchEvaluationName" class="form-control">
                    <div class="input-group-append">
                        <button type="button" id="evaluationSearch" class="btn btn-outline-success"><i class="fas fa-search-location me-1"></i>검색</button>
                    </div>
                </div>
                <form>
                    <table class="table table-bordered table-hover" id="evaluationSearchTable">
                        <thead class="text-center align-middle">
                            <th>이름</th>
                            <th>학년</th>
                            <th>학교</th>
                        </thead>
                        <tbody id="evaluationSearchList">
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> 닫기</button>
            </div>
        </div>
    </div>
</div>
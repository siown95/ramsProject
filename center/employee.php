<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/_config/session_start.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/dbClass.php";
$db = new DBCmp();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/commonClass.php";
$codeInfoCmp = new codeInfoCmp();

$positionSelect = $codeInfoCmp->getCodeInfo('20'); //센터 직책 불러오기
$stateSelect    = $codeInfoCmp->getCodeInfo('09'); //근무상태 불러오기

$dayArray = array(
    array("type" => "1", "text" => "일요일"),
    array("type" => "2", "text" => "월요일"),
    array("type" => "3", "text" => "화요일"),
    array("type" => "4", "text" => "수요일"),
    array("type" => "5", "text" => "목요일"),
    array("type" => "6", "text" => "금요일"),
    array("type" => "7", "text" => "토요일")
);
?>

<script src="/center/js/employee.js?v=<?= date("YmdHis") ?>"></script>
<script>
    var center_no = '<?= $_SESSION['center_idx'] ?>';
</script>
<main>
    <!-- 콘텐츠 -->
    <div class="row mt-2 size-14">
        <!-- 직원 목록 -->
        <div class="col-3">
            <div class="card border-left-primary shadow h-100">
                <div class="card-header">
                    <h6>직원목록</h6>
                </div>
                <div class="card-body">
                    <div class="container">
                        <table id="employeeTable" class="table table-bordered table-hover">
                            <div class="input-group justify-content-end mb-2">
                                <div class="form-floating">
                                    <select class="form-select" onchange="loadEmployee(this.value)">
                                        <?php
                                        if (!empty($stateSelect)) {
                                            foreach ($stateSelect as $key => $val) {
                                                echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <label>상태</label>
                                </div>
                            </div>
                            <thead class="text-center align-middle">
                                <th>이름</th>
                                <th>직급</th>
                                <th>전화번호</th>
                                <th class="d-none">idx</th>
                                <th class="d-none">code_num</th>
                            </thead>
                            <tbody id="employeeList" class="text-center">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- 직원 설정 -->
        <div class="col-9">
            <div class="card border-left-primary shadow h-100">
                <div class="card-header">
                    <h6>직원설정</h6>
                </div>
                <div class="card-body" id="loadDiv1">
                    <div id="loadDiv2">
                        <form id="employeeForm">
                            <div class="input-group justify-content-end mb-2">
                                <button type="button" id="btnClearEmployee" onclick="clearForm()" class="btn btn-sm btn-secondary mr-1"><i class="fas fa-redo me-1"></i>초기화</button>
                                <button type="button" id="btnSaveEmployee" onclick="employeeUpdate()" class="btn btn-sm btn-primary" style="display: none;"><i class="fas fa-save me-1"></i>저장</button>
                            </div>
                            <div class="input-group mb-2">
                                <div class="form-inline">
                                    <div class="form-floating">
                                        <input type="text" id="user_name" class="form-control" placeholder="이름" maxlength="10">
                                        <label for="user_name">이름</label>
                                    </div>
                                </div>
                                <div class="form-inline form-check align-self-center">
                                    <label class="form-check-label me-2">성별</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="genderM" name="gender" value="M">
                                        <label class="form-check-label">남</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="genderF" name="gender" value="F">
                                        <label class="form-check-label">여</label>
                                    </div>
                                </div>
                                <div class="form-inline ms-2">
                                    <div class="form-floating">
                                        <input type="date" id="dtBirthday" class="form-control">
                                        <label class="form-label">생년월일</label>
                                    </div>
                                </div>
                                <div class="form-inline ms-2">
                                    <div class="form-floating">
                                        <select class="form-select" id="state">
                                            <option value="">선택</option>
                                            <?php
                                            if (!empty($stateSelect)) {
                                                foreach ($stateSelect as $key => $val) {
                                                    echo "<option value=\"" . $val['code_num2'] . "\">" . $val['code_name'] . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                        <label for="state">상태</label>
                                    </div>
                                </div>
                                <div class="form-inline ms-2">
                                    <div class="form-floating">
                                        <select class="form-select" id="position">
                                            <option value="">선택</option>
                                            <?php
                                            if (!empty($positionSelect)) {
                                                foreach ($positionSelect as $key => $val) {
                                                    echo "<option id=\"position_" . $val['code_num2'] . "\" value=" . $val['code_num2'] . ">" . $val['code_name'] . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                        <label for="position">직책</label>
                                    </div>
                                </div>
                                <div class="form-inline ms-2">
                                    <div class="form-floating">
                                        <input type="date" id="dtHiringDate" class="form-control" placeholder="채용일">
                                        <label for="dtHiringDate">채용일</label>
                                    </div>
                                </div>
                                <div class="form-inline ms-2">
                                    <div class="form-floating">
                                        <input type="date" id="dtResignationDate" class="form-control">
                                        <label for="dtResignationDate">퇴사일</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-2">
                                <div class="form-inline">
                                    <div class="form-floating">
                                        <input type="text" id="user_id" class="form-control" placeholder="아이디" maxlength="20" data-id-check="N" />
                                        <label for="user_id">아이디</label>
                                    </div>
                                </div>
                                <div class="form-inline ms-2">
                                    <div class="form-floating">
                                        <input type="password" id="txtPassword" class="form-control" placeholder="비밀번호" minlength="4" maxlength="20" />
                                        <label for="txtPassword">비밀번호</label>
                                    </div>
                                </div>
                                <div class="form-inline ms-2">
                                    <div class="form-floating">
                                        <input type="text" id="txtHp" class="form-control" placeholder="연락처(휴대전화번호)" maxlength="11">
                                        <label for="txtHp">연락처(휴대전화번호)</label>
                                    </div>
                                </div>
                                <div class="form-inline ms-2">
                                    <div class="form-floating">
                                        <input type="text" id="txtHp2" class="form-control" placeholder="비상연락처" maxlength="11">
                                        <label for="txtHp2">비상연락처</label>
                                    </div>
                                </div>
                                <div class="form-inline ms-2">
                                    <div class="form-floating">
                                        <input type="email" id="email" class="form-control" placeholder="이메일" maxlength="50">
                                        <label for="email">이메일</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-2">
                                <div class="form-inline me-2">
                                    <div class="form-floating">
                                        <input type="text" id="txtGraduationSchool" class="form-control" placeholder="출신학교" maxlength="30">
                                        <label for="txtGraduationSchool">출신학교</label>
                                    </div>
                                </div>
                                <div class="form-inline me-2">
                                    <div class="form-floating">
                                        <input type="month" id="txtGraduationDate" class="form-control">
                                        <label for="txtGraduationDate">졸업년도</label>
                                    </div>
                                </div>
                                <div class="form-inline me-2">
                                    <div class="form-floating">
                                        <input type="text" id="txtMajor" class="form-control" placeholder="전공" maxlength="30">
                                        <label for="txtMajor">전공</label>
                                    </div>
                                </div>
                                <div class="form-inline">
                                    <div class="form-floating">
                                        <input type="text" id="txtDegreeNumber" class="form-control" placeholder="학위번호" maxlength="50">
                                        <label for="txtDegreeNumber">학위번호</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-2">
                                <div class="form-inline me-2">
                                    <div class="form-floating">
                                        <input type="text" id="txtCareer" class="form-control" placeholder="주요경력" maxlength="30">
                                        <label for="txtCareer">주요경력</label>
                                    </div>
                                </div>
                                <div class="form-inline me-2">
                                    <div class="form-floating">
                                        <input type="text" id="txtCareerYear" class="form-control" placeholder="경력년수" maxlength="2">
                                        <label for="txtCareerYear">경력년수</label>
                                    </div>
                                </div>
                                <div class="form-inline me-2">
                                    <div class="form-floating">
                                        <input type="text" id="txtCertificate" class="form-control" placeholder="자격증" maxlength="50">
                                        <label for="txtCertificate">자격증</label>
                                    </div>
                                </div>
                                <div class="form-inline me-2">
                                    <div class="form-floating">
                                        <input type="text" id="txtBank" class="form-control" placeholder="은행명" maxlength="10">
                                        <label for="txtBank">은행명</label>
                                    </div>
                                </div>
                                <div class="form-inline">
                                    <div class="form-floating">
                                        <input type="text" id="txtPassbook" class="form-control" placeholder="통장번호" maxlength="30">
                                        <label for="txtPassbook">통장번호</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="form-floating">
                                        <input type="text" id="txtZipCode" class="form-control bg-white" placeholder="우편번호" disabled>
                                        <label for="txtZipCode">우편번호</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-floating">
                                        <input type="text" id="txtAddr" class="form-control" maxlength="100" placeholder="주소">
                                        <label for="txtAddr">주소</label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button onclick="daum_postcode()" class="btn btn-outline-success" type="button"><i class="fas fa-search-location"></i>주소찾기</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3">
            <div class="card border-left-primary shadow h-100">
                <div class="card-header">
                    <h6>권한 설정</h6>
                </div>
                <div class="card-body">
                    <div class="input-group" id="menuList">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" id="chkAll" class="form-check-input chk" value="0">
                            <label class="form-check-label" for="chkAll">모든권한</label>
                        </div>
                        <div class="input-group">
                            <?php
                            // $menuClassCmp = new menuClassCmp();
                            // $menu_list = $menuClassCmp->getMenu('center');

                            if (!empty($menu_list)) {
                                foreach ($menu_list as $key => $val) {
                                    echo "<div class=\"form-check form-check-inline\">
                                            <input type=\"checkbox\" id=\"chkMenu" . $key . "\" name=\"chkNo\" class=\"form-check-input chk\" value=\"" . $val['menu_idx'] . "\">
                                            <label class=\"form-check-label\" for=\"chkMenu" . $key . "\">" . $val['menu_name'] . "</label>
                                          </div>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3">
            <div class="card border-left-primary shadow h-100" id="loadDiv3">
                <div class="card-header">
                    <h6>출근 기초설정</h6>
                </div>
                <div class="card-body" id="loadDiv4">
                    <form id="commuteForm">
                        <div class="input-group mt-3">
                            <div class="form-inline me-2">
                                <div class="form-floating">
                                    <select id="selHoliday1" class="form-select" onchange="setHoliday('paid',this.value)">
                                        <option value="">선택</option>
                                        <?php
                                        foreach ($dayArray as $key => $val) {
                                            echo "<option value=\"" . $val['type'] . "\">" . $val['text'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="selHoliday1">유급휴일</label>
                                </div>
                            </div>
                            <div class="form-inline">
                                <div class="form-floating">
                                    <select id="selHoliday2" class="form-select" onchange="setHoliday('unpaid',this.value)">
                                        <option value="">선택</option>
                                        <?php
                                        foreach ($dayArray as $key => $val) {
                                            echo "<option value=\"" . $val['type'] . "\">" . $val['text'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <label class="form-label">무급휴일</label>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mt-3">
                            <div class="form-inline align-self-center me-2">
                                <label class="form-label">출근</label>
                            </div>
                            <?php
                            foreach ($dayArray as $key => $val) {
                                echo "<div class=\"form-inline me-2\">
                                    <div class=\"form-floating\">
                                        <input type=\"time\" id=\"timefrom" . $val['type'] . "\" name=\"from_time\" class=\"form-control from-time\">
                                        <label class=\"timefrom" . $val['type'] . "\">" . $val['text'] . "</label>
                                    </div>
                                </div>";
                            }
                            ?>
                        </div>
                        <div class="input-group mt-3">
                            <div class="form-inline align-self-center me-2">
                                <label class="form-label">퇴근</label>
                            </div>
                            <?php
                            foreach ($dayArray as $key => $val) {
                                echo "<div class=\"form-inline me-2\">
                                    <div class=\"form-floating\">
                                        <input type=\"time\" id=\"timeto" . $val['type'] . "\" name=\"to_time\" class=\"form-control to-time\">
                                        <label for=\"timeto" . $val['type'] . "\">" . $val['text'] . "</label>
                                    </div>
                                </div>";
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
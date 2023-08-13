<?php
$grade = $_GET['grade'];
if (empty($grade)) {
    header('HTTP/2 403 Forbidden');
    exit;
}

if ($grade == "1") {
    $form = "<form id=\"question_form\" method=\"post\" action=\"reading_rac.php\">
    <input type=\"hidden\" name=\"flag\" value=\"r\" />
    <input type=\"hidden\" name=\"grade\" value=\"{$grade}\" />
    <h5 class=\"mb-2\">&quot;쑥 한 줌과 마늘 이십 개&quot; 이야기입니다. 잘 읽고 물음에 답해 보세요.</h5>
    <div class=\"card\">
        <div class=\"card-body\">
        <p>&nbsp;&nbsp;아주 먼 옛날, 저 높은 하늘에 환인이라는 하느님이 다스리고 있는 아름다운 하늘나라가 있었습니다. 환인에게는 환웅이라는 아들이 있었습니다. 환웅은 하늘 아래의 세계가 매우 아름답다고 생각하여 땅에 내려가 사람들을 다스리며 살고 싶어 했습니다. 그래서 아버지 환인은 환웅에게 땅에 내려가 살도록 ㉠했습니다.<br>
        &nbsp;&nbsp;바람 신, 비 신, 구름 신과 3천 명의 무리를 거느리고 땅으로 내려온 환웅은 사람들을 다스렸습니다. 그러던 어느 날 곰과 호랑이가 환웅을 찾아가 자기들도 지혜로운 사람이 되게 해 달라고 말했습니다. 환웅은 사람이 되고 싶다면 쑥 한 줌과 마늘 이십 개를 먹으면서 백 일 동안 햇빛을 보지 않으면 원하는 대로 사람이 될 것이라고 말해 주었습니다. 곰과 호랑이는 쑥과 마늘을 가지고 어두운 동굴로 들어갔습니다. 하지만 성질이 급한 호랑이는 참지 못하고 백 일이 되기도 전에 동굴 밖으로 뛰쳐나가고 말았습니다.<br>
        &nbsp;&nbsp;그러나 곰은 어떠한 어려움에도 참고 견디었습니다. 그렇게 참고 견딘 지 백 일 만에 곰은 드디어 예쁜 여자가 되었고, 환웅은 사람이 된 곰에게 '웅녀'라는 이름을 지어 주었습니다.<br>
        &nbsp;&nbsp;마침내 소원하던 사람이 된 웅녀는 아기를 갖고 싶어졌습니다. 그래서 웅녀는 날마다 신단수 앞에 나아가 아기를 갖게 해 달라고 ㉡빌었습니다. 환웅은 웅녀의 ㉢을 알고 몹시 딱하게 여겨 잠시 인간이 되어 웅녀를 아내로 맞이했습니다.<br>
        &nbsp;&nbsp;날이 지나고 달이 차자 환웅과 웅녀 사이에는 기다리던 아기가 태어났습니다. 환웅은 아이의 이름을 큰 사람이 되라는 의미에서 '단군'이라 했습니다. 하늘의 환웅과 땅의 웅녀 사이에서 태어난 단군은 어진 사람으로 성장했습니다.<br>
        &nbsp;&nbsp;단군은 사람들의 ㉣을 받으며 여러 마을을 다스리는 슬기로운 임금이 되었습니다. 단군왕검은 나라 이름을 조선이라 했습니다. 단군왕검은 천오백 년 동안 조선을 평화롭고 살기 좋은 나라로 잘 다스렸습니다.</p>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">1. 사람이 되려면 며칠동안 기다려야 하는지 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_1\" class=\"form-check-input\" name=\"answer1\" value=\"1\" />
            <label for=\"a1_1\" class=\"answer_chk1\">① 50일</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_2\" class=\"form-check-input\" name=\"answer1\" value=\"2\" />
            <label for=\"a1_2\" class=\"answer_chk1\">② 100일</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_3\" class=\"form-check-input\" name=\"answer1\" value=\"3\" />
            <label for=\"a1_3\" class=\"answer_chk1\">③ 300일</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a1_4\" class=\"form-check-input\" name=\"answer1\" value=\"4\" />
            <label for=\"a1_4\" class=\"answer_chk1\">④ 1년</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">2. '단군'이 세운 나라 이름은 무엇인지 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_1\" class=\"form-check-input\" name=\"answer2\" value=\"1\" />
            <label for=\"a2_1\" class=\"answer_chk2\">① 조선</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_2\" class=\"form-check-input\" name=\"answer2\" value=\"2\" />
            <label for=\"a2_2\" class=\"answer_chk2\">② 백제</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_3\" class=\"form-check-input\" name=\"answer2\" value=\"3\" />
            <label for=\"a2_3\" class=\"answer_chk2\">③ 고구려</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a2_4\" class=\"form-check-input\" name=\"answer2\" value=\"4\" />
            <label for=\"a2_4\" class=\"answer_chk2\">④ 신라</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">3. 곰과 호랑이가 사람이 되기 위해 필요한 조건이 아닌 것을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_1\" class=\"form-check-input\" name=\"answer3\" value=\"1\" />
            <label for=\"a3_1\" class=\"answer_chk3\">① 햇빛을 보지 말아야 한다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_2\" class=\"form-check-input\" name=\"answer3\" value=\"2\" />
            <label for=\"a3_2\" class=\"answer_chk3\">② 쑥과 마늘만 먹어야한다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_3\" class=\"form-check-input\" name=\"answer3\" value=\"3\" />
            <label for=\"a3_3\" class=\"answer_chk3\">③ 사람의 옷을 입어야 한다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a3_4\" class=\"form-check-input\" name=\"answer3\" value=\"4\" />
            <label for=\"a3_4\" class=\"answer_chk3\">④ 어려움을 참고 견뎌야한다.</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">4. ㉠에 들어갈 알맞은 말을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_1\" class=\"form-check-input\" name=\"answer4\" value=\"1\" />
            <label for=\"a4_1\" class=\"answer_chk4\">① 용서</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_2\" class=\"form-check-input\" name=\"answer4\" value=\"2\" />
            <label for=\"a4_2\" class=\"answer_chk4\">② 허락</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_3\" class=\"form-check-input\" name=\"answer4\" value=\"3\" />
            <label for=\"a4_3\" class=\"answer_chk4\">③ 인정</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a4_4\" class=\"form-check-input\" name=\"answer4\" value=\"4\" />
            <label for=\"a4_4\" class=\"answer_chk4\">④ 감시</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">5. ㉡'빌었습니다.'의 뜻으로 가장 알맞은 말을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_1\" class=\"form-check-input\" name=\"answer5\" value=\"1\" />
            <label for=\"a5_1\" class=\"answer_chk5\">① 소원을 빌다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_2\" class=\"form-check-input\" name=\"answer5\" value=\"2\" />
            <label for=\"a5_2\" class=\"answer_chk5\">② 물건을 빌리다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_3\" class=\"form-check-input\" name=\"answer5\" value=\"3\" />
            <label for=\"a5_3\" class=\"answer_chk5\">③ 돈을 구걸하다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a5_4\" class=\"form-check-input\" name=\"answer5\" value=\"4\" />
            <label for=\"a5_4\" class=\"answer_chk5\">④ 용서를 구하다.</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">6. ㉢과 ㉣에 들어갈 알맞은 말을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_1\" class=\"form-check-input\" name=\"answer6\" value=\"1\" />
            <label for=\"a6_1\" class=\"answer_chk6\">① ㉢ : 정체, ㉣ : 존경</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_2\" class=\"form-check-input\" name=\"answer6\" value=\"2\" />
            <label for=\"a6_2\" class=\"answer_chk6\">② ㉢ : 소원, ㉣ : 미움</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_3\" class=\"form-check-input\" name=\"answer6\" value=\"3\" />
            <label for=\"a6_3\" class=\"answer_chk6\">③ ㉢ : 정체, ㉣ : 질투</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a6_4\" class=\"form-check-input\" name=\"answer6\" value=\"4\" />
            <label for=\"a6_4\" class=\"answer_chk6\">④ ㉢ : 바람, ㉣ : 존경</label>
        </div>
    </div>
    <div class=\"text-end\"><button type=\"button\" id=\"btnSubmit\" class=\"btn btn-outline-primary\">정답제출 / 해설 및 정답확인</button></div>
    </form>";
} else if ($grade == "2") {
    $form = "<form id=\"question_form\" method=\"post\" action=\"reading_rac.php\">
    <input type=\"hidden\" name=\"flag\" value=\"r\" />
    <input type=\"hidden\" name=\"grade\" value=\"{$grade}\" />
    <h5 class=\"mb-2\">&quot;미래 이야기&quot; 입니다. 잘 읽은 후 물음에 답해 보세요.</h5>
    <div class=\"card\">
        <div class=\"card-body\">
        <p>&nbsp;&nbsp;어느 마을에 마음이 너그러운 부자가 살고 있었습니다. 그에게는 성실한 노예가 한 명 있었습니다. 어느 날 부자는 배 한 ㉠을 지어 노예에게 필요한 물건을 실어 주며 원하는 곳으로 가서 자유인으로 살라고 말했습니다. 노예는 주인의 말을 듣고 깜짝 놀랐지만 이내 기뻐하며 바다로 나갔습니다. 그러나 ㉡망망대해에서 그는 심한 풍랑을 만났습니다. 자유인이 되자마자 폭풍우 속에서 모든 것을 읽게 된 것입니다.<br>
        &nbsp;&nbsp;노예는 겨우 목숨만 건진 채 이름 모를 섬에 닿았습니다. 해안에 도착하자마자 그는 쓰러지고 말았습니다. 몸이 몹시 ㉢고단했던 것입니다. 한참 후 정신이 든 노예에게 놀라운 광경이 눈에 띄었습니다. 그 섬에는 커다란 도시가 있었습니다.<br>
        &nbsp;&nbsp;그러나 곰은 어떠한 어려움에도 참고 견디었습니다. 그렇게 참고 견딘 지 백 일 만에 곰은 드디어 예쁜 여자가 되었고, 환웅은 사람이 된 곰에게 '웅녀'라는 이름을 지어 주었습니다.<br>
        &nbsp;&nbsp;그러던 어느 날 신하를 불러서 자신을 왕으로 섬기게 된 이유를 물었습니다. 신하는 이렇게 대답했습니다.<br>
        &nbsp;&nbsp;&quot;우리는 매해 한 번 씩 바다를 건너온 사람을 왕으로 모시는 전통이 있습니다. 그래서 당신을 우리들의 왕으로 모셨던 것입니다. 그러나 일 년 후에 당신은 반대편에 있는 죽음의 섬으로 가야 합니다. 그곳에는 생명체가 존재하지 않으며 풀도, 나무도, 동물도 없습니다.&quot;<br>
        &nbsp;&nbsp;신하의 이야기를 듣고 난 노예는 생각에 잠겼습니다. 그리고 마음속으로 뭔가를 결심했습니다. 노예는 왕으로 있는 동안 여유 시간이 생길 때마다 건너편의 죽음의 섬으로 갔습니다. 그는 그 죽음의 섬에 나무를 ㉣ 샘을 팠습니다. 동물과 식물이 살 수 있도록 섬을 가꿔 나갔습니다. 밭도 일구고 꽃과 과일나무도 심었습니다. 그렇게 일 년이 지나갔습니다.<br>
        &nbsp;&nbsp;예정된 대로 그는 이 섬에 왔을 때처럼 맨몸으로 죽음의 섬으로 쫓겨났습니다. 그러나 그 죽음의 섬은 이제 더 이상 죽음의 땅이 아니었습니다. 온갖 생물이 자라고 열매와 곡식이 풍성한 ㉤이 되어 있었던 것입니다.
        </p>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">1. ㉠에 들어갈 알맞은 말을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_1\" class=\"form-check-input\" name=\"answer1\" value=\"1\" />
            <label for=\"a1_1\" class=\"answer_chk1\">① 채</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_2\" class=\"form-check-input\" name=\"answer1\" value=\"2\" />
            <label for=\"a1_2\" class=\"answer_chk1\">② 척</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_3\" class=\"form-check-input\" name=\"answer1\" value=\"3\" />
            <label for=\"a1_3\" class=\"answer_chk1\">③ 그루</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a1_4\" class=\"form-check-input\" name=\"answer1\" value=\"4\" />
            <label for=\"a1_4\" class=\"answer_chk1\">④ 개</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">2. ㉡'망망대해'의 뜻으로 알맞은 것을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_1\" class=\"form-check-input\" name=\"answer2\" value=\"1\" />
            <label for=\"a2_1\" class=\"answer_chk2\">① 매우 깊은 바다</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_2\" class=\"form-check-input\" name=\"answer2\" value=\"2\" />
            <label for=\"a2_2\" class=\"answer_chk2\">② 사람이 어찌할 수 없는 일</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_3\" class=\"form-check-input\" name=\"answer2\" value=\"3\" />
            <label for=\"a2_3\" class=\"answer_chk2\">③ 한없이 크고 넓은 바다</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a2_4\" class=\"form-check-input\" name=\"answer2\" value=\"4\" />
            <label for=\"a2_4\" class=\"answer_chk2\">④ 엄청난 재앙</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">3. ㉢'고단하다'의 의미가 아닌 것을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_1\" class=\"form-check-input\" name=\"answer3\" value=\"1\" />
            <label for=\"a3_1\" class=\"answer_chk3\">① 피곤하다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_2\" class=\"form-check-input\" name=\"answer3\" value=\"2\" />
            <label for=\"a3_2\" class=\"answer_chk3\">② 지친다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_3\" class=\"form-check-input\" name=\"answer3\" value=\"3\" />
            <label for=\"a3_3\" class=\"answer_chk3\">③ 졸리다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a3_4\" class=\"form-check-input\" name=\"answer3\" value=\"4\" />
            <label for=\"a3_4\" class=\"answer_chk3\">④ 힘이 든다.</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">4. (독해력) 섬의 주민들이 노예를 왕으로 삼은 이유로 적절한 것을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_1\" class=\"form-check-input\" name=\"answer4\" value=\"1\" />
            <label for=\"a4_1\" class=\"answer_chk4\">① 거친 바다를 지나 올만큼 용기 있는 사람이기 때문에</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_2\" class=\"form-check-input\" name=\"answer4\" value=\"2\" />
            <label for=\"a4_2\" class=\"answer_chk4\">② 자유인의 신분이 되어 축하해 주기 위해</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_3\" class=\"form-check-input\" name=\"answer4\" value=\"3\" />
            <label for=\"a4_3\" class=\"answer_chk4\">③ 노예의 얼굴이 왕처럼 잘 생겼기 때문에</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a4_4\" class=\"form-check-input\" name=\"answer4\" value=\"4\" />
            <label for=\"a4_4\" class=\"answer_chk4\">④ 그 섬의 전통이었기 때문에</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">5. 신하의 말을 들은 노예가 결심한 것으로 적절한 것을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_1\" class=\"form-check-input\" name=\"answer5\" value=\"1\" />
            <label for=\"a5_1\" class=\"answer_chk5\">① 죽음의 땅을 살기 좋은 것으로 만들어야겠다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_2\" class=\"form-check-input\" name=\"answer5\" value=\"2\" />
            <label for=\"a5_2\" class=\"answer_chk5\">② 왕의 자리에서 쫓겨나지 않도록 잘 다스려야겠다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_3\" class=\"form-check-input\" name=\"answer5\" value=\"3\" />
            <label for=\"a5_3\" class=\"answer_chk5\">③ 일 년이 지나면 다시 나의 주인에게로 돌아가야겠다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a5_4\" class=\"form-check-input\" name=\"answer5\" value=\"4\" />
            <label for=\"a5_4\" class=\"answer_chk5\">④ 달력을 없애서 시간이 지난 것을 사람들이 모르게 해야겠다.</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">6. ㉣과 ㉤에 들어갈 알맞은 말을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_1\" class=\"form-check-input\" name=\"answer6\" value=\"1\" />
            <label for=\"a6_1\" class=\"answer_chk6\">① ㉣ : 베고, ㉤ : 낙원</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_2\" class=\"form-check-input\" name=\"answer6\" value=\"2\" />
            <label for=\"a6_2\" class=\"answer_chk6\">② ㉣ : 베고, ㉤ : 사막</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_3\" class=\"form-check-input\" name=\"answer6\" value=\"3\" />
            <label for=\"a6_3\" class=\"answer_chk6\">③ ㉣ : 심고, ㉤ : 사막</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a6_4\" class=\"form-check-input\" name=\"answer6\" value=\"4\" />
            <label for=\"a6_4\" class=\"answer_chk6\">④ ㉣ : 심고, ㉤ : 낙원</label>
        </div>
    </div>
    <div class=\"text-end\"><button type=\"button\" id=\"btnSubmit\" class=\"btn btn-outline-primary\">정답제출 / 해설 및 정답확인</button></div>
    </form>";
} else if ($grade == "3") {
    $form = "<form id=\"question_form\" method=\"post\" action=\"reading_rac.php\">
    <input type=\"hidden\" name=\"flag\" value=\"r\" />
    <input type=\"hidden\" name=\"grade\" value=\"{$grade}\" />
    <h5 class=\"mb-2\">&quot;맹자의 어머니&quot; 이야기입니다. 잘 읽은 후 물음에 답해 보세요.</h5>
    <div class=\"card\">
        <div class=\"card-body\">
        <p><첫 번째 이야기><br>
        &nbsp;&nbsp;맹자는 가난한 집안에서 태어났습니다. 아버지가 일찍 돌아가신 뒤 ㉠홀어머니 손에서 자랐습니다. 맹자의 집은 처음에는 공동묘지 근처였습니다. 어린 맹자는 묘지에서 장례를 치르는 모습을 보고 ㉡을(를) 하거나 관을 묻는 흉내를 내며 놀았습니다. 아들의 노는 모습을 지켜본 맹자의 어머니는 이곳이 아이를 키울 곳이 못 된다고 생각했습니다. 그래서 시장 근처로 이사를 갔습니다. 그러자 이번에는 어린 맹자가 상인들이 물건을 사고파는 흉내를 내면서 놀았습니다.<br>
        &nbsp;&nbsp;'이 곳도 아이를 기를 곳이 못 된다.'<br>
        &nbsp;&nbsp;이렇게 생각한 맹자의 어머니는 다시 서당 근처로 이사를 갔습니다. 맹자는 서당에서 학생들이 공부하는 모습을 보고 그 흉내를 내면서 놀았습니다.<br>
        &nbsp;&nbsp;그러던 어느 날 신하를 불러서 자신을 왕으로 섬기게 된 이유를 물었습니다. 신하는 이렇게 대답했습니다.<br>
        &nbsp;&nbsp;'이곳이야말로 아이를 가르칠 만한 곳이다.'<br>
        &nbsp;&nbsp;맹자의 어머니는 이렇게 생각해 그곳에서 살았습니다.<br><br>
        <두 번째 이야기><br>
        &nbsp;&nbsp;소년 시절 유학을 갔던 맹자가 어느 날 집으로 돌아왔습니다. 맹자의 어머니는 베를 짜고 있다가 맹자에게 물었습니다.<br>
        &nbsp;&nbsp;&quot;네 공부가 어느 정도 나아졌느냐?&quot;<br>
        &nbsp;&nbsp;&quot;그대로입니다.&quot;<br>
        &nbsp;&nbsp;그러자 맹자의 어머니는 칼로 베를 끊어버렸습니다. 맹자가 벌벌 떨면서 그 이유를 묻자 맹자의 어머니가 말했습니다.<br>
        &nbsp;&nbsp;&quot;네가 공부를 그만두는 것은 내가 베를 끊는 것과 같다. ㉢는 학문에 힘써 이름을 날리고, 모르는 것은 물어 지식을 넓혀야 한다. 그래야만 몸과 마음이 편안해지고, 세상에 나가서는 위험을 멀리하게 된다. 그런데 지금 네가 공부를 그만두었으니 앞으로는 심부름이나 하면서 생계를 걱정할 것이다. 베를 짜서 생계를 꾸려 나가다가 ㉣에 그만두는 것과 무엇이 다르겠느냐? 여자가 생업을 그만두거나 남자가 덕을 닦다가 타락하면 도둑이 되거나 남의 심부름꾼이 될 뿐이다.&quot;<br>
        &nbsp;&nbsp;어머니의 말에 충격을 받은 맹자는 그때부터 쉬지 않고 학문을 쌓았으며, 공자의 손자인 자사의 문하에 들어가 마침내 천하의 유명한 학자가 되었습니다.
        </p>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">1. 첫 번째 이야기와 관련된 한자성어를 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_1\" class=\"form-check-input\" name=\"answer1\" value=\"1\" />
            <label for=\"a1_1\" class=\"answer_chk1\">① 대기만성(大器晩成)</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_2\" class=\"form-check-input\" name=\"answer1\" value=\"2\" />
            <label for=\"a1_2\" class=\"answer_chk1\">② 온고지신(溫故知新)</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_3\" class=\"form-check-input\" name=\"answer1\" value=\"3\" />
            <label for=\"a1_3\" class=\"answer_chk1\">③ 홍익인간(弘益人間)</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a1_4\" class=\"form-check-input\" name=\"answer1\" value=\"4\" />
            <label for=\"a1_4\" class=\"answer_chk1\">④ 맹모삼천지교(孟母三遷之敎)</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">2. ㉠'홀어머니'의 의미로 적절한 것을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_1\" class=\"form-check-input\" name=\"answer2\" value=\"1\" />
            <label for=\"a2_1\" class=\"answer_chk2\">① 자식이 한 명밖에 없는 어머니</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_2\" class=\"form-check-input\" name=\"answer2\" value=\"2\" />
            <label for=\"a2_2\" class=\"answer_chk2\">② 베를 짜며 생계를 유지하는 여자</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_3\" class=\"form-check-input\" name=\"answer2\" value=\"3\" />
            <label for=\"a2_3\" class=\"answer_chk2\">③ 남편을 잃고 혼자 지내는 여자</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a2_4\" class=\"form-check-input\" name=\"answer2\" value=\"4\" />
            <label for=\"a2_4\" class=\"answer_chk2\">④ 교육에 관심이 많은 어머니</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">3. ㉡에 들어갈 적절한 단어를 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_1\" class=\"form-check-input\" name=\"answer3\" value=\"1\" />
            <label for=\"a3_1\" class=\"answer_chk3\">① 곡</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_2\" class=\"form-check-input\" name=\"answer3\" value=\"2\" />
            <label for=\"a3_2\" class=\"answer_chk3\">② 공부</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_3\" class=\"form-check-input\" name=\"answer3\" value=\"3\" />
            <label for=\"a3_3\" class=\"answer_chk3\">③ 장난</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a3_4\" class=\"form-check-input\" name=\"answer3\" value=\"4\" />
            <label for=\"a3_4\" class=\"answer_chk3\">④ 행진</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">4. 맹자의 어머니가 이사를 간 이유로 적절한 것을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_1\" class=\"form-check-input\" name=\"answer4\" value=\"1\" />
            <label for=\"a4_1\" class=\"answer_chk4\">① 맹자에게 친구를 만들어 주기 위해</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_2\" class=\"form-check-input\" name=\"answer4\" value=\"2\" />
            <label for=\"a4_2\" class=\"answer_chk4\">② 직장을 구하기 쉬운 곳을 찾기 위해</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_3\" class=\"form-check-input\" name=\"answer4\" value=\"3\" />
            <label for=\"a4_3\" class=\"answer_chk4\">③ 집값이 싼 곳을 찾기 위해</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a4_4\" class=\"form-check-input\" name=\"answer4\" value=\"4\" />
            <label for=\"a4_4\" class=\"answer_chk4\">④ 적절한 교육 환경을 찾기 위해</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">5. 맹자의 어머니가 베를 끊은 이유로 적절한 것을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_1\" class=\"form-check-input\" name=\"answer5\" value=\"1\" />
            <label for=\"a5_1\" class=\"answer_chk5\">① 맹자와 이야기를 하다가 베를 잘못 짰기 때문에</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_2\" class=\"form-check-input\" name=\"answer5\" value=\"2\" />
            <label for=\"a5_2\" class=\"answer_chk5\">② 맹자에게 학문의 중요성을 이야기해주기 위해서</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_3\" class=\"form-check-input\" name=\"answer5\" value=\"3\" />
            <label for=\"a5_3\" class=\"answer_chk5\">③ 공부를 한 맹자가 어머니를 무시했기 때문에</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a5_4\" class=\"form-check-input\" name=\"answer5\" value=\"4\" />
            <label for=\"a5_4\" class=\"answer_chk5\">④ 맹자가 베보다 더 좋은 옷감을 사왔기 때문에</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">6. ㉢과 ㉣에 들어갈 알맞은 말을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_1\" class=\"form-check-input\" name=\"answer6\" value=\"1\" />
            <label for=\"a6_1\" class=\"answer_chk6\">① ㉢ : 군자, ㉣ : 결국</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_2\" class=\"form-check-input\" name=\"answer6\" value=\"2\" />
            <label for=\"a6_2\" class=\"answer_chk6\">② ㉢ : 군자, ㉣ : 중도</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_3\" class=\"form-check-input\" name=\"answer6\" value=\"3\" />
            <label for=\"a6_3\" class=\"answer_chk6\">③ ㉢ : 소인배, ㉣ : 중도</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a6_4\" class=\"form-check-input\" name=\"answer6\" value=\"4\" />
            <label for=\"a6_4\" class=\"answer_chk6\">④ ㉢ : 소인배, ㉣ : 결국</label>
        </div>
    </div>
    <div class=\"text-end\"><button type=\"button\" id=\"btnSubmit\" class=\"btn btn-outline-primary\">정답제출 / 해설 및 정답확인</button></div>
    </form>";
} else if ($grade == "4") {
    $form = "<form id=\"question_form\" method=\"post\" action=\"reading_rac.php\">
    <input type=\"hidden\" name=\"flag\" value=\"r\" />
    <input type=\"hidden\" name=\"grade\" value=\"{$grade}\" />
    <h5 class=\"mb-2\">&quot;세계 어린이와 함께 배우는 시민학교 환경&quot; 이야기입니다. 잘 읽은 후 물음에 답해 보세요.</h5>
    <div class=\"card\">
        <div class=\"card-body\">
        <p><왜 환경오염이 생기는 걸까요?><br>
        &nbsp;&nbsp;모든 생물은 자연을 통해 생명을 유지하고 있습니다. 그런데 한 세기 전부터 사람들은 여러 가지 기계를 만들어내 자원을 고갈시키고 자연에 함부로 폐기물을 버리고 있습니다. 그 폐기물들은 자연의 순환을 혼란에 빠뜨리고 환경을 오염시킵니다. 그렇게 되면 환경이 변해서 생물도 혼란을 겪고 목숨을 위협받게 됩니다.<br><br>
        <자원을 왜 아껴 써야 할까요?><br>
        &nbsp;&nbsp;150년 전부터 인간들은 불을 밝히고 난방을 하고 이동을 하기 위해 점점 더 많은 석탄과 석유 연료를 사용하고 있습니다. 그런데 지구의 자원은 한정돼 있어 언젠가는 바닥이 드러날 것입니다. 땅 속에 매장되어 있는 석유의 반을 이미 써 버렸고, 지구의 있는 물중에서 1%만이 우리가 마실 수 있는 식수입니다. 석유, 가스, 석탄은 연소될 때 오염 가스를 뿜어냅니다. 오염 가스 중에는 ㉠처럼 대기 중의 태양 광선을 가두어 지구 표면의 온도를 높이는 가스도 있습니다. 그렇게 되면 극지방과 산에 있는 빙산이 녹아 바다의 수면이 높아지고, 우리가 살 수 있는 땅이 좁아지게 됩니다.<br><br>
        <이로운 폐기물과 해로운 폐기물><br>
        &nbsp;&nbsp;폐기물에는 두 종류가 있습니다. 식물과 동물에서 나온 천연 배설물은 흙이나 물속에 그대로 남아 있지 않습니다. 버섯, 박테리아 같은 유기체가 그것을 먹어 치우기 때문이지요.<br>
        &nbsp;&nbsp;이와는 달리 인공 폐기물이라는 것도 있습니다. 인공 폐기물은 분해되지 않고 하늘과 바다와 땅에 ㉡됩니다. 왜냐하면 유리, 플라스틱, 철 등을 먹어 치울 수 있는 생물은 없기 때문입니다.<br><br>
        <무엇이 지구의 환경을 위협하고 있을까요?><br>
        &nbsp;&nbsp;사람들은 자신에게 필요한 물건을 손쉽게 마련하기 위해 기술을 발전시켰습니다. 그런데 그로 인해 일어날 일에 대해서는 신중히 생각하지 않았습니다. 특히, 부유한 나라에 사는 사람들은 더 많은 이익을 남기기 위해 비용을 ㉢ 들이고 ㉣ 물건을 만들어 내려고 했습니다. 그래서 미래의 지구와 후손들에게 큰 피해를 입힐 수 있는 방법을 서슴지 않고 사용했습니다.
        </p>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">1. '돈이나 물건 따위가 거의 없어져 매우 귀해진다.'의 의미를 가진 단어를 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_1\" class=\"form-check-input\" name=\"answer1\" value=\"1\" />
            <label for=\"a1_1\" class=\"answer_chk1\">① 유지</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_2\" class=\"form-check-input\" name=\"answer1\" value=\"2\" />
            <label for=\"a1_2\" class=\"answer_chk1\">② 고갈</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_3\" class=\"form-check-input\" name=\"answer1\" value=\"3\" />
            <label for=\"a1_3\" class=\"answer_chk1\">③ 순환</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a1_4\" class=\"form-check-input\" name=\"answer1\" value=\"4\" />
            <label for=\"a1_4\" class=\"answer_chk1\">④ 혼란</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">2. 연소될 때 오염 가스를 뿜어내는 자원이 아닌 것을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_1\" class=\"form-check-input\" name=\"answer2\" value=\"1\" />
            <label for=\"a2_1\" class=\"answer_chk2\">① 석유</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_2\" class=\"form-check-input\" name=\"answer2\" value=\"2\" />
            <label for=\"a2_2\" class=\"answer_chk2\">② 석탄</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_3\" class=\"form-check-input\" name=\"answer2\" value=\"3\" />
            <label for=\"a2_3\" class=\"answer_chk2\">③ 방사능</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a2_4\" class=\"form-check-input\" name=\"answer2\" value=\"4\" />
            <label for=\"a2_4\" class=\"answer_chk2\">④ 가스</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">3. ㉠에 들어갈 적절한 단어를 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_1\" class=\"form-check-input\" name=\"answer3\" value=\"1\" />
            <label for=\"a3_1\" class=\"answer_chk3\">① 감옥</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_2\" class=\"form-check-input\" name=\"answer3\" value=\"2\" />
            <label for=\"a3_2\" class=\"answer_chk3\">② 창문</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_3\" class=\"form-check-input\" name=\"answer3\" value=\"3\" />
            <label for=\"a3_3\" class=\"answer_chk3\">③ 온실</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a3_4\" class=\"form-check-input\" name=\"answer3\" value=\"4\" />
            <label for=\"a3_4\" class=\"answer_chk3\">④ 외투</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">4. ㉡에 들어갈 적절한 단어를 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_1\" class=\"form-check-input\" name=\"answer4\" value=\"1\" />
            <label for=\"a4_1\" class=\"answer_chk4\">① 축적</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_2\" class=\"form-check-input\" name=\"answer4\" value=\"2\" />
            <label for=\"a4_2\" class=\"answer_chk4\">② 분산</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_3\" class=\"form-check-input\" name=\"answer4\" value=\"3\" />
            <label for=\"a4_3\" class=\"answer_chk4\">③ 소각</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a4_4\" class=\"form-check-input\" name=\"answer4\" value=\"4\" />
            <label for=\"a4_4\" class=\"answer_chk4\">④ 연소</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">5. 사람들이 기술을 발전시키는 동안 신중하게 생각하지 않은 점으로 적절한 것을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_1\" class=\"form-check-input\" name=\"answer5\" value=\"1\" />
            <label for=\"a5_1\" class=\"answer_chk5\">① 좀 더 많은 물건을 만드는 방법은 무엇인가</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_2\" class=\"form-check-input\" name=\"answer5\" value=\"2\" />
            <label for=\"a5_2\" class=\"answer_chk5\">② 가난한 사람들에게 베푸는 방법은 무엇인가</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_3\" class=\"form-check-input\" name=\"answer5\" value=\"3\" />
            <label for=\"a5_3\" class=\"answer_chk5\">③ 기술의 발전이 지구에 어떤 영향을 미칠 것인가</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a5_4\" class=\"form-check-input\" name=\"answer5\" value=\"4\" />
            <label for=\"a5_4\" class=\"answer_chk5\">④ 다른 사람과 조화롭게 살려면 어떻게 해야 하는가</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">6. ㉢과 ㉣에 들어갈 알맞은 말을 찾아보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_1\" class=\"form-check-input\" name=\"answer6\" value=\"1\" />
            <label for=\"a6_1\" class=\"answer_chk6\">① ㉢ : 많이, ㉣ : 빨리</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_2\" class=\"form-check-input\" name=\"answer6\" value=\"2\" />
            <label for=\"a6_2\" class=\"answer_chk6\">② ㉢ : 많이, ㉣ : 천천히</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_3\" class=\"form-check-input\" name=\"answer6\" value=\"3\" />
            <label for=\"a6_3\" class=\"answer_chk6\">③ ㉢ : 적게, ㉣ : 천천히</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a6_4\" class=\"form-check-input\" name=\"answer6\" value=\"4\" />
            <label for=\"a6_4\" class=\"answer_chk6\">④ ㉢ : 적게, ㉣ : 빨리</label>
        </div>
    </div>
    <div class=\"text-end\"><button type=\"button\" id=\"btnSubmit\" class=\"btn btn-outline-primary\">정답제출 / 해설 및 정답확인</button></div>
    </form>";
} else if ($grade == "5") {
    $form = "<form id=\"question_form\" method=\"post\" action=\"reading_rac.php\">
    <input type=\"hidden\" name=\"flag\" value=\"r\" />
    <input type=\"hidden\" name=\"grade\" value=\"{$grade}\" />
    <h5 class=\"mb-2\">&quot;우리 조상들의 의식주이야기&quot; 입니다. 잘 읽은 후 물음에 답해 보세요.</h5>
    <div class=\"card\">
        <div class=\"card-body\">
        <p>&quot;엄마, 고추장이 뭐예요?&quot;<br>
        &quot;메주 가루, 찹쌀풀, 고춧가루, 소금을 넣어 만든 장을 고추장이라고 하지.&quot;<br>
        &quot;고추장은 언제 만들어졌어요?&quot;<br>
        &quot;고추가 조선 시대에 들어왔으니까 조선 시대부터 만들어졌지.&quot;<br>
        &nbsp;&nbsp;(A)[고추장 하면 제일 먼저 떠오르는 곳이 순창입니다. 순창 고추장은 태조 이성계 때문에 유명해졌습니다.<br>
        &nbsp;&nbsp;태조 이성계는 명당자리에 궁궐을 짓기 위해 무학대사를 찾아 길을 나섰습니다. 당시 무학대사는 순창에 있는 만일사라는 절에 머무르고 있었습니다. 먼 길을 달려온 태조는 너무너무 배가 고팠습니다. 그래서 염치불구하고 농가나 들어가 점심을 얻어먹기를 청했습니다.<br>
        &nbsp;&nbsp;그러자 집주인은 대접할 게 마땅치 않은 듯 집에서 담근 고추장을 별미라고 내놓았습니다. 태조는 밥에 고추장을 ㉮쓱싹쓱싹 비벼 먹었는데, 두고두고 그 맛을 잊을 수가 없었습니다.<br>
        &nbsp;&nbsp;얼마 후, 한양으로 돌아간 태조는 순창 현감에게 편지를 보내 고추장을 왕실에 진상하라고 명령했다고 합니다.]<br>
        &quot;순창은 물 좋기로 소문난 고장이야.&quot;<br>
        &quot;물이 어떻게 좋은데요?&quot;<br>
        &quot;물속에 철분이 많이 들어 있어서 이 물로 고추장을 만들면 단맛이 아주 강해.&quot;<br>
        &nbsp;&nbsp;순창 사람들은 매년 입동을 전후로 메주를 만들어 왔습니다. 콩을 삶아 둥근 사발 모양의 메주를 만들고 그늘진 곳에서 한 달 정도 말립니다. 그러면 노란 곰팡이가 생깁니다.<br>
        &nbsp;&nbsp;곰팡이가 핀 메주는 다시 쪼개 햇볕에 잘 말린 다음, 빻아서 가루를 냅니다. 이렇게 만든 메주 가루를 가지고 매년 정월에 고추장을 담급니다.<br>
        &nbsp;&nbsp;순창 사람들은 고춧가루를 만들기 위해 ㉠찹쌀한말, 고춧가루넉되, 메주가루두되를 준비합니다. 그런 다음 끓여서 식힌 물에 메주 가루를 버무려 하룻밤을 재웁니다.<br>
        &nbsp;&nbsp;이튿날 찹쌀을 물에 불려 시루에 찐 뒤, 절구에 담아 메로 치면서 메주 가루를 부어 골고루 잘 섞습니다. 여기다 간장을 넣고 살살 뒤적입니다. 그런 다음 찌꺼기는 체로 걸러 버리고 고춧가루를 넣고 소금으로 간을 하면 됩니다. 고추장을 항아리에 담아 볕이 잘 드는 곳에 놓아두고 나무주걱으로 잘 저어 주면 맛깔스런 고추장이 됩니다.<br>
        &nbsp;&nbsp;요즘에는 보리고추장, 수수고추장, 약고추장, 고구마고추장, 사과고추장 등 고추장의 종류가 다양합니다.<br><br>
        &nbsp;&nbsp;&lt;출처: 표시정, 우리 조상들의 의식주 이야기&gt;
        </p>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">1. 다음 중 고추장의 재료로 사용되지 않는 것을 골라보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_1\" class=\"form-check-input\" name=\"answer1\" value=\"1\" />
            <label for=\"a1_1\" class=\"answer_chk1\">① 메주</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_2\" class=\"form-check-input\" name=\"answer1\" value=\"2\" />
            <label for=\"a1_2\" class=\"answer_chk1\">② 고춧가루</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_3\" class=\"form-check-input\" name=\"answer1\" value=\"3\" />
            <label for=\"a1_3\" class=\"answer_chk1\">③ 찹쌀 찌꺼기</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a1_4\" class=\"form-check-input\" name=\"answer1\" value=\"4\" />
            <label for=\"a1_4\" class=\"answer_chk1\">④ 소금</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">2. 다음 중 단어의 설명이 틀린 것을 골라 표시하세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_1\" class=\"form-check-input\" name=\"answer2\" value=\"1\" />
            <label for=\"a2_1\" class=\"answer_chk2\">① 명당자리 : 풍수지리에서 말하는 좋은 자리</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_2\" class=\"form-check-input\" name=\"answer2\" value=\"2\" />
            <label for=\"a2_2\" class=\"answer_chk2\">② 염치 : 깨끗하여 부끄러움을 아는 마음</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_3\" class=\"form-check-input\" name=\"answer2\" value=\"3\" />
            <label for=\"a2_3\" class=\"answer_chk2\">③ 별미 : 평소에 먹는 밑반찬</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a2_4\" class=\"form-check-input\" name=\"answer2\" value=\"4\" />
            <label for=\"a2_4\" class=\"answer_chk2\">④ 진상 : 지방에서 나는 물건을 임금에게 바침</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">3. 순창 고추장이 다른 고추장보다 맛있는 이유를 골라보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_1\" class=\"form-check-input\" name=\"answer3\" value=\"1\" />
            <label for=\"a3_1\" class=\"answer_chk3\">① 곰팡이가 핀 메주로 만들어서</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_2\" class=\"form-check-input\" name=\"answer3\" value=\"2\" />
            <label for=\"a3_2\" class=\"answer_chk3\">② 간장을 넣어 만들었으므로</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_3\" class=\"form-check-input\" name=\"answer3\" value=\"3\" />
            <label for=\"a3_3\" class=\"answer_chk3\">③ 물속에 철분이 많이 들어 있어서 고추장을 만들면 단맛이 아주 강하므로</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a3_4\" class=\"form-check-input\" name=\"answer3\" value=\"4\" />
            <label for=\"a3_4\" class=\"answer_chk3\">④ 고춧가루를 넣고 소금으로 간을 해 만들었으므로</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">4. 다음 빈칸에 들어갈 말로 맞는 것을 골라보세요.</h6>
    <div class=\"border mb-2\">
        <p class=\"text-center ms-1\">고추장 만드는 과정<br>
        메주 만들기 → 메주 말리기 → (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) → 찹쌀 섞기 → 고춧가루 및 양념</p>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_1\" class=\"form-check-input\" name=\"answer4\" value=\"1\" />
            <label for=\"a4_1\" class=\"answer_chk4\">① 메주 빻아 가르내기</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_2\" class=\"form-check-input\" name=\"answer4\" value=\"2\" />
            <label for=\"a4_2\" class=\"answer_chk4\">② 시루에 찌기</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_3\" class=\"form-check-input\" name=\"answer4\" value=\"3\" />
            <label for=\"a4_3\" class=\"answer_chk4\">③ 콩 삶기</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a4_4\" class=\"form-check-input\" name=\"answer4\" value=\"4\" />
            <label for=\"a4_4\" class=\"answer_chk4\">④ 소금으로 간하기</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">5. 밑줄 친 ㉮의 ‘쓱싹쓱싹’과 같은 종류의 낱말에 표시해 보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_1\" class=\"form-check-input\" name=\"answer5\" value=\"1\" />
            <label for=\"a5_1\" class=\"answer_chk5\">① 시냇물이 졸졸 흘러갑니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_2\" class=\"form-check-input\" name=\"answer5\" value=\"2\" />
            <label for=\"a5_2\" class=\"answer_chk5\">② 캥거루가 껑충껑충 뛰어갑니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_3\" class=\"form-check-input\" name=\"answer5\" value=\"3\" />
            <label for=\"a5_3\" class=\"answer_chk5\">③ 보름달이 휘영청 밝았습니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a5_4\" class=\"form-check-input\" name=\"answer5\" value=\"4\" />
            <label for=\"a5_4\" class=\"answer_chk5\">④ 아기가 아장아장 걸어옵니다.</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">6. 밑줄 친 ㉮의 ‘쓱싹쓱싹’과 같은 종류의 낱말에 표시해 보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_1\" class=\"form-check-input\" name=\"answer6\" value=\"1\" />
            <label for=\"a6_1\" class=\"answer_chk6\">① ㉢ : 많이, ㉣ : 빨리</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_2\" class=\"form-check-input\" name=\"answer6\" value=\"2\" />
            <label for=\"a6_2\" class=\"answer_chk6\">② ㉢ : 많이, ㉣ : 천천히</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_3\" class=\"form-check-input\" name=\"answer6\" value=\"3\" />
            <label for=\"a6_3\" class=\"answer_chk6\">③ ㉢ : 적게, ㉣ : 천천히</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a6_4\" class=\"form-check-input\" name=\"answer6\" value=\"4\" />
            <label for=\"a6_4\" class=\"answer_chk6\">④ ㉢ : 적게, ㉣ : 빨리</label>
        </div>
    </div>
    <div class=\"text-end\"><button type=\"button\" id=\"btnSubmit\" class=\"btn btn-outline-primary\">정답제출 / 해설 및 정답확인</button></div>
    </form>";
} else if ($grade == "6") {
    $form = "<form id=\"question_form\" method=\"post\" action=\"reading_rac.php\">
    <input type=\"hidden\" name=\"flag\" value=\"r\" />
    <input type=\"hidden\" name=\"grade\" value=\"{$grade}\" />
    <h5 class=\"mb-2\">&quot;어린 과학자를 위한 몸 이야기&quot; 입니다. 잘 읽은 후 물음에 답해 보세요.</h5>
    <div class=\"card\">
        <div class=\"card-body\">
        <p>&nbsp;&nbsp;춥고 건조한 겨울엔 실내에서 ㉠난방기와 가습기를 틉니다. 겨울에 마스크를 하는 것과 날씨가 건조할 때 젖먹이 아기 방에 ㉡젖은 빨래를 널어 두는 것도 같은 까닭이에요. 코를 통해 차가운 공기가 들어오면 ㉢비갑개는 열을 내뿜어서 따뜻한 공기로 바꿔 ㉣허파로 들여보낸답니다. 또 너무 건조한 공기가 들어오면 적당하게 습기를 머금은 공기로 바꿔서 허파로 보내지요.<br>
        &nbsp;&nbsp;다시 말해서 차가운 공기는 데우고, 더운 공기는 식혀서 허파에 들게 한다는 뜻입니다. 아마 허파가 너무 차거나 더운 공기를 만나면 다치기 쉬우니까 그렇겠지요?<br>
        &nbsp;&nbsp;그걸 알면 인종에 따라 코가 왜 다르게 생겼는지도 잘 알 수 있답니다. 이란이나 이라크처럼 건조한 곳에 사는 사람이나 러시아처럼 추운 데 사는 사람들의 코는 어떻게 생겼을까요? 이라크는 공기가 메마른 곳이고, 또 러시아는 아주 추운 곳이어서, 그런 데서 오랫동안 살아온 사람들은 자연히 콧등이 높고 긴 코주부가 되었답니다. 그런가 하면 아프리카처럼 더운 열대 지방 사람들은 코가 짧고 작지요. 습기가 많고 온도가 높은 곳에 사는 사람들의 콧등이 높을 까닭이 없으니까요. 코가 납작하거나 우뚝 솟은 것도 다 환경에 따라 적응한 결과라는 것, 잘 알겠지요?<br>
        &nbsp;&nbsp;만약에 사람이 냄새를 맡지 못하면 어떤 일이 일어날까요? 아마 음식 맛을 조금도 모를 게 분명합니다. 혀로 맛을 느끼는 것과는 분명히 다르니까요. 솥에 안친 밥이 다 타고 있는데 그걸 모르고 있다면 큰일이겠지요? 아무튼 콧속에는 60만 개가 넘는 후각 세포가 퍼져 있어서 온갖 냄새를 알아낸답니다. 더욱 놀라운 것은 사람이 1만 가지나 되는 냄새를 구별할 수 있다는 거에요.<br>
        &nbsp;&nbsp;코를 이야기하는 데 감기를 빼놓을 수는 없습니다. 아무리 콧대 높은 사람도 감기는 걸리는 법이니까요. 코감기에 걸려 수시로 코를 풀면서 &quot;도대체 이 많은 콧물이 다 어디서 나오는 거야?&quot; 하며 불평해 본 친구도 있을 거예요.<br>
        &nbsp;&nbsp;감기 바이러스가 코로 들어와 상처를 내면 우리 몸은 자연스럽게 나쁜 바이러스를 없애려고 듭니다. (&nbsp;&nbsp;A&nbsp;&nbsp;) 평소보다 더 많은 콧물을 흘려보내게 되지요. 병균을 씻어 내느라 흘리는 콧물이니 억지로 약을 먹는 것 보다는 그대로 두는 게 좋답니다. 건강한 사람은 콧물감기쯤이야 너끈히 견뎌낼 수 있어요. 감기는 바이러스라 그것을 잡는 약이란 아예 없답니다. 몸을 쉬는 것이 바로 감기 잡는 약이지요.<br>
        &nbsp;&nbsp;이튿날 찹쌀을 물에 불려 시루에 찐 뒤, 절구에 담아 메로 치면서 메주 가루를 부어 골고루 잘 섞습니다. 여기다 간장을 넣고 살살 뒤적입니다. 그런 다음 찌꺼기는 체로 걸러 버리고 고춧가루를 넣고 소금으로 간을 하면 됩니다. 고추장을 항아리에 담아 볕이 잘 드는 곳에 놓아두고 나무주걱으로 잘 저어 주면 맛깔스런 고추장이 됩니다.<br><br>
        &nbsp;&nbsp;&lt;출처: 권오길, 어린 과학자를 위한 몸 이야기&gt;</p>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">1. 차가운 겨울에 코 속의 비갑개의 역할이 아닌 것은?</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_1\" class=\"form-check-input\" name=\"answer1\" value=\"1\" />
            <label for=\"a1_1\" class=\"answer_chk1\">① 입과 귓구멍으로 공기를 보내 순환시킨다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_2\" class=\"form-check-input\" name=\"answer1\" value=\"2\" />
            <label for=\"a1_2\" class=\"answer_chk1\">② 코로 건조한 공기가 들어오면 습기를 머금는다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_3\" class=\"form-check-input\" name=\"answer1\" value=\"3\" />
            <label for=\"a1_3\" class=\"answer_chk1\">③ 코로 차가운 공기가 들어오면, 공기를 데운다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a1_4\" class=\"form-check-input\" name=\"answer1\" value=\"4\" />
            <label for=\"a1_4\" class=\"answer_chk1\">④ 공기를 허파로 내보낸다.</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">2. 인종에 따라서 코가 다르게 생긴 이유는 무엇인가요?</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_1\" class=\"form-check-input\" name=\"answer2\" value=\"1\" />
            <label for=\"a2_1\" class=\"answer_chk2\">① 인종에 따라 미의 기준이 다르기 때문이다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_2\" class=\"form-check-input\" name=\"answer2\" value=\"2\" />
            <label for=\"a2_2\" class=\"answer_chk2\">② 냄새를 맡는 기능이 코에 있기 때문이다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_3\" class=\"form-check-input\" name=\"answer2\" value=\"3\" />
            <label for=\"a2_3\" class=\"answer_chk2\">③ 혼인으로 상대적으로 우위에 있는 유전자가 살아남기 때문이다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a2_4\" class=\"form-check-input\" name=\"answer2\" value=\"4\" />
            <label for=\"a2_4\" class=\"answer_chk2\">④ 환경에 적응했기 때문이다.</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">3. 감기에 걸리면 왜 많은 콧물이 나오나요?</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_1\" class=\"form-check-input\" name=\"answer3\" value=\"1\" />
            <label for=\"a3_1\" class=\"answer_chk3\">① 숨을 쉬기 어렵기 때문이다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_2\" class=\"form-check-input\" name=\"answer3\" value=\"2\" />
            <label for=\"a3_2\" class=\"answer_chk3\">② 병균을 씻어내야 하기 때문이다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_3\" class=\"form-check-input\" name=\"answer3\" value=\"3\" />
            <label for=\"a3_3\" class=\"answer_chk3\">③ 기침을 하기 때문이다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a3_4\" class=\"form-check-input\" name=\"answer3\" value=\"4\" />
            <label for=\"a3_4\" class=\"answer_chk3\">④ 좋은 바이러스가 살 수 있도록 해주기 위해서이다.</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">4. 다음 ㉠~㉣중 그 역할이 다른 것을 고르세요.</h6>
    <div class=\"border mb-2\">
        <p class=\"text-center ms-1\">고추장 만드는 과정<br>
        메주 만들기 → 메주 말리기 → (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) → 찹쌀 섞기 → 고춧가루 및 양념</p>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_1\" class=\"form-check-input\" name=\"answer4\" value=\"1\" />
            <label for=\"a4_1\" class=\"answer_chk4\">① ㉠난방기</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_2\" class=\"form-check-input\" name=\"answer4\" value=\"2\" />
            <label for=\"a4_2\" class=\"answer_chk4\">② ㉡젖은 빨래</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_3\" class=\"form-check-input\" name=\"answer4\" value=\"3\" />
            <label for=\"a4_3\" class=\"answer_chk4\">③ ㉢비갑개</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a4_4\" class=\"form-check-input\" name=\"answer4\" value=\"4\" />
            <label for=\"a4_4\" class=\"answer_chk4\">④ ㉣허파</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">5. 사람이 냄새를 맡지 못하면 어떤 일이 일어날까요?</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_1\" class=\"form-check-input\" name=\"answer5\" value=\"1\" />
            <label for=\"a5_1\" class=\"answer_chk5\">① 소리를 들을 수 없다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_2\" class=\"form-check-input\" name=\"answer5\" value=\"2\" />
            <label for=\"a5_2\" class=\"answer_chk5\">② 음식맛을 느낄 수 없다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a5_3\" class=\"form-check-input\" name=\"answer5\" value=\"3\" />
            <label for=\"a5_3\" class=\"answer_chk5\">③ 후각 세포가 죽는다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a5_4\" class=\"form-check-input\" name=\"answer5\" value=\"4\" />
            <label for=\"a5_4\" class=\"answer_chk5\">④ 감기 바이러스가 증식을 한다.</label>
        </div>
    </div>
    <br>
    <br>
    <h6 class=\"mb-2\">6. 감기에 대한 설명으로 잘못된 것을 골라보세요.</h6>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_1\" class=\"form-check-input\" name=\"answer6\" value=\"1\" />
            <label for=\"a6_1\" class=\"answer_chk6\">① 바이러스가 코로 들어와 상처를 내면 우리 몸은 나쁜 바이러스를 없애려고 한다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_2\" class=\"form-check-input\" name=\"answer6\" value=\"2\" />
            <label for=\"a6_2\" class=\"answer_chk6\">② 콧물이 날 때는 억지로 약을 먹는 것 보다는 그대로 두는 게 좋다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a6_3\" class=\"form-check-input\" name=\"answer6\" value=\"3\" />
            <label for=\"a6_3\" class=\"answer_chk6\">③ 감기는 바이러스라 잡는 약은 없다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline\">
            <input type=\"radio\" id=\"a6_4\" class=\"form-check-input\" name=\"answer6\" value=\"4\" />
            <label for=\"a6_4\" class=\"answer_chk6\">④ 운동을 열심히 하면 열이 나 감기를 잡을 수 있다.</label>
        </div>
    </div>
    <div class=\"text-end\"><button type=\"button\" id=\"btnSubmit\" class=\"btn btn-outline-primary\">정답제출 / 해설 및 정답확인</button></div>
    </form>";
} else {
    header('HTTP/2 403 Forbidden');
    exit;
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        $(".answer_chk1").click(function(e) {
            $(".answer_chk1").removeClass("text-danger");
            $(this).addClass("text-danger");
        });

        $(".answer_chk2").click(function(e) {
            $(".answer_chk2").removeClass("text-danger");
            $(this).addClass("text-danger");
        });

        $(".answer_chk3").click(function(e) {
            $(".answer_chk3").removeClass("text-danger");
            $(this).addClass("text-danger");
        });

        $(".answer_chk4").click(function(e) {
            $(".answer_chk4").removeClass("text-danger");
            $(this).addClass("text-danger");
        });

        $(".answer_chk5").click(function(e) {
            $(".answer_chk5").removeClass("text-danger");
            $(this).addClass("text-danger");
        });

        $(".answer_chk6").click(function(e) {
            $(".answer_chk6").removeClass("text-danger");
            $(this).addClass("text-danger");
        });

        $("#btnSubmit").click(function() {
            if (!$("input[name='answer1']:checked").val()) {
                alert("1번 문제를 풀어보세요.");
                return false;
            } else if (!$("input[name='answer2']:checked").val()) {
                alert("2번 문제를 풀어보세요.");
                return false;
            } else if (!$("input[name='answer3']:checked").val()) {
                alert("3번 문제를 풀어보세요.");
                return false;
            } else if (!$("input[name='answer4']:checked").val()) {
                alert("4번 문제를 풀어보세요.");
                return false;
            } else if (!$("input[name='answer5']:checked").val()) {
                alert("5번 문제를 풀어보세요.");
                return false;
            } else if (!$("input[name='answer6']:checked").val()) {
                alert("6번 문제를 풀어보세요.");
                return false;
            } else {
                $("#question_form").submit();
            }
        })
    });
</script>
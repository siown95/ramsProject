<?php
$grade = $_GET['grade'];
if (empty($grade)) {
    header('HTTP/2 403 Forbidden');
    exit;
}

$form = "";

if ( ($grade == "1") || ($grade == "2") ) {
    $form = "<form id=\"writingForm\" method=\"post\" action=\"reading_rac.php\">
    <input type=\"hidden\" name=\"flag\" value=\"w\" />
    <input type=\"hidden\" name=\"grade\" value=\"{$grade}\" />
    <br>
    <h6 class=\"mb-2\">1. 글쓴이가  보기와 같은 주제로 글을 쓸 때  자신의 의견(중심문장)으로 적절하지 않은 것은 무엇일까요?</h6>
    <br>
    <div class=\"card\">
        <div class=\"card-body align-items-center d-flex\">
            <주제> 겨울과 가장 어울리는 색깔은 어떤 색깔일까요?
        </div>
    </div>
    <br>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_1\" class=\"form-check-input\" name=\"answer1\" value=\"1\" />
            <label for=\"a1_1\" class=\"answer_chk1\">①겨울과 가장 어울리는 색깔은 하얀색입니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_2\" class=\"form-check-input\" name=\"answer1\" value=\"2\" />
            <label for=\"a1_2\" class=\"answer_chk1\">②겨울과 가장 잘 어울리는 색깔은 노랑입니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_3\" class=\"form-check-input\" name=\"answer1\" value=\"3\" />
            <label for=\"a1_3\" class=\"answer_chk1\">③저는 여름보다는 겨울을 더 좋아합니다.</label>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">2. 글쓴이가 보기와 같이 자신의 생각을 적었다면, 그 생각을 더 구체적으로 설명하는 문장(뒷받침 문장)으로 적절한 것을 골라보세요.</h6>
    <br>
    <div class=\"card\">
        <div class=\"card-body align-items-center d-flex\">
            겨울과 가장 어울리는 색깔은 하얀색입니다.
        </div>
    </div>
    <br>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_1\" class=\"form-check-input\" name=\"answer2\" value=\"1\" />
            <label for=\"a2_1\" class=\"answer_chk2\">①왜냐하면 겨울에는 눈이 많이 내려 세상이 온통 하얗게 바뀌기 때문입니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_2\" class=\"form-check-input\" name=\"answer2\" value=\"2\" />
            <label for=\"a2_2\" class=\"answer_chk2\">②겨울에는 나무들이 벌거숭이가 됩니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_3\" class=\"form-check-input\" name=\"answer2\" value=\"3\" />
            <label for=\"a2_3\" class=\"answer_chk2\">③하얀 눈으로 만든 눈사람이 매우 예쁩니다.</label>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">3. 글쓴이가 ‘겨울과 가장 어울리는 색깔을 하얀색입니다“라고 말했을 때, 겨울과 하얀색의 공통점으로 뒷받침 문장을 구성할 때 적절한 것을  골라보세요.</h6>
    <br>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_1\" class=\"form-check-input\" name=\"answer3\" value=\"1\" />
            <label for=\"a3_1\" class=\"answer_chk3\">①겨울은 매우 날씨가 추운데, 하얀색을 차가운 느낌이 듭니다. </label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_2\" class=\"form-check-input\" name=\"answer3\" value=\"2\" />
            <label for=\"a3_2\" class=\"answer_chk3\">②겨울에는 썰매를 탈 수 있고 썰매는 하얀색 썰매가 최고입니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_3\" class=\"form-check-input\" name=\"answer3\" value=\"3\" />
            <label for=\"a3_3\" class=\"answer_chk3\">③겨울에 하얀 연을 날리는 것 만큼 더 재미있는 놀이는 없습니다.</label>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">4. 좋은 글이  되기 위해서 보기를 순서대로 잘 배열해보세요.</h6>
    <br>
    <div class=\"card\">
        <div class=\"card-body\">
            <p>(가) 겨울은 매우 날씨가 추운데, 하얀색은 차가운 느낌이 듭니다.</p>
            <p>(나) 겨울과 가장 어울리는 색깔은 하얀색입니다.</p>
            <p>(다) 왜냐하면 겨울에는 눈이 많이 내려 세상이 온통 하얗게 바뀌기 때문입니다.</p>
            <p>(라) 그래서 저는 하얀색이 겨울과 가장 잘 어울린다고 생각합니다.</p>
        </div>
    </div>
    <br>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_1\" class=\"form-check-input\" name=\"answer4\" value=\"1\" />
            <label for=\"a4_1\" class=\"answer_chk4\">①(나)->(라)->(가)->(다)</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_2\" class=\"form-check-input\" name=\"answer4\" value=\"2\" />
            <label for=\"a4_2\" class=\"answer_chk4\">②(가)->(다)->(라)->(나)</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_3\" class=\"form-check-input\" name=\"answer4\" value=\"3\" />
            <label for=\"a4_3\" class=\"answer_chk4\">③(나)->(다)->(가)->(라)</label>
        </div>
    </div>
    </form>";
} else if ( ($grade == "3") || ($grade == "4") ) {
    $form = "<form id=\"writingForm\" method=\"post\" action=\"reading_rac.php\">
    <input type=\"hidden\" name=\"flag\" value=\"w\" />
    <input type=\"hidden\" name=\"grade\" value=\"{$grade}\" />
    <br>
    <h6 class=\"mb-2\">1. 글쓴이가  보기와 같은 주제로 글을 쓸 때  자신의 의견(중심문장)으로 적절한 것은 무엇일까요?</h6>
    <br>
    <div class=\"card\">
        <div class=\"card-body align-items-center d-flex\">
        <주제> 초등학생이 학교에서 스마트폰을 사용하는 것은 바람직할까요?
        </div>
    </div>
    <br>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_1\" class=\"form-check-input\" name=\"answer1\" value=\"1\" />
            <label for=\"a1_1\" class=\"answer_chk1\">①초등학생이 학교에서 스마트폰을 사용하는 것은 바람직하지 않습니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_2\" class=\"form-check-input\" name=\"answer1\" value=\"2\" />
            <label for=\"a1_2\" class=\"answer_chk1\">②스마트폰은 좋은 것을 사용해야 합니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_3\" class=\"form-check-input\" name=\"answer1\" value=\"3\" />
            <label for=\"a1_3\" class=\"answer_chk1\">③스마트폰으로 게임을 할 수 있습니다.</label>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">2. 글쓴이가 보기와 같이 자신의 생각을 적었다면, 그 생각을 더 구체적으로 설명하는 문장(뒷받침 문장)으로 적절한 것을 골라보세요.</h6>
    <br>
    <div class=\"card\">
        <div class=\"card-body align-items-center d-flex\">
        초등학생은 학교에서 스마트폰을 사용하면 안됩니다.
        </div>
    </div>
    <br>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_1\" class=\"form-check-input\" name=\"answer2\" value=\"1\" />
            <label for=\"a2_1\" class=\"answer_chk2\">①왜냐하면 학교에서 수업을 할 때 방해가 되기 때문입니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_2\" class=\"form-check-input\" name=\"answer2\" value=\"2\" />
            <label for=\"a2_2\" class=\"answer_chk2\">②왜냐하면 스마트폰이 너무 무겁기 때문입니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_3\" class=\"form-check-input\" name=\"answer2\" value=\"3\" />
            <label for=\"a2_3\" class=\"answer_chk2\">③선생님이 걷어가기 때문입니다.</label>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">3. 글쓴이가 보기와 같이 이유를 적었다면, 이어질 뒷받침 문장으로 적절한 것을 골라보세요.</h6>
    <br>
    <div class=\"card\">
        <div class=\"card-body align-items-center d-flex\">
        왜냐하면 학교에서 수업을 할 때 방해가 되기 때문입니다.
        </div>
    </div>
    <br>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_1\" class=\"form-check-input\" name=\"answer3\" value=\"1\" />
            <label for=\"a3_1\" class=\"answer_chk3\">①선생님이 큰 소리로 수업을 할 수밖에 없습니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_2\" class=\"form-check-input\" name=\"answer3\" value=\"2\" />
            <label for=\"a3_2\" class=\"answer_chk3\">②게임에 중독되면, 친구들이 싫어하게 됩니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_3\" class=\"form-check-input\" name=\"answer3\" value=\"3\" />
            <label for=\"a3_3\" class=\"answer_chk3\">③수업중에 게임을 하는 친구도 있고, 전화가 와서 진동이나 소리가 들려 피해를 줍니다.</label>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">4. 좋은 글이  되기 위해서 보기를 순서대로 잘 배열해보세요.</h6>
    <br>
    <div class=\"card\">
        <div class=\"card-body\">
            <p>(가) 왜냐하면 학교에서 수업을 할 때 방해가 되기 때문입니다.</p>
            <p>(나) 그러므로 스마트폰은 학교에 들고 오지 못하도록 하거나, 수거해야 합니다.</p>
            <p>(다) 초등학생이 학교에서 스마트폰을 사용하는 것은 바람직하지 않습니다.</p>
            <p>(라) 수업중에 게임을 하는 친구도 있고, 전화가 와서 진동이나 소리가 들려 피해를 줍니다.</p>
        </div>
    </div>
    <br>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_1\" class=\"form-check-input\" name=\"answer4\" value=\"1\" />
            <label for=\"a4_1\" class=\"answer_chk4\">①(다)->(가)->(라)->(나)</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_2\" class=\"form-check-input\" name=\"answer4\" value=\"2\" />
            <label for=\"a4_2\" class=\"answer_chk4\">②(가)->(다)->(라)->(나)</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_3\" class=\"form-check-input\" name=\"answer4\" value=\"3\" />
            <label for=\"a4_3\" class=\"answer_chk4\">③(나)->(다)->(가)->(라)</label>
        </div>
    </div>
    </form>";
} else if ( ($grade == "5") || ($grade == "6") ) {
    $form = "<form id=\"writingForm\" method=\"post\" action=\"reading_rac.php\">
    <input type=\"hidden\" name=\"flag\" value=\"w\" />
    <input type=\"hidden\" name=\"grade\" value=\"{$grade}\" />
    <br>
    <h6 class=\"mb-2\">1. 글쓴이가  보기와 같은 주제로 글을 쓸 때  첫 문장으로 적절하지 않은 것은 무엇일까요?</h6>
    <br>
    <div class=\"card\">
        <div class=\"card-body align-items-center d-flex\">
        <주제> 지구가 따뜻해지는 원인과 그 대책은 무엇이 있을까요?
        </div>
    </div>
    <br>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_1\" class=\"form-check-input\" name=\"answer1\" value=\"1\" />
            <label for=\"a1_1\" class=\"answer_chk1\">①환경오염으로 지구가 매우 위험해지고 있습니다. </label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_2\" class=\"form-check-input\" name=\"answer1\" value=\"2\" />
            <label for=\"a1_2\" class=\"answer_chk1\">②지구가 따뜻해지고 있어, 북극의 빙하가 녹고 있습니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a1_3\" class=\"form-check-input\" name=\"answer1\" value=\"3\" />
            <label for=\"a1_3\" class=\"answer_chk1\">③소가 내뿜는 메탄가스를 줄여야 합니다.</label>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">2. 글쓴이가 보기에 대한 근거로 적절하지 않은 것을 골라보세요..</h6>
    <br>
    <div class=\"card\">
        <div class=\"card-body align-items-center d-flex\">
        지구가 따뜻해져 기온이 올라가는 이유는 무엇일까요?
        </div>
    </div>
    <br>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_1\" class=\"form-check-input\" name=\"answer2\" value=\"1\" />
            <label for=\"a2_1\" class=\"answer_chk2\">①바닷물이 많아지기 때문입니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_2\" class=\"form-check-input\" name=\"answer2\" value=\"2\" />
            <label for=\"a2_2\" class=\"answer_chk2\">②우리가 쓰는 석탄과 석유같은 화석연료 때문입니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a2_3\" class=\"form-check-input\" name=\"answer2\" value=\"3\" />
            <label for=\"a2_3\" class=\"answer_chk2\">③공장에서 나오는 이산화탄소 때문입니다.</label>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">3. 글쓴이가 보기와 같이 자신의 생각을 적었다면, 그 생각에 대한 근거로 적절하지 않은 것을 골라보세요.</h6>
    <br>
    <div class=\"card\">
        <div class=\"card-body align-items-center d-flex\">
        지구가 따뜻해지는 것을 막은 방법은 있다.
        </div>
    </div>
    <br>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_1\" class=\"form-check-input\" name=\"answer3\" value=\"1\" />
            <label for=\"a3_1\" class=\"answer_chk3\">①화석연료 대신 대체에너지인 신재생 에너지를 사용합니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_2\" class=\"form-check-input\" name=\"answer3\" value=\"2\" />
            <label for=\"a3_2\" class=\"answer_chk2\">②풍력발전, 수력발전 등을 이용합니다.</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a3_3\" class=\"form-check-input\" name=\"answer3\" value=\"3\" />
            <label for=\"a3_3\" class=\"answer_chk2\">③소고기를 많이 먹습니다.</label>
        </div>
    </div>
    <br>
    <h6 class=\"mb-2\">4. 좋은 글이  되기 위해서 보기를 순서대로 잘 배열해보세요.</h6>
    <br>
    <div class=\"card\">
        <div class=\"card-body\">
            <p>(가) 환경오염으로 지구의 온도가 올라가고 있고, 북극의 빙하가 녹고 있습니다.</p>
            <p>(나) 지구가 따뜻해지는 이유는 자동차가 많고, 공장이 많기 때문입니다.</p>
            <p>(다) 지구가 따뜻해지는 것을 막기 위해서는  차를 타기 보다는 걸어다니는 것이 좋습니다.</p>
            <p>(라) 석유나 석탄보다는 바람이나 물을 이용해 에너지를 만들면 좋습니다.</p>
            <p>(마) 갑자기 눈이 많이 오거나, 비가 많이 내려 사람들에게 큰 피해가 생기고 있습니다.</p>
            <p>(바) 지구 온도가 올라가는 것을 막기 위해서는 우리 모두의 노력이 필요합니다. </p>
        </div>
    </div>
    <br>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_1\" class=\"form-check-input\" name=\"answer4\" value=\"1\" />
            <label for=\"a4_1\" class=\"answer_chk4\">①(나)->(라)->(가)->(다)->(마)->(바)</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_2\" class=\"form-check-input\" name=\"answer4\" value=\"2\" />
            <label for=\"a4_2\" class=\"answer_chk4\">②(가)->(마)-(나)->(라)->(다)->(바)</label>
        </div>
    </div>
    <div class=\"input-group mb-2\">
        <div class=\"form-inline form-check-inline me-2\">
            <input type=\"radio\" id=\"a4_3\" class=\"form-check-input\" name=\"answer4\" value=\"3\" />
            <label for=\"a4_3\" class=\"answer_chk4\">③(바)->(나)->(가)->(라)->(다)->(마)</label>
        </div>
    </div>
    </form>";
} else {
    header('HTTP/2 403 Forbidden');
    exit;
}
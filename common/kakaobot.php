<?php
// error_reporting(E_ALL);
// ini_set("display_errors", true);
function curlPost($url, $header, $data)
{
    $ch = curl_init();
    $opt = array(
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSLVERSION => 6,
        CURLOPT_HEADER => 1,
        CURLOPT_VERBOSE => 0
    );
    curl_setopt_array($ch, $opt);
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_POST, true);
    }

    # CURL 전송
    $result['response']    = curl_exec($ch);
    # CURL 전송에 대한 리턴
    $result['aRtnHeader']  = curl_getinfo($ch);
    # CURL 종료
    curl_close($ch);

    return $result;
}

// if (empty($_POST)) {
//     header('HTTP/2 403 Forbidden');
//     exit;
// }
$data = $_POST;
// $data['mode'] => 타입
if (!empty($data['mode'])) {
    $mode = $data['mode'];
}
// $data['conversation_id'] => 채팅방 번호
if (!empty($data['conversation_id'])) {
    $conversation_id = $data['conversation_id'];
}
// $data['user_id'] => 단일 회원 번호
if (!empty($data['user_id'])) {
    $user_id = $data['user_id'];
}
// $data['user_ids'] => 회원번호 리스트
if (!empty($data['user_ids'])) {
    $user_ids_arr = explode(",", $data['user_ids']);
    $user_ids = array_unique(array_filter($user_ids_arr));
}
// $data['url'] => 이미지 링크
if (!empty($data['url'])) {
    $url = $data['url'];
}
// $data['email'] => 이메일
if (!empty($data['email'])) {
    $email = $data['email'];
}
// $data['text'] => 메시지 내용
if (!empty($data['text'])) {
    $text = $data['text'];
}

# ----------------------------------------------------------------------------
# [카카오워크] 공용 변수 선언
# [카카오워크] API 키
$KWORK_API  =  '';
# [Request UserAgent] - URL 정보 있어야 함. 없을 경우 리턴 안됨!
$USER_AGENT = '';
# 전송 헤더 설정
$aHeader    = array();
$aHeader[]  = 'Authorization: Bearer ' . $KWORK_API;
$aHeader[]  = 'Content-Type: application/json; charset=utf-8';
$aHeader[]  = "Accept-Encoding: gzip, deflate";
$aHeader[]  = "Connection: keep-alive";
$aHeader[]  = "User-Agent: " . $USER_AGENT;
#
# ----------------------------------------------------------------------------

# ----------------------------------------------------------------------------
#
# [카카오워크] API 유저 목록 불러오기 /  user_id 목록
#
# ----------------------------------------------------------------------------
# curl -X GET https://api.kakaowork.com/v1/users.list -H "Authorization: Bearer {YOUR_APP_KEY}"
#
# ../test.php userlist
#

if ($mode == 'userlist') {
    # 2페이지 부터는 리턴받은 커서의 값을 보내줘야 한다.
    $url_kwork  = 'https://api.kakaowork.com/v1/users.list?limit=100';
}

# ----------------------------------------------------------------------------
#
# [카카오워크] API 유저 목록 불러오기 /  user_id 목록
#
# ----------------------------------------------------------------------------
# curl -X GET https://api.kakaowork.com/v1/users.list -H "Authorization: Bearer {YOUR_APP_KEY}"
#
# ../test.php userlist
#

else if ($mode == 'clist') {
    # 2페이지 부터는 리턴받은 커서의 값을 보내줘야 한다.
    $url_kwork  = 'https://api.kakaowork.com/v1/conversations.list';
}

# ----------------------------------------------------------------------------
#
# [카카오워크] API 채팅방에 참여한 사용자 리스트를 조회
#
# ----------------------------------------------------------------------------
# curl -X GET https://api.kakaowork.com/v1/conversations/{conversation_id}/users
#
# ../test.php cuserlist
#

else if ($mode == 'cuserlist') {
    $aSendInfo  = array();
    $aSendInfo['conversation_id'] = $conversation_id;

    $url_kwork  = 'https://api.kakaowork.com/v1/conversations/' . $conversation_id . '/users';
}

# ----------------------------------------------------------------------------
#
# [카카오워크] API 채팅방 만들기 / 채팅방 하나 만들기 1:1
#
# ----------------------------------------------------------------------------
# curl -X POST https://api.kakaowork.com/v1/conversations.open -H "Authorization: Bearer {YOUR_APP_KEY}" -H "Content-Type: application/json" -d '{ "user_id": {채팅 상대방의 사용자 ID} }'
#
# ./test.php conversations

else if ($mode == 'conversations') {
    $aSendInfo  = array();
    $aSendInfo['user_id'] = $user_id;
    $json_data  = json_encode($aSendInfo);

    $url_kwork  = 'https://api.kakaowork.com/v1/conversations.open';
}

# ----------------------------------------------------------------------------
#
# [카카오워크] API 채팅방 만들기 / 채팅방 하나 만들기 1:1
#
# ----------------------------------------------------------------------------
# curl -X POST https://api.kakaowork.com/v1/conversations.open -H "Authorization: Bearer {YOUR_APP_KEY}" -H "Content-Type: application/json" -d '{ "user_id": {채팅 상대방의 사용자 ID} }'
#
# ./test.php conversations

else if ($mode == 'conversations2') {
    $aSendInfo  = array();
    $aSendInfo['user_ids'] = $user_ids;
    $json_data  = json_encode($aSendInfo);

    $url_kwork  = 'https://api.kakaowork.com/v1/conversations.open';
}

# ----------------------------------------------------------------------------
#
# [카카오워크] API 메일주소로 메세지 보내기 / 메세지보내기 - 이메일
#
# ----------------------------------------------------------------------------
# curl -X POST https://api.kakaowork.com/v1/messages.send_by_email -H "Authorization: Bearer {YOUR_APP_KEY}" -H "Content-Type: application/json" -d '{ "email": "{메시지를 수신할 사용자의 인증된 email 주소}", "text": "{전송할 채팅 메시지}" }'
#
# ./test.php sendMsg

else if ($mode == 'sendMsg') {
    $aSendInfo  = array();
    // $aSendInfo['email'] = 'jhsong915@textsolution.co.kr';
    $aSendInfo['email'] = $email;
    $aSendInfo['text']  = $text;
    $json_data  = json_encode($aSendInfo);

    $url_kwork  = 'https://api.kakaowork.com/v1/messages.send_by_email';
}

# ----------------------------------------------------------------------------
#
# [카카오워크] API 메일주소로 메세지 보내기 / 메세지보내기 - 채팅방
#
# ----------------------------------------------------------------------------
# curl -X POST https://api.kakaowork.com/v1/messages.send -H "Authorization: Bearer {YOUR_APP_KEY}" -H "Content-Type: application/json" -d '{ "conversation_id": "{메시지를 보낼 채팅방 Id}", "text": "{전송할 채팅 메시지}" }'
#
# ./test.php sendMsg2

else if ($mode == 'sendMsg2') {
    $aSendInfo  = array();
    $aSendInfo['conversation_id'] = $conversation_id; // 채팅방 Id
    $aSendInfo['text']  = $text; // 문자 내용
    $json_data  = json_encode($aSendInfo);

    $url_kwork  = 'https://api.kakaowork.com/v1/messages.send';
}

# ----------------------------------------------------------------------------
#
# [카카오워크] API 채팅방 id로 메세지 보내기 / 템플릿 메세지보내기 - 채팅방 (이미지 + 텍스트)
#
# ----------------------------------------------------------------------------
# curl -X POST https://api.kakaowork.com/v1/messages.send -H "Authorization: Bearer {YOUR_APP_KEY}" -H "Content-Type: application/json" 
#-d '{ "conversation_id": "{메시지를 보낼 채팅방 Id}", "blocks": "[{"type": "{전송할 메시지 타입}", "url": "{전송할 이미지 링크}"}, {"type": "{전송할 메시지 타입}", "text": "{전송할 채팅 메시지}"}]" }'
#
# ./test.php sendMsg3

else if ($mode == 'sendMsg3') {
    $aSendInfo  = array(
        "conversation_id" => $conversation_id,
        "text" => $text,
        "blocks" => array(
            array(
                "type" => "image_link",
                "url" => $url
            ), array(
                "type" => "text",
                "text" => $text
            )
        )
    );
    $json_data  = json_encode($aSendInfo);
    $url_kwork  = 'https://api.kakaowork.com/v1/messages.send';
}

# ----------------------------------------------------------------------------
#
# [카카오워크] API 채팅방 id로 메세지 보내기 / 템플릿 메세지보내기 - 채팅방 (헤더 + 이미지 + 텍스트)
#
# ----------------------------------------------------------------------------
# curl -X POST https://api.kakaowork.com/v1/messages.send -H "Authorization: Bearer {YOUR_APP_KEY}" -H "Content-Type: application/json" 
#-d '{ "conversation_id": "{메시지를 보낼 채팅방 Id}", "blocks": "[{"type": "{전송할 메시지 타입}", "url": "{전송할 이미지 링크}"}, {"type": "{전송할 메시지 타입}", "text": "{전송할 채팅 메시지}"}]" }'
#
# ./test.php sendAlertMsg

else if ($mode == 'sendAlertMsg') {
    $color = array(
        "0" => "red",
        "1" => "blue",
        "2" => "yellow"
    );
    $aSendInfo  = array(
        "conversation_id" => $conversation_id,
        "text" => $text,
        "blocks" => array(
            array(
                "type" => "header",
                "text" => "알림 메시지",
                "style" => $color[random_int(0, 2)]
            ),
            array(
                "type" => "image_link",
                "url" => $url
            ), array(
                "type" => "text",
                "text" => $text
            )
        )
    );
    $json_data  = json_encode($aSendInfo);
    $url_kwork  = 'https://api.kakaowork.com/v1/messages.send';
}

# ----------------------------------------------------------------------------
#
# [카카오워크] API 채팅방 id로 메세지 보내기 / 템플릿 메세지보내기 - 채팅방 (헤더 + 이미지 + 텍스트)
#
# ----------------------------------------------------------------------------
# curl -X POST https://api.kakaowork.com/v1/messages.send -H "Authorization: Bearer {YOUR_APP_KEY}" -H "Content-Type: application/json" 
#-d '{ "conversation_id": "{메시지를 보낼 채팅방 Id}", "blocks": "[{"type": "{전송할 메시지 타입}", "url": "{전송할 이미지 링크}"}, {"type": "{전송할 메시지 타입}", "text": "{전송할 채팅 메시지}"}]" }'
#
# ./test.php sendAlertMsg

else if ($mode == 'sendAlertMsg2') {
    $color = array(
        "0" => "red",
        "1" => "blue",
        "2" => "yellow"
    );
    $aSendInfo  = array(
        "conversation_id" => $conversation_id,
        "text" => $text,
        "blocks" => array(
            array(
                "type" => "header",
                "text" => "일정알림",
                "style" => $color[random_int(0, 2)]
            ),
            array(
                "type" => "divider"
            ),
            array(
                "type" => "text",
                "text" => $text,
            ),
            array(
                "type" => "divider"
            ),
            array(
                "type" => "text",
                "text" => "일시 : " . date('Y-m-d H:i') . ' ~ ' . date('Y-m-d H:i')
            ),
            array(
                "type" => "text",
                "text" => "장소 : 본사 회의실"
            )
        )
    );
    $json_data  = json_encode($aSendInfo);
    $url_kwork  = 'https://api.kakaowork.com/v1/messages.send';
}

# ----------------------------------------------------------------------------
#
# [카카오워크] API 채팅방 초대
#
# ----------------------------------------------------------------------------
# curl -X POST https://api.kakaowork.com/v1/conversations/{conversation_id}/invite -H "Authorization: Bearer {YOUR_APP_KEY}" -H "Content-Type: application/json" -d '{ "user_ids": "{채팅방에 초대할 상대방 사용자 ID 리스트}" }'
#
# ./test.php invite

else if ($mode == 'invite') {
    $aSendInfo  = array();
    $aSendInfo['conversation_id'] = $conversation_id; // 채팅방 Id
    $aSendInfo['user_ids'] = $user_ids;
    $json_data  = json_encode($aSendInfo);

    $url_kwork  = 'https://api.kakaowork.com/v1/conversations/' . $conversation_id . '/invite';
}

# ----------------------------------------------------------------------------
#
# [카카오워크] API 채팅방 추방
#
# ----------------------------------------------------------------------------
# curl -X POST https://api.kakaowork.com/v1/conversations/{conversation_id}/kick -H "Authorization: Bearer {YOUR_APP_KEY}" -H "Content-Type: application/json" -d '{ "user_ids": "{채팅방에 추방할 상대방 사용자 ID 리스트}" }'
#
# ./test.php kick

else if ($mode == 'kick') {
    $aSendInfo  = array();
    $aSendInfo['conversation_id'] = $conversation_id; // 채팅방 Id
    $aSendInfo['user_ids'] = $user_ids;
    $json_data  = json_encode($aSendInfo);

    $url_kwork  = 'https://api.kakaowork.com/v1/conversations/' . $conversation_id . '/kick';
} else {
    echo '형식이 올바르지 않거나, 잘못된 접근입니다.';
    exit;
}

$res = curlPost($url_kwork, $aHeader, $json_data);
$response = $res['response'];
$aRtnHeader = $res['aRtnHeader'];

# 리스폰에서 헤더와 바디 별도 변수에 담아 놓기 - 압축되었으면 풀어 줌.
$rtn_header = substr($response, 0, $aRtnHeader['header_size']);
$rtn_body = substr($response, $aRtnHeader['header_size']);
$http_header = $rtn_header;
$http_body = $rtn_body;

if (stristr($rtn_header, 'Content-Encoding: gzip')) {
    $http_body  = gzdecode($rtn_body);
}

$aJson = array();
$aJson['header'] = $http_header;
$aJson['body'] = json_decode($http_body, true);
// echo '<pre>';
if ($mode == 'userlist') {
    $data = array();
    for ($i = 0; $i < count($aJson['body']['users']); $i++) {
        $data[$i]['id'] .= $aJson['body']['users'][$i]['id'];
        $data[$i]['name'] .= $aJson['body']['users'][$i]['display_name'];
    }
} else if ($mode == 'clist') {
    $data = array();
    for ($i = 0; $i < count($aJson['body']['conversations']); $i++) {
        $data[$i]['id'] .= $aJson['body']['conversations'][$i]['id'];
        $data[$i]['name'] .= $aJson['body']['conversations'][$i]['name'];
    }
} else if ($mode == 'cuserlist') {
    $data = array();
    for ($i = 0; $i < count($aJson['body']['users']); $i++) {
        $data[$i]['id'] .= $aJson['body']['users'][$i]['id'];
        $data[$i]['name'] .= $aJson['body']['users'][$i]['display_name'];
    }
} else {
    $data = $aJson;
}
# CURL 리턴 값 체크
// if ($mode == 'userlist') {
//     $cursor = $aRtnHeader['cursor'];
//     if ($cursor != '') {
//         $url_kwork = 'https://api.kakaowork.com/v1/users.list?cursor=' . $cursor;
//         curl_setopt($ch, CURLOPT_URL, $url_kwork); // 요청 URL
//         $response2 = curl_exec($ch);
//         $aRtnHeader2 = curl_getinfo($ch);
//     }
// }
echo json_encode($data);

// print_r($aJson);
// echo '-----------------------------------------------' . "<br>";
// $aRtnInfo = array();
// $aTemp = explode("\n", $rtn_header);
// foreach ($aTemp as $key => $val) {
//     $aTmp = explode(': ', $val);
//     $hname = $aTmp[0];
//     $hval = $aTmp[1];
//     $aRtnInfo[$hname] = trim($hval);
// }

// echo 'ratelimit-limit: ' . $aRtnInfo['ratelimit-limit'] . "<br>"; // 분당 허용되는 최대 요청 횟수
// echo 'ratelimit-remaining: ' . $aRtnInfo['ratelimit-remaining'] . "<br>"; // 잔여 분(minute) 동안 가능한 요청 횟수
// echo 'ratelimit-reset: ' . $aRtnInfo['ratelimit-reset'] . "<br>"; // 시작된 ratelimit 시간 단위가 갱신되는 시간(millisecond)
// echo 'retry-after: ' . $aRtnInfo['retry-after'] . "<br>";   // 요청 갱신 시간까지 남은 초(second)
<?php

/**
 * @공용 함수 지정 파일
 */

//전화번호 설정
function phoneFormat($phone, $division = false)
{
    if(!empty($phone)){
        $phone = preg_replace("/[^0-9]/", "", $phone);  // 숫자 이외 제거

        if ($division == true) {
            if (substr($phone, 0, 2) == '02') {    // 지역번호 - 서울
                $phone = preg_replace("/([0-9]{2})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $phone);
            } else if (strlen($phone) == '8' && (substr($phone, 0, 2) == '15' || substr($phone, 0, 2) == '16' || substr($phone, 0, 2) == '18')) {    // 지능망 번호
                $phone = preg_replace("/([0-9]{4})([0-9]{4})$/", "\\1-\\2", $phone);
            } else {
                $phone = preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $phone);
            }
        }
    }else{
        return false;
    }
    
    return $phone;
}

function import_ob($path, $_a = null)
{

    ob_start();

    import($path, $_a);
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

function import($path, $_a = null)
{
    unset($GLOBALS['_a']);
    extract($GLOBALS);
    return require $path;
}

function getImageLink($query)
{
    $api_server = 'https://dapi.kakao.com/v3/search/book';
    $headers = array('Authorization: KakaoAK 620c009b7536d7a7692ef356b78fecd9 ');
    $opts = array(
        CURLOPT_URL => $api_server . '?' . $query,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSLVERSION => 1,
        CURLOPT_HEADER => false,
        CURLOPT_HTTPHEADER => $headers
    );
    $curl_session = curl_init();
    curl_setopt_array($curl_session, $opts);
    $return_data = curl_exec($curl_session);
    if (curl_errno($curl_session)) {
        throw new Exception(curl_error($curl_session));
    } else {
        curl_close($curl_session);
        return $return_data;
    }
}

function setWildcardId($id)
{
    if (empty($id)) {
        return '';
    }
    $len = mb_strlen($id);
    if ($len <= 1) {
        return '*';
    } else if ($len <= 2) {
        return mb_substr($id, 0, 1) . '*';
    } else if ($len < 8) {
        return mb_substr($id, 0, -2) . str_repeat('*', mb_strlen(substr($id, -2)));
    }
    return mb_substr($id, 0, -3) . str_repeat('*', mb_strlen(substr($id, -3)));
}

function makePassword()
{
    $charEng = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $charSpe = "!~@_$?";
    $maxEng = strlen($charEng) - 1;
    $maxSpe = strlen($charSpe) - 1;
    $randomEng = "";
    $randomSpe = "";

    for ($i = 0; $i < 6; $i++) {
        $randomEng .= substr($charEng, rand(0, $maxEng), 1);
    }

    for ($i = 0; $i < 2; $i++) {
        $randomSpe .= substr($charSpe, rand(0, $maxSpe), 1);
    }

    $random = $randomEng . $randomSpe;

    return str_shuffle($random);
}

function getGrade($age)
{
    $grade = '';

    if ($age < 7) {
        $grade = '유아';
    } else if ($age == 7) {
        $grade = '초0';
    } else if ($age == 8) {
        $grade = '초1';
    } else if ($age == 9) {
        $grade = '초2';
    } else if ($age == 10) {
        $grade = '초3';
    } else if ($age == 11) {
        $grade = '초4';
    } else if ($age == 12) {
        $grade = '초5';
    } else if ($age == 13) {
        $grade = '초6';
    } else if ($age == 14) {
        $grade = '중1';
    } else if ($age == 15) {
        $grade = '중2';
    } else if ($age == 16) {
        $grade = '중3';
    } else if ($age == 17) {
        $grade = '고1';
    } else if ($age == 18) {
        $grade = '고2';
    } else if ($age == 19) {
        $grade = '고3';
    } else {
        $grade = '';
    }

    return $grade;
}

function getAge($grade)
{
    $age = '';

    if ($grade == '05') {
        $age = '6';
    } else if ($grade == '10') {
        $age = '7';
    } else if ($grade == '11') {
        $age = '8';
    } else if ($grade == '12') {
        $age = '9';
    } else if ($grade == '13') {
        $age = '10';
    } else if ($grade == '14') {
        $age = '11';
    } else if ($grade == '15') {
        $age = '12';
    } else if ($grade == '16') {
        $age = '13';
    } else if ($grade == '21') {
        $age = '14';
    } else if ($grade == '22') {
        $age = '15';
    } else if ($grade == '23') {
        $age = '16';
    } else if ($grade == '31') {
        $age = '17';
    } else if ($grade == '32') {
        $age = '18';
    } else if ($grade == '33') {
        $age = '19';
    } else if ($grade == '00') {
        $age = 'all';
    } else {
        $age = '';
    }

    return $age;
}

function getScoreGrade($score)
{
    $grade = '';

    if ($score > 4) {
        $grade = 'S';
    } else if ($score <= 4 && $score > 3) {
        $grade = 'A';
    } else if ($score <= 3 && $score > 2) {
        $grade = 'B';
    } else if ($score <= 2 && $score > 1) {
        $grade = 'C';
    } else {
        $grade = 'D';
    }

    return $grade;
}

function getWeek($date){
    $DEFAULT_DAYS = 0; //1 ~ 7 (월 ~ 일)
    list($yy, $mm, $dd) = explode('-', $date); // - 로 잘라서 연,월,일을 구합니다

    $first_day = date('N', mktime(0,0,0,(int)$mm, 1, $yy)); //입력한 날짜의 해당하는 월의 1일이 몇요일인지 구합니다.
    
    if($first_day <= $DEFAULT_DAYS){  
        $remain_days = $DEFAULT_DAYS - $first_day;
        $next_monday = $remain_days +1;
    }else{
        $remain_days= 7 - $first_day; //1일을 기준으로 해당주가 몇일 남았는지 구합니다.
        $next_monday = $remain_days + $DEFAULT_DAYS +1; //1일 기준으로 차주의 월요일이 몇일인지 구합니다.
    }

    if($dd < $next_monday){ //입력한 날짜가 전달의 마지막주에 포함될 경우.
        $new_date = date('Y-m-d', mktime(0,0,0,(int)$mm,0,$yy)); //날짜를 0으로 입력해서 지난달의 마지막 주를 새로계산하도록 합니다.       
        return getWeek($new_date);
    }else{
        $week = ceil(($dd-($next_monday -1))/7); //몇번째 주차인지 구하기.
        return array(
            "date" => $date,
            "week" => $week
        );
    }
}

function getWeekData($week, $year)
{
    $dto = new DateTime();
    $result['start'] = $dto->setISODate($year, $week, 1)->format('Y-m-d');
    $result['end'] = $dto->setISODate($year, $week, 7)->format('Y-m-d');
    $result['month'] = $dto->setISODate($year, $week, 4)->format('m');
    return $result;
}

function getWeekday($date) {
    $weekday = array("일","월","화","수","목","금","토"); // 0 ~ 6 일요일부터 시작

    return "(" . ($weekday[date('w', strtotime($date))]) . ")";
}
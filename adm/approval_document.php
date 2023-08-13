<?php
    $data = $_POST['data'];
    if (!empty($data)) {
        $result['msg'] = '잘못된 접근입니다. 다시 한 번 확인해주세요.';
        echo $result;
        exit;
    }

    $form ='';

    // 제안 건의
    if ($data == '01') {
        $title = '제안건의서';
        $form = '';
    }
    // 사직서
    else if ($data == '02') {
        $title = '사직서';
        $form = '';
    }
    // 시간외 근무
    else if ($data == '03') {
        $title = '시간외 근무신청서';
        $form = '';
    }
    // 서면진술
    else if ($data == '04') {
        $title = '서면진술서';
        $form = '';
    }
    // 휴직
    else if ($data == '05') {
        $title = '휴직신청서';
        $form = '';
    }
    // 휴가 조퇴
    else if ($data == '06') {
        $title = '휴가 조퇴 신청서';
        $form = '';
    }
    // 유아 휴직
    else if ($data == '07') {
        $title = '유아 휴직 신청서';
        $form = '';
    }
    // 출산전후 휴가
    else if ($data == '08') {
        $title = '출산전후 휴가 신청서';
        $form = '';
    }
    // 지각
    else if ($data == '09') {
        $title = '지각사유서';
        $form = '';
    }
    $result['data'] = $form;
    $result['title'] = $title;

    echo json_encode($result);
?>
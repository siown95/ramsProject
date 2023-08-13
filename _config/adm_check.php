<?php
include_once $_SERVER['DOCUMENT_ROOT']."/_config/session_start.php";

if( (empty($_SESSION['logged_id_adm'])) && (empty($_SESSION['logged_id']))){
    echo "<script>
            alert('로그인 후 사용 가능합니다.');
            location.href = '/';
        </script>";
}
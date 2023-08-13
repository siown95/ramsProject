<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}else if (session_status() === PHP_SESSION_NONE) {
    session_start();
}else{
    return false;
}

extract($_ENV);
extract($_GET);
extract($_POST);
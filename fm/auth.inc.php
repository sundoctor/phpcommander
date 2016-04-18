<?php

$test  = isset($_SERVER['PHP_AUTH_USER']) &&
         isset($_SERVER['PHP_AUTH_PW']) &&
         $pwdMan->pwdChk($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);

if (!$test) {
    header('WWW-Authenticate: Basic realm="Authorizarion"');
    header('HTTP/1.0 401 Unauthorized');
    echo "Permission Denied!";
    exit;
}

?>
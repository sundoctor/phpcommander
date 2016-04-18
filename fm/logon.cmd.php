<?php
if (!defined('ACTIVE')) die(__FILE__);

$login = isset($_POST['login'])? $_POST['login'] : '';
$pwd = isset($_POST['pwd'])? $_POST['pwd'] : '';

$errMsg = '';

// Тип
if (!is_string($login)) $login = '';
// Длина
if (strlen($login)>50) $errMsg = 'Длина логина слишком велика!';
$login = substr($login,0,50);
if (strlen(addslashes($login))>50) $errMsg = 'Длина логина слишком велика!';
// Теги
if (strip_tags($login)!=$login) if ($errMsg=='') $errFlag = 'Логин содержит теги!';
// Множество
$regexp = '/^[\w|\d|.|,|!|?|+|\-|*|\/|(|)|\@]+$/i';
if (!preg_match($regexp,$login)) if ($errMsg=='') $errMsg = 'Логин содержит запрещенные символы!';

// Тип
if (!is_string($pwd)) $pwd = '';
// Длина
if (strlen($pwd)>50) $errMsg = 'Длина пароля слишком велика!';
$pwd = substr($pwd,0,50);
if (strlen(addslashes($pwd))>50) $errMsg = 'Длина пароля слишком велика!';
// Теги
if (strip_tags($pwd)!=$pwd) if ($errMsg=='') $errFlag = 'Пароль содержит теги!';
// Множество
$regexp = '/^[\w|\d|.|+|\-|\@]+$/i';
if (!preg_match($regexp,$login)) if ($errMsg=='') $errMsg = 'Пароль содержит запрещенные символы!';

if ($errMsg=='') {
    if ($pwdMan->pwdChk($login,$pwd)) $_SESSION['registered'] = true;
    $LOCATION = 'index.php?cmd=list';
} else {
    include('login.cmd.php');
}


?>
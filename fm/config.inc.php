<?php
/*////////////////////////////////////////////////////////////////////

 Конфигурация

 File    : config.inc.php
 Version : 1.01

 Igor Salnikov, Copyright(C), 1995-2016

 Error found? Nothing is perfect...

////////////////////////////////////////////////////////////////////*/

// Версия
define('VERSION', '1');

// Активность
define('ACTIVE', true);

// Время
$TIMEPOINT = time();
$NOW = date("Y-m-d H:i:s");

// Подсветка файлов
$COLORS = array(
    'exe' => '#FF0000',
    'com' => '#FF0000',
    'bat' => '#FF0000',
    'php' => '#6A179B',
    'txt' => '#696969',
    'gif' => '#008000',
    'jpg' => '#008000',
    'png' => '#008000'

);

// Корневой защищенный каталог
$BASEPATH = '';

// Стартовый каталог
$STARTDIR = realpath('.');

// Максимальный размер файла для просмотра
$MAXVIEWSIZE = 1024*100*3;

// ОС
$WINDOWS = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'? true: false;

// Диски
$WINDRIVES = array();

// Кодировка для страниц
$CHARSET = 'utf-8';

// Корневые описания страниц

$TITLE          = array('phpcommander-1.01');
$KEYWORDS       = '1';
$DESCRIPTION    = '2';
$CONTENT        = '3';
$TEMPLATE       = 'page.html';

// Заменяемые константы
$CONST = array(
    'email'    => 'sun-doctor@7masters.org',
    'site'     => 'www.7masters.org',
    'phone'    => '8-916-115-78-42',
    'author'   => '(C) SunDoctor aka Artix' 
);

// Корень файловой системы
$ROOT = dirname(__FILE__);

// Корень виртуальной системы
$_DOCUMENT_ROOT = isset($_SERVER['DOCUMENT_ROOT'])?
                  $_SERVER['DOCUMENT_ROOT'] : $ROOT;
$_PATH = substr($ROOT,strlen($_DOCUMENT_ROOT));
$_PATH = str_replace('\\','/',$_PATH);
$PATH = $_PATH;

unset($_DOCUMENT_ROOT);
unset($_PATH);

// Сообщения об ошибках
error_reporting(E_ALL);

// Время работы страницы
// default: 30
set_time_limit(30);

// Обработка кавычек
ini_set("magic_quotes_gpc","Off");
ini_set("magic_quotes_runtime","Off");
ini_set("magic_quotes_sybase","Off");

// Заблокированные адреса
$BLOCKED = array(
    '255.255.255.255'
);

// Мои адреса
$MYREFERERS = array(
    'http://localhost',
);

// ~= Ниже этой точки не менять =~

// Время генерации страницы
function getmicrotime() {
    list($usec, $sec) = explode(' ', microtime());
    return ((float)$usec + (float)$sec);
}

$TIMEPOINT_START = getmicrotime();
// $TIMEPOINT_FINISH = getmicrotime();
// $TIMEGEN = $TIMEPOINT_FINISH - $TIMEPOINT_START;

?>

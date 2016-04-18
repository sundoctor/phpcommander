<?php
include("config.inc.php");
include("quotesoff.inc.php");
include("fileformer.php");
include("session.inc.php");
include("common.inc.php");
include('pwd.lib.php');

// Менеджер паролей
$pwdMan = new TPwd();

// Аутентификация Apache
// include('auth.inc.php');

// Флаг сброса сессии
$reset = isset($_REQUEST['reset'])? true:false;

// Переменные сессии и значения по умолчанию
$sesvar = array(
    'bufopr'     => '',
    'bufsrcpath' => '',
    'buffiles'   => array()
);

// Регистрация сессий
foreach($sesvar as $k=>$v) {
    if (!isset($_SESSION[$k]) || $reset) {
        $_SESSION[$k] = $v;
        $$k = $v;
    }
}

$sorts = array(
    'nameaz', 'nameza',
    'extaz',  'extza',
    'sizeaz', 'sizeza',
    'dateaz', 'dateza'
);

// Параметры
$now         = date('Y-m-d H:i:s');
$freespace   = number_format(diskfreespace("/"));
$sort        = isset($_REQUEST['sort'])? $_REQUEST['sort'] : 'nameaz';
if (!in_array($sort, $sorts)) $sort = 'nameaz';
$sortdir     = substr($sort,-2);
$sortord     = substr($sort,0,strlen($sort)-2);
if ($sortdir!='az' && $sortdir!='za') $sortdir = 'az';
$path        = isset($_REQUEST['path'])? ($_REQUEST['path']) : $STARTDIR;
$path        = strtr($path,'\\','/');
if ($BASEPATH!='' && strpos($path,$BASEPATH)!==0) $path = $BASEPATH;
while (!file_exists($path)) {
   $p = strrpos($path,'/');
   if ($p===false) break;
   $path = substr($path,0,$p);
}

$errCode = 0;
if (isset($_REQUEST['error']))
    setErrorCode(substr($_REQUEST['error'],0,2));
$errMsg = array(
    0  => '',
    1  => 'File not found!',
    2  => 'File already exists!',
    3  => 'Directory read error!',
    4  => 'Bad file name!',
    5  => 'File too large!',
    6  => 'File not writable!'
);

// Какое действие выполнять
$cmd = isset($_REQUEST['cmd'])? substr($_REQUEST['cmd'],0,10) : 'list';
if (!$_SESSION['registered'] && $cmd!='logon') $cmd = 'login';

// Защита от случая
if ($BASEPATH!='' && strpos($path,$BASEPATH)!==0) {
    $cmd = 'login';
}

// Перехват глобальных параметров в сессию
foreach($sesvar as $k=>$v) {
    $$k = $_SESSION[$k];
    if (isset($_REQUEST[$k])) {
        $$k = $_REQUEST[$k];
        $_SESSION[$k] = $$k;
    }
}

// Доступные команды
$cmds = array(
    'login',
    'logon',
    'logout',
    'list',
    'newdir',
    'mkdir',
    'upload',
    'new',
    'save',
    'view',
    'edit',
    'update',
    'download',
    'cut',
    'copy',
    'paste',
    'clear',
    'newname',
    'rename',
    'attr',
    'setattr',
    'remove',
    'removeall',
    'removex'
);

$OUTPUT = '';
$LOCATION = '';
if (in_array($cmd,$cmds) && file_exists('./'.$cmd.'.cmd.php'))
    include('./'.$cmd.'.cmd.php');


if ($OUTPUT!='') {
    echo $OUTPUT;
    exit;
}

if ($LOCATION!='') {
    header("Location: $LOCATION");
    exit;
}

include('page.inc.php');
echo $OUTPUT;

?>
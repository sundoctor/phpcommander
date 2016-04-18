<?php
if (!defined('ACTIVE')) die(__FILE__);

$filename = isset($_REQUEST['filename'])? $_REQUEST['filename'] : '';
$filename = substr($filename,1);

$u = isset($_REQUEST['u'])?$_REQUEST['u']:array();
$g = isset($_REQUEST['g'])?$_REQUEST['g']:array();
$o = isset($_REQUEST['o'])?$_REQUEST['o']:array();

$su = 0; foreach($u as $v) $su+=is_numeric($v)?$v:0;
$sg = 0; foreach($g as $v) $sg+=is_numeric($v)?$v:0;
$so = 0; foreach($o as $v) $so+=is_numeric($v)?$v:0;

if ($filename=='') setErrorCode(4);
if (!file_exists($path.'/'.$filename)) setErrorCode(1);

if ($errCode==0) {
    
    $r = octdec($su.$sg.$so);
    @chmod($path.'/'.$filename,$r);
    
}

$params = array(
    'cmd'  => 'list',
    'sort' => $sort,
    'path' => urlencode($path)
);

if ($errCode!=0) $params['error'] = $errCode;

$LOCATION = 'index.php?'.buildQuery($params);

?>
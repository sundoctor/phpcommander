<?php
if (!defined('ACTIVE')) die(__FILE__);

$oldname = isset($_REQUEST['oldname'])? $_REQUEST['oldname'] : '';
$oldname = substr($oldname,1);
$newname = isset($_REQUEST['filename'])? $_REQUEST['filename'] : '';

if ($oldname=='') setErrorCode(4);
if (!file_exists($path.'/'.$oldname)) setErrorCode(1);
if ($newname=='') setErrorCode(4);
if (file_exists($path.'/'.$newname)) setErrorCode(2);

if ($errCode==0) @rename($path.'/'.$oldname,$path.'/'.$newname);

$params = array(
    'cmd'  => 'list',
    'sort' => $sort,
    'path' => urlencode($path)
);

if ($errCode!=0) $params['error'] = $errCode;

$LOCATION = 'index.php?'.buildQuery($params);

?>
<?php
if (!defined('ACTIVE')) die(__FILE__);

$dirname = isset($_REQUEST['filename'])?
           $_REQUEST['filename'] : '';

if ($dirname=='') setErrorCode(4);
if (file_exists($path.'/'.$dirname)) setErrorCode(2);

if ($errCode==0) @mkdir($path.'/'.$dirname);

$params = array(
    'cmd'  => 'list',
    'sort' => $sort,
    'path' => urlencode($path)
);

if ($errCode!=0) $params['error'] = $errCode;

$LOCATION = 'index.php?'.buildQuery($params);

?>
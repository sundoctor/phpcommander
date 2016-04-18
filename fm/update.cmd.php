<?php
if (!defined('ACTIVE')) die(__FILE__);

$filename = isset($_REQUEST['filename'])? $_REQUEST['filename'] : '';
$text     = isset($_REQUEST['text'])? $_REQUEST['text'] : '';

if ($filename=='') setErrorCode(4);
if (!file_exists($path.'/'.$filename)) setErrorCode(1);
else {
    if (!(is_file($path.'/'.$filename) &&
        is_writable($path.'/'.$filename)))
            setErrorCode(6);
}

if ($errCode==0) {
    
    $fp = @fopen($path.'/'.$filename,"w");
    @fwrite($fp, $text);
    @fclose($fp);
}

$params = array(
    'cmd'  => 'list',
    'sort' => $sort,
    'path' => urlencode($path)
);

if ($errCode!=0) $params['error'] = $errCode;

$LOCATION = 'index.php?'.buildQuery($params);

?>
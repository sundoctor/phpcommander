<?php
if (!defined('ACTIVE')) die(__FILE__);

$filename = isset($_REQUEST['filename'])? urldecode($_REQUEST['filename']) : '';
if ($filename=='') setErrorCode(4);
if (!file_exists($path.'/'.$filename)) setErrorCode(1);
if ($errCode==0) {
    $now = gmdate('D, d M Y H:i:s') . ' GMT';
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-type: application/octetstream');
    header('Content-Length: ' . filesize($path.'/'.$filename));
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Expires: '.$now);
    header('Pragma: no-cache');
    $fp = fopen($path.'/'.$filename,'rb');
    $contents = fread($fp,filesize($path.'/'.$filename));
    fclose($fp);
    echo $contents;
    exit;
} else {
    $params = array(
        'cmd'  => 'list',
        'sort' => $sort,
        'path' => urlencode($path),
        'error'=> $errCode
    );
    header("Location: index.php?".buildQuery($params));
    exit;
}
?>
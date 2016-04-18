<?php
if (!defined('ACTIVE')) die(__FILE__);
$filename = isset($_REQUEST['filename'])? urldecode($_REQUEST['filename']) : '';

if ($filename=='') setErrorCode(4);
if (!file_exists($path.'/'.$filename)) setErrorCode(1);
else {
    if (!(is_file($path.'/'.$filename) &&
         filesize($path.'/'.$filename)<=$MAXVIEWSIZE))
            setErrorCode(5);
}

if ($errCode==0) {
    $handle = @fopen($path.'/'.$filename, "r");
    $filesize = filesize($path.'/'.$filename);
    $contents = @fread($handle, $filesize);
    @fclose($handle);
    $pathinfo = pathinfo($filename);
    $ext = isset($pathinfo['extension'])? $pathinfo['extension'] : '';
    // Вывод на экран
    if (strtolower($ext)=='gif') {
        Header("Content-type: image/gif");
        echo $contents; exit;
    } else if (strtolower($ext)=='jpg') {
        Header("Content-type: image/jpeg");
        echo $contents; exit;
    } else if (strtolower($ext)=='png') {
        Header("Content-type: image/png");
        echo $contents; exit;
    } else if (in_array(strtolower($ext), array('php','php4','php3','phtml'))) {
        Header("Content-type: text/html");
        echo highlight_string($contents,TRUE); exit;
    } else if (in_array(strtolower($ext), array('html','htm'))) {
        Header("Content-type: text/html");
        echo $contents; exit;
    } else if (strtolower($ext)=='txt') {
        Header("Content-type: text/plain");
        Header("Content-Disposition: inline; filename=".$filename);
        echo $contents; exit;
    } else {
        $CONTENT = "<PRE>" . htmlspecialchars($contents) . "</PRE>\n";
    }
}
else
    $CONTENT = "<P>".$errMsg[$errCode]."</P>\n";

?>
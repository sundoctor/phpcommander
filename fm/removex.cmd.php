<?php
if (!defined('ACTIVE')) die(__FILE__);

$files = isset($_POST['file'])?$_POST['file']:array();
$dirarr = array();
$filearr = array();

foreach($files as $f) {
    $f = urldecode($f);
    $ft = substr($f,0,1);
    $f  = substr($f,1);
    if ($ft=='F')
        $filearr[] = $path.'/'.$f;
    else
        $dirarr[] = $path.'/'.$f;
}

function removeAllX($from) {
    global $dirarr, $filearr;
    if ($handle = @opendir($from)) {
        while (false !== ($file = readdir($handle))) {
            if ($file!='.' && $file!='..') {
                if (is_dir($from.'/'.$file)) {
                    if (!in_array($from.'/'.$file,$dirarr)) {
                        removeAllX($from.'/'.$file);
                        @rmdir($from.'/'.$file);
                    }
                } else if (!in_array($from.'/'.$file,$filearr)) {
                    @unlink($from.'/'.$file);
                }
            }
        }
        closedir($handle);
    }
}

removeAllX($path);

$params = array(
    'cmd'  => 'list',
    'sort' => $sort,
    'path' => urlencode($path)
);
$LOCATION = "index.php?".buildQuery($params);

?>
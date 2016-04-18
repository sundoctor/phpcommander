<?php
if (!defined('ACTIVE')) die(__FILE__);

$files = isset($_POST['file'])?$_POST['file'] : array();

if (count($files)>0) {
    foreach($files as $f) {
        $f = urldecode($f);
        $ft = substr($f,0,1);
        $f  = substr($f,1);
        if ($ft=='F') {
            if (file_exists($path.'/'.$f))
                @unlink($path.'/'.$f);
        } else {
            if (file_exists($path.'/'.$f)) {
                removeAll($path.'/'.$f);
                @rmdir($path.'/'.$f);
            }
        }
    }
}

$params = array(
    'cmd'  => 'list',
    'sort' => $sort,
    'path' => urlencode($path)
);
$LOCATION = "index.php?".buildQuery($params);

?>
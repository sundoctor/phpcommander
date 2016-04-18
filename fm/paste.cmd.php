<?php
if (!defined('ACTIVE')) die(__FILE__);

$files = $buffiles;
$to    = $path;
$from  = $bufsrcpath;

// Копирование
if ($bufopr == 'copy') {
    if ($to!='' && $to!=$from) {
        foreach($files as $f) {
            $f = urldecode($f);
            $ft = substr($f,0,1);
            $f  = substr($f,1);
            if ($ft=='F')
                @copy($from.'/'.$f, $to.'/'.$f);
            else {
                if (!file_exists($to.'/'.$f))
                    @mkdir($to.'/'.$f);
                copyAll($from.'/'.$f, $to.'/'.$f);
            }
        }
    }
    $LOCATION = "index.php?cmd=list&sort=$sort&path=".urlencode($path);
}
// Перемещение
else if ($bufopr == 'cut') {
    if ($to!='' && $to!=$from) {
        foreach($files as $f) {
            $f = urldecode($f);
            $ft = substr($f,0,1);
            $f  = substr($f,1);
            if ($ft=='F') {
                copy($from.'/'.$f, $to.'/'.$f);
                unlink($from.'/'.$f);
            }
            else {
                if (!file_exists($to.'/'.$f))
                    @mkdir($to.'/'.$f);
                moveAll($from.'/'.$f, $to.'/'.$f);
                @rmdir($from.'/'.$f);
            }
        }
    }
    $LOCATION = "index.php?cmd=list&sort=$sort&path=".urlencode($path);
}

$_SESSION['bufopr']     = '';
$_SESSION['bufsrcpath'] = '';
$_SESSION['buffiles']   = array();

?>
<?php
if (!defined('ACTIVE')) die(__FILE__);

$filename = isset($_REQUEST['file'])? urldecode($_REQUEST['file'][0]) : '';
if ($filename!='') $filename = substr($filename,1);

if ($filename=='') setErrorCode(4);
if (!file_exists($path.'/'.$filename)) setErrorCode(1);
else {
    if (!(is_file($path.'/'.$filename) && 
          filesize($path.'/'.$filename)<=$MAXVIEWSIZE))
              setErrorCode(5);
}

if ($errCode==0) {
    
    $fp   = fopen($path.'/'.$filename, "rb");
    $data = fread($fp, filesize($path.'/'.$filename));
    fclose($fp);
    $form = new TFileFormer('.');
    $form->setItems(array(
        'now'         => $now,
        'path'        => $path,
        'safepath'    => urlencode($path),
        'sort'        => $sort,
        'freespace'   => $freespace,
        'filename'    => htmlspecialchars($filename),
        'filecontent' => htmlspecialchars($data)
    ));
    $CONTENT = $form->transform('edit.html');
    
} else {
    
    $params = array(
        'cmd'  => 'list',
        'sort' => $sort,
        'path' => urlencode($path),
        'error'=> $errCode
    );
    $LOCATION = "index.php?".buildQuery($params);
}


?>
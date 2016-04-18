<?php
if (!defined('ACTIVE')) die(__FILE__);

$filename = '';
if (isset($_REQUEST['file']) && is_array($_REQUEST['file']))
    $filename = urldecode($_REQUEST['file'][0]);

if (!file_exists($path.'/'.substr($filename,1))) {
    setErrorCode(1);
}

if ($errCode == 0) {
    
    $a = getTextPerm(getFilePerm($path, substr($filename,1)));
    
    $r = array(
        'ur' => $a{1}=='r'?' checked':'',
        'uw' => $a{2}=='w'?' checked':'',
        'ux' => $a{3}=='x'?' checked':'',
        'gr' => $a{4}=='r'?' checked':'',
        'gw' => $a{5}=='w'?' checked':'',
        'gx' => $a{6}=='x'?' checked':'',
        'or' => $a{7}=='r'?' checked':'',
        'ow' => $a{8}=='w'?' checked':'',
        'ox' => $a{9}=='x'?' checked':''
    );
    
    $form = new TFileFormer('.');
    $form->setItems($r);
    $form->setItems(array(
        'now'       => $now,
        'path'      => htmlspecialchars($path),
        'safepath'  => urlencode($path),
        'sort'      => $sort,
        'freespace' => $freespace,
        'filename'   => htmlspecialchars($filename),
        'xfilename'  => htmlspecialchars(substr($filename,1))
    ));
    $CONTENT = $form->transform('attr.html');

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
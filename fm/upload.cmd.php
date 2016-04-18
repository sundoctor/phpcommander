<?php
if (!defined('ACTIVE')) die(__FILE__);

if (!(isset($_FILES['userfile']) &&
        is_uploaded_file($_FILES['userfile']['tmp_name'])))
            setErrorCode(1);

if ($errCode==0) {
    $filename = $_FILES['userfile']['name'];
    if (!file_exists($path.'/'.$filename)) {
        $fp = @fopen($_FILES['userfile']['tmp_name'], "rb");
        $data = @fread($fp, $_FILES['userfile']['size']);
        @fclose($fp);
        // Сохраняем файл
        $fp = @fopen($path.'/'.$filename,'wb');
        @fwrite($fp,$data,$_FILES['userfile']['size']);
        @fclose($fp);
    } else {
        setErrorCode(2);
    }
}

$params = array(
    'cmd'  => 'list',
    'sort' => $sort,
    'path' => urlencode($path)
);

if ($errCode!=0) $params['error'] = $errCode;

$LOCATION = 'index.php?'.buildQuery($params);

?>
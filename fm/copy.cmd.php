<?php
if (!defined('ACTIVE')) die(__FILE__);

$files = isset($_POST['file'])?$_POST['file']:array();

$_SESSION['bufopr']     = 'copy';
$_SESSION['bufsrcpath'] = $path;
$_SESSION['buffiles']   = $files;

$LOCATION = "index.php?cmd=list&sort=$sort&path=".urlencode($path);

?>
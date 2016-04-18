<?php
if (!defined('ACTIVE')) die(__FILE__);

$_SESSION['bufopr']     = '';
$_SESSION['bufsrcpath'] = '';
$_SESSION['buffiles']   = array();

$LOCATION = "index.php?cmd=list&sort=$sort&path=".urlencode($path);

?>
<?php
if (!defined('ACTIVE')) die(__FILE__);

session_start();

srand((double)microtime()*1000000);

if (!isset($_SESSION['registered'])) {
    $_SESSION['registered'] = false;
}

?>
<?php
if (!defined('ACTIVE')) die(__FILE__);

$c = new TFileFormer('.');
$screen[] = $c->transform('login.html');
$CONTENT = $screen;
$TEMPLATE = 'center.html';

?>
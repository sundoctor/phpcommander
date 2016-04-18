<?php
if (!defined('ACTIVE')) die(__FILE__);

removeAll($path);

$params = array(
    'cmd'  => 'list',
    'sort' => $sort,
    'path' => urlencode($path)
);
$LOCATION = "index.php?".buildQuery($params);
    
?>
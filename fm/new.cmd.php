<?php
if (!defined('ACTIVE')) die(__FILE__);

$text = isset($_REQUEST['text'])?
        htmlspecialchars(substr($_REQUEST['text'],0,500000)): '';
$new = new TFileFormer('.');
$new->setItems(array(
    'now'       => $now,
    'path'      => htmlspecialchars($path),
    'safepath'  => urlencode($path),
    'sort'      => $sort,
    'freespace' => $freespace,
    'text'      => $text
));
$CONTENT = $new->transform('new.html');

?>
<?php
if (!defined('ACTIVE')) die(__FILE__);

$form = new TFileFormer('.');
$form->setItems(array(
    'now'       => $now,
    'path'      => htmlspecialchars($path),
    'safepath'  => urlencode($path),
    'sort'      => $sort,
    'freespace' => $freespace
));
$CONTENT = $form->transform('newdir.html');

?>
<?php
if (!defined('ACTIVE')) die(__FILE__);

$oldname = '';
if (isset($_REQUEST['file']) && is_array($_REQUEST['file']))
    $oldname = urldecode($_REQUEST['file'][0]);

$form = new TFileFormer('.');
$form->setItems(array(
    'now'       => $now,
    'path'      => htmlspecialchars($path),
    'safepath'  => urlencode($path),
    'sort'      => $sort,
    'freespace' => $freespace,
    'oldname'   => htmlspecialchars($oldname),
    'xoldname'  => htmlspecialchars(substr($oldname,1))
));
$CONTENT = $form->transform('rename.html');

?>
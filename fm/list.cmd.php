<?php

if (!defined('ACTIVE')) die(__FILE__);

clearstatcache();
$files = getFiles($path);

switch ($sortord) {
    case 'name' : usort($files, 'cmpName'); break;
    case 'ext'  : usort($files, 'cmpExt');  break;
    case 'size' : usort($files, 'cmpSize'); break;
    case 'date' : usort($files, 'cmpDate'); break;
}

$p = new TTextFormer('.');
$p->load("table.html");
// JavaScript Menu
$js = join('',file('menu.js'));
$js = str_replace('<!--$safepath-->',urlencode($path),$js);
// Таблица-форма
$vars = array(
    'js'          => $js,
    'now'         => $now,
    'path'        => htmlspecialchars($path),
    'safepath'    => urlencode($path),
    'sort'        => htmlspecialchars($sort),
    'freespace'   => $freespace
);
$p->post("1","first",$vars);

$c = 0;

foreach($files as $file) {
    // Проверка наличия файла в буфере
    $rowcol = '#E6E6E6';
    $cname = urlencode($file['type'].$file['fullname']);
    $test = ($path==$bufsrcpath) && in_array($cname, $buffiles);
    if ($test) $rowcol = '#FFFF80';
    // Вывод строки
    $fileline = array(
        'rowcol' => $rowcol,
        'chk'    => file2checkbox($file),
        'attr'   => $file['attr'],
        'name'   => color(file2html($path,$file),$file['ext']),
        'ext'    => color($file['ext'],$file['ext']),
        'size'   => number_format($file['size']),
        'date'   => $file['date']
    );
    $p->post("2","middle",$fileline);
    $c++;
}

include('drives.inc.php');
$vars['filter'] = $drives;
$vars['errmsg'] = $errMsg[$errCode];
$p->post("3","last",$vars);
$CONTENT = $p->getText();


?>
<?php

// П А Р А М Е Т Р Ы   Ф А Й Л О В

// Получение имени файла без расширения
function getFileName($path, $filename) {
    if (!is_string($filename)) return '';
    if (is_dir($path.'/'.$filename)) return $filename;
    $pos = strrpos($filename, ".");
    if ($pos === false) return $filename;
    return substr($filename,0,$pos);
}

// Получение расширения файла без имени
function getFileExt($path, $filename) {
    if (!is_string($filename)) return '';
    if (is_dir($path.'/'.$filename)) return '[DIR]';
    $pos = strrpos($filename, ".");
    if ($pos === false) return '';
    return substr($filename, $pos+1);
}

// Получение типа файла (каталог/ссылка/файл)
function getFileType($path, $filename) {
    if (is_dir($path.'/'.$filename)) return 'D';
    if (is_link($path.'/'.$filename)) return '@';
    //if (is_executable($path.'/'.$filename)) return '*';
    return 'F';
}

// Получение даты файла
function getFileDate($path, $filename) {
    return date("Y-m-d H:i:s", filemtime($path.'/'.$filename));
}

// Получение размера файла
function getFileSize($path, $filename) {
    return filesize($path.'/'.$filename);
}

// Расшифровка прав файла
function getTextPerm( $in_Perms ) {
    if($in_Perms & 0x1000)     // FIFO pipe
        $sP = 'p';
    elseif($in_Perms & 0x2000) // Character special
        $sP = 'c';
    elseif($in_Perms & 0x4000) // Directory
        $sP = 'd';
    elseif($in_Perms & 0x6000) // Block special
        $sP = 'b';
    elseif($in_Perms & 0x8000) // Regular
        $sP = '-';
    elseif($in_Perms & 0xA000) // Symbolic Link
        $sP = 'l';
    elseif($in_Perms & 0xC000) // Socket
        $sP = 's';
    else                         // UNKNOWN
        $sP = 'u';

    // owner
    $sP .= (($in_Perms & 0x0100) ? 'r' : '-;') .
           (($in_Perms & 0x0080) ? 'w' : '-') .
           (($in_Perms & 0x0040) ? (($in_Perms & 0x0800) ? 's' : 'x' ) :
                                   (($in_Perms & 0x0800) ? 'S' : '-'));

    // group
    $sP .= (($in_Perms & 0x0020) ? 'r' : '-') .
           (($in_Perms & 0x0010) ? 'w' : '-') .
           (($in_Perms & 0x0008) ? (($in_Perms & 0x0400) ? 's' : 'x' ) :
                                   (($in_Perms & 0x0400) ? 'S' : '-'));

    // world
    $sP .= (($in_Perms & 0x0004) ? 'r' : '-') .
           (($in_Perms & 0x0002) ? 'w' : '-') .
           (($in_Perms & 0x0001) ? (($in_Perms & 0x0200) ? 't' : 'x' ) :
                                   (($in_Perms & 0x0200) ? 'T' : '-'));
    return $sP;
}

// Получение прав файла
function getFilePerm($path, $filename) {
    return fileperms($path.'/'.$filename);
}

// Получение списка файлов в каталоге
function getFiles($path) {
    $res = array();
    if ($dir = @opendir($path)) {
        while (false !== ($file = readdir($dir))) {
            if ($file != '.')
            $res[] = array(
                'fullname' => $file,
                'name'     => getFileName($path, $file),
                'ext'      => getFileExt($path, $file),
                'type'     => getFileType($path, $file),
                'date'     => getFileDate($path, $file),
                'size'     => getFileSize($path, $file),
                'attr'     => getTextPerm(getFilePerm($path, $file))
            );
        }
        closedir($dir);
    } else {
        setErrorCode(3);
    }
    return $res;
}

// Ф А Й Л О В Ы Е   О П Е Р А Ц И И

// Копирование из каталога в каталог
function copyAll($from, $to) {
    if ($handle = @opendir($from)) {
        while (false !== ($file = readdir($handle))) {
            if ($file!='.' && $file!='..') {
                if (is_dir($file)) {
                    mkdir($to.'/'.$file);
                    copyAll($from.'/'.$file, $to.'/'.$file);
                } else {
                    copy($from.'/'.$file, $to.'/'.$file);
                }
            }
        }
        closedir($handle);
    }
}

// Перемещение файлов из каталого в каталог
function moveAll($from, $to) {
    if ($handle = @opendir($from)) {
        while (false !== ($file = readdir($handle))) {
            if ($file!='.' && $file!='..') {
                if (is_dir($file)) {
                    mkdir($to.'/'.$file);
                    moveAll($from.'/'.$file, $to.'/'.$file);
                    rmdir($from.'/'.$file);
                } else {
                    copy($from.'/'.$file, $to.'/'.$file);
                    unlink($from.'/'.$file);
                }
            }
        }
        closedir($handle);
    }
}

// Удаление всех файлов в каталоге
function removeAll($from) {
    if ($handle = @opendir($from)) {
        while (false !== ($file = readdir($handle))) {
            if ($file!='.' && $file!='..') {
                if (is_dir($file)) {
                    removeAll($from.'/'.$file);
                    rmdir($from.'/'.$file);
                } else {
                    unlink($from.'/'.$file);
                }
            }
        }
        closedir($handle);
    }
}


// С О Р Т И Р О В К А   Ф А Й Л О В

// Сравнение двух имен
function cmpTwoNames($a, $b) {
    if ($a['name']==$b['name']) return 0;
    if ($a['name']=='..') return -1;
    if ($b['name']=='..') return 1;
    return ($a['name'] < $b['name']) ? -1 : 1;
}

// Сортировка по именам файлов
function cmpName ($a, $b) {
    global $sortdir;
    // Если оба не каталоги
    if ($a['type']!='D' && $b['type']!='D') {
        if ($sortdir=='az')
            return ($a['name'] < $b['name']) ? -1 : 1;
        else
            return ($a['name'] > $b['name']) ? -1 : 1;
    }
    // Если оба каталоги
    else if ($a['type']=='D' && $b['type']=='D') {
        return cmpTwoNames($a,$b);
    }
    // Если первый каталог, а второй файл
    else if ($a['type']=='D' && $b['type']!='D')
        return -1;
    // Если первый файл, а второй каталог
    else if ($a['type']!='D' && $b['type']=='D')
        return 1;
}

// Сортировка по расширению
function cmpExt($a, $b) {
    global $sortdir;
    // Если оба каталоги или оба - файлы
    if (($a['type']=='D' && $b['type']=='D') ||
        ($a['type']!='D' && $b['type']!='D')) {
        if ($a['ext'] == $b['ext'])
             return cmpTwoNames($a,$b);
        if ($sortdir=='az')
            return ($a['ext'] < $b['ext']) ? -1 : 1;
        else
            return ($a['ext'] > $b['ext']) ? -1 : 1;
    }
    // Если первый каталог, а второй файл
    else if ($a['type']=='D' && $b['type']!='D')
        return -1;
    // Если первый файл, а второй каталог
    else if ($a['type']!='D' && $b['type']=='D')
        return 1;
}

// Сортировка по размеру
function cmpSize($a, $b) {
    global $sortdir;
    // Если оба каталоги или оба - файлы
    if (($a['type']!='D' && $b['type']!='D') ||
        ($a['type']=='D' && $b['type']=='D')) {
        if ($a['size'] == $b['size'])
            return cmpTwoNames($a,$b);
        if ($sortdir=='az')
            return ($a['size'] < $b['size']) ? -1 : 1;
        else
            return ($a['size'] > $b['size']) ? -1 : 1;
    }
    // Если первый каталог, а второй файл
    else if ($a['type']=='D' && $b['type']!='D')
        return -1;
    // Если первый файл, а второй каталог
    else if ($a['type']!='D' && $b['type']=='D')
        return 1;
}

// Сортировка по дате
function cmpDate($a, $b) {
    global $sortdir;
    // Если оба не каталоги
    if ($a['type']!='D' && $b['type']!='D') {
        if ($a['date'] == $b['date'])
            return cmpTwoNames($a,$b);
        if ($sortdir=='az')
            return ($a['date'] < $b['date']) ? -1 : 1;
        else
            return ($a['date'] > $b['date']) ? -1 : 1;
    }
    // Если оба каталоги
    else if ($a['type']=='D' && $b['type']=='D') {
        return cmpTwoNames($a,$b);
    }
    // Если первый каталог, а второй файл
    else if ($a['type']=='D' && $b['type']!='D')
        return -1;
    // Если первый файл, а второй каталог
    else if ($a['type']!='D' && $b['type']=='D')
        return 1;
}

// H T M L

// Подсветка
function color($str, $ext) {
    global $COLORS;
    if (isset($COLORS[$ext]))
        return '<FONT COLOR="'.$COLORS[$ext].'">'.$str.'</FONT>';
    return $str;
}

// Преобразование файла в HTML
function file2html($path, $fileinfo) {
    global $sort;
    // Если это файл
    if ($fileinfo['type'] != 'D')
        return $fileinfo['name'];
    // Если это каталог
    else {
        $goto = $path."/".$fileinfo['fullname'];
        if ($fileinfo['name']=='..') {
            // Вычисляем сокращенный путь
            $a = preg_split('#[\\/]#',$path);
            if (count($a)>0)
                unset($a[count($a)-1]);
            $goto = join('/',$a);
        }
        $html = '<A href="index.php?cmd=list&sort='.$sort.
                '&path='.urlencode($goto).'">'.$fileinfo['name'].'</A>';
        return $html;
    }
}

// Получение формочки для файла
function file2checkbox($fileinfo) {
    if ($fileinfo['type'] != 'D')
        return '<INPUT type="checkbox" name="file[]" '.
               'value="F'.urlencode($fileinfo['fullname']).'">';
    if ($fileinfo['fullname']!='..')
        return '<INPUT type="checkbox" name="file[]" '.
               'value="D'.urlencode($fileinfo['fullname']).'">';
    return '&nbsp;&nbsp;&nbsp;&nbsp;';
}

// Установка кода ошибки
function setErrorCode($newcode) {
    global $errCode, $errMsg;
    if (is_numeric($newcode) && $newcode>=0 && $newcode<count($errMsg)) {
        if ($errCode==0) $errCode = $newcode;
    } else {
        $errCode = 0;
    }
}

// Подготовка строки параметров
function buildQuery($a) {
    $result = array();
    foreach ($a as $k=>$v) {
        $result[] = "$k=$v";
    }
    return join('&',$result);
}
  
?>

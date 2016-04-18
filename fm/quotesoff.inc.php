<?php
/*////////////////////////////////////////////////////////////////////

 Модуль очистки глобальных переменных
 и удаления слешей во входных данных

 Подключается сразу после config.inc.php

 File    : quotesoff.inc.php
 Version : 1.02

 Igor Salnikov, Copyright(C), 1995-2016

 Error found? Nothing is perfect...

////////////////////////////////////////////////////////////////////*/

$SAVEVARS = array(
    'GLOBALS',
    'ROOT',
    'PATH',
    'CHARSET',
    'TITLE',
    'DESCRIPTION',
    'KEYWORDS',
    'CONTENT',
    'TEMPLATE',
    'CONST',
    'BLOCKED',
    'MYREFERS',
    'NOW',
    'TIMEPOINT',
    'TIMEPOINT_START',
    'COLORS',
    'BASEPATH',
    'STARTDIR',
    'MAXVIEWSIZE',
    'WINDOWS',
    'WINDRIVES'
);


// Очистка слэшей

function stripVars(&$var) {
    global $SAVEVARS;
    if (is_array($var)) {
        foreach($var as $k=>$v) {
            if ($k!='GLOBALS' && $k!='_FILES' && !in_array($k, $SAVEVARS)) {
                stripVars($var[$k]);
            }
        }
    } else {
        $var = stripslashes($var);
    }
}

if (get_magic_quotes_gpc()) stripVars($GLOBALS);


// Очистка глобальных переменных

function clearGlobals(&$var) {
    global $SAVEVARS;
    if (is_array($var)) {
        foreach($var as $k=>$v) {
            if (!in_array($k, $SAVEVARS)) {
                if (isset($GLOBALS[$k]))
                    unset($GLOBALS[$k]);
              }
        }
    }
}
clearGlobals($_REQUEST);
clearGlobals($_SESSION);
clearGlobals($_ENV);
clearGlobals($_SERVER);

?>
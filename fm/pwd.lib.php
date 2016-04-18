<?php
/*////////////////////////////////////////////////////////////////////

 Класс для управления логинами и паролями для входа

 File    : pwd.lib.php
 Version : 1.01

 Igor Salnikov, Copyright(C), 1995-2004

 Error found? Nothing is perfect...

////////////////////////////////////////////////////////////////////*/

define('DEFAULTPWDFILE', '.userlist');

class TPwd {

    var $filename = DEFAULTPWDFILE;

    function pwdOpen($filename=DEFAULTPWDFILE) {
        $a = array();
        if (file_exists($filename)) {
            $c = file($filename);
            foreach($c as $s) {
                list($k,$v) = explode(':',rtrim($s));
                $a[$k] = $v;
            }
        }
        return $a;
    }

    function pwdSave($filename=DEFAULTPWDFILE, $a) {
        $s = '';
        foreach($a as $k=>$v)
            $s.="$k:$v\r\n";
        $fp = fopen($filename, 'w');
        fwrite($fp, $s);
        fclose($fp);
    }

    function pwdAdd($login, $pwd) {
        $login = strtolower($login);
        $a = $this->pwdOpen($this->filename);
        $a[$login] = md5($pwd);
        $this->pwdSave($this->filename, $a);
    }

    function pwdRem($login, $pwd='') {
        $login = strtolower($login);
        $a = $this->pwdOpen($this->filename);
        if (isset($a[$login])) {
            if ($pwd!='' && $a[$login]==md5($pwd)) unset($a[$login]);
            else if ($pwd=='') unset($a[$login]);
        }
        $this->pwdSave($this->filename, $a);
    }

    function pwdChk($login, $pwd) {
        $login = strtolower($login);
        $a = $this->pwdOpen($this->filename);
        if (isset($a[$login]) && $a[$login]==md5($pwd))
            return true;
        return false;
    }

} // class

?>

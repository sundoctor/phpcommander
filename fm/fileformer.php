<?php
/*////////////////////////////////////////////////////////////////////

 TFileFormer, v1.07

 File    : fileformer.php
 Version : 1.07

 Igor Salnikov, Copyright(C), 1995-2016

 Error found? Nothing is perfect...

////////////////////////////////////////////////////////////////////*/

//////////////////////////////////////////////////////////////////////
// #class TFileFormer
//      Класс - многоуровневый шаблон
//////////////////////////////////////////////////////////////////////
class TFileFormer {

    var $rootdir = '';
    var $params  = array();
    var $cache   = array();

//////////////////////////////////////////////////////////////////////
// #constructor TFileFormer([string $setroot])
//      Конструктор класса
//////////////////////////////////////////////////////////////////////
    function __construct($setroot = '.') {
        $this->rootdir = $setroot;
    }

//////////////////////////////////////////////////////////////////////
// #method void setItem(string $name, string $value)
//      Установка параметра
//////////////////////////////////////////////////////////////////////
    function setItem($name, $value) {
        $this->params[$name] = $value;
    }

//////////////////////////////////////////////////////////////////////
// #method any getItem(string $name)
//      Получение параметра
//////////////////////////////////////////////////////////////////////
    function getItem($name) {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        }
        return null;
    }

//////////////////////////////////////////////////////////////////////
// #method string getViewItem(string $name)
//      Получение параметра, подготовленного к выводу на экран
//////////////////////////////////////////////////////////////////////
    function getViewItem($name) {
        $v = $this->getItem($name);
        if ($v!==null) {
            if (is_array($v))
                return join('', $v);
            return $v;
        }
        return '';
    }
//////////////////////////////////////////////////////////////////////
// #method void setItems(array $attrs)
//      Установка группы параметров
//////////////////////////////////////////////////////////////////////
    function setItems($attrs) {
        while(list($k,$v) = each($attrs)) {
            $this->params[$k] = $v;
        }
    }

//////////////////////////////////////////////////////////////////////
// #method array getItems(array $attrs)
//      Получение группы параметров
//////////////////////////////////////////////////////////////////////
    function getItems($attrs) {
        $result = array();
        foreach($attrs as $k) {
            if (isset($this->params[$k]))
                $result[$k] = $this->params[$k];
        }
        return $result;
    }

//////////////////////////////////////////////////////////////////////
// #method void clear()
//      Очистка
//////////////////////////////////////////////////////////////////////
    function clear() {
        $this->params = array();
        $this->cache  = array();
    }

//////////////////////////////////////////////////////////////////////
// #method transform(string $filename)
//      Основной метод трансформирования
//      Ищет и заменяет конструкции вида:
//      <!--#/test.html-->   <!--$var-->
//////////////////////////////////////////////////////////////////////
    function transform($filename) {
        $usefile = filename($filename, $this->rootdir);
        $test = isset($this->cache[$usefile]) || file_exists($usefile);
        if (isset($this->cache[$usefile])) {
            $s = $this->cache[$usefile];
        }
        else if (file_exists($usefile)) {
            $s = join('', file($usefile));
            $this->cache[$usefile] = $s;
        }
        if ($test) {
            $search  = "/<!--#(.*?)-->/";
            $s = preg_replace_callback($search, function ($m) {
                return TFileFormer::transform($m[1]);
            }, $s);
            $search  = "/<!--\\$(.*?)-->/";
            $s = preg_replace_callback($search, function ($m) {
                return TFileFormer::getViewItem($m[1]);
            }, $s);
            return $s;
        }
        return '';
    }

} // class TFileFormer


//////////////////////////////////////////////////////////////////////
// #class TTextFormer
//      Класс - многоступенчатый шаблон
//////////////////////////////////////////////////////////////////////
class TTextFormer {

    var $rootdir     = '';
    var $filecontent = array();
    var $out         = array();

    function __construct($setroot = '.') {
        $this->rootdir = $setroot;
    }

    function clear() {
        $this->file       = array();
        $this->out        = array();
    }

    function load($filename="") {
        $usefile = filename($filename, $this->rootdir);
        $filecontent = '';
        if (file_exists($usefile)) {
            $filecontent = join('', file($usefile));
        }
        $reg = "/<!--\+(\w+?)\+-->/ims";
        if (preg_match_all($reg, $filecontent, $m)) {
            $matches = $m[1];
            foreach ($matches as $v) {
                $reg = "/<!--\+$v\+-->(.*)<!--=$v=-->/ims";
                if (preg_match_all($reg, $filecontent, $m))
                    $this->file[$v] = $m[1][0];
            }
        }
    }

    function post($outhandle, $filehandle, $vars=array()) {
        $search  = "/<!--\\$(.*?)-->/";
        $s = '';
        if (isset($this->file[$filehandle]))
            $s = $this->file[$filehandle];
        if (preg_match_all($search, $s, $matches)!==false) {
            foreach($matches[1] as $v) {
                $search = "<!--$".$v."-->";
                if (isset($vars[$v]))
                    $s = str_replace($search, $vars[$v], $s);
                else
                    $s = str_replace($search, '', $s);
            }
        }
        if (!isset($this->out[$outhandle]))
            $this->out[$outhandle] = array();
        $this->out[$outhandle][] = $s;
    }

    function getText($n = '') {
        $result = '';
        if ($n=='')
            foreach($this->out as $k=>$v) {
                $result .= join('',$this->out[$k]);
            }
        else
            $result = join('',$this->out[$n]);
        return $result;
    }
} // class TTextFormer

//////////////////////////////////////////////////////////////////////
// #function string filename(string $filename, string $rootdir)
//      Формирование имени файла с базовым путем
//////////////////////////////////////////////////////////////////////
function filename($filename, $rootdir='.') {
    $filename = trim($filename);
    if (substr($filename, 0, 1) != "/")
        $new_filename = $rootdir.'/'.$filename;
    else
        $new_filename = $filename;
    return $new_filename;
}

//////////////////////////////////////////////////////////////////////
// #function string FileFormer(string $filename, string $rootdir)
//      Отдельная шаблонная функция
//////////////////////////////////////////////////////////////////////
function FileFormer($filename, $rootdir='.') {
    $usefile = filename($filename, $rootdir);
    if (file_exists($usefile)) {
        $s = join('', file($usefile));
        $search  = "/<!--#(.*?)-->/";
        $s = preg_replace_callback($search, function ($m) use ($rootdir) {
            return FileFormer($m[1],$rootdir);
        }, $s);
        return $s;
    }
    return "";
}

//////////////////////////////////////////////////////////////////////
// #function string ParamFileFormer(string $filename, array $params)
//      Шаблонная функция с параметрами
//////////////////////////////////////////////////////////////////////
function ParamFileFormer($filename, $rootdir='.', $params=array()) {
    $s = FileFormer($filename, $rootdir);
    foreach($params as $k => $v) {
        $search  = "<!--$".$k."-->";
        $s = str_replace($search,$v,$s);
    }
    $search  = "/<!--\\$(.*?)-->/";
    $s = preg_replace($search, '', $s);
    return $s;
}

/*
    include_once('./lib/fileformer/fileformer.php');

    Example 1:
        echo FileFormer('form.html', '.');

    Example 2:
        echo ParamFileFormer('form.html', '.', array('test'=>'<B>test</B>'));

    Example 3:
        $c = new TFileFormer('.');
        $c->setItem("test","<U>test</U>");
        echo $c->transform('form.html');

    Example 4:
        $p = new TTextFormer('.');
        $p->load("one", "form.html");
        $p->post("1", "one", array("test" => "<B>test</B>"));
        echo $p->getText();
*/

?>

<?php
include('pwd.lib.php');

if (!isset($argc)) exit;

$pwd = new TPwd();
if ($argc==1) {
  echo "USAGE\n";
  echo "Check:  php.php c login password\n";
  echo "Add:    php.php a login password\n";
  echo "Remove: php.php r login [password]\n";
} else if ($argc==4 && $argv[1]=='c') {
    if ($pwd->pwdChk($argv[2],$argv[3])) echo "Passed"; else echo "Failed";
} else if ($argc==4 && $argv[1]=='a') {
    $pwd->pwdAdd($argv[2],$argv[3]);
} else if ($argc==4 && $argv[1]=='r') {
    $pwd->pwdRem($argv[2],$argv[3]);
} else if ($argc==3 && $argv[1]=='r') {
    $pwd->pwdRem($argv[2]);
}

?>
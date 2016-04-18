<?php
if (!defined('ACTIVE')) die(__FILE__);

$filename = isset($_REQUEST['filename'])? $_REQUEST['filename'] : '';
$text = isset($_REQUEST['text'])? $_REQUEST['text'] : '';

if ($filename=='') setErrorCode(4);
if (file_exists($path.'/'.$filename)) setErrorCode(2);

if ($errCode==0) {

    $fp = @fopen($path.'/'.$filename,"w");
    @fwrite($fp, $text);
    @fclose($fp);
    
    $params = array(
        'cmd'  => 'list',
        'sort' => $sort,
        'path' => urlencode($path),
        'error'=> $errCode
    );
    $LOCATION = "index.php?".buildQuery($params);

} else {

$text = htmlspecialchars($text);
$path = htmlspecialchars($path);
$OUTPUT =<<<EOL
<HTML>
<BODY>
<FORM action="index.php" method="post" name="next">
<INPUT TYPE="hidden" NAME="cmd" VALUE="new">
<INPUT TYPE="hidden" NAME="path" VALUE="$path">
<INPUT TYPE="hidden" NAME="sort" VALUE="$sort">
<INPUT TYPE="hidden" NAME="text" VALUE="$text">
</FORM>
<SCRIPT>
document.next.submit();
</SCRIPT>
</BODY>
</HTML>
EOL;

}


?>
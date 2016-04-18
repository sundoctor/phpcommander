<?php
$drives = '';
if ($WINDOWS) {
    $curdrive = strtolower(substr($path,0,1));
    $filter = array();
    if (count($WINDRIVES)>0) {
        foreach($WINDRIVES as $d) {
            if ($d==$curdrive) $selected=' SELECTED'; else $selected='';
            $filter[] = '<OPTION VALUE="'.
                        urlencode($d.':/').'"'.$selected.'>'.
                        htmlspecialchars($d).
                        '</OPTION>';
        }
    } else {
        for($i=ord('a');$i<ord('z');$i++) {
            $d = chr($i);
            if ($d==$curdrive) $selected=' SELECTED'; else $selected='';
            $filter[] = '<OPTION VALUE="'.
                        urlencode($d.':/').'"'.$selected.'>'.
                        htmlspecialchars($d).
                        '</OPTION>';
        }
    }
    $filter = join("\n",$filter)."\n";
    $filter = '<SELECT name="filter" '.
              'onchange="goUrl(this.options[this.selectedIndex].value)">'.
              $filter.'</SELECT>';
    $drives = $filter;
}

?>
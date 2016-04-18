<?php

$html = new TFileFormer('.');
$html->setItems(
    array(
        "title"          => join(' ~ ',$TITLE),
        "description"    => $KEYWORDS,
        "keywords"       => $DESCRIPTION,
        "content"        => $CONTENT
    )
);

$OUTPUT = $html->transform($TEMPLATE);

foreach($CONST as $k=>$v) {
    $OUTPUT = str_replace('#'.$k,$v,$OUTPUT);
}

?>
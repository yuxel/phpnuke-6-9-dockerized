<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (eregi("block-Ephemerids.php",$_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}

global $prefix, $multilingual, $currentlang, $db;

if ($multilingual == 1) {
    $querylang = "AND elanguage='$currentlang'";
} else {
    $querylang = "";
}

$today = getdate();
$eday = $today[mday];
$emonth = $today[mon];
$title = ""._EPHEMERIDS."";
$content = "<b>"._ONEDAY."</b><br>";
$sql = "SELECT yid, content FROM ".$prefix."_ephem WHERE did='$eday' AND mid='$emonth' $querylang";
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)) {
    if ($cnt == 1) {
	$boxstuff .= "<br><br>";
    }
    $content .= "<b>$row[yid]</b><br>";
    $content .= "$row[content]";
    $cnt = 1;
}

?>
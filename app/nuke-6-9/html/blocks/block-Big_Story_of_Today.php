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

if (eregi("block-Big_Story_of_Today.php", $_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}

global $cookie, $prefix, $multilingual, $currentlang, $db;

if ($multilingual == 1) {
    $querylang = "AND (alanguage='$currentlang' OR alanguage='')"; /* the OR is needed to display stories who are posted to ALL languages */
} else {
    $querylang = "";
}

$today = getdate();
$day = $today["mday"];
if ($day < 10) {
    $day = "0$day";
}
$month = $today["mon"];
if ($month < 10) {
    $month = "0$month";
}
$year = $today["year"];
$tdate = "$year-$month-$day";
$sql = "SELECT sid, title FROM ".$prefix."_stories WHERE (time LIKE '%$tdate%') $querylang ORDER BY counter DESC LIMIT 0,1";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$fsid = $row['sid'];
$ftitle = $row[title];
$content = "<font class=\"content\">";
if ((!$fsid) AND (!$ftitle)) {
    $content .= ""._NOBIGSTORY."</font>";
} else {
    $content .= ""._BIGSTORY."<br><br>";
    $r_options = "";
    if (isset($cookie[4])) { $r_options .= "&amp;mode=$cookie[4]"; }
    if (isset($cookie[5])) { $r_options .= "&amp;order=$cookie[5]"; }
    if (isset($cookie[6])) { $r_options .= "&amp;thold=$cookie[6]"; }
    $content .= "<a href=\"modules.php?name=News&amp;file=article&amp;sid=$fsid$r_options\">$ftitle</a></font>";
}

?>
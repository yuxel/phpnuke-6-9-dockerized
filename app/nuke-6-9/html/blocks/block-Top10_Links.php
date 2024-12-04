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

if (eregi("block-Top10_Links.php",$_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}

global $prefix, $db;

$a = 1;
$sql = "SELECT lid, title FROM ".$prefix."_links_links ORDER BY hits DESC LIMIT 0,10";
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)) {
    $title2 = ereg_replace("_", " ", $row[title]);
    $content .= "<strong><big>&middot;</big></strong>&nbsp;$a: <a href=\"modules.php?name=Web_Links&amp;l_op=viewlinkdetails&amp;lid=$row[lid]&amp;ttitle=$row[title]\">$title2</a><br>";
    $a++;
}

?>
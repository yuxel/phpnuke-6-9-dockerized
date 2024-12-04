<?php

/************************************************************************/
/* PHP-NUKE: Advanced Content Management System                         */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

include("mainfile.php");

header("Content-Type: text/xml");
if ($cat != "") {
    $sql = "SELECT catid FROM ".$prefix."_stories_cat WHERE title LIKE '%$cat%' LIMIT 1";
    $result = $db->sql_query($sql);
    $catid = $db->sql_fetchrow($result);
    if ($catid == "") {
	$sql = "SELECT sid, title FROM ".$prefix."_stories ORDER BY sid DESC LIMIT 10";
	$result = $db->sql_query($sql);
    } else {
	$sql = "SELECT sid, title FROM ".$prefix."_stories WHERE catid='$catid' ORDER BY sid DESC LIMIT 10";
	$result = $db->sql_query($sql);
    }
} else {
    $sql = "SELECT sid, title FROM ".$prefix."_stories ORDER BY sid DESC LIMIT 10";
    $result = $db->sql_query($sql);
}

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n\n";
echo "<!DOCTYPE rss PUBLIC \"-//Netscape Communications//DTD RSS 0.91//EN\"\n";
echo " \"http://my.netscape.com/publish/formats/rss-0.91.dtd\">\n\n";
echo "<rss version=\"0.91\">\n\n";
echo "<channel>\n";
echo "<title>".htmlspecialchars($sitename)."</title>\n";
echo "<link>$nukeurl</link>\n";
echo "<description>".htmlspecialchars($backend_title)."</description>\n";
echo "<language>$backend_language</language>\n\n";

while ($row = $db->sql_fetchrow($result)) {
    echo "<item>\n";
    echo "<title>".htmlspecialchars($row[title])."</title>\n";
    echo "<link>$nukeurl/modules.php?name=News&amp;file=article&amp;sid=$row[sid]</link>\n";
    echo "</item>\n\n";
}
echo "</channel>\n";
echo "</rss>";

?>
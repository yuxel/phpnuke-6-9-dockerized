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

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);
$pagetitle = "- "._ACTIVETOPICS."";
include("header.php");

$sql = "SELECT topicid, topicname, topicimage, topictext FROM ".$prefix."_topics ORDER BY topictext";
$result = $db->sql_query($sql);
if ($db->sql_numrows($result) == 0) {
    include("header.php");
    include("footer.php");
}
if ($db->sql_numrows($result) > 0) {
    $r_options = "";
    if (isset($cookie[4])) { $r_options .= "&amp;mode=$cookie[4]"; }
    if (isset($cookie[5])) { $r_options .= "&amp;order=$cookie[5]"; }
    if (isset($cookie[6])) { $r_options .= "&amp;thold=$cookie[6]"; }
    OpenTable();
    echo "<center><font class=\"title\"><b>"._ACTIVETOPICS."</b></font><br>\n"
	."<font class=\"content\">"._CLICK2LIST."</font><br><br>\n"
	."<form action=\"modules.php?name=Search\" method=\"post\">"
	."<input type=\"name\" name=\"query\" size=\"30\">&nbsp;&nbsp;"
	."<input type=\"submit\" value=\""._SEARCH."\">"
	."</form></center><br><br>";
    while ($row = $db->sql_fetchrow($result)) {
	$topicid = $row[topicid];
	$topicname = $row[topicname];
	$topicimage = $row[topicimage];
	$topictext = $row[topictext];
	$ThemeSel = get_theme();
	if (@file_exists("themes/$ThemeSel/images/topics/$topicimage")) {
	    $t_image = "themes/$ThemeSel/images/topics/$topicimage";
	} else {
	    $t_image = "$tipath$topicimage";
	}
	$sql = "SELECT counter FROM ".$prefix."_stories WHERE topic='$topicid'";
	$res = $db->sql_query($sql);
	$numrows = $db->sql_numrows($res);
	$reads = 0;
	while ($counting = $db->sql_fetchrow($res)) {
	    $reads = $reads+$counting[counter];
	}
	title($topictext);
	echo "<table border=\"1\" width=\"100%\" align=\"center\" cellpadding=\"2\">\n"
	    ."<tr><td valign=\"top\" width='25%'>\n"
	    ."<a href=\"modules.php?name=News&amp;new_topic=$topicid\"><img src=\"$t_image\" border=\"0\" alt=\"$topictext\" title=\"$topictext\" hspace='5' vspace='5'></a><br><br>\n"
	    ."<font class=\"content\">\n"
	    ."<big><strong>&middot</strong></big>&nbsp;<b>"._TOPIC.":</b> $topictext<br>\n"
	    ."<big><strong>&middot</strong></big>&nbsp;<b>"._TOTNEWS.":</b> $numrows<br>\n"
	    ."<big><strong>&middot</strong></big>&nbsp;<b>"._TOTREADS.":</b> $reads</font>\n"
	    ."</td>\n"
	    ."<td valign='top'>";
	$sql = "SELECT sid, catid, title FROM ".$prefix."_stories WHERE topic='$topicid' ORDER BY sid DESC LIMIT 0,10";
	$result2 = $db->sql_query($sql);
	$num = $db->sql_numrows($result2);
	if ($num != 0) {
	    while ($row2 = $db->sql_fetchrow($result2)) {
		$sql = "SELECT title FROM ".$prefix."_stories_cat WHERE catid='$row2[catid]'";
		$result3 = $db->sql_query($sql);
		$row3 = $db->sql_fetchrow($result3);
		if ($row2[catid] == 0) {
		    $cat_link = "";
		} else {
		    $cat_link = "<a href='modules.php?name=News&file=categories&op=newindex&catid=$row2[catid]'><b>$row3[title]</b></a>: ";
		}
		echo "<img src='modules/$module_name/images/arrow.gif' border='0' alt='' title=''>&nbsp;&nbsp;$cat_link<a href='modules.php?name=News&file=article&sid=$row2[sid]$r_options'>$row2[title]</a><br>";
	    }
	    if ($num == 10) {
		echo "<div align='right'><big><strong>&middot</strong></big>&nbsp;<a href='modules.php?name=News&new_topic=$topicid'><b>"._MORE." --></b></a>&nbsp;&nbsp;</div>";
	    }
	} else {
	    echo "<i>"._NONEWSYET."</i>";
	}
	echo "</td></tr></table><br><br>";
    }
} 
CloseTable();
include("footer.php");

?>
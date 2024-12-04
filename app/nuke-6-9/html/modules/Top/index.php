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

include("header.php");

if ($multilingual == 1) {
    $queryalang = "WHERE (alanguage='$currentlang' OR alanguage='')"; /* top stories */
    $querya1lang = "WHERE (alanguage='$currentlang' OR alanguage='') AND"; /* top stories */
    $queryslang = "WHERE slanguage='$currentlang' "; /* top section articles */
    $queryplang = "WHERE planguage='$currentlang' "; /* top polls */
    $queryrlang = "WHERE rlanguage='$currentlang' "; /* top reviews */
} else {
    $queryalang = "";
    $querya1lang = "WHERE";
    $queryslang = "";
    $queryplang = "";
    $queryrlang = "";
}

OpenTable();
echo "<center><font class=\"title\"><b>"._TOPWELCOME." $sitename!</b></font></center>";
CloseTable();
echo "<br>\n\n";
OpenTable();

/* Top 10 read stories */

$sql = "SELECT sid, title, counter FROM ".$prefix."_stories $queryalang ORDER BY counter DESC LIMIT 0,$top";
$result = $db->sql_query($sql);
if ($db->sql_numrows($result) > 0) {
    echo "<table border=\"0\" cellpadding=\"10\" width=\"100%\"><tr><td align=\"left\">\n"
        ."<font class=\"option\"><b>$top "._READSTORIES."</b></font><br><br><font class=\"content\">\n";
    $lugar=1;
    while ($row = $db->sql_fetchrow($result)) {
	$sid = $row['sid'];
	$title = $row[title];
	$counter = $row[counter];
        if($counter>0) {
    	    echo "<strong><big>&middot;</big></strong>&nbsp;$lugar: <a href=\"modules.php?name=News&amp;file=article&amp;sid=$sid\">$title</a> - ($counter "._READS.")<br>\n";
	    $lugar++;
	}
    }
    echo "</font></td></tr></table><br>\n";
}

/* Top 10 most voted stories */

$sql = "SELECT sid, title, ratings FROM ".$prefix."_stories $querya1lang score!='0' ORDER BY ratings DESC LIMIT 0,$top";
$result = $db->sql_query($sql);
if ($db->sql_numrows($result) > 0) {
    echo "<table border=\"0\" cellpadding=\"10\" width=\"100%\"><tr><td align=\"left\">\n"
        ."<font class=\"option\"><b>$top "._MOSTVOTEDSTORIES."</b></font><br><br><font class=\"content\">\n";
    $lugar=1;
    while ($row = $db->sql_fetchrow($result)) {
	$sid = $row['sid'];
	$title = $row[title];
	$ratings = $row[ratings];
        if($ratings>0) {
    	    echo "<strong><big>&middot;</big></strong>&nbsp;$lugar: <a href=\"modules.php?name=News&amp;file=article&amp;sid=$sid\">$title</a> - ($ratings "._LVOTES.")<br>\n";
	    $lugar++;
	}
    }
    echo "</font></td></tr></table><br>\n";
}

/* Top 10 best rated stories */

$sql = "SELECT sid, title, score, ratings FROM ".$prefix."_stories $querya1lang score!='0' ORDER BY ratings+score DESC LIMIT 0,$top";
$result = $db->sql_query($sql);
if ($db->sql_numrows($result) > 0) {
    echo "<table border=\"0\" cellpadding=\"10\" width=\"100%\"><tr><td align=\"left\">\n"
        ."<font class=\"option\"><b>$top "._BESTRATEDSTORIES."</b></font><br><br><font class=\"content\">\n";
    $lugar=1;
    while ($row = $db->sql_fetchrow($result)) {
	$sid = $row['sid'];
	$title = $row[title];
	$score = $row[score];
	$ratings = $row[ratings];
        if($score>0) {
	    $rate = substr($score / $ratings, 0, 4);
    	    echo "<strong><big>&middot;</big></strong>&nbsp;$lugar: <a href=\"modules.php?name=News&amp;file=article&amp;sid=$sid\">$title</a> - ($rate "._POINTS.")<br>\n";
	    $lugar++;
	}
    }
    echo "</font></td></tr></table><br>\n";
}

/* Top 10 commented stories */

if ($articlecomm == 1) {
    $sql = "SELECT sid, title, comments FROM ".$prefix."_stories $queryalang ORDER BY comments DESC LIMIT 0,$top";
    $result = $db->sql_query($sql);
    if ($db->sql_numrows($result) > 0) {
	echo "<table border=\"0\" cellpadding=\"10\" width=\"100%\"><tr><td align=\"left\">\n"
	    ."<font class=\"option\"><b>$top "._COMMENTEDSTORIES."</b></font><br><br><font class=\"content\">\n";
	$lugar=1;
	while ($row = $db->sql_fetchrow($result)) {
	    $sid = $row['sid'];
	    $title = $row[title];
	    $comments = $row[comments];
	    if($comments>0) {
		echo "<strong><big>&middot;</big></strong>&nbsp;$lugar: <a href=\"modules.php?name=News&amp;file=article&amp;sid=$sid\">$title</a> - ($comments "._COMMENTS.")<br>\n";
		$lugar++;
	    }
	}
	echo "</font></td></tr></table><br>\n";
    }
}

/* Top 10 categories */

$sql = "SELECT catid, title, counter FROM ".$prefix."_stories_cat ORDER BY counter DESC LIMIT 0,$top";
$result = $db->sql_query($sql);
if ($db->sql_numrows($result) > 0) {
    echo "<table border=\"0\" cellpadding=\"10\" width=\"100%\"><tr><td align=\"left\">\n"
	."<font class=\"option\"><b>$top "._ACTIVECAT."</b></font><br><br><font class=\"content\">\n";
    $lugar=1;
    while ($row = $db->sql_fetchrow($result)) {
	$catid = $row[catid];
	$title = $row[title];
	$counter = $row[counter];
	if($counter>0) {
	    echo "<strong><big>&middot;</big></strong>&nbsp;$lugar: <a href=\"modules.php?name=News&amp;file=categories&amp;op=newindex&amp;catid=$catid\">$title</a> - ($counter "._HITS.")<br>\n";
	    $lugar++;
	}
    }
    echo "</font></td></tr></table><br>\n";
}

/* Top 10 articles in special sections */

$sql = "SELECT artid, secid, title, content, counter FROM ".$prefix."_seccont $queryslang ORDER BY counter DESC LIMIT 0,$top";
$result = $db->sql_query($sql);
if ($db->sql_numrows($result) > 0) {
    echo "<table border=\"0\" cellpadding=\"10\" width=\"100%\"><tr><td align=\"left\">\n"
	."<font class=\"option\"><b>$top "._READSECTION."</b></font><br><br><font class=\"content\">\n";
    $lugar=1;
    while ($row = $db->sql_fetchrow($result)) {
	$artid = $row[artid];
	$secid = $row[secid];
	$title = $row[title];
	$content = $row[content];
	$counter = $row[counter];
        echo "<strong><big>&middot;</big></strong>&nbsp;$lugar: <a href=\"modules.php?name=Sections&amp;op=viewarticle&amp;artid=$artid\">$title</a> - ($counter "._READS.")<br>\n";
	$lugar++;
    }
    echo "</font></td></tr></table><br>\n";
}

/* Top 10 users submitters */

$sql = "SELECT username, counter FROM ".$user_prefix."_users WHERE counter > '0' ORDER BY counter DESC LIMIT 0,$top";
$result = $db->sql_query($sql);
if ($db->sql_numrows($result) > 0) {
    echo "<table border=\"0\" cellpadding=\"10\" width=\"100%\"><tr><td align=\"left\">\n"
	."<font class=\"option\"><b>$top "._NEWSSUBMITTERS."</b></font><br><br><font class=\"content\">\n";
    $lugar=1;
    while ($row = $db->sql_fetchrow($result)) {
	$uname = $row[username];
	$counter = $row[counter];
	if($counter>0) {
	    echo "<strong><big>&middot;</big></strong>&nbsp;$lugar: <a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$uname\">$uname</a> - ($counter "._NEWSSENT.")<br>\n";
	    $lugar++;
	}
    }
    echo "</font></td></tr></table><br>\n";
}

/* Top 10 Polls */

$result = sql_query("select * from ".$prefix."_poll_desc $queryplang", $dbi);
if (sql_num_rows($result, $dbi)>0) {
    echo "<table border=\"0\" cellpadding=\"10\" width=\"100%\"><tr><td align=\"left\">\n"
	."<font class=\"option\"><b>$top "._VOTEDPOLLS."</b></font><br><br><font class=\"content\">\n";
    $lugar = 1;
    $result = sql_query("SELECT pollID, pollTitle, timeStamp, voters FROM ".$prefix."_poll_desc $querylang order by voters DESC limit 0,$top", $dbi);
    $counter = 0;
    while($object = sql_fetch_object($result, $dbi)) {
	$resultArray[$counter] = array($object->pollID, $object->pollTitle, $object->timeStamp, $object->voters);
	$counter++;
    }
    for ($count = 0; $count < count($resultArray); $count++) {
	$id = $resultArray[$count][0];
	$pollTitle = $resultArray[$count][1];
	$voters = $resultArray[$count][3];
	for($i = 0; $i < 12; $i++) {
	    $result = sql_query("SELECT optionCount FROM ".$prefix."_poll_data WHERE (pollID=$id) AND (voteID=$i)", $dbi);
	    $object = sql_fetch_object($result, $dbi);
	    $optionCount = $object->optionCount;
	    $sum = (int)$sum+$optionCount;
	}
	echo "<strong><big>&middot;</big></strong>&nbsp;$lugar: <a href=\"modules.php?name=Surveys&amp;pollID=$id\">$pollTitle</a> - ($sum "._LVOTES.")<br>\n";
	$lugar++;
	$sum = 0;
    }
    echo "</font></td></tr></table><br>\n";
}

/* Top 10 authors */

$sql = "SELECT aid, counter FROM ".$prefix."_authors ORDER BY counter DESC LIMIT 0,$top";
$result = $db->sql_query($sql);
if ($db->sql_numrows($result) > 0) {
    echo "<table border=\"0\" cellpadding=\"10\" width=\"100%\"><tr><td align=\"left\">\n"
	."<font class=\"option\"><b>$top "._MOSTACTIVEAUTHORS."</b></font><br><br><font class=\"content\">\n";
    $lugar=1;
    while ($row = $db->sql_fetchrow($result)) {
	$aid = $row[aid];
	$counter = $row[counter];
	if($counter>0) {
	    echo "<strong><big>&middot;</big></strong>&nbsp;$lugar: <a href=\"modules.php?name=Search&amp;query=&amp;author=$aid\">$aid</a> - ($counter "._NEWSPUBLISHED.")<br>\n";
	    $lugar++;
	}
    }
    echo "</font></td></tr></table><br>\n";
}

/* Top 10 reviews */

$sql = "SELECT id, title, hits FROM ".$prefix."_reviews $queryrlang ORDER BY hits DESC LIMIT 0,$top";
$result = $db->sql_query($sql);
if ($db->sql_numrows($result) > 0) {
    echo "<table border=\"0\" cellpadding=\"10\" width=\"100%\"><tr><td align=\"left\">\n"
	."<font class=\"option\"><b>$top "._READREVIEWS."</b></font><br><br><font class=\"content\">\n";
    $lugar=1;
    while ($row = $db->sql_fetchrow($result)) {
	$id = $row[id];
	$title = $row[title];
	$hits = $row[hits];
	if($hits>0) {
	    echo "<strong><big>&middot;</big></strong>&nbsp;$lugar: <a href=\"modules.php?name=Reviews&amp;op=showcontent&amp;id=$id\">$title</a> - ($hits "._READS.")<br>\n";
	    $lugar++;
	}
    }
    echo "</font></td></tr></table><br>\n";
}

/* Top 10 downloads */

$sql = "SELECT lid, cid, title, hits FROM ".$prefix."_downloads_downloads ORDER BY hits DESC LIMIT 0,$top";
$result = $db->sql_query($sql);
if ($db->sql_numrows($result) > 0) {
    echo "<table border=\"0\" cellpadding=\"10\" width=\"100%\"><tr><td align=\"left\">\n"
	."<font class=\"option\"><b>$top "._DOWNLOADEDFILES."</b></font><br><br><font class=\"content\">\n";
    $lugar=1;
    while ($row = $db->sql_fetchrow($result)) {
	$lid = $row[lid];
	$cid = $row[cid];
	$title = $row[title];
	$hits = $row[hits];
	if($hits>0) {
	    $res = "SELECT title FROM ".$prefix."_downloads_categories WHERE cid='$cid'";
	    $result2 = $db->sql_query($res);
	    $row2 = $db->sql_fetchrow($result2);
	    $ctitle = $row2[title];
	    $utitle = ereg_replace(" ", "_", $title);
	    echo "<strong><big>&middot;</big></strong>&nbsp;$lugar: <a href=\"modules.php?name=Downloads&amp;d_op=viewdownloaddetails&amp;lid=$lid&amp;ttitle=$utitle\">$title</a> ("._CATEGORY.": $ctitle) - ($hits "._LDOWNLOADS.")<br>\n";
	    $lugar++;
	}
    }
    echo "</font></td></tr></table>\n\n";
}

/* Top 10 Pages in Content */

$sql = "SELECT pid, title, counter FROM ".$prefix."_pages WHERE active='1' ORDER BY counter DESC LIMIT 0,$top";
$result = $db->sql_query($sql);
if ($db->sql_numrows($result) > 0) {
    echo "<table border=\"0\" cellpadding=\"10\" width=\"100%\"><tr><td align=\"left\">\n"
	."<font class=\"option\"><b>$top "._MOSTREADPAGES."</b></font><br><br><font class=\"content\">\n";
    $lugar=1;
    while ($row = $db->sql_fetchrow($result)) {
	$pid = $row[pid];
	$title = $row[title];
	$counter = $row[counter];
	if($counter>0) {
	    echo "<strong><big>&middot;</big></strong>&nbsp;$lugar: <a href=\"modules.php?name=Content&amp;pa=showpage&amp;pid=$pid\">$title</a> ($counter "._READS.")<br>\n";
	    $lugar++;
	}
    }
    echo "</font></td></tr></table>\n\n";
}

CloseTable();
include("footer.php");

?>
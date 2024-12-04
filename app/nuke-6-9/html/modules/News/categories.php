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

$index = 1;
$categories = 1;
$cat = $catid;
automated_news();

function theindex($catid) {
    global $storyhome, $httpref, $httprefmax, $topicname, $topicimage, $topictext, $datetime, $user, $cookie, $nukeurl, $prefix, $multilingual, $currentlang, $db, $articlecomm, $module_name;
    if ($multilingual == 1) {
	    $querylang = "AND (alanguage='$currentlang' OR alanguage='')"; /* the OR is needed to display stories who are posted to ALL languages */
    } else {
	    $querylang = "";
    }
    include("header.php");
    if (isset($cookie[3])) {
	$storynum = $cookie[3];
    } else {
	$storynum = $storyhome;
    }
    $db->sql_query("update ".$prefix."_stories_cat set counter=counter+1 where catid='$catid'");
    $sql = "SELECT sid, aid, title, time, hometext, bodytext, comments, counter, topic, informant, notes, acomm, score, ratings FROM ".$prefix."_stories where catid='$catid' $querylang ORDER BY sid DESC limit $storynum";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)){
	$s_sid = $row['sid'];
	$aid = $row[aid];
	$title = $row[title];
	$time = $row[time];
	$hometext = $row[hometext];
	$bodytext = $row[bodytext];
	$comments = $row[comments];
	$counter = $row[counter];
	$topic = $row[topic];
	$informant = $row[informant];
	$notes = $row[notes];
	$acomm = $row[acomm];
	$score = $row[score];
	$ratings = $row[ratings];
	getTopics($s_sid);
	formatTimestamp($time);
	$subject = stripslashes($subject);
	$hometext = stripslashes($hometext);
	$notes = stripslashes($notes);
	$introcount = strlen($hometext);
	$fullcount = strlen($bodytext);
	$totalcount = $introcount + $fullcount;
	$c_count = $comments;
	$r_options = "";
        if (isset($cookie[4])) { $r_options .= "&amp;mode=$cookie[4]"; }
        if (isset($cookie[5])) { $r_options .= "&amp;order=$cookie[5]"; }
        if (isset($cookie[6])) { $r_options .= "&amp;thold=$cookie[6]"; }
	if (is_user($user)) {
	    $the_icons = " | <a href=\"modules.php?name=News&amp;file=print&amp;sid=$s_sid\"><img src=\"images/print.gif\" border=\"0\" alt=\""._PRINTER."\" title=\""._PRINTER."\" width=\"16\" height=\"11\"></a>&nbsp;&nbsp;<a href=\"modules.php?name=News&amp;file=friend&amp;op=FriendSend&amp;sid=$s_sid\"><img src=\"images/friend.gif\" border=\"0\" alt=\""._FRIEND."\" title=\""._FRIEND."\" width=\"16\" height=\"11\"></a>";
	} else {
	    $the_icons = "";
	}
	$story_link = "<a href=\"modules.php?name=News&amp;file=article&amp;sid=$s_sid$r_options\">";
	$morelink = "(";
	if ($fullcount > 0 OR $c_count > 0 OR $articlecomm == 0 OR $acomm == 1) {
	    $morelink .= "$story_link<b>"._READMORE."</b></a> | ";
	} else {
	    $morelink .= "";
	}
	if ($fullcount > 0) { $morelink .= "$totalcount "._BYTESMORE." | "; }
	if ($articlecomm == 1 AND $acomm == 0) {
	    if ($c_count == 0) { $morelink .= "$story_link"._COMMENTSQ."</a>"; } elseif ($c_count == 1) { $morelink .= "$story_link$c_count "._COMMENT."</a>"; } elseif ($c_count > 1) { $morelink .= "$story_link$c_count "._COMMENTS."</a>"; }
	}
	$morelink .= "$the_icons";
	if ($score != 0) {
	    $rated = substr($score / $ratings, 0, 4);
	} else {
	    $rated = 0;
	}
	$morelink .= " | "._SCORE." $rated";
	$morelink .= ")";
	$morelink = str_replace(" |  | ", " | ", $morelink);
	$sid = $s_sid;
	$sql2 = "select title from ".$prefix."_stories_cat where catid='$catid'";
	$result2 = $db->sql_query($sql2);
	$row2 = $db->sql_fetchrow($result2);
	$title1 = $row2[title];
	
	$title = "$title1: $title";
	themeindex($aid, $informant, $datetime, $title, $counter, $topic, $hometext, $notes, $morelink, $topicname, $topicimage, $topictext);
    }
    if ($httpref==1) {
	$referer = $_SERVER["HTTP_REFERER"];
	if ($referer=="" OR ereg("unknown", $referer) OR eregi($nukeurl,$referer)) {
	} else {
	    $db->sql_query("insert into ".$prefix."_referer values (NULL, '$referer')");
	}
	$numrows = $db->sql_numrows($db->sql_query("select * from ".$prefix."_referer"));
	if($numrows==$httprefmax) {
    	    $db->sql_query("delete from ".$prefix."_referer");
	}
    }
    include("footer.php");
}

switch ($op) {

    case "newindex":
	if ($catid == 0 OR $catid == "") {
	    Header("Location: modules.php?name=$module_name");
	}
	theindex($catid);
    break;

    default:
    Header("Location: modules.php?name=$module_name");

}

?>
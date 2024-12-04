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

$index = 1;
require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

function theindex($new_topic=0) {
    global $db, $storyhome, $topicname, $topicimage, $topictext, $datetime, $user, $cookie, $nukeurl, $prefix, $multilingual, $currentlang, $articlecomm, $sitename, $user_news;
    if ($multilingual == 1) {
	$querylang = "AND (alanguage='$currentlang' OR alanguage='')";
    } else {
	$querylang = "";
    }
    include("header.php");
    automated_news();
    if (isset($cookie[3]) AND $user_news == 1) {
	$storynum = $cookie[3];
    } else {
	$storynum = $storyhome;
    }
    if ($new_topic == 0) {
	$qdb = "WHERE (ihome='0' OR catid='0')";
	$home_msg = "";
    } else {
	$qdb = "WHERE topic='$new_topic'";
	$sql_a = "SELECT topictext FROM ".$prefix."_topics WHERE topicid='$new_topic'";
	$result_a = $db->sql_query($sql_a);
	$row_a = $db->sql_fetchrow($result_a);	
	$numrows_a = $db->sql_numrows($result_a);
	$topic_title = $row_a[topictext];
	OpenTable();
	if ($numrows == 0) {
	    echo "<center><font class=\"title\">$sitename</font><br><br>"._NOINFO4TOPIC."<br><br>[ <a href=\"modules.php?name=News\">"._GOTONEWSINDEX."</a> | <a href=\"modules.php?name=Topics\">"._SELECTNEWTOPIC."</a> ]</center>";
	} else {
	    echo "<center><font class=\"title\">$sitename: $topic_title</font><br><br>"
		."<form action=\"modules.php?name=Search\" method=\"post\">"
		."<input type=\"hidden\" name=\"topic\" value=\"$new_topic\">"
		.""._SEARCHONTOPIC.": <input type=\"name\" name=\"query\" size=\"30\">&nbsp;&nbsp;"
		."<input type=\"submit\" value=\""._SEARCH."\">"
		."</form>"
		."[ <a href=\"index.php\">"._GOTOHOME."</a> | <a href=\"modules.php?name=Topics\">"._SELECTNEWTOPIC."</a> ]</center>";
	}
	CloseTable();
	echo "<br>";
    }
    $sql = "SELECT sid, catid, aid, title, time, hometext, bodytext, comments, counter, topic, informant, notes, acomm, score, ratings FROM ".$prefix."_stories $qdb $querylang ORDER BY sid DESC limit $storynum";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	$s_sid = $row['sid'];
	$catid = $row[catid];
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
	if ($catid > 0) {
	    $sql2 = "SELECT title FROM ".$prefix."_stories_cat WHERE catid='$catid'";
	    $result2 = $db->sql_query($sql2);
	    $row2 = $db->sql_fetchrow($result2);
	    $cattitle = $row2[title];
	}
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
	$sid = $s_sid;
	if ($catid != 0) {
	    $sql3 = "SELECT title FROM ".$prefix."_stories_cat WHERE catid='$catid'";
	    $result3 = $db->sql_query($sql3);
	    $row3 = $db->sql_fetchrow($result3);
	    $title1 = $row3[title];
	    $title = "<a href=\"modules.php?name=News&amp;file=categories&amp;op=newindex&amp;catid=$catid\"><font class=\"storycat\">$title1</font></a>: $title";
	    $morelink .= " | <a href=\"modules.php?name=News&amp;file=categories&amp;op=newindex&amp;catid=$catid\">$title1</a>";
	}
	if ($score != 0) {
	    $rated = substr($score / $ratings, 0, 4);
	} else {
	    $rated = 0;
	}
	$morelink .= " | "._SCORE." $rated";
	$morelink .= ")";
	$morelink = str_replace(" |  | ", " | ", $morelink);
	themeindex($aid, $informant, $datetime, $title, $counter, $topic, $hometext, $notes, $morelink, $topicname, $topicimage, $topictext);
    }
    include("footer.php");
}

function rate_article($sid, $score) {
    global $prefix, $dbi, $ratecookie, $sitename, $r_options;
    $score = intval($score);
    if ($score) {
	if ($score > 5) { $score = 5; }
	if ($score < 1) { $score = 1; }
	if ($score != 1 AND $score != 2 AND $score != 3 AND $score != 4 AND $score != 5) {
	    Header("Location: index.php");
	    die();
	}
	if (isset($ratecookie)) {
	    $rcookie = base64_decode($ratecookie);
	    $r_cookie = explode(":", $rcookie);
	}
	for ($i=0; $i < sizeof($r_cookie); $i++) {
	    if ($r_cookie[$i] == $sid) {
		$a = 1;
	    }
	}
	if ($a == 1) {
	    Header("Location: modules.php?name=News&op=rate_complete&sid=$sid&rated=1");
	} else {
	    $result = sql_query("update ".$prefix."_stories set score=score+$score, ratings=ratings+1 where sid='$sid'", $dbi);
	    $info = base64_encode("$rcookie$sid:");
	    setcookie("ratecookie","$info",time()+3600);
	    Header("Location: modules.php?name=News&op=rate_complete&sid=$sid$r_options");
	}
    } else {
	include("header.php");
	title("$sitename: "._ARTICLERATING."");
	OpenTable();
	echo "<center>"._DIDNTRATE."<br><br>"
	    .""._GOBACK."</center>";
	CloseTable();
	include("footer.php");
    }
}

function rate_complete($sid, $rated=0) {
    global $sitename, $user, $cookie;
    $r_options = "";
    if (is_user($user)) {
	if (isset($cookie[4])) { $r_options .= "&amp;mode=$cookie[4]"; }
	if (isset($cookie[5])) { $r_options .= "&amp;order=$cookie[5]"; }
        if (isset($cookie[6])) { $r_options .= "&amp;thold=$cookie[6]"; }
    }
    include("header.php");
    title("$sitename: "._ARTICLERATING."");
    OpenTable();
    if ($rated == 0) {
	echo "<center>"._THANKSVOTEARTICLE."<br><br>"
	    ."[ <a href=\"modules.php?name=News&amp;file=article&amp;sid=$sid$r_options\">"._BACKTOARTICLEPAGE."</a> ]</center>";
    } elseif ($rated == 1) {
	echo "<center>"._ALREADYVOTEDARTICLE."<br><br>"
	    ."[ <a href=\"modules.php?name=News&amp;file=article&amp;sid=$sid$r_options\">"._BACKTOARTICLEPAGE."</a> ]</center>";
    }
    CloseTable();
    include("footer.php");
}

switch ($op) {

    default:
    theindex($new_topic);
    break;

    case "rate_article":
    rate_article($sid, $score);
    break;

    case "rate_complete":
    rate_complete($sid, $rated);
    break;

}

?>
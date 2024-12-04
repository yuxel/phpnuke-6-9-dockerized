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

if (stristr($REQUEST_URI,"mainfile")) {
    Header("Location: modules.php?name=$module_name&file=article&sid=$sid");
} elseif (!isset($sid) && !isset($tid)) {
    Header("Location: index.php");
}

if ($save AND is_user($user)) {
    cookiedecode($user);
    $db->sql_query("UPDATE ".$user_prefix."_users SET umode='$mode', uorder='$order', thold='$thold' where uid='$cookie[0]'");
    getusrinfo($user);
    $info = base64_encode("$userinfo[user_id]:$userinfo[username]:$userinfo[user_password]:$userinfo[storynum]:$userinfo[umode]:$userinfo[uorder]:$userinfo[thold]:$userinfo[noscore]");
    setcookie("user","$info",time()+$cookieusrtime);
}

if ($op == "Reply") {
    Header("Location: modules.php?name=$module_name&file=comments&op=Reply&pid=0&sid=$sid&mode=$mode&order=$order&thold=$thold");
}

$sql = "select catid, aid, time, title, hometext, bodytext, topic, informant, notes, acomm, haspoll, pollID, score, ratings FROM ".$prefix."_stories where sid='$sid'";
$result = $db->sql_query($sql);
if ($numrows = $db->sql_numrows($result) != 1) {
    Header("Location: index.php");
    die();
}
$row = $db->sql_fetchrow($result);
$catid = $row[catid];
$aid = $row[aid];
$time = $row[time];
$title = $row[title];
$hometext = $row[hometext];
$bodytext = $row[bodytext];
$topic = $row[topic];
$informant = $row[informant];
$notes = $row[notes];
$acomm = $row[acomm];
$haspoll = $row[haspoll];
$pollID = $row[pollID];
$score = $row[score];
$ratings = $row[ratings];

if ($aid == "") {
    Header("Location: modules.php?name=$module_name");
}

$db->sql_query("UPDATE ".$prefix."_stories SET counter=counter+1 where sid=$sid");

$artpage = 1;
$pagetitle = "- $title";
require("header.php");
$artpage = 0;

formatTimestamp($time);
$title = stripslashes($title);
$hometext = stripslashes($hometext);
$bodytext = stripslashes($bodytext);
$notes = stripslashes($notes);

if ($notes != "") {
    $notes = "<br><br><b>"._NOTE."</b> <i>$notes</i>";
} else {
    $notes = "";
}

if($bodytext == "") {
    $bodytext = "$hometext$notes";
} else {
    $bodytext = "$hometext<br><br>$bodytext$notes";
}

if($informant == "") {
    $informant = $anonymous;
}

getTopics($sid);

if ($catid != 0) {
    $sql = "select title from ".$prefix."_stories_cat where catid='$catid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $title1 = $row[title];
    $title = "<a href=\"modules.php?name=$module_name&amp;file=categories&amp;op=newindex&amp;catid=$catid\"><font class=\"storycat\">$title1</font></a>: $title";
}

echo "<table width=\"100%\" border=\"0\"><tr><td valign=\"top\" width=\"100%\">\n";
themearticle($aid, $informant, $datetime, $title, $bodytext, $topic, $topicname, $topicimage, $topictext);
echo "</td><td>&nbsp;</td><td valign=\"top\">\n";

if ($multilingual == 1) {
    $querylang = "AND (blanguage='$currentlang' OR blanguage='')";
} else {
    $querylang = "";
}

/* Determine if the article has attached a poll */
if ($haspoll == 1) {
    $url = sprintf("modules.php?name=Surveys&amp;op=results&amp;pollID=%d", $pollID);
    $boxContent = "<form action=\"modules.php?name=Surveys\" method=\"post\">";
    $boxContent .= "<input type=\"hidden\" name=\"pollID\" value=\"".$pollID."\">";
    $boxContent .= "<input type=\"hidden\" name=\"forwarder\" value=\"".$url."\">";
    $sql = "SELECT pollTitle, voters FROM ".$prefix."_poll_desc WHERE pollID='$pollID'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $pollTitle = $row[pollTitle];
    $voters = $row[voters];
    $boxTitle = _ARTICLEPOLL;
    $boxContent .= "<font class=\"content\"><b>$pollTitle</b></font><br><br>\n";
    $boxContent .= "<table border=\"0\" width=\"100%\">";
    for($i = 1; $i <= 12; $i++) {
	$sql = "SELECT pollID, optionText, optionCount, voteID FROM ".$prefix."_poll_data WHERE (pollID='$pollID') AND (voteID='$i')";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$numrows = $db->sql_numrows($result);
	if($numrows != 0) {
	    $optionText = $row[optionText];
	    if($optionText != "") {
		$boxContent .= "<tr><td valign=\"top\"><input type=\"radio\" name=\"voteID\" value=\"".$i."\"></td><td width=\"100%\"><font class=\"content\">$optionText</font></td></tr>\n";
	    }
	}
    }
    $boxContent .= "</table><br><center><font class=\"content\"><input type=\"submit\" value=\""._VOTE."\"></font><br>";
    if (is_user($user)) {
        cookiedecode($user);
    }
    for($i = 0; $i < 12; $i++) {
	$sql = "SELECT optionCount FROM ".$prefix."_poll_data WHERE (pollID='$pollID') AND (voteID='$i')";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$optionCount = $row[optionCount];
	$sum = (int)$sum+$optionCount;
    }
    $boxContent .= "<font class=\"content\">[ <a href=\"modules.php?name=Surveys&amp;op=results&amp;pollID=$pollID&amp;mode=$cookie[4]&amp;order=$cookie[5]&amp;thold=$cookie[6]\"><b>"._RESULTS."</b></a> | <a href=\"modules.php?name=Surveys\"><b>"._POLLS."</b></a> ]<br>";

    if ($pollcomm) {
	$sql = "select * from ".$prefix."_pollcomments where pollID='$pollID'";
	$result = $db->sql_query($sql);
	$numcom = $db->sql_numrows($result);
	$boxContent .= "<br>"._VOTES.": <b>$sum</b><br>"._PCOMMENTS." <b>$numcom</b>\n\n";
    } else {
        $boxContent .= "<br>"._VOTES." <b>$sum</b>\n\n";
    }
    $boxContent .= "</font></center></form>\n\n";
    themesidebox($boxTitle, $boxContent);
}

$sql = "select title, content, active, position from ".$prefix."_blocks where blockfile='block-Login.php' $querylang";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$title = $row[title];
$content = $row[content];
$active = $row[active];
$position = $row[position];
if (($active == 1) AND ($position == "r") AND (!is_user($user))) {
    loginbox();
}

$boxtitle = ""._RELATED."";
$boxstuff = "<font class=\"content\">";
$sql = "select name, url from ".$prefix."_related where tid=$topic";
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)) {
    $name = $row[name];
    $url = $row[url];
    $boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$url\" target=\"new\">$name</a><br>\n";
}

$boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?name=Search&amp;topic=$topic\">"._MOREABOUT." $topictext</a><br>\n";
$boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?name=Search&amp;author=$aid\">"._NEWSBY." $aid</a>\n";

$boxstuff .= "</font><br><hr noshade width=\"95%\" size=\"1\"><center><font class=\"content\"><b>"._MOSTREAD." $topictext:</b><br>\n";

global $multilingual, $currentlang;
    if ($multilingual == 1) {
	$querylang = "AND (alanguage='$currentlang' OR alanguage='')"; /* the OR is needed to display stories who are posted to ALL languages */
    } else {
	$querylang = "";
    }
$sql = "select sid, title from ".$prefix."_stories where topic=$topic $querylang order by counter desc limit 0,1";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$topstory = $row['sid'];
$ttitle = $row[title];

$boxstuff .= "<a href=\"modules.php?name=$module_name&file=article&sid=$topstory\">$ttitle</a></font></center><br>\n";
themesidebox($boxtitle, $boxstuff);

if ($ratings != 0) {
    $rate = substr($score / $ratings, 0, 4);
    $r_image = round($rate);
    $the_image = "<br><br><img src=\"images/articles/stars-$r_image.gif\" border=\"1\"></center><br>";
} else {
    $rate = 0;
    $the_image = "</center><br>";
}
$ratetitle = ""._RATEARTICLE."";
$ratecontent = "<center>"._AVERAGESCORE.": <b>$rate</b><br>"._VOTES.": <b>$ratings</b>$the_image";
$ratecontent .= "<form action=\"modules.php?name=$module_name\" method=\"post\"><center>"._RATETHISARTICLE."</center><br>";
$ratecontent .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\">";
$ratecontent .= "<input type=\"hidden\" name=\"op\" value=\"rate_article\">";
$ratecontent .= "<input type=\"radio\" name=\"score\" value=\"5\"> <img src=\"images/articles/stars-5.gif\" border=\"0\" alt=\""._EXCELLENT."\" title=\""._EXCELLENT."\"><br>";
$ratecontent .= "<input type=\"radio\" name=\"score\" value=\"4\"> <img src=\"images/articles/stars-4.gif\" border=\"0\" alt=\""._VERYGOOD."\" title=\""._VERYGOOD."\"><br>";
$ratecontent .= "<input type=\"radio\" name=\"score\" value=\"3\"> <img src=\"images/articles/stars-3.gif\" border=\"0\" alt=\""._GOOD."\" title=\""._GOOD."\"><br>";
$ratecontent .= "<input type=\"radio\" name=\"score\" value=\"2\"> <img src=\"images/articles/stars-2.gif\" border=\"0\" alt=\""._REGULAR."\" title=\""._REGULAR."\"><br>";
$ratecontent .= "<input type=\"radio\" name=\"score\" value=\"1\"> <img src=\"images/articles/stars-1.gif\" border=\"0\" alt=\""._BAD."\" title=\""._BAD."\"><br><br>";
$ratecontent .= "<center><input type=\"submit\" value=\""._CASTMYVOTE."\"></center></form><br>";
themesidebox($ratetitle, $ratecontent);

$optiontitle = ""._OPTIONS."";
$optionbox .= "<br>&nbsp;<img src=\"images/print.gif\" border=\"0\" alt=\""._PRINTER."\" title=\""._PRINTER."\" width=\"16\" height=\"11\">&nbsp;&nbsp;<a href=\"modules.php?name=$module_name&amp;file=print&amp;sid=$sid\">"._PRINTER."</a><br><br>";
$optionbox .= "&nbsp;<img src=\"images/friend.gif\" border=\"0\" alt=\""._FRIEND."\" title=\""._FRIEND."\" width=\"16\" height=\"11\">&nbsp;&nbsp;<a href=\"modules.php?name=$module_name&amp;file=friend&amp;op=FriendSend&amp;sid=$sid\">"._FRIEND."</a><br><br>\n";
if (is_admin($admin)) {
    $optionbox .= "<center><b>"._ADMIN."</b><br>[ <a href=\"admin.php?op=adminStory\">"._ADD."</a> | <a href=\"admin.php?op=EditStory&sid=$sid\">"._EDIT."</a> | <a href=\"admin.php?op=RemoveStory&sid=$sid\">"._DELETE."</a> ]</center>";
}
themesidebox($optiontitle, $optionbox);

echo "</td></tr></table>\n";
cookiedecode($user);

include("modules/$module_name/associates.php");

if ((($mode != "nocomments") OR ($acomm == 0)) OR ($articlecomm == 1)) {
    include("modules/News/comments.php");
}
include ("footer.php");

?>
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
$pagetitle = "- "._RECOMMEND."";

function FriendSend($sid) {
    global $user, $cookie, $prefix, $db, $user_prefix, $module_name;
    if(!isset($sid)) { exit(); }
    include ("header.php");
    $sql = "SELECT title FROM ".$prefix."_stories WHERE sid='$sid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $title = $row[title];
    title(""._FRIEND."");
    OpenTable();
    echo "<center><font class=\"content\"><b>"._FRIEND."</b></font></center><br><br>"
	.""._YOUSENDSTORY." <b>$title</b> "._TOAFRIEND."<br><br>"
	."<form action=\"modules.php?name=$module_name&amp;file=friend\" method=\"post\">"
	."<input type=\"hidden\" name=\"sid\" value=\"$sid\">";
    if (is_user($user)) {
	$sql = "SELECT name, user_email FROM ".$user_prefix."_users WHERE username='$cookie[1]'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($resul);
	$yn = $row[name];
	$ye = $row[user_email];
    }
    echo "<b>"._FYOURNAME." </b> <input type=\"text\" name=\"yname\" value=\"$yn\"><br><br>\n"
	."<b>"._FYOUREMAIL." </b> <input type=\"text\" name=\"ymail\" value=\"$ye\"><br><br><br>\n"
	."<b>"._FFRIENDNAME." </b> <input type=\"text\" name=\"fname\"><br><br>\n"
	."<b>"._FFRIENDEMAIL." </b> <input type=\"text\" name=\"fmail\"><br><br>\n"
	."<input type=\"hidden\" name=\"op\" value=\"SendStory\">\n"
	."<input type=\"submit\" value="._SEND.">\n"
	."</form>\n";
    CloseTable();
    include ('footer.php');
}

function SendStory($sid, $yname, $ymail, $fname, $fmail) {
    global $sitename, $nukeurl, $prefix, $db, $module_name;

    $fname = removecrlf($fname);
    $fmail = removecrlf($fmail);
    $yname = removecrlf($yname);
    $ymail = removecrlf($ymail);    

    $sql = "SELECT title, time, topic FROM ".$prefix."_stories WHERE sid='$sid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $title = $row[title];
    $time = $row[time];
    $topic = $row[topic];

    $sql = "SELECT topictext FROM ".$prefix."_topics WHERE topicid='$topic'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $topictext = $row[topictext];

    $subject = ""._INTERESTING." $sitename";
    $message = ""._HELLO." $fname:\n\n"._YOURFRIEND." $yname "._CONSIDERED."\n\n\n$title\n("._FDATE." $time)\n"._FTOPIC." $topictext\n\n"._URL.": $nukeurl/modules.php?name=$module_name&file=article&sid=$sid\n\n"._YOUCANREAD." $sitename\n$nukeurl";
    mail($fmail, $subject, $message, "From: \"$yname\" <$ymail>\nX-Mailer: PHP/" . phpversion());
    $title = urlencode($title);
    $fname = urlencode($fname);
    Header("Location: modules.php?name=$module_name&file=friend&op=StorySent&title=$title&fname=$fname");
}

function StorySent($title, $fname) {
    include ("header.php");
    $title = urldecode($title);
    $fname = urldecode($fname);
    OpenTable();
    echo "<center><font class=\"content\">"._FSTORY." <b>$title</b> "._HASSENT." $fname... "._THANKS."</font></center>";
    CloseTable();
    include ("footer.php");
}

switch($op) {

    case "SendStory":
    SendStory($sid, $yname, $ymail, $fname, $fmail);
    break;
	
    case "StorySent":
    StorySent($title, $fname);
    break;

    case "FriendSend":
    FriendSend($sid);
    break;

}

?>
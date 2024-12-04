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

$pagetitle = "- "._USERSJOURNAL."";
include("header.php");
include("modules/$module_name/functions.php");

cookiedecode($user);
$username = $cookie[1];

if ($debug == "true") :
    echo ("UserName:$username<br>SiteName: $sitename");
endif;

startjournal($sitename,$user);

$sql = "select title from ".$prefix."_journal where jid='$jid'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$jtitle = $row[title];

if ($send == 1) {
    $fname= removecrlf($fname);
    $fmail= removecrlf($fmail);
    $yname= removecrlf($yname);
    $ymail= removecrlf($ymail);
    $subject = ""._INTERESTING." $sitename";
    $message = ""._HELLO." $fname:\n\n"._YOURFRIEND." $yname "._CONSIDERED."\n\n\n$jtitle\n"._URL.": $nukeurl/modules.php?name=$module_name&file=display&jid=$jid\n\n\n"._AREMORE."\n\n---\n$sitename\n$nukeurl";
    mail($fmail, $subject, $message, "From: \"$yname\" <$ymail>\nX-Mailer: PHP/" . phpversion());
    $title = urlencode($title);
    $fname = urlencode($fname);
    $sent = 1;
}

if ($sent == 1) {
    echo "<br>";
    title(""._SENDJFRIEND."");
    OpenTable();
    echo "<center>"._FSENT."<br><br>[ <a href=\"modules.php?name=$module_name&file=display&jid=$jid\">"._RETURNJOURNAL2."</a> ]</center>";
    CloseTable();
    journalfoot();
    die();
}

echo "<br>";
title(""._SENDJFRIEND."");
OpenTable();
echo "<table align=center border=0><tr><td>"
    ."<center><b>$jtitle</b><br>"._YOUSENDJOURNAL."</center><br><br>"
    ."<form action=\"modules.php?name=$module_name&file=friend\" method=\"post\">"
    ."<input type=\"hidden\" name=\"send\" value=\"1\">"
    ."<input type=\"hidden\" name=\"jid\" value=\"$jid\">";
if (is_user($user)) {
    $sql = "select name, username, user_email from ".$user_prefix."_users where username='$cookie[1]'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $yn = $row[name];
    $yun = $row[username];
    $ye = $row[user_email];
}
if ($yn == "") {
    $yn = $yun;
}
echo "<b>"._FYOURNAME." </b> <input type=\"text\" name=\"yname\" value=\"$yn\"><br><br>\n"
    ."<b>"._FYOUREMAIL." </b> <input type=\"text\" name=\"ymail\" value=\"$ye\"><br><br><br>\n"
    ."<b>"._FFRIENDNAME." </b> <input type=\"text\" name=\"fname\"><br><br>\n"
    ."<b>"._FFRIENDEMAIL." </b> <input type=\"text\" name=\"fmail\"><br><br>\n"
    ."<input type=\"hidden\" name=\"op\" value=\"SendStory\">\n"
    ."<input type=\"submit\" value="._SEND.">\n"
    ."</form></td></tr></table>\n";
CloseTable();

journalfoot();

?>
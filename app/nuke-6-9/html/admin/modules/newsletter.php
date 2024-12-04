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

if (!eregi("admin.php", $_SERVER['PHP_SELF'])) { die ("Access Denied"); }

$result = sql_query("select radminnewsletter, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radminnewsletter, $radminsuper) = sql_fetch_row($result, $dbi);
if (($radminnewsletter==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Sections Manager Functions                            */
/*********************************************************/

function newsletter() {
    global $prefix, $user_prefix, $dbi, $sitename;
    include("header.php");
    GraphicAdmin();
    $srow = sql_num_rows(sql_query("select * from ".$user_prefix."_users where newsletter='1'", $dbi), $dbi);
    $urow = sql_num_rows(sql_query("select * from ".$user_prefix."_users", $dbi), $dbi);
    $urow--;
    OpenTable();
    echo "<center><font class=\"title\"><b>"._NEWSLETTER."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"content\"><b>"._NEWSLETTER."</b></font></center>"
	."<br><br>"
	."<form method=\"post\" action=\"admin.php\">"
	."<b>From:</b> $sitename"
	."<br><br>"
	."<b>"._SUBJECT.":</b><br><input type=\"text\" name=\"subject\" size=\"50\">"
	."<br><br>"
	."<b>"._CONTENT.":</b><br><textarea name=\"content\" cols=\"50\" rows=\"10\"></textarea>"
	."<br><br>"
	."<b>"._WHATTODO."</b><br>"
	."<input type=\"radio\" name=\"type\" value=\"newsletter\" checked> "._ANEWSLETTER." ($srow "._SUBSCRIBEDUSERS.")<br>"
	."<input type=\"radio\" name=\"type\" value=\"massmail\"> "._MASSMAIL." ($urow "._USERS.")"
	."<br><br>"
	."<input type=\"hidden\" name=\"op\" value=\"check_type\">"
	."<input type=\"submit\" value=\""._PREVIEW."\">"
	."</form>";
    CloseTable();
    include("footer.php");
}

function check_type($subject, $content, $type) {
    global $user_prefix, $dbi, $sitename;
    include("header.php");
    GraphicAdmin();
    $srow = sql_num_rows(sql_query("select * from ".$user_prefix."_users where newsletter='1'", $dbi), $dbi);
    $urow = sql_num_rows(sql_query("select * from ".$user_prefix."_users", $dbi), $dbi);
    $urow--;
    OpenTable();
    echo "<center><font class=\"title\"><b>"._NEWSLETTER."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $content = stripslashes($content);
    if ($type == "newsletter") {
	echo "<center><font class=\"content\"><b>"._NEWSLETTER."</b></font>"
	    ."<br><br>"
	    ."<form action\"admin.php\" method=\"post\">"
	    .""._NYOUAREABOUTTOSEND."<br>"
	    ."<b>$srow</b> "._NUSERWILLRECEIVE."<br><br>"
	    ."<b>"._REVIEWTEXT."</b></center><br><br>"
	    ."<b>"._FROM.":</b> $sitename<br><br>"
	    ."<b>"._SUBJECT.":</b><br><input type=\"text\" name=\"title\" value=\"$subject\" size=\"50\"><br><br>"
	    ."<b>"._CONTENT.":</b><br><textarea name=\"content\" cols=\"50\" rows=\"10\">$content</textarea><br><br><br><br>"
	    ."<b>"._NAREYOUSURE2SEND."</b><br><br>"
	    ."<input type=\"hidden\" name=\"op\" value=\"newsletter_send\">"
	    ."<input type=\"submit\" value=\""._SEND."\"> &nbsp;&nbsp; "._GOBACK.""
	    ."</form>";
    } elseif ($type == "massmail") {
	echo "<center><font class=\"content\"><b>"._MASSEMAIL."</b></font>"
	    ."<br><br>"
	    ."<form action\"admin.php\" method=\"post\">"
	    .""._MYOUAREABOUTTOSEND."<br>"
	    ."<b>$urow</b> "._MUSERWILLRECEIVE."<br>"
	    ."<i><b>"._POSSIBLESPAM."</b></i><br><br>"
	    ."<b>"._REVIEWTEXT."</b></center><br><br>"
	    ."<b>"._FROM.":</b> $sitename<br><br>"
	    ."<b>"._SUBJECT.":</b><br><input type=\"text\" name=\"title\" value=\"$subject\" size=\"50\"><br><br>"
	    ."<b>"._CONTENT.":</b><br><textarea name=\"content\" cols=\"50\" rows=\"10\">$content</textarea><br><br><br><br>"
	    ."<b>"._MAREYOUSURE2SEND."</b><br><br>"
	    ."<input type=\"hidden\" name=\"op\" value=\"massmail_send\">"
	    ."<input type=\"submit\" value=\""._SEND."\"> &nbsp;&nbsp; "._GOBACK.""
	    ."</form>";
    }
    if (($type == "newsletter") AND ($srow > 500)) {
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><i>"._MANYUSERSNOTE."</i></center>";
    } elseif (($type == "massmail") AND ($urow > 500)) {
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><i>"._MANYUSERSNOTE."</i></center>";
    }
    CloseTable();
    include("footer.php");
}

function newsletter_send($title, $content) {
    global $user_prefix, $sitename, $dbi, $nukeurl, $adminmail;
    $from = $adminmail;
    $subject = "[$sitename Newsletter]: ".stripslashes($title)."";
    $content = stripslashes($content);
    $content = "$sitename "._NEWSLETTER."\n\n\n$content\n\n- $sitename "._STAFF."\n\n\n\n\n\n"._NLUNSUBSCRIBE."";
    $result = sql_query("select user_email from ".$user_prefix."_users where newsletter='1'", $dbi);
    while(list($user_email) = sql_fetch_row($result, $dbi)) {
	mail($user_email, $subject, $content, "From: $from\nX-Mailer: PHP/" . phpversion());
    }
    Header("Location: admin.php?op=newsletter_sent");
}

function newsletter_sent() {
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._NEWSLETTER."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"content\"><b>"._NEWSLETTER."</b></font><br><br>";
    echo "<b>"._NEWSLETTERSENT."</b></center>";
    CloseTable();
    include("footer.php");
}

function massmail_send($title, $content) {
    global $user_prefix, $sitename, $dbi, $nukeurl, $adminmail;
    $from = $adminmail;
    $subject = "[$sitename]: $title";
    $content = stripslashes($content);
    $content = ""._FROM.": $sitename\n\n\n\n$content\n\n\n\n- $sitename "._STAFF."\n\n\n\n"._MASSEMAILMSG."";
    $result = sql_query("select user_email from ".$user_prefix."_users where user_id != '1'", $dbi);
    while(list($user_email) = sql_fetch_row($result, $dbi)) {
	mail($user_email, $subject, $content, "From: $from\nX-Mailer: PHP/" . phpversion());
    }
    Header("Location: admin.php?op=massmail_sent");
}

function massmail_sent() {
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._MASSEMAIL."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"content\"><b>"._MASSEMAIL."</b></font><br><br>";
    echo "<b>"._MASSEMAILSENT."</b></center>";
    CloseTable();
    include("footer.php");
}

switch ($op) {

    case "newsletter":
    newsletter();
    break;

    case "newsletter_send":
    newsletter_send($title, $content);
    break;

    case "newsletter_sent":
    newsletter_sent();
    break;

    case "massmail_send":
    massmail_send($title, $content);
    break;

    case "massmail_sent":
    massmail_sent();
    break;

    case "check_type":
    check_type($subject, $content, $type);
    break;

}

} else {
    echo "Access Denied";
}

?>
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

function RecommendSite() {
    global $user, $cookie, $prefix, $dbi, $user_prefix, $module_name;
    include ("header.php");
    title(""._RECOMMEND."");
    OpenTable();
    echo "<center><font class=\"content\"><b>"._RECOMMEND."</b></font></center><br><br>"
	."<form action=\"modules.php?name=$module_name\" method=\"post\">"
	."<input type=\"hidden\" name=\"op\" value=\"SendSite\">";
    if (is_user($user)) {
	$result=sql_query("select username, user_email from ".$user_prefix."_users where username='$cookie[1]'", $dbi);
	list($yn, $ye) = sql_fetch_row($result, $dbi);
    }
    echo "<b>"._FYOURNAME." </b> <input type=\"text\" name=\"yname\" value=\"$yn\"><br><br>\n"
	."<b>"._FYOUREMAIL." </b> <input type=\"text\" name=\"ymail\" value=\"$ye\"><br><br><br>\n"
	."<b>"._FFRIENDNAME." </b> <input type=\"text\" name=\"fname\"><br><br>\n"
	."<b>"._FFRIENDEMAIL." </b> <input type=\"text\" name=\"fmail\"><br><br>\n"
	."<input type=submit value="._SEND.">\n"
	."</form>\n";
    CloseTable();
    include ('footer.php');
}


function SendSite($yname, $ymail, $fname, $fmail) {
    global $sitename, $slogan, $nukeurl, $module_name;
    $fname = removecrlf($fname);
    $fmail = removecrlf($fmail);
    $yname = removecrlf($yname);
    $ymail = removecrlf($ymail);
    $subject = ""._INTSITE." $sitename";
    $message = ""._HELLO." $fname:\n\n"._YOURFRIEND." $yname "._OURSITE." $sitename "._INTSENT."\n\n\n"._FSITENAME." $sitename\n$slogan\n"._FSITEURL." $nukeurl\n";
    mail($fmail, $subject, $message, "From: \"$yname\" <$ymail>\nX-Mailer: PHP/" . phpversion());
    Header("Location: modules.php?name=$module_name&op=SiteSent&fname=$fname");
}

function SiteSent($fname) {
    include ('header.php');
    OpenTable();
    echo "<center><font class=\"content\">"._FREFERENCE." $fname...<br><br>"._THANKSREC."</font></center>";
    CloseTable();
    include ('footer.php');
}


switch($op) {

    case "SendSite":
    SendSite($yname, $ymail, $fname, $fmail);
    break;
	
    case "SiteSent":
    SiteSent($fname);
    break;

    default:
    RecommendSite();
    break;

}

?>
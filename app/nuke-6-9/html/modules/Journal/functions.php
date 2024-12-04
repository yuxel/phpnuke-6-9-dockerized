<?php

/************************************************************************/
/* Journal &#167 ZX                                                     */
/* ================                                                     */
/*                                                                      */
/* Original work done by Joseph Howard known as Member's Journal, which */
/* was based on Trevor Scott's vision of Atomic Journal.                */
/*                                                                      */
/* Modified on 25 May 2002 by Paul Laudanski (paul@computercops.biz)    */
/* Copyright (c) 2002 Modifications by Computer Cops.                   */
/* http://computercops.biz                                              */
/*                                                                      */
/* Required: PHPNuke 5.5 ( http://www.phpnuke.org/ ) and phpbb2         */
/* ( http://bbtonuke.sourceforge.net/ ) forums port.                    */
/*                                                                      */
/* Member's Journal did not work on a PHPNuke 5.5 portal which had      */
/* phpbb2 port integrated.  Thus was Journal &#167 ZX created with the  */
/* Member's Journal author's blessings.                                 */
/*                                                                      */
/* To install, backup everything first and then FTP the Journal package */
/* files into your site's module directory.  Also run the tables.sql    */
/* script so the proper tables and fields can be created and used.  The */
/* default table prefix is "nuke" which is hard-coded throughout the    */
/* entire system as a left-over from Member's Journal.  If a demand     */
/* exists, that can be changed for a future release.                    */
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

$debug = "false";

function journalfoot() {
    include("footer.php");
}

function startjournal($sitename, $user) {
    global $module_name;
    if (is_user($user)) {
	$j_user1 = "<center>[ <a href=\"modules.php?name=$module_name\">"._JOURNALDIR."</a> | <a href=\"modules.php?name=$module_name&file=edit\">"._YOURJOURNAL."</a> ]</center>";
	$j_user2 = "";
    } else {
	$j_user1 = "<center>[ <a href=\"modules.php?name=$module_name\">"._JOURNALDIR."</a> | <a href=\"modules.php?name=Your_Account&op=new_user\">"._CREATEACCOUNT."</a> ]</center>";
	$j_user2 = "<br><center><font class=\"tiny\">"._MEMBERSCAN."</font></center>";
    }
    title("$sitename: "._USERSJOURNAL."");
    if (is_user($user)) {
	include("modules/Your_Account/navbar.php");
	OpenTable();
	nav();
	CloseTable();
	echo "<br>";
    }
    OpenTable();
    echo "<center><img src=modules/$module_name/images/bgimage.gif><br><font class=title><b>"._USERSJOURNAL."</b></font></center>";
    echo "$j_user1";
    echo "$j_user2";
    CloseTable();
}

?>
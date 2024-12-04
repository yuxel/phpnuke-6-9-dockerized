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

$pagetitle = "- "._USERSJOURNAL."";
include("header.php");
include("modules/$module_name/functions.php");

cookiedecode($user);
$username = $cookie[1];

if ($debug == "true") :
    echo ("UserName:$username<br>SiteName: $sitename");
endif;

startjournal($sitename,$user);

if ($jid == "") :
    opentable();
    echo ("<div align=\"center\">"._ANERROR."</div>");
    closetable();
    echo ("<br><br>");
    journalfoot();
endif;

$sql = "SELECT j.jid, j.aid, j.title, j.pdate, j.ptime, j.mdate, j.mtime, j.bodytext, j.status, j.mood, u.user_id, u.username FROM ".$prefix."_journal j, ".$user_prefix."_users u WHERE u.username=j.aid and j.jid = '$jid'";
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result)) {
    $owner = $row[aid];
    if (($row[status] == 'no') && ($row[aid] != $username)):
	OpenTable();
	echo "<center><br>"._ISPRIVATE."<br></center>";
	CloseTable();
	journalfoot();
    endif;
    echo "<br>";
    OpenTable();
    printf ("<div class=title align=center>%s</div>", $row[title]);
//The Following line had an incorrect uname entry.//
    printf ("<div align=center>"._BY.": <a href=\"modules.php?name=Your_Account&op=userinfo&username=$row[username]\">%s</a></div>", $row[aid], $row[aid]);
    printf ("<div align=center class=tiny>"._POSTEDON.": %s @ %s</div>", $row[pdate], $row[ptime]);
    CloseTable();
    echo "<br>";
    openTable();
    $row[bodytext]=check_html($row[bodytext], $strip);
    printf ("%s", $row[bodytext]);
    if ($row[mood] != ""):
    	printf ("<br><div align=center><img src=\"modules/$module_name/images/moods/%s\" alt=\"%s\" title=\"%s\"></div>", $row[mood], $row[mood], $row[mood]);
    endif;
    printf ("<br><br><div class=tiny align=center>"._LASTUPDATED." %s @ %s</div><br>", $row[mdate], $row[mtime]);
    printf ("<div class=tiny align=center>[ <a href=\"modules.php?name=$module_name&file=friend&jid=%s\">"._SENDJFRIEND."</a> ]</div>", $row[jid]);
    closeTable();
    print  ("<br>");
    openTable();
    print  ("<table width=\"100%\" align=\"center\"><tr>");
    if ($row[aid] == $username):
	echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=$module_name&file=modify&jid=$jid\"><img src=\"modules/$module_name/images/edit.gif\" border=0 alt=\""._EDIT."\" title=\""._EDIT."\"><br>"._EDIT."</a></td>";
	echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=$module_name&file=delete&jid=$jid&forwhat=$jid\"><img src=\"modules/$module_name/images/trash.gif\" border=0 alt=\""._DELETE."\" title=\""._DELETE."\"><br>"._DELETE."</a></td>";
    endif;
    if ($username != ""):
	echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=$module_name&file=comment&onwhat=$jid\"><img src=\"modules/$module_name/images/write.gif\" border=0 alt=\""._WRITECOMMENT."\" title=\""._WRITECOMMENT."\"><br>"._WRITECOMMENT."</a></td>";
    endif;
    echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=$module_name&file=search&bywhat=aid&forwhat=$row[aid]\"><img src=\"modules/$module_name/images/binocs.gif\" border=0 alt=\""._VIEWMORE."\" title=\""._VIEWMORE."\"><br>"._VIEWMORE."</a></td>";
//The following line had an incorrect uname entry.//
	echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=Your_Account&op=userinfo&username=$row[username]\"><img src=\"modules/$module_name/images/nuke.gif\" border=0 alt=\""._USERPROFILE."\" title=\""._USERPROFILE."\"><br>"._USERPROFILE."</a></td>";
    if ($username != "" AND is_active("Private_Messages")):
//the following line had a uname entry and a reference to reply.php which doesn't exist.//
	echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=Private_Messages&mode=post&u=$row[user_id]\"><img src=\"modules/$module_name/images/chat.gif\" border=0 alt=\""._SENDMESSAGE."\" title=\""._SENDMESSAGE."\"><br>"._SENDMESSAGE."</a></td>";
    endif;
    if ($username == ""):
	echo "<td align=\"center\" width=\"15%\"><a href=\"modules.php?name=Your_Account\"><img src=\"modules/$module_name/images/folder.gif\" border=0 alt=\"Create an account\" title=\"Create an account\"><br>"._CREATEACCOUNT."</a></td>";
    endif;
    print  ("</tr></table>");
    closeTable();
}

$commentheader = "no";
//The following line had an incorrect u.uid entry.//
$sql = "SELECT j.cid, j.rid, j.aid, j.comment, j.pdate, j.ptime, u.user_id FROM ".$prefix."_journal_comments j, ".$user_prefix."_users u WHERE j.aid=u.username and j.rid = '$jid'";
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result)) {
    if ($row == 0):
    	$commentheader = "yes";
    else:
	if ($commentheader == "no"):
	    echo "<br>";
	    if ($username == "" OR $username == $anonymous) {
		$ann_co = "<br><div align=center class=tiny>"._REGUSERSCOMM."</div>";
	    } else {
		$ann_co = "";
	    }
	    title("Posted Comments$ann_co");
	    $commentheader = "yes";
	elseif ($commentheader = "yes"):
	    // Do not print comment header.
	endif;
    endif;
    openTable();
//The following line had an incorrect uname entry.//
    printf (""._COMMENTBY.": <a href=\"modules.php?name=Your_Account&op=userinfo&username=$row[username]\">%s</a> <div class=tiny>("._POSTEDON." $row[pdate] @ $row[ptime])</div><br>", $row[aid], $row[aid], $row[pdate], $row[ptime]);
    $row[comment]=check_html($row[comment], $strip);		
    printf ("<strong>Comment:</strong> %s", $row[comment]);
    if ($username == $owner):
	printf ("<br><div align=center>[ <a href=\"modules.php?name=$module_name&file=commentkill&onwhat=%s&ref=$jid\">"._DELCOMMENT."</a> ]</div>", $row[cid], $row[jid]);
    endif;
    closeTable();
    print  ("<br><br>");
}

journalfoot();

?>
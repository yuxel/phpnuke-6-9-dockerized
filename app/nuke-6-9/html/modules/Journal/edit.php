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

$pagetitle = "- "._USERSJOURNAL."";

include("header.php");
include("modules/$module_name/functions.php");

cookiedecode($user);
$username = $cookie[1];

if ($edit == 1) {
    $htime = date(h);
    $mtime = date(i);
    $ntime = date(a);
    $mtime = "$htime:$mtime $ntime";
    $mdate = date(m);
    $ddate = date(d);
    $ydate = date(Y);
    $ndate = "$mdate-$ddate-$ydate";
    $pdate = $ndate;
    $ptime = $mtime;
    $micro = microtime();
    $sql = "SELECT * FROM ".$prefix."_journal WHERE jid = '$jid'";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	if ($username != $row[aid]):
    	    echo ("<br>");
	    openTable();
	    echo ("<div align=center>".NOTYOURS."</div>");
	    closeTable();
	    journalfoot();
	    die();
        endif;
    }
    echo ("<div align=center><strong>"._UPDATEOK."</strong></div><br>");
    $sql="UPDATE ".$prefix."_journal SET title='$title', bodytext='$bodytext', mood='$mood', status='$status', mdate='$ndate', mtime='$mtime' WHERE jid='$jid'";
    $db->sql_query($sql);
    $edited = "<br><br><center><b>"._UPDATED."</b></center>";
} else {
    $edited = "";
}

if ($debug == "true") :
    echo ("UserName:$username<br>SiteName: $sitename");
endif;

startjournal($sitename,$user);

echo "<br>";
OpenTable();
echo ("<div align=center class=title>"._JOURNALFOR." $username</div><br>");
echo ("<div align=center> [ <a href=\"modules.php?name=$module_name&file=add\">"._ADDENTRY."</a> | <a href=\"modules.php?name=$module_name&file=edit&disp=last\">"._YOURLAST20."</a> | <a href=\"modules.php?name=$module_name&file=edit&disp=all\">"._LISTALLENTRIES."</a> ]</div>");
echo "$edited";
CloseTable();
echo "<br>";

function list20($username,$bgcolor1,$bgcolor2,$bgcolor3) {
    global $prefix, $user_prefix, $db, $module_name;
    openTable();
    echo ("<div align=\"center\" class=title>"._LAST20FOR." $username</div><br>");
    echo ("<table align=center border=1 width=\"90%\">");
    echo ("<tr>");
    echo ("<td align=center bgcolor=$bgcolor1 width=70><strong><div align=\"center\">"._DATE."</div></strong></td>");
    echo ("<td align=center bgcolor=$bgcolor1 width=70><strong><div align=\"center\">"._TIME."</div></strong></td>");
    echo ("<td align=center bgcolor=$bgcolor1><strong>"._TITLE."</strong> "._CLICKTOVIEW."</td>");
    echo ("<td align=center bgcolor=$bgcolor1 width=\"5%\"><strong><div align=\"center\">"._PUBLIC."</div></strong></td>");
    echo ("<td align=center bgcolor=$bgcolor1 width=\"5%\"><strong><div align=\"center\">"._EDIT."</div></strong></td>");
    echo ("<td align=center bgcolor=$bgcolor1 width=\"5%\"><strong><div align=\"center\">"._DELETE."</div></strong></td>");
    echo ("</tr>");
    $sql = "SELECT jid, aid, title, pdate, ptime, mdate, mtime, status, mood FROM ".$prefix."_journal WHERE aid='$username' order by 'jid' DESC";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	if ($dcount >= 21) :
	    echo ("</tr></table>");
	    closeTable();
	    echo ("<br>");
	    journalfoot();
	    die();
	else :
	    $dcount = $dcount + 1;
	    print  ("<tr>");
	    printf ("<td align=center bgcolor=$bgcolor2><div align=\"center\">%s</div></td>", $row[pdate]);
	    printf ("<td align=center bgcolor=$bgcolor2><div align=\"center\">%s</div></td>", $row[ptime]);
	    printf ("<td align=left bgcolor=$bgcolor2>&nbsp;<a href=\"modules.php?name=$module_name&file=display&jid=%s\">%s</a>", $row[jid], $row[title]);
	    $sqlscnd = "SELECT cid from ".$prefix."_journal_comments where rid=$row[jid]";
	    $rstscnd = $db->sql_query($sqlscnd);
	    $scndcount=0;
	    while ($rowscnd = $db->sql_fetchrow($rstscnd)) {
		$scndcount = $scndcount + 1;
	    }
	    if ($scndcount == 1) {
		printf (" &#151&#151 $scndcount "._COMMENT."</td>");
	    } else {
		printf (" &#151&#151 $scndcount "._COMMENTS."</td>");
	    }
	    if ($row[status] == "yes") {
		$row[status] = _YES;
	    } else {
		$row[status] = _NO;
	    }
	    printf ("<td align=center bgcolor=$bgcolor2><div align=\"center\">%s</div></td>", $row[status]);
	    printf ("<td align=center bgcolor=$bgcolor2><div align=\"center\"><a href=\"modules.php?name=$module_name&file=modify&jid=%s\"><img src='modules/$module_name/images/edit.gif' border='0' alt=\""._EDIT."\" title=\""._EDIT."\"></a></div></td>", $row[jid], $row[title]);
	    printf ("<td align=center bgcolor=$bgcolor2><div align=\"center\"><a href=\"modules.php?name=$module_name&file=delete&jid=%s\"><img src='modules/$module_name/images/trash.gif' border='0' alt=\""._DELETE."\" title=\""._DELETE."\"></a></div></td>", $row[jid], $row[title]);
	    print  ("</tr>");
	endif;
    }
    echo ("</table>");
    closeTable();
}

function listall($username,$bgcolor1,$bgcolor2,$bgcolor3,$sitename) {
    global $prefix, $user_prefix, $db, $module_name;
    openTable();
    echo ("<div align=\"center\" class=title>"._COMPLETELIST." $username</div><br>");
    echo ("<table align=center border=1 width=\"90%\">");
    echo ("<tr>");
    echo ("<td align=center bgcolor=$bgcolor1 width=70><strong><div align=\"center\">"._DATE."</div></strong></td>");
    echo ("<td align=center bgcolor=$bgcolor1 width=70><strong><div align=\"center\">"._TIME."</div></strong></td>");
    echo ("<td align=center bgcolor=$bgcolor1><strong>Title</strong></td>");
    echo ("<td align=center bgcolor=$bgcolor1 width=\"5%\"><strong><div align=\"center\">"._PUBLIC."</div></strong></td>");
    echo ("<td align=center bgcolor=$bgcolor1 width=\"5%\"><strong><div align=\"center\">"._EDIT."</div></strong></td>");
    echo ("<td align=center bgcolor=$bgcolor1 width=\"5%\"><strong><div align=\"center\">"._DELETE."</div></strong></td>");
    echo ("</tr>");
    $sql = "SELECT jid, aid, title, pdate, ptime, mdate, mtime, status, mood FROM ".$prefix."_journal WHERE aid='$username' order by 'jid' DESC";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	$dcount = $dcount + 1;
	if ($row[status] == "yes"):
	    $pubcount = $pubcount +1;
	    $row[status] = _YES;
	else:
	    $prvcount = $prvcount + 1;
	    $row[status] = _NO;
	endif;
	print  ("<tr>");
	printf ("<td align=center bgcolor=$bgcolor2><div align=\"center\">%s</div></td>", $row[pdate]);
	printf ("<td align=center bgcolor=$bgcolor2><div align=\"center\">%s</div></td>", $row[ptime]);
	printf ("<td align=left bgcolor=$bgcolor2><a href=\"modules.php?name=$module_name&file=display&jid=%s\">%s</a>", $row[jid], $row[title]);
	$sqlscnd = "SELECT cid from ".$prefix."_journal_comments where rid=$row[jid]";
	$rstscnd = $db->sql_query($sqlscnd);
	$scndcount=0;
	while ($rowscnd = $db->sql_fetchrow($rstscnd)) {
	    $scndcount = $scndcount + 1;
	}
	if ($scndcount == 1) {
	    printf (" &#151&#151 $scndcount "._COMMENT."</td>");
	} else {
	    printf (" &#151&#151 $scndcount "._COMMENTS."</td>");
	}
	printf ("<td align=center bgcolor=$bgcolor2><div align=\"center\">%s</div></td>", $row[status]);
	printf ("<td align=center bgcolor=$bgcolor2><div align=\"center\"><a href=\"modules.php?name=$module_name&file=modify&jid=%s\"><img src='modules/$module_name/images/edit.gif' border='0' alt='"._EDIT."'></a></div></td>", $row[jid]);
	printf ("<td align=center bgcolor=$bgcolor2><div align=\"center\"><a href=\"modules.php?name=$module_name&file=delete&jid=%s\"><img src='modules/$module_name/images/trash.gif' border='0' alt='"._DELETE."'></a></div></td>", $row[jid]);
	print  ("</tr>");
    }
    echo ("</table>");
    if ($prvcount == "") {
	$prvcount = 0;
    }
    if ($pubcount == "") {
	$pubcount = 0;
    }
    if ($dcount == "") {
	$dcount = 0;
    }
    echo "<br><div align=center>$pubcount "._PUBLICENTRIES." - "
	."$prvcount "._PRIVATEENTRIES." - "
	."$dcount "._TOTALENTRIES."</div>";
    closeTable();
}

switch($disp) {

    case "last":
	list20($username,$bgcolor1,$bgcolor2,$bgcolor3);
	break;

    case "all":
	listall($username,$bgcolor1,$bgcolor2,$bgcolor3,$sitename);
	break;

    default:
	list20($username,$bgcolor1,$bgcolor2,$bgcolor3);
	break;

}

journalfoot();

?>
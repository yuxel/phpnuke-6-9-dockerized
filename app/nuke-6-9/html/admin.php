<?php

/************************************************************************/
/* PHP-NUKE: Advanced Content Management System                         */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

require_once("mainfile.php");
get_lang(admin);

function create_first($name, $url, $email, $pwd, $user_new) {
    global $prefix, $db, $user_prefix;
    $first = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_authors"));
    if ($first == 0) {
	$pwd = md5($pwd);
	$the_adm = "God";
	$sql = "INSERT INTO ".$prefix."_authors VALUES ('$name', '$the_adm', '$url', '$email', '$pwd', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '')";
	$db->sql_query($sql);
	if ($user_new == 1) {
	    $user_regdate = date("M d, Y");
	    $user_avatar = "blank.gif";
	    $commentlimit = 4096;
	    if ($url == "http://") { $url = ""; }
	    $sql = "INSERT INTO ".$user_prefix."_users (user_id, username, user_email, user_website, user_avatar, user_regdate, user_password, theme, commentmax, user_lang, user_dateformat) VALUES (NULL,'$name','$email','$url','$user_avatar','$user_regdate','$pwd','$Default_Theme','$commentlimit','english','D M d, Y g:i a')";
	    $db->sql_query($sql);
	}
	login();
    }
}

$the_first = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_authors"));
if ($the_first == 0) {
    if (!$name) {
    include("header.php");
    title("$sitename: "._ADMINISTRATION."");
    OpenTable();
    echo "<center><b>"._NOADMINYET."</b></center><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<table border=\"0\">"
	."<tr><td><b>"._NICKNAME.":</b></td><td><input type=\"text\" name=\"name\" size=\"30\" maxlength=\"25\"></td></tr>"
	."<tr><td><b>"._HOMEPAGE.":</b></td><td><input type=\"text\" name=\"url\" size=\"30\" maxlength=\"255\" value=\"http://\"></td></tr>"
	."<tr><td><b>"._EMAIL.":</b></td><td><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"255\"></td></tr>"
	."<tr><td><b>"._PASSWORD.":</b></td><td><input type=\"password\" name=\"pwd\" size=\"11\" maxlength=\"10\"></td></tr>"
	."<tr><td colspan=\"2\">"._CREATEUSERDATA."  <input type=\"radio\" name=\"user_new\" value=\"1\" checked>"._YES."&nbsp;&nbsp;<input type=\"radio\" name=\"user_new\" value=\"0\">"._NO."</td></tr>"
	."<tr><td><input type=\"hidden\" name=\"fop\" value=\"create_first\">"
	."<input type=\"submit\" value=\""._SUBMIT."\">"
	."</td></tr></table></form>";
    CloseTable();
    include("footer.php");
    }
    switch($fop) {
	case "create_first":
	create_first($name, $url, $email, $pwd, $user_new);
	break;
    }
    die();
}

require("auth.php");

if(!isset($op)) { $op = "adminMain"; }
$pagetitle = "- "._ADMINMENU."";

/*********************************************************/
/* Login Function                                        */
/*********************************************************/

function login() {
    global $gfx_chk;
    include ("header.php");
    mt_srand ((double)microtime()*1000000);
    $maxran = 1000000;
    $random_num = mt_rand(0, $maxran);
    OpenTable();
    echo "<center><font class=\"title\"><b>"._ADMINLOGIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<form action=\"admin.php\" method=\"post\">"
        ."<table border=\"0\">"
	."<tr><td>"._ADMINID."</td>"
	."<td><input type=\"text\" NAME=\"aid\" SIZE=\"20\" MAXLENGTH=\"25\"></td></tr>"
	."<tr><td>"._PASSWORD."</td>"
	."<td><input type=\"password\" NAME=\"pwd\" SIZE=\"20\" MAXLENGTH=\"18\"></td></tr>";
    if (extension_loaded("gd") AND ($gfx_chk == 1 OR $gfx_chk == 5 OR $gfx_chk == 6 OR $gfx_chk == 7)) {
	echo "<tr><td colspan='2'>"._SECURITYCODE.": <img src='admin.php?op=gfx&random_num=$random_num' border='1' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'></td></tr>"
	    ."<tr><td colspan='2'>"._TYPESECCODE.": <input type=\"text\" NAME=\"gfx_check\" SIZE=\"7\" MAXLENGTH=\"6\"></td></tr>";
    }
    echo "<tr><td>"
	."<input type=\"hidden\" NAME=\"random_num\" value=\"$random_num\">"
	."<input type=\"hidden\" NAME=\"op\" value=\"login\">"
	."<input type=\"submit\" VALUE=\""._LOGIN."\">"
	."</td></tr></table>"
	."</form>";
    CloseTable();
    include ("footer.php");
}

function gfx($random_num) {
    global $prefix, $db;
    require("config.php");
    $datekey = date("F j");
    $rcode = hexdec(md5($_SERVER[HTTP_USER_AGENT] . $sitekey . $random_num . $datekey));
    $code = substr($rcode, 2, 6);
    $image = ImageCreateFromJPEG("images/admin/code_bg.jpg");
    $text_color = ImageColorAllocate($image, 80, 80, 80);
    Header("Content-type: image/jpeg");
    ImageString ($image, 5, 12, 2, $code, $text_color);
    ImageJPEG($image, '', 75);
    ImageDestroy($image);
    die();
}

function deleteNotice($id, $table, $op_back) {
    global $db;
    $db->sql_query("DELETE FROM $table WHERE id = '$id'"); 
    Header("Location: admin.php?op=$op_back");
}

/*********************************************************/
/* Administration Menu Function                          */
/*********************************************************/

function adminmenu($url, $title, $image) {
    global $counter, $admingraphic, $Default_Theme;
    $ThemeSel = get_theme();
    if (file_exists("themes/$ThemeSel/images/admin/$image")) {
	$image = "themes/$ThemeSel/images/admin/$image";
    } else {
	$image = "images/admin/$image";
    }
    if ($admingraphic == 1) {
	$img = "<img src=\"$image\" border=\"0\" alt=\"$title\" title=\"$title\"></a><br>";
	$close = "";
    } else {
	$image = "";
	$close = "</a>";
    }
    echo "<td align=\"center\"><font class=\"content\"><a href=\"$url\">$img<b>$title</b>$close<br><br></font></td>";
    if ($counter == 5) {
	echo "</tr><tr>";
	$counter = 0;
    } else {
	$counter++;
    }
}

function GraphicAdmin() {
    global $aid, $admingraphic, $language, $admin, $prefix, $db;
    $newsubs = $db->sql_numrows($db->sql_query("SELECT qid FROM ".$prefix."_queue"));
    $sql = "SELECT radminarticle,radmintopic,radminuser,radminsurvey,radminsection,radminlink,radminephem,radminfaq,radmindownload,radminreviews,radminnewsletter,radminforum,radmincontent,radminency,radminsuper FROM ".$prefix."_authors WHERE aid='$aid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $radminarticle = $row[radminarticle];
    $radmintopic = $row[radmintopic];
    $radminuser = $row[radminuser];
    $radminsurvey = $row[radminsurvey];
    $radminsection = $row[radminsection];
    $radminlink = $row[radminlink];
    $radminephem = $row[radminephem];
    $radminfaq = $row[radminfaq];
    $radmindownload = $row[radmindownload];
    $radminreviews = $row[radminreviews];
    $radminnewsletter = $row[radminnewsletter];
    $radminforum = $row[radminforum];
    $radmincontent = $row[radmincontent];
    $radminency = $row[radminency];
    $radminsuper = $row[radminsuper];
    OpenTable();
    echo "<center><a href=\"admin.php\"><font class='title'>"._ADMINMENU."</font></a>";
    echo "<br><br>";
    echo"<table border=\"0\" width=\"100%\" cellspacing=\"1\"><tr>";
    $linksdir = dir("admin/links");
    while($func=$linksdir->read()) {
        if(substr($func, 0, 6) == "links.") {
    	    $menulist .= "$func ";
	}
    }
    closedir($linksdir->handle);
    $menulist = explode(" ", $menulist);
    sort($menulist);
    for ($i=0; $i < sizeof($menulist); $i++) {
	if($menulist[$i]!="") {
	    $counter = 0;
	    include($linksdir->path."/$menulist[$i]");
	}
    }
    adminmenu("admin.php?op=logout", ""._ADMINLOGOUT."", "logout.gif");
    echo"</tr></table></center>";
    CloseTable();
    echo "<br>";
}

/*********************************************************/
/* Administration Main Function                          */
/*********************************************************/

function adminMain() {
    global $language, $admin, $aid, $prefix, $file, $db, $sitename, $user_prefix;
    include ("header.php");
    $dummy = 0;
    $Today = getdate();
    $month = $Today['month'];
    $mday = $Today['mday'];
    $year = $Today['year'];
    $pmonth = $Today['month'];
    $pmday = $Today['mday'];
    $pmday = $mday-1;
    $pyear = $Today['year'];
    if ($pmonth=="January") { $pmonth=1; } else
    if ($pmonth=="February") { $pmonth=2; } else
    if ($pmonth=="March") { $pmonth=3; } else
    if ($pmonth=="April") { $pmonth=4; } else
    if ($pmonth=="May") { $pmonth=5; } else
    if ($pmonth=="June") { $pmonth=6; } else
    if ($pmonth=="July") { $pmonth=7; } else
    if ($pmonth=="August") { $pmonth=8; } else
    if ($pmonth=="September") { $pmonth=9; } else
    if ($pmonth=="October") { $pmonth=10; } else
    if ($pmonth=="November") { $pmonth=11; } else
    if ($pmonth=="December") { $pmonth=12; };
    $test = mktime (0,0,0,$pmonth,$pmday,$pyear,1);
    $curDate2 = "%".$month[0].$month[1].$month[2]."%".$mday."%".$year."%";
    $preday = strftime ("%d",$test);
    $premonth = strftime ("%B",$test);
    $preyear = strftime ("%Y",$test);
    $curDateP = "%".$premonth[0].$premonth[1].$premonth[2]."%".$preday."%".$preyear."%";
    GraphicAdmin();
    $sql = "SELECT radminarticle, radminsuper, admlanguage FROM ".$prefix."_authors WHERE aid='$aid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $radminarticle = $row[radminarticle];
    $radminsuper = $row[radminsuper];
    $admlanguage = $row[admlanguage];
    if ($admlanguage != "" ) {
	$queryalang = "WHERE alanguage='$admlanguage' ";
    } else {
	$queryalang = "";
    }
    $sql = "SELECT main_module from ".$prefix."_main";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $main_module = $row[main_module];
    OpenTable();
    echo "<center><b>$sitename: "._DEFHOMEMODULE."</b><br><br>"
	.""._MODULEINHOME." <b>$main_module</b><br>[ <a href=\"admin.php?op=modules\">"._CHANGE."</a> ]</center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $guest_online_num = $db->sql_numrows($db->sql_query("SELECT uname FROM ".$prefix."_session WHERE guest='1'"));
    $member_online_num = $db->sql_numrows($db->sql_query("SELECT uname FROM ".$prefix."_session WHERE guest='0'"));
    $who_online_num = $guest_online_num + $member_online_num;
    $who_online = "<center><font class=\"option\">"._WHOSONLINE."</font><br><br><font class=\"content\">"._CURRENTLY." $guest_online_num "._GUESTS." $member_online_num "._MEMBERS."<br>";
    $sql = "SELECT COUNT(user_id) AS userCount from $user_prefix"._users." WHERE user_regdate LIKE '$curDate2'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $userCount = $row[userCount];
    $sql = "SELECT COUNT(user_id) AS userCount FROM $user_prefix"._users." WHERE user_regdate LIKE '$curDateP'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $userCount2 = $row[userCount];
    echo "<center>$who_online<br>"
	.""._BTD.": <b>$userCount</b> - "._BYD.": <b>$userCount2</b></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>"._AUTOMATEDARTICLES."</b></center><br>";
    $count = 0;
    $sql = "SELECT anid, aid, title, time, alanguage FROM ".$prefix."_autonews $queryalang ORDER BY time ASC";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	$anid = $row[anid];
	$said = $row[aid];
	$title = $row[title];
	$time = $row[time];
	$alanguage = $row[alanguage];
	if ($alanguage == "") {
	    $alanguage = ""._ALL."";
	}
	if ($anid != "") {
	    if ($count == 0) {
		echo "<table border=\"1\" width=\"100%\">";
		$count = 1;
	    }
	    $time = ereg_replace(" ", "@", $time);
	    if (($radminarticle==1) OR ($radminsuper==1)) {
		if (($radminarticle==1) AND ($aid == $said) OR ($radminsuper==1)) {
    		    echo "<tr><td nowrap>&nbsp;(<a href=\"admin.php?op=autoEdit&amp;anid=$anid\">"._EDIT."</a>-<a href=\"admin.php?op=autoDelete&amp;anid=$anid\">"._DELETE."</a>)&nbsp;</td><td width=\"100%\">&nbsp;$title&nbsp;</td><td align=\"center\">&nbsp;$alanguage&nbsp;</td><td nowrap>&nbsp;$time&nbsp;</td></tr>"; /* Multilingual Code : added column to display language */
		} else {
		    echo "<tr><td>&nbsp;("._NOFUNCTIONS.")&nbsp;</td><td width=\"100%\">&nbsp;$title&nbsp;</td><td align=\"center\">&nbsp;$alanguage&nbsp;</td><td nowrap>&nbsp;$time&nbsp;</td></tr>"; /* Multilingual Code : added column to display language */
		}
	    } else {
		echo "<tr><td width=\"100%\">&nbsp;$title&nbsp;</td><td align=\"center\">&nbsp;$alanguage&nbsp;</td><td nowrap>&nbsp;$time&nbsp;</td></tr>"; /* Multilingual Code : added column to display language */
	    }
	}
    }
    if (($anid == "") AND ($count == 0)) {
	echo "<center><i>"._NOAUTOARTICLES."</i></center>";
    }
    if ($count == 1) {
        echo "</table>";
    }
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>"._LAST." 20 "._ARTICLES."</b></center><br>";
    $sql = "SELECT sid, aid, title, time, topic, informant, alanguage FROM ".$prefix."_stories $queryalang ORDER BY time DESC LIMIT 0,20";
    $result = $db->sql_query($sql);
    echo "<center><table border=\"1\" width=\"100%\" bgcolor=\"$bgcolor1\">";
    while ($row = $db->sql_fetchrow($result)) {
	$sid = $row['sid'];
	$said = $row[aid];
	$title = $row[title];
	$time = $row[time];
	$topic = $row[topic];
	$informant = $row[informant];
	$alanguage = $row[alanguage];
	$sql = "SELECT topicname FROM ".$prefix."_topics WHERE topicid='$topic'";
	$ta = $db->sql_query($sql);
	$row = $db->sql_fetchrow($ta);
	$topicname = $row[topicname];
	if ($alanguage == "") {
	    $alanguage = ""._ALL."";
	}
	formatTimestamp($time);
	echo "<tr><td align=\"right\"><b>$sid</b>"
	    ."</td><td align=\"left\" width=\"100%\"><a href=\"modules.php?name=News&file=article&sid=$sid\">$title</a>"
	    ."</td><td align=\"center\">$alanguage"
	    ."</td><td align=\"right\">$topicname";
	if (($radminarticle==1) OR ($radminsuper==1)) {
	    if (($radminarticle==1) AND ($aid == $said) OR ($radminsuper==1)) {
		echo "</td><td align=\"right\" nowrap>(<a href=\"admin.php?op=EditStory&sid=$sid\">"._EDIT."</a>-<a href=\"admin.php?op=RemoveStory&sid=$sid\">"._DELETE."</a>)"
		    ."</td></tr>";
	    } else {
		echo "</td><td align=\"right\" nowrap><font class=\"content\"><i>("._NOFUNCTIONS.")</i></font>"
		    ."</td></tr>";
	    }
	} else {
	    echo "</td></tr>";
	}
    }
    echo "</table>";
    if (($radminarticle==1) OR ($radminsuper==1)) {
	echo "<center>"
	    ."<form action=\"admin.php\" method=\"post\">"
	    .""._STORYID.": <input type=\"text\" NAME=\"sid\" SIZE=\"10\">"
	    ."<select name=\"op\">"
	    ."<option value=\"EditStory\" SELECTED>"._EDIT."</option>"
	    ."<option value=\"RemoveStory\">"._DELETE."</option>"
	    ."</select>"
	    ."<input type=\"submit\" value=\""._GO."\">"
	    ."</form></center>";
    }
    CloseTable();
    $sql = "SELECT pollID, pollTitle FROM ".$prefix."_poll_desc WHERE artid='0' ORDER BY pollID DESC LIMIT 1";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $pollID = $row[pollID];
    $pollTitle = $row[pollTitle];
    echo "<br>";
    OpenTable();
    echo "<center><b>"._CURRENTPOLL.":</b> $pollTitle [ <a href=\"admin.php?op=polledit&pollID=$pollID\">"._EDIT."</a> | <a href=\"admin.php?op=create\">"._ADD."</a> ]</center>";
    CloseTable();
    include ("footer.php");
}

if($admintest) {

    switch($op) {

	case "do_gfx":
	do_gfx();
	break;

	case "deleteNotice":
	deleteNotice($id, $table, $op_back);
	break;

	case "GraphicAdmin":
        GraphicAdmin();
        break;

	case "adminMain":
	adminMain();
	break;

	case "logout":
	setcookie("admin");
	$admin = "";
	include("header.php");
	OpenTable();
	echo "<center><font class=\"title\"><b>"._YOUARELOGGEDOUT."</b></font></center>";
	CloseTable();
	include("footer.php");
	break;

	case "login";
	unset($op);

	default:
	$casedir = dir("admin/case");
	while($func=$casedir->read()) {
	    if(substr($func, 0, 5) == "case.") {
		include($casedir->path."/$func");
	    }
	}
	closedir($casedir->handle);
	break;

	}

} else {

    switch($op) {

	case "gfx":
	gfx($random_num);
	break;
	
	default:
	login();
	break;

    }

}

?>
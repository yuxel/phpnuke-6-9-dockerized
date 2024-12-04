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
$result = sql_query("select radminarticle, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radminarticle, $radminsuper) = sql_fetch_row($result, $dbi);
if (($radminarticle==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Story/News Functions                                  */
/*********************************************************/

function puthome($ihome, $acomm) {
    echo "<br><b>"._PUBLISHINHOME."</b>&nbsp;&nbsp;";
    if (($ihome == 0) OR ($ihome == "")) {
	$sel1 = "checked";
	$sel2 = "";
    }
    if ($ihome == 1) {
	$sel1 = "";
	$sel2 = "checked";
    }
    echo "<input type=\"radio\" name=\"ihome\" value=\"0\" $sel1>"._YES."&nbsp;"
	."<input type=\"radio\" name=\"ihome\" value=\"1\" $sel2>"._NO.""
	."&nbsp;&nbsp;<font class=\"content\">[ "._ONLYIFCATSELECTED." ]</font><br>";
	
    echo "<br><b>"._ACTIVATECOMMENTS."</b>&nbsp;&nbsp;";
    if (($acomm == 0) OR ($acomm == "")) {
	$sel1 = "checked";
	$sel2 = "";
    }
    if ($acomm == 1) {
	$sel1 = "";
	$sel2 = "checked";    
    }
    echo "<input type=\"radio\" name=\"acomm\" value=\"0\" $sel1>"._YES."&nbsp;"
	."<input type=\"radio\" name=\"acomm\" value=\"1\" $sel2>"._NO."</font><br><br>";

}

function deleteStory($qid) {
    global $prefix, $dbi;
    $result = sql_query("delete from ".$prefix."_queue where qid=$qid", $dbi);
    if (!$result) {
	return;
    }
    Header("Location: admin.php?op=submissions");
}

function SelectCategory($cat) {
    global $prefix, $dbi;
    $selcat = sql_query("select catid, title from ".$prefix."_stories_cat order by title", $dbi);
    $a = 1;
    echo "<b>"._CATEGORY."</b> ";
    echo "<select name=\"catid\">";
    if ($cat == 0) {
	$sel = "selected";
    } else {
	$sel = "";
    }
    echo "<option name=\"catid\" value=\"0\" $sel>"._ARTICLES."</option>";
    while(list($catid, $title) = sql_fetch_row($selcat, $dbi)) {
	if ($catid == $cat) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"catid\" value=\"$catid\" $sel>$title</option>";
	$a++;
    }
    echo "</select> [ <a href=\"admin.php?op=AddCategory\">"._ADD."</a> | <a href=\"admin.php?op=EditCategory\">"._EDIT."</a> | <a href=\"admin.php?op=DelCategory\">"._DELETE."</a> ]";
}

function putpoll($pollTitle, $optionText) {
    OpenTable();
    echo "<center><font class=\"title\"><b>"._ATTACHAPOLL."</b></font><br>"
	."<font class=\"tiny\">"._LEAVEBLANKTONOTATTACH."</font><br>"
	."<br><br>"._POLLTITLE.": <input type=\"text\" name=\"pollTitle\" size=\"50\" maxlength=\"100\" value=\"$pollTitle\"><br><br>"
	."<font class=\"content\">"._POLLEACHFIELD."<br>"
	."<table border=\"0\">";
    for($i = 1; $i <= 12; $i++)	{
	echo "<tr>"
	    ."<td>"._OPTION." $i:</td><td><input type=\"text\" name=\"optionText[$i]\" size=\"50\" maxlength=\"50\" value=\"$optionText[$i]\"></td>"
	    ."</tr>";
    }
    echo "</table>";
    CloseTable();
}

function AddCategory () {
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._CATEGORIESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._CATEGORYADD."</b></font><br><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._CATNAME.":</b> "
	."<input type=\"text\" name=\"title\" size=\"22\" maxlength=\"20\"> "
	."<input type=\"hidden\" name=\"op\" value=\"SaveCategory\">"
	."<input type=\"submit\" value=\""._SAVE."\">"
	."</form></center>";
    CloseTable();
    include("footer.php");
}

function EditCategory($catid) {
    global $prefix, $dbi;
    $result = sql_query("select title from ".$prefix."_stories_cat where catid='$catid'", $dbi);
    list($title) = sql_fetch_row($result, $dbi);
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._CATEGORIESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._EDITCATEGORY."</b></font><br>";
    if (!$catid) {
	$selcat = sql_query("select catid, title from ".$prefix."_stories_cat", $dbi);
	echo "<form action=\"admin.php\" method=\"post\">";
	echo "<b>"._ASELECTCATEGORY."</b>";
	echo "<select name=\"catid\">";
	echo "<option name=\"catid\" value=\"0\" $sel>Articles</option>";
	while(list($catid, $title) = sql_fetch_row($selcat, $dbi)) {
	    echo "<option name=\"catid\" value=\"$catid\" $sel>$title</option>";
	}
	echo "</select>";
	echo "<input type=\"hidden\" name=\"op\" value=\"EditCategory\">";
	echo "<input type=\"submit\" value=\""._EDIT."\"><br><br>";
	echo ""._NOARTCATEDIT."";
    } else {
	echo "<form action=\"admin.php\" method=\"post\">";
	echo "<b>"._CATEGORYNAME.":</b> ";
	echo "<input type=\"text\" name=\"title\" size=\"22\" maxlength=\"20\" value=\"$title\"> ";
	echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">";
	echo "<input type=\"hidden\" name=\"op\" value=\"SaveEditCategory\">";
	echo "<input type=\"submit\" value=\""._SAVECHANGES."\"><br><br>";
	echo ""._NOARTCATEDIT."";
	echo "</form>";
    }
    echo "</center>";
    CloseTable();
    include("footer.php");
}

function DelCategory($cat) {
    global $prefix, $dbi;
    $result = sql_query("select title from ".$prefix."_stories_cat where catid='$cat'", $dbi);
    list($title) = sql_fetch_row($result, $dbi);
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._CATEGORIESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._DELETECATEGORY."</b></font><br>";
    if (!$cat) {
	$selcat = sql_query("select catid, title from ".$prefix."_stories_cat", $dbi);
	echo "<form action=\"admin.php\" method=\"post\">"
	    ."<b>"._SELECTCATDEL.": </b>"
	    ."<select name=\"cat\">";
	while(list($catid, $title) = sql_fetch_row($selcat, $dbi)) {
	    echo "<option name=\"cat\" value=\"$catid\">$title</option>";
	}
	echo "</select>"
	    ."<input type=\"hidden\" name=\"op\" value=\"DelCategory\">"
	    ."<input type=\"submit\" value=\"Delete\">"
	    ."</form>";
    } else {
	$result2 = sql_query("select * from ".$prefix."_stories where catid='$cat'", $dbi);
	$numrows = sql_num_rows($result2, $dbi);
	if ($numrows == 0) {
	    sql_query("delete from ".$prefix."_stories_cat where catid='$cat'", $dbi);
	    echo "<br><br>"._CATDELETED."<br><br>"._GOTOADMIN."";
	} else {
	    echo "<br><br><b>"._WARNING.":</b> "._THECATEGORY." <b>$title</b> "._HAS." <b>$numrows</b> "._STORIESINSIDE."<br>"
		.""._DELCATWARNING1."<br>"
		.""._DELCATWARNING2."<br><br>"
		.""._DELCATWARNING3."<br><br>"
		."<b>[ <a href=\"admin.php?op=YesDelCategory&amp;catid=$cat\">"._YESDEL."</a> | "
		."<a href=\"admin.php?op=NoMoveCategory&amp;catid=$cat\">"._NOMOVE."</a> ]</b>";
	}
    }
    echo "</center>";
    CloseTable();
    include("footer.php");
}

function YesDelCategory($catid) {
    global $prefix, $dbi;
    sql_query("delete from ".$prefix."_stories_cat where catid='$catid'", $dbi);
    $result = sql_query("select sid from ".$prefix."_stories where catid='$catid'", $dbi);
    while(list($sid) = sql_fetch_row($result, $dbi)) {
	sql_query("delete from ".$prefix."_stories where catid='$catid'", $dbi);
	sql_query("delete from ".$prefix."_comments where sid='$sid'", $dbi);
    }
    Header("Location: admin.php");
}

function NoMoveCategory($catid, $newcat) {
    global $prefix, $dbi;
    $result = sql_query("select title from ".$prefix."_stories_cat where catid='$catid'", $dbi);
    list($title) = sql_fetch_row($result, $dbi);
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._CATEGORIESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._MOVESTORIES."</b></font><br><br>";
    if (!$newcat) {
	echo ""._ALLSTORIES." <b>$title</b> "._WILLBEMOVED."<br><br>";
	$selcat = sql_query("select catid, title from ".$prefix."_stories_cat", $dbi);
	echo "<form action=\"admin.php\" method=\"post\">";
	echo "<b>"._SELECTNEWCAT.":</b> ";
	echo "<select name=\"newcat\">";
        echo "<option name=\"newcat\" value=\"0\">"._ARTICLES."</option>";
	while(list($newcat, $title) = sql_fetch_row($selcat, $dbi)) {
    	    echo "<option name=\"newcat\" value=\"$newcat\">$title</option>";
	}
	echo "</select>";
	echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">";
	echo "<input type=\"hidden\" name=\"op\" value=\"NoMoveCategory\">";
	echo "<input type=\"submit\" value=\""._OK."\">";
	echo "</form>";
    } else {
	$resultm = sql_query("select sid from ".$prefix."_stories where catid='$catid'", $dbi);
	while(list($sid) = sql_fetch_row($resultm, $dbi)) {
	    sql_query("update ".$prefix."_stories set catid='$newcat' where sid='$sid'", $dbi);
	}
	sql_query("delete from ".$prefix."_stories_cat where catid='$catid'", $dbi);
	echo ""._MOVEDONE."";
    }
    CloseTable();
    include("footer.php");
}

function SaveEditCategory($catid, $title) {
    global $prefix, $dbi;
    $title = ereg_replace("\"","",$title);
    $check = sql_query("select catid from ".$prefix."_stories_cat where title=$title", $dbi);
    if ($check) {
	$what1 = _CATEXISTS;
	$what2 = _GOBACK;
    } else {
	$what1 = _CATSAVED;
	$what2 = "[ <a href=\"admin.php\">"._GOTOADMIN."</a> ]";
	$result = sql_query("update ".$prefix."_stories_cat set title='$title' where catid='$catid'", $dbi);
	if (!$result) {
	    return;
	}
    }
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._CATEGORIESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"content\"><b>$what1</b></font><br><br>";
    echo "$what2</center>";
    CloseTable();
    include("footer.php");
}

function SaveCategory($title) {
    global $prefix, $dbi;
    $title = ereg_replace("\"","",$title);
    $check = sql_query("select catid from ".$prefix."_stories_cat where title=$title", $dbi);
    if ($check) {
	$what1 = _CATEXISTS;
	$what2 = _GOBACK;
    } else {
	$what1 = _CATADDED;
	$what2 = _GOTOADMIN;
	$result = sql_query("insert into ".$prefix."_stories_cat values (NULL, '$title', '0')", $dbi);
	if (!$result) {
	    return;
	}
    }
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._CATEGORIESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"content\"><b>$what1</b></font><br><br>";
    echo "$what2</center>";
    CloseTable();
    include("footer.php");
}

function autodelete($anid) {
    global $prefix, $dbi;
    sql_query("delete from ".$prefix."_autonews where anid=$anid", $dbi);
    Header("Location: admin.php?op=adminMain");
}

function autoEdit($anid) {
    global $aid, $bgcolor1, $bgcolor2, $prefix, $dbi, $multilingual;
    $result = sql_query("select radminarticle, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
    list($radminarticle, $radminsuper) = sql_fetch_row($result, $dbi);
    $result2 = sql_query("select aid from ".$prefix."_stories where sid='$sid'", $dbi);
    list($aaid) = sql_fetch_row($result2, $dbi);
    if (($radminarticle == 1) AND ($aaid == $aid) OR ($radminsuper == 1)) {
    include ("header.php");
    $result = sql_query("select catid, aid, title, time, hometext, bodytext, topic, informant, notes, ihome, alanguage, acomm from ".$prefix."_autonews where anid=$anid", $dbi);
    list($catid, $aid, $title, $time, $hometext, $bodytext, $topic, $informant, $notes, $ihome, $alanguage, $acomm) = sql_fetch_row($result, $dbi);
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._ARTICLEADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    echo "<center><font class=\"option\"><b>"._AUTOSTORYEDIT."</b></font></center><br><br>"
	."<form action=\"admin.php\" method=\"post\">";
    $title = stripslashes($title);
    $hometext = stripslashes($hometext);
    $bodytext = stripslashes($bodytext);
    $notes = stripslashes($notes);
    $result=sql_query("select topicimage from ".$prefix."_topics where topicid=$topic", $dbi);
    list($topicimage) = sql_fetch_row($result, $dbi);
    echo "<table border=\"0\" width=\"75%\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>"
	."<table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"$bgcolor1\"><tr><td>"
	."<img src=\"images/topics/$topicimage\" border=\"0\" align=\"right\">";
    themepreview($title, $hometext, $bodytext);
    echo "</td></tr></table></td></tr></table>"
	."<br><br><b>"._TITLE."</b><br>"
	."<input type=\"text\" name=\"title\" size=\"50\" value=\"$title\"><br><br>"
	."<b>"._TOPIC."</b> <select name=\"topic\">";
    $toplist = sql_query("select topicid, topictext from ".$prefix."_topics order by topictext", $dbi);
    echo "<option value=\"\">"._ALLTOPICS."</option>\n";
    while(list($topicid, $topics) = sql_fetch_row($toplist, $dbi)) {
    if ($topicid==$topic) { $sel = "selected "; }
        echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select><br><br>";
    $cat = $catid;
    SelectCategory($cat);
    echo "<br>";
    puthome($ihome, $acomm);
    if ($multilingual == 1) {
	echo "<br><b>"._LANGUAGE.": </b>"
	    ."<select name=\"alanguage\">";
	$handle=opendir('language');
	while ($file = readdir($handle)) {
	    if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
		$langFound = $matches[1];
	        $languageslist .= "$langFound ";
	    }
	}
	closedir($handle);
	$languageslist = explode(" ", $languageslist);
	sort($languageslist);
	for ($i=0; $i < sizeof($languageslist); $i++) {
	    if($languageslist[$i]!="") {
		echo "<option value=\"$languageslist[$i]\" ";
		if($languageslist[$i]==$alanguage) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	if ($alanguage == "") {
	    $sellang = "selected";
	} else {
    	    $sellang = "";
	}
	echo "<option value=\"\" $sellang>"._ALL."</option></select>";
    } else {
	echo "<input type=\"hidden\" name=\"alanguage\" value=\"\">";
    }
    echo "<br><br><b>"._STORYTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"hometext\">$hometext</textarea><br><br>"
	."<b>"._EXTENDEDTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"bodytext\">$bodytext</textarea><br>"
	."<font class=\"content\">"._ARESUREURL."</font><br><br>";
    if ($aid != $informant) {
    	echo "<b>"._NOTES."</b><br>
	<textarea wrap=\"virtual\" cols=\"50\" rows=\"4\" name=\"notes\">$notes</textarea><br><br>";
    }
    echo "<br><b>"._CHNGPROGRAMSTORY."</b><br><br>"
	.""._NOWIS.": $date<br><br>";
    $xday = 1;
    echo ""._DAY.": <select name=\"day\">";
    while ($xday <= 31) {
	if ($xday == $datetime[3]) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$xday</option>";
	$xday++;
    }
    echo "</select>";
    $xmonth = 1;
    echo ""._UMONTH.": <select name=\"month\">";
    while ($xmonth <= 12) {
	if ($xmonth == $datetime[2]) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$xmonth</option>";
	$xmonth++;
    }
    echo "</select>";
    echo ""._YEAR.": <input type=\"text\" name=\"year\" value=\"$datetime[1]\" size=\"5\" maxlength=\"4\">";
    echo "<br>"._HOUR.": <select name=\"hour\">";
    $xhour = 0;
    $cero = "0";
    while ($xhour <= 23) {
	$dummy = $xhour;
	if ($xhour < 10) {
	    $xhour = "$cero$xhour";
	}
	if ($xhour == $datetime[4]) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"hour\" $sel>$xhour</option>";
	$xhour = $dummy;
	$xhour++;
    }
    echo "</select>";
    echo ": <select name=\"min\">";
    $xmin = 0;
    while ($xmin <= 59) {
	if (($xmin == 0) OR ($xmin == 5)) {
	    $xmin = "0$xmin";
	}
	if ($xmin == $datetime[5]) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"min\" $sel>$xmin</option>";
	$xmin = $xmin + 5;
    }
    echo "</select>";
    echo ": 00<br><br>
    <input type=\"hidden\" name=\"anid\" value=\"$anid\">
    <input type=\"hidden\" name=\"op\" value=\"autoSaveEdit\">
    <input type=\"submit\" value=\""._SAVECHANGES."\">
    </form>";
    CloseTable();
    include ('footer.php');
    } else {
	include ('header.php');
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._ARTICLEADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><b>"._NOTAUTHORIZED1."</b><br><br>"
	    .""._NOTAUTHORIZED2."<br><br>"
	    .""._GOBACK."";
	CloseTable();
	include("footer.php");
    }
}

function autoSaveEdit($anid, $year, $day, $month, $hour, $min, $title, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $alanguage, $acomm) {
    global $aid, $ultramode, $prefix, $dbi;
    $result = sql_query("select radminarticle, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
    list($radminarticle, $radminsuper) = sql_fetch_row($result, $dbi);
    $result2 = sql_query("select aid from ".$prefix."_stories where sid='$sid'", $dbi);
    list($aaid) = sql_fetch_row($result2, $dbi);
    if (($radminarticle == 1) AND ($aaid == $aid) OR ($radminsuper == 1)) {
    if ($day < 10) {
	$day = "0$day";
    }
    if ($month < 10) {
	$month = "0$month";
    }
    $sec = "00";
    $date = "$year-$month-$day $hour:$min:$sec";
    $title = stripslashes(FixQuotes($title));
    $hometext = stripslashes(FixQuotes($hometext));
    $bodytext = stripslashes(FixQuotes($bodytext));
    $notes = stripslashes(FixQuotes($notes));
    $result = sql_query("update ".$prefix."_autonews set catid='$catid', title='$title', time='$date', hometext='$hometext', bodytext='$bodytext', topic='$topic', notes='$notes', ihome='$ihome', alanguage='$alanguage', acomm='$acomm' where anid=$anid", $dbi);
    if (!$result) {
	exit();
    }
    if ($ultramode) {
	ultramode();
    }
    Header("Location: admin.php?op=adminMain");
    } else {
	include ('header.php');
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._ARTICLEADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><b>"._NOTAUTHORIZED1."</b><br><br>"
	    .""._NOTAUTHORIZED2."<br><br>"
	    .""._GOBACK."";
	CloseTable();
	include("footer.php");
    }
}

function displayStory($qid) {
    global $user, $subject, $story, $bgcolor1, $bgcolor2, $anonymous, $prefix, $dbi, $db, $multilingual;
    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._SUBMISSIONSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $ttmon = $today[mon];
    if ($ttmon < 10){
	$ttmon = "0$ttmon";
    }
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    $result = sql_query("SELECT qid, uid, uname, subject, story, storyext, topic, alanguage FROM ".$prefix."_queue where qid=$qid", $dbi);
    list($qid, $uid, $uname, $subject, $story, $storyext, $topic, $alanguage) = sql_fetch_row($result, $dbi);
    $subject = stripslashes($subject);
    $story = stripslashes($story);
    $storyext = stripslashes($storyext);

    OpenTable();
    echo "<font class=\"content\">"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._NAME."</b><br>"
	."<input type=\"text\" NAME=\"author\" size=\"25\" value=\"$uname\">";
    if ($uname != $anonymous) {
	$res = sql_query("select user_email from ".$prefix."_users where username='$uname'", $dbi);
	list($email) = sql_fetch_row($res, $dbi);
	echo "&nbsp;&nbsp;<font class=\"content\">[ <a href=\"mailto:$email?Subject=Re: $subject\">"._EMAILUSER."</a> | <a href='modules.php?name=Your_Account&op=userinfo&username=$uname'>"._USERPROFILE."</a> | <a href=\"modules.php?name=Private_Messages&amp;mode=post&amp;u=$uid\">"._SENDPM."</a> ]</font>";
    }
    echo "<br><br><b>"._TITLE."</b><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"><br><br>";
    if($topic=="") {
        $topic = 1;
    }
    $result = sql_query("select topicimage from ".$prefix."_topics where topicid=$topic", $dbi);
    list($topicimage) = sql_fetch_row($result, $dbi);
    echo "<table border=\"0\" width=\"70%\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>"
	."<table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"$bgcolor1\"><tr><td>"
	."<img src=\"images/topics/$topicimage\" border=\"0\" align=\"right\" alt=\"\">";
    $storypre = "$story<br><br>$storyext";
    themepreview($subject, $storypre);
    echo "</td></tr></table></td></tr></table>"
	."<br><b>"._TOPIC."</b> <select name=\"topic\">";
    $toplist = sql_query("select topicid, topictext from ".$prefix."_topics order by topictext", $dbi);
    echo "<option value=\"\">"._SELECTTOPIC."</option>\n";
    while(list($topicid, $topics) = sql_fetch_row($toplist, $dbi)) {
        if ($topicid==$topic) {
	    $sel = "selected ";
	}
        echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select>";
    echo "<br><br>";
    echo "<table border='0' width='100%' cellspacing='0'><tr><td width='20%'><b>"._ASSOTOPIC."</b></td><td width='100%'>"
	."<table border='1' cellspacing='3' cellpadding='8'><tr>";
    $sql = "SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	if ($a == 3) {
	    echo "</tr><tr>";
	    $a = 0;
	}
	echo "<td><input type='checkbox' name='assotop[]' value='$row[topicid]'>$row[topictext]</td>";
	$a++;
    }
    echo "</tr></table></td></tr></table><br><br>";
    SelectCategory($cat);
    echo "<br>";
    puthome($ihome, $acomm);
    if ($multilingual == 1) {
	echo "<br><b>"._LANGUAGE.": </b>"
	    ."<select name=\"alanguage\">";
	$handle=opendir('language');
	while ($file = readdir($handle)) {
	    if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
	        $langFound = $matches[1];
	        $languageslist .= "$langFound ";
	    }
	}
	closedir($handle);
	$languageslist = explode(" ", $languageslist);
	sort($languageslist);
	for ($i=0; $i < sizeof($languageslist); $i++) {
	    if($languageslist[$i]!="") {
		echo "<option value=\"$languageslist[$i]\" ";
		if($languageslist[$i]==$alanguage) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	if ($alanguage == "") {
	    $sellang = "selected";
	} else {
    	    $sellang = "";
	}
	echo "<option value=\"\" $sellang>"._ALL."</option></select>";
    } else {
	echo "<input type=\"hidden\" name=\"alanguage\" value=\"\">";
    }
    echo "<br><br><b>"._STORYTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"7\" name=\"hometext\">$story</textarea><br><br>"
	."<b>"._EXTENDEDTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"8\" name=\"bodytext\">$storyext</textarea><BR>"
	."<font class=\"content\">"._AREYOUSURE."</font><br><br>"
	."<b>"._NOTES."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"4\" name=\"notes\"></textarea><br>"
	."<input type=\"hidden\" NAME=\"qid\" size=\"50\" value=\"$qid\">"
	."<input type=\"hidden\" NAME=\"uid\" size=\"50\" value=\"$uid\">"
	."<br><b>"._PROGRAMSTORY."</b>&nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"0\" checked>"._NO."<br><br>"
	.""._NOWIS.": $date<br><br>";
    $day = 1;
    echo ""._DAY.": <select name=\"day\">";
    while ($day <= 31) {
	if ($tday==$day) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$day</option>";
	$day++;
    }
    echo "</select>";
    $month = 1;
    echo ""._UMONTH.": <select name=\"month\">";
    while ($month <= 12) {
	if ($ttmon==$month) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$month</option>";
	$month++;
    }
    echo "</select>";
    $date = getdate();
    $year = $date[year];
    echo ""._YEAR.": <input type=\"text\" name=\"year\" value=\"$year\" size=\"5\" maxlength=\"4\">";
    echo "<br>"._HOUR.": <select name=\"hour\">";
    $hour = 0;
    $cero = "0";
    while ($hour <= 23) {
	$dummy = $hour;
	if ($hour < 10) {
	    $hour = "$cero$hour";
	}
	echo "<option name=\"hour\">$hour</option>";
	$hour = $dummy;
	$hour++;
    }
    echo "</select>";
    echo ": <select name=\"min\">";
    $min = 0;
    while ($min <= 59) {
	if (($min == 0) OR ($min == 5)) {
	    $min = "0$min";
	}
	echo "<option name=\"min\">$min</option>";
	$min = $min + 5;
    }
    echo "</select>";
    echo ": 00<br><br>"
	."<select name=\"op\">"
	."<option value=\"DeleteStory\">"._DELETESTORY."</option>"
	."<option value=\"PreviewAgain\" selected>"._PREVIEWSTORY."</option>"
	."<option value=\"PostStory\">"._POSTSTORY."</option>"
	."</select>"
	."<input type=\"submit\" value=\""._OK."\"> &nbsp;&nbsp [ <a href=\"admin.php?op=DeleteStory&qid=$qid\">"._DELETE."</a> ]";
    CloseTable();
    echo "<br>";
    putpoll($pollTitle, $optionText);
    echo "</form>";
    include ('footer.php');
}

function previewStory($automated, $year, $day, $month, $hour, $min, $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $alanguage, $acomm, $pollTitle, $optionText, $assotop) {
    global $user, $boxstuff, $anonymous, $bgcolor1, $bgcolor2, $prefix, $dbi, $multilingual;
    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._ARTICLEADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    $subject = stripslashes($subject);
    $hometext = stripslashes($hometext);
    $bodytext = stripslashes($bodytext);
    $notes = stripslashes($notes);
    OpenTable();
    echo "<font class=\"content\">"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._NAME."</b><br>"
	."<input type=\"text\" name=\"author\" size=\"25\" value=\"$author\">";
    if ($author != $anonymous) {
	$res = sql_query("select user_id, user_email from ".$prefix."_users where username='$author'", $dbi);
	list($pm_userid, $email) = sql_fetch_row($res, $dbi);
	echo "&nbsp;&nbsp;<font class=\"content\">[ <a href=\"mailto:$email?Subject=Re: $subject\">"._EMAILUSER."</a> | <a href='modules.php?name=Your_Account&op=userinfo&username=$author'>"._USERPROFILE."</a> | <a href=\"modules.php?name=Private_Messages&amp;mode=post&amp;u=$uid\">"._SENDPM."</a> ]</font>";
    }
    echo "<br><br><b>"._TITLE."</b><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"><br><br>";
    $result = sql_query("select topicimage from ".$prefix."_topics where topicid=$topic", $dbi);
    list($topicimage) = sql_fetch_row($result, $dbi);
    echo "<table width=\"70%\" bgcolor=\"$bgcolor2\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\"align=\"center\"><tr><td>"
	."<table width=\"100%\" bgcolor=\"$bgcolor1\" cellpadding=\"8\" cellspacing=\"1\" border=\"0\"><tr><td>"
	."<img src=\"images/topics/$topicimage\" border=\"0\" align=\"right\">";
    themepreview($subject, $hometext, $bodytext, $notes);
    echo "</td></tr></table></td></tr></table>"
	."<br><b>"._TOPIC."</b> <select name=\"topic\">";
    $toplist = sql_query("select topicid, topictext from ".$prefix."_topics order by topictext", $dbi);
    echo "<option value=\"\">"._ALLTOPICS."</option>\n";
    while(list($topicid, $topics) = sql_fetch_row($toplist, $dbi)) {
        if ($topicid==$topic) {
	    $sel = "selected ";
	}
        echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select>";
    echo "<br><br>";
    for ($i=0; $i<sizeof($assotop); $i++) {
	$associated .= "$assotop[$i]-";
    }
    $asso_t = explode("-", $associated);
    echo "<table border='0' width='100%' cellspacing='0'><tr><td width='20%'><b>"._ASSOTOPIC."</b></td><td width='100%'>"
        ."<table border='1' cellspacing='3' cellpadding='8'><tr>";
    $sql = "SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
        if ($a == 3) {
    	    echo "</tr><tr>";
	    $a = 0;
	}
	for ($i=0; $i<sizeof($asso_t); $i++) {
	    if ($asso_t[$i] == $row[topicid]) {
	        $checked = "CHECKED";
	        break;
	    }
	}
	echo "<td><input type='checkbox' name='assotop[]' value='$row[topicid]' $checked>$row[topictext]</td>";
	$checked = "";
	$a++;
    }
    echo "</tr></table></td></tr></table><br><br>";
    $cat = $catid;
    SelectCategory($cat);
    echo "<br>";
    puthome($ihome, $acomm);
    if ($multilingual == 1) {
	echo "<br><b>"._LANGUAGE.": </b>"
	    ."<select name=\"alanguage\">";
	$handle=opendir('language');
	while ($file = readdir($handle)) {
	    if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
	        $langFound = $matches[1];
	        $languageslist .= "$langFound ";
	    }
	}
	closedir($handle);
	$languageslist = explode(" ", $languageslist);
	sort($languageslist);
	for ($i=0; $i < sizeof($languageslist); $i++) {
	    if($languageslist[$i]!="") {
		echo "<option value=\"$languageslist[$i]\" ";
		if($languageslist[$i]==$alanguage) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	if ($alanguage == "") {
	    $sellang = "selected";
	} else {
    	    $sellang = "";
	}
	echo "<option value=\"\" $sellang>"._ALL."</option></select>";
    } else {
	echo "<input type=\"hidden\" name=\"alanguage\" value=\"$language\">";
    }
    echo "<br><br><b>"._STORYTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"7\" name=\"hometext\">$hometext</textarea><br><br>"
	."<b>"._EXTENDEDTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"10\" name=\"bodytext\">$bodytext</textarea><br>"
	."<font class=\"content\">"._AREYOUSURE."</font><br><br>"
	."<b>"._NOTES."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"4\" name=\"notes\">$notes</textarea><br><br>"
	."<input type=\"hidden\" NAME=\"qid\" size=\"50\" value=\"$qid\">"
	."<input type=\"hidden\" NAME=\"uid\" size=\"50\" value=\"$uid\">";
    if ($automated == 1) {
	$sel1 = "checked";
	$sel2 = "";
    } else {
	$sel1 = "";
	$sel2 = "checked";
    }
    echo "<b>"._PROGRAMSTORY."</b>&nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"1\" $sel1>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"0\" $sel2>"._NO."<br><br>"
	.""._NOWIS.": $date<br><br>";
    $xday = 1;
    echo ""._DAY.": <select name=\"day\">";
    while ($xday <= 31) {
	if ($xday == $day) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$xday</option>";
	$xday++;
    }
    echo "</select>";
    $xmonth = 1;
    echo ""._UMONTH.": <select name=\"month\">";
    while ($xmonth <= 12) {
	if ($xmonth == $month) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$xmonth</option>";
	$xmonth++;
    }
    echo "</select>";
    echo ""._YEAR.": <input type=\"text\" name=\"year\" value=\"$year\" size=\"5\" maxlength=\"4\">";
    echo "<br>"._HOUR.": <select name=\"hour\">";
    $xhour = 0;
    $cero = "0";
    while ($xhour <= 23) {
	$dummy = $xhour;
	if ($xhour < 10) {
	    $xhour = "$cero$xhour";
	}
	if ($xhour == $hour) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"hour\" $sel>$xhour</option>";
	$xhour = $dummy;
	$xhour++;
    }
    echo "</select>";
    echo ": <select name=\"min\">";
    $xmin = 0;
    while ($xmin <= 59) {
	if (($xmin == 0) OR ($xmin == 5)) {
	    $xmin = "0$xmin";
	}
	if ($xmin == $min) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"min\" $sel>$xmin</option>";
	$xmin = $xmin + 5;
    }
    echo "</select>";
    echo ": 00<br><br>"
	."<select name=\"op\">"
	."<option value=\"DeleteStory\">"._DELETESTORY."</option>"
	."<option value=\"PreviewAgain\" selected>"._PREVIEWSTORY."</option>"
	."<option value=\"PostStory\">"._POSTSTORY."</option>"
	."</select>"
	."<input type=\"submit\" value=\""._OK."\">";
    CloseTable();
    echo "<br>";
    putpoll($pollTitle, $optionText);
    echo "</form>";
    include ('footer.php');
}

function postStory($automated, $year, $day, $month, $hour, $min, $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $alanguage, $acomm, $pollTitle, $optionText, $assotop) {
    global $aid, $ultramode, $prefix, $dbi;
    for ($i=0; $i<sizeof($assotop); $i++) {
	$associated .= "$assotop[$i]-";
    }
    if ($automated == 1) {
        if ($day < 10) {
	    $day = "0$day";
	}
	if ($month < 10) {
	    $month = "0$month";
	}
	$sec = "00";
	$date = "$year-$month-$day $hour:$min:$sec";
	if ($uid == 1) $author = "";
	if ($hometext == $bodytext) $bodytext = "";
	$subject = stripslashes(FixQuotes($subject));
	$hometext = stripslashes(FixQuotes($hometext));
	$bodytext = stripslashes(FixQuotes($bodytext));
	$notes = stripslashes(FixQuotes($notes));
	$result = sql_query("insert into ".$prefix."_autonews values (NULL, '$catid', '$aid', '$subject', '$date', '$hometext', '$bodytext', '$topic', '$author', '$notes', '$ihome', '$alanguage', '$acomm', '$associated')", $dbi);
	if (!$result) {
	    return;
	}
	if ($uid == 1) {
	} else {
	    sql_query("update ".$prefix."_users set counter=counter+1 where user_id='$uid'", $dbi);
	}
	    sql_query("update ".$prefix."_authors set counter=counter+1 where aid='$aid'", $dbi);
	if ($ultramode) {
    	    ultramode();
	}
	sql_query("delete from ".$prefix."_queue where qid=$qid", $dbi);
	Header("Location: admin.php?op=submissions");
    } else {
	if ($uid == 1) $author = "";
	if ($hometext == $bodytext) $bodytext = "";
	$subject = stripslashes(FixQuotes($subject));
	$hometext = stripslashes(FixQuotes($hometext));
	$bodytext = stripslashes(FixQuotes($bodytext));
	$notes = stripslashes(FixQuotes($notes));
	if (($pollTitle != "") AND ($optionText[1] != "") AND ($optionText[2] != "")) {
	    $haspoll = 1;
	    $timeStamp = time();
	    $pollTitle = FixQuotes($pollTitle);
	    if(!sql_query("INSERT INTO ".$prefix."_poll_desc VALUES (NULL, '$pollTitle', '$timeStamp', 0, '$alanguage', '0')", $dbi)) {
		return;
	    }
	    $object = sql_fetch_object(sql_query("SELECT pollID FROM ".$prefix."_poll_desc WHERE pollTitle='$pollTitle'", $dbi), $dbi);
	    $id = $object->pollID;
	    for($i = 1; $i <= sizeof($optionText); $i++) {
		if($optionText[$i] != "") {
		    $optionText[$i] = FixQuotes($optionText[$i]);
		}
		if(!sql_query("INSERT INTO ".$prefix."_poll_data (pollID, optionText, optionCount, voteID) VALUES ($id, '$optionText[$i]', 0, $i)", $dbi)) {
		    return;
		}
	    }
	} else {
	    $haspoll = 0;
	    $id = 0;
	}
	$result = sql_query("insert into ".$prefix."_stories values (NULL, '$catid', '$aid', '$subject', now(), '$hometext', '$bodytext', '0', '0', '$topic', '$author', '$notes', '$ihome', '$alanguage', '$acomm', '$haspoll', '$id', '0', '0', '$associated')", $dbi);
	$result = sql_query("select sid from ".$prefix."_stories WHERE title='$subject' order by time DESC limit 0,1", $dbi);
	list($artid) = sql_fetch_row($result, $dbi);
	sql_query("UPDATE ".$prefix."_poll_desc SET artid='$artid' WHERE pollID='$id'", $dbi);
	if (!$result) {
	    return;
	}
	if ($uid == 1) {
	} else {
    	    sql_query("update ".$prefix."_users set counter=counter+1 where user_id='$uid'", $dbi);
	}
	sql_query("update ".$prefix."_authors set counter=counter+1 where aid='$aid'", $dbi);
	if ($ultramode) {
    	    ultramode();
	}
	deleteStory($qid);
    }
}

function editStory($sid) {
    global $user, $bgcolor1, $bgcolor2, $aid, $prefix, $dbi, $db, $multilingual;
    $result = sql_query("select radminarticle, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
    list($radminarticle, $radminsuper) = sql_fetch_row($result, $dbi);
    $result2 = sql_query("select aid from ".$prefix."_stories where sid='$sid'", $dbi);
    list($aaid) = sql_fetch_row($result2, $dbi);
    if (($radminarticle == 1) AND ($aaid == $aid) OR ($radminsuper == 1)) {
	include ('header.php');
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._ARTICLEADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	$result = sql_query("SELECT catid, title, hometext, bodytext, topic, notes, ihome, alanguage, acomm FROM ".$prefix."_stories where sid=$sid", $dbi);
        list($catid, $subject, $hometext, $bodytext, $topic, $notes, $ihome, $alanguage, $acomm) = sql_fetch_row($result, $dbi);
	$subject = stripslashes($subject);
        $hometext = stripslashes($hometext);
        $bodytext = stripslashes($bodytext);
        $notes = stripslashes($notes);
        $result2=sql_query("select topicimage from ".$prefix."_topics where topicid=$topic", $dbi);
        list($topicimage) = sql_fetch_row($result2, $dbi);
        OpenTable();
        echo "<center><font class=\"option\"><b>"._EDITARTICLE."</b></font></center><br>"
	    ."<table width=\"80%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>"
	    ."<table width=\"100%\" border=\"0\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"$bgcolor1\"><tr><td>"
	    ."<img src=\"images/topics/$topicimage\" border=\"0\" align=\"right\">";
	themepreview($subject, $hometext, $bodytext, $notes);
	echo "</td></tr></table></td></tr></table><br><br>"
	    ."<form action=\"admin.php\" method=\"post\">"
	    ."<b>"._TITLE."</b><br>"
	    ."<input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"><br><br>"
	    ."<b>"._TOPIC."</b> <select name=\"topic\">";
	$toplist = sql_query("select topicid, topictext from ".$prefix."_topics order by topictext", $dbi);
	echo "<option value=\"\">"._ALLTOPICS."</option>\n";
	while(list($topicid, $topics) = sql_fetch_row($toplist, $dbi)) {
    	    if ($topicid==$topic) { $sel = "selected "; }
        	echo "<option $sel value=\"$topicid\">$topics</option>\n";
		$sel = "";
	}
	echo "</select>";
	echo "<br><br>";
	$asql = "SELECT associated FROM ".$prefix."_stories WHERE sid='$sid'";
	$aresult = $db->sql_query($asql);
	$arow = $db->sql_fetchrow($aresult);
	$asso_t = explode("-", $arow[associated]);
	echo "<table border='0' width='100%' cellspacing='0'><tr><td width='20%'><b>"._ASSOTOPIC."</b></td><td width='100%'>"
	    ."<table border='1' cellspacing='3' cellpadding='8'><tr>";
	$sql = "SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext";
        $result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
	    if ($a == 3) {
		echo "</tr><tr>";
		$a = 0;
	    }
	    for ($i=0; $i<sizeof($asso_t); $i++) {
		if ($asso_t[$i] == $row[topicid]) {
		    $checked = "CHECKED";
		    break;
		}
	    }
	    echo "<td><input type='checkbox' name='assotop[]' value='$row[topicid]' $checked>$row[topictext]</td>";
	    $checked = "";
	    $a++;
	}
	echo "</tr></table></td></tr></table><br><br>";
	$cat = $catid;
	SelectCategory($cat);
	echo "<br>";
	puthome($ihome, $acomm);
	if ($multilingual == 1) {
	    echo "<br><b>"._LANGUAGE.":</b>"
		."<select name=\"alanguage\">";
	    $handle=opendir('language');
	    while ($file = readdir($handle)) {
		if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
	    	    $langFound = $matches[1];
	    	    $languageslist .= "$langFound ";
	    	}
	    }
	    closedir($handle);
	    $languageslist = explode(" ", $languageslist);
	    sort($languageslist);
	    for ($i=0; $i < sizeof($languageslist); $i++) {
	        if($languageslist[$i]!="") {
		    echo "<option name=\"alanguage\" value=\"$languageslist[$i]\" ";
		    if($languageslist[$i]==$alanguage) echo "selected";
		    echo ">".ucfirst($languageslist[$i])."\n</option>";
		}
	    }
	    if ($alanguage == "") {
		$sellang = "selected";
	    } else {
    		$sellang = "";
	    }
	    echo "<option value=\"\" $sellang>"._ALL."</option></select>";
	} else {
	    echo "<input type=\"hidden\" name=\"alanguage\" value=\"\">";
	}
	echo "<br><br><b>"._STORYTEXT."</b><br>"
	    ."<textarea wrap=\"virtual\" cols=\"50\" rows=\"7\" name=\"hometext\">$hometext</textarea><br><br>"
	    ."<b>"._EXTENDEDTEXT."</b><br>"
	    ."<textarea wrap=\"virtual\" cols=\"50\" rows=\"10\" name=\"bodytext\">$bodytext</textarea><br>"
	    ."<font class=\"content\">"._AREYOUSURE."</font><br><br>"
	    ."<b>"._NOTES."</b><br>"
	    ."<textarea wrap=\"virtual\" cols=\"50\" rows=\"4\" name=\"notes\">$notes</textarea><br><br>"
	    ."<input type=\"hidden\" NAME=\"sid\" size=\"50\" value=\"$sid\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"ChangeStory\">"
	    ."<input type=\"submit\" value=\""._SAVECHANGES."\">"
	    ."</form>";
	CloseTable();
	include ('footer.php');
    } else {
	include ('header.php');
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._ARTICLEADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><b>"._NOTAUTHORIZED1."</b><br><br>"
	    .""._NOTAUTHORIZED2."<br><br>"
	    .""._GOBACK."";
	CloseTable();
	include("footer.php");
    }
}

function removeStory($sid, $ok=0) {
    global $ultramode, $aid, $prefix, $dbi;
    $result = sql_query("select counter, radminarticle, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
    list($counter, $radminarticle, $radminsuper) = sql_fetch_row($result, $dbi);
    $result2 = sql_query("select aid from ".$prefix."_stories where sid='$sid'", $dbi);
    list($aaid) = sql_fetch_row($result2, $dbi);
    if (($radminarticle == 1) AND ($aaid == $aid) OR ($radminsuper == 1)) {
	if($ok) {
	    $counter--;
    	    sql_query("DELETE FROM ".$prefix."_stories where sid=$sid", $dbi);
	    sql_query("DELETE FROM ".$prefix."_comments where sid=$sid", $dbi);
	    sql_query("update ".$prefix."_poll_desc set artid='0' where artid='$sid'", $dbi);
	    $result = sql_query("update ".$prefix."_authors set counter='$counter' where aid='$aid'", $dbi);
	    if ($ultramode) {
	        ultramode();
	    }
	    Header("Location: admin.php");
	} else {
	    include("header.php");
	    GraphicAdmin();
	    OpenTable();
	    echo "<center><font class=\"title\"><b>"._ARTICLEADMIN."</b></font></center>";
	    CloseTable();
	    echo "<br>";
	    OpenTable();
	    echo "<center>"._REMOVESTORY." $sid "._ANDCOMMENTS."";
	    echo "<br><br>[ <a href=\"admin.php\">"._NO."</a> | <a href=\"admin.php?op=RemoveStory&amp;sid=$sid&amp;ok=1\">"._YES."</a> ]</center>";
    	    CloseTable();
	    include("footer.php");
	}
    } else {
	include ('header.php');
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._ARTICLEADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><b>"._NOTAUTHORIZED1."</b><br><br>"
	    .""._NOTAUTHORIZED2."<br><br>"
	    .""._GOBACK."";
	CloseTable();
	include("footer.php");
    }
}


function changeStory($sid, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $alanguage, $acomm, $assotop) {
    global $aid, $ultramode, $prefix, $dbi;
    for ($i=0; $i<sizeof($assotop); $i++) {
	$associated .= "$assotop[$i]-";
    }
    $result = sql_query("select radminarticle, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
    list($radminarticle, $radminsuper) = sql_fetch_row($result, $dbi);
    $result2 = sql_query("select aid from ".$prefix."_stories where sid='$sid'", $dbi);
    list($aaid) = sql_fetch_row($result2, $dbi);
    if (($radminarticle == 1) AND ($aaid == $aid) OR ($radminsuper == 1)) {
	$subject = stripslashes(FixQuotes($subject));
	$hometext = stripslashes(FixQuotes($hometext));
	$bodytext = stripslashes(FixQuotes($bodytext));
	$notes = stripslashes(FixQuotes($notes));
	sql_query("update ".$prefix."_stories set catid='$catid', title='$subject', hometext='$hometext', bodytext='$bodytext', topic='$topic', notes='$notes', ihome='$ihome', alanguage='$alanguage', acomm='$acomm', associated='$associated' where sid=$sid", $dbi);
	if ($ultramode) {
    	    ultramode();
	}
	Header("Location: admin.php?op=adminMain");
    }
}

function adminStory() {
    global $prefix, $dbi, $db, $language, $multilingual;
    include ('header.php');
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._ARTICLEADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $ttmon = $today[mon];
    if ($ttmon < 10){
	$ttmon = "0$ttmon";
    }
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._ADDARTICLE."</b></font></center><br><br>"
    	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._TITLE."</b><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\"><br><br>"
	."<b>"._TOPIC."</b> ";
    $toplist = sql_query("select topicid, topictext from ".$prefix."_topics order by topictext", $dbi);
    echo "<select name=\"topic\">";
    echo "<option value=\"\">"._SELECTTOPIC."</option>\n";
    while(list($topicid, $topics) = sql_fetch_row($toplist, $dbi)) {
        if ($topicid == $topic) {
	    $sel = "selected ";
	}
    	echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select><br><br>";
    echo "<table border='0' width='100%' cellspacing='0'><tr><td width='20%'><b>"._ASSOTOPIC."</b></td><td width='100%'>"
	."<table border='1' cellspacing='3' cellpadding='8'><tr>";
    $sql = "SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	if ($a == 3) {
	    echo "</tr><tr>";
	    $a = 0;
	}
	echo "<td><input type='checkbox' name='assotop[]' value='$row[topicid]'>$row[topictext]</td>";
	$a++;
    }
    echo "</tr></table></td></tr></table><br><br>";
    $cat = 0;
    SelectCategory($cat);
    echo "<br>";
    puthome($ihome, $acomm);
    if ($multilingual == 1) {
	echo "<br><b>"._LANGUAGE.": </b>"
	    ."<select name=\"alanguage\">";
	$handle=opendir('language');
	while ($file = readdir($handle)) {
	    if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
	        $langFound = $matches[1];
	        $languageslist .= "$langFound ";
	    }
	}
	closedir($handle);
	$languageslist = explode(" ", $languageslist);
	sort($languageslist);
	for ($i=0; $i < sizeof($languageslist); $i++) {
	    if($languageslist[$i]!="") {
		echo "<option value=\"$languageslist[$i]\" ";
		if($languageslist[$i]==$language) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	echo "<option value=\"\">"._ALL."</option></select>";
    } else {
	echo "<input type=\"hidden\" name=\"alanguage\" value=\"$language\">";
    }
    echo "<br><br><b>"._STORYTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"hometext\"></textarea><br><br>"
	."<b>"._EXTENDEDTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"bodytext\"></textarea><br>"
	."<font class=\"content\">"._ARESUREURL."</font>"
	."<br><br><b>"._PROGRAMSTORY."</b>&nbsp;&nbsp;"
	."<input type=radio name=automated value=1>"._YES." &nbsp;&nbsp;"
	."<input type=radio name=automated value=0 checked>"._NO."<br><br>"
	.""._NOWIS.": $date<br><br>";
    $day = 1;
    echo ""._DAY.": <select name=\"day\">";
    while ($day <= 31) {
	if ($tday==$day) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$day</option>";
	$day++;
    }
    echo "</select>";
    $month = 1;
    echo ""._UMONTH.": <select name=\"month\">";
    while ($month <= 12) {
	if ($ttmon==$month) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$month</option>";
	$month++;
    }
    echo "</select>";
    $date = getdate();
    $year = $date[year];
    echo ""._YEAR.": <input type=\"text\" name=\"year\" value=\"$year\" size=\"5\" maxlength=\"4\">"
	."<br>"._HOUR.": <select name=\"hour\">";
    $hour = 0;
    $cero = "0";
    while ($hour <= 23) {
	$dummy = $hour;
	if ($hour < 10) {
	    $hour = "$cero$hour";
	}
	echo "<option name=\"hour\">$hour</option>";
	$hour = $dummy;
	$hour++;
    }
    echo "</select>"
	.": <select name=\"min\">";
    $min = 0;
    while ($min <= 59) {
	if (($min == 0) OR ($min == 5)) {
	    $min = "0$min";
	}
	echo "<option name=\"min\">$min</option>";
	$min = $min + 5;
    }
    echo "</select>";
    echo ": 00<br><br>"
	."<select name=\"op\">"
	."<option value=\"PreviewAdminStory\" selected>"._PREVIEWSTORY."</option>"
	."<option value=\"PostAdminStory\">"._POSTSTORY."</option>"
	."</select>"
	."<input type=\"submit\" value=\""._OK."\">";
    CloseTable();
    echo "<br>";
    putpoll($pollTitle, $optionText);
    echo "</form>";
    include ('footer.php');
}

function previewAdminStory($automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $topic, $catid, $ihome, $alanguage, $acomm, $pollTitle, $optionText, $assotop) {
    global $user, $bgcolor1, $bgcolor2, $prefix, $dbi, $db, $alanguage, $multilingual;
    include ('header.php');
    if ($topic<1) {
        $topic = 1;
    }
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._ARTICLEADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._PREVIEWSTORY."</b></font></center><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<input type=\"hidden\" name=\"catid\" value=\"$catid\">";
    $subject = stripslashes($subject);
    $subject = ereg_replace("\"", "''", $subject);
    $hometext = stripslashes($hometext);
    $bodytext = stripslashes($bodytext);
    $result=sql_query("select topicimage from ".$prefix."_topics where topicid=$topic", $dbi);
    list($topicimage) = sql_fetch_row($result, $dbi);
    echo "<table border=\"0\" width=\"75%\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>"
	."<table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"$bgcolor1\"><tr><td>"
	."<img src=\"images/topics/$topicimage\" border=\"0\" align=\"right\" alt=\"\">";
    themepreview($subject, $hometext, $bodytext);
    echo "</td></tr></table></td></tr></table>"
	."<br><br><b>"._TITLE."</b><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"><br><br>"
	."<b>"._TOPIC."</b><select name=\"topic\">";
    $toplist = sql_query("select topicid, topictext from ".$prefix."_topics order by topictext", $dbi);
    echo "<option value=\"\">"._ALLTOPICS."</option>\n";
    while(list($topicid, $topics) = sql_fetch_row($toplist, $dbi)) {
	if ($topicid==$topic) {
	    $sel = "selected ";
	}
        echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select><br><br>";
    for ($i=0; $i<sizeof($assotop); $i++) {
	$associated .= "$assotop[$i]-";
    }
    $asso_t = explode("-", $associated);
    echo "<table border='0' width='100%' cellspacing='0'><tr><td width='20%'><b>"._ASSOTOPIC."</b></td><td width='100%'>"
        ."<table border='1' cellspacing='3' cellpadding='8'><tr>";
    $sql = "SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
        if ($a == 3) {
    	    echo "</tr><tr>";
	    $a = 0;
	}
	for ($i=0; $i<sizeof($asso_t); $i++) {
	    if ($asso_t[$i] == $row[topicid]) {
	        $checked = "CHECKED";
	        break;
	    }
	}
	echo "<td><input type='checkbox' name='assotop[]' value='$row[topicid]' $checked>$row[topictext]</td>";
	$checked = "";
	$a++;
    }
    echo "</tr></table></td></tr></table><br><br>";
    $cat = $catid;
    SelectCategory($cat);
    echo "<br>";
    puthome($ihome, $acomm);
    if ($multilingual == 1) {
	echo "<br><b>"._LANGUAGE.": </b>"
	    ."<select name=\"alanguage\">";
	$handle=opendir('language');
	while ($file = readdir($handle)) {
	    if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
	        $langFound = $matches[1];
	        $languageslist .= "$langFound ";
	    }
	}
	closedir($handle);
	$languageslist = explode(" ", $languageslist);
	sort($languageslist);
	for ($i=0; $i < sizeof($languageslist); $i++) {
	    if($languageslist[$i]!="") {
		echo "<option value=\"$languageslist[$i]\" ";
		if($languageslist[$i]==$alanguage) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	if ($alanguage == "") {
	    $sellang = "selected";
	} else {
    	    $sellang = "";
	}
	echo "<option value=\"\" $sellang>"._ALL."</option></select>";
    } else {
	echo "<input type=\"hidden\" name=\"alanguage\" value=\"$language\">";
    }
    echo "<br><br><b>"._STORYTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"hometext\">$hometext</textarea><br><br>"
	."<b>"._EXTENDEDTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"bodytext\">$bodytext</textarea><br><br>";
    if ($automated == 1) {
	$sel1 = "checked";
	$sel2 = "";
    } else {
	$sel1 = "";
	$sel2 = "checked";
    }
    echo "<br><b>"._PROGRAMSTORY."</b>&nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"1\" $sel1>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"0\" $sel2>"._NO."<br><br>"
	.""._NOWIS.": $date<br><br>";
    $xday = 1;
    echo ""._DAY.": <select name=\"day\">";
    while ($xday <= 31) {
	if ($xday == $day) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$xday</option>";
	$xday++;
    }
    echo "</select>";
    $xmonth = 1;
    echo ""._UMONTH.": <select name=\"month\">";
    while ($xmonth <= 12) {
	if ($xmonth == $month) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$xmonth</option>";
	$xmonth++;
    }
    echo "</select>";
    echo ""._YEAR.": <input type=\"text\" name=\"year\" value=\"$year\" size=\"5\" maxlength=\"4\">";
    echo "<br>"._HOUR.": <select name=\"hour\">";
    $xhour = 0;
    $cero = "0";
    while ($xhour <= 23) {
	$dummy = $xhour;
	if ($xhour < 10) {
	    $xhour = "$cero$xhour";
	}
	if ($xhour == $hour) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"hour\" $sel>$xhour</option>";
	$xhour = $dummy;
	$xhour++;
    }
    echo "</select>";
    echo ": <select name=\"min\">";
    $xmin = 0;
    while ($xmin <= 59) {
	if (($xmin == 0) OR ($xmin == 5)) {
	    $xmin = "0$xmin";
	}
	if ($xmin == $min) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"min\" $sel>$xmin</option>";
	$xmin = $xmin + 5;
    }
    echo "</select>";
    echo ": 00<br><br>"
	."<select name=\"op\">"
	."<option value=\"PreviewAdminStory\" selected>"._PREVIEWSTORY."</option>"
	."<option value=\"PostAdminStory\">"._POSTSTORY."</option>"
	."</select>"
	."<input type=\"submit\" value=\""._OK."\">";
    CloseTable();
    echo "<br>";
    putpoll($pollTitle, $optionText);
    echo "</form>";
    include ('footer.php');
}

function postAdminStory($automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $topic, $catid, $ihome, $alanguage, $acomm, $pollTitle, $optionText, $assotop) {
    global $ultramode, $aid, $prefix, $dbi;
    for ($i=0; $i<sizeof($assotop); $i++) {
	$associated .= "$assotop[$i]-";
    }
    if ($automated == 1) {
	if ($day < 10) {
	    $day = "0$day";
	}
	if ($month < 10) {
	    $month = "0$month";
	}
	$sec = "00";
	$date = "$year-$month-$day $hour:$min:$sec";
	$notes = "";
	$author = $aid;
	$subject = stripslashes(FixQuotes($subject));
	$subject = ereg_replace("\"", "''", $subject);
	$hometext = stripslashes(FixQuotes($hometext));
	$bodytext = stripslashes(FixQuotes($bodytext));
	$result = sql_query("insert into ".$prefix."_autonews values (NULL, '$catid', '$aid', '$subject', '$date', '$hometext', '$bodytext', '$topic', '$author', '$notes', '$ihome', '$alanguage', '$acomm', '$associated')", $dbi);
	if (!$result) {
	    exit();
	}
	$result = sql_query("update ".$prefix."_authors set counter=counter+1 where aid='$aid'", $dbi);
	if ($ultramode) {
	    ultramode();
	}
	Header("Location: admin.php?op=adminMain");
    } else {
	$subject = stripslashes(FixQuotes($subject));
	$hometext = stripslashes(FixQuotes($hometext));
	$bodytext = stripslashes(FixQuotes($bodytext));
	if (($pollTitle != "") AND ($optionText[1] != "") AND ($optionText[2] != "")) {
	    $haspoll = 1;
	    $timeStamp = time();
	    $pollTitle = FixQuotes($pollTitle);
	    if(!sql_query("INSERT INTO ".$prefix."_poll_desc VALUES (NULL, '$pollTitle', '$timeStamp', 0, '$alanguage', '0')", $dbi)) {
		return;
	    }
	    $object = sql_fetch_object(sql_query("SELECT pollID FROM ".$prefix."_poll_desc WHERE pollTitle='$pollTitle'", $dbi), $dbi);
	    $id = $object->pollID;
	    for($i = 1; $i <= sizeof($optionText); $i++) {
		if($optionText[$i] != "") {
		    $optionText[$i] = FixQuotes($optionText[$i]);
		}
		if(!sql_query("INSERT INTO ".$prefix."_poll_data (pollID, optionText, optionCount, voteID) VALUES ($id, '$optionText[$i]', 0, $i)", $dbi)) {
		    return;
		}
	    }
	} else {
	    $haspoll = 0;
	    $id = 0;
	}
	$result = sql_query("insert into ".$prefix."_stories values (NULL, '$catid', '$aid', '$subject', now(), '$hometext', '$bodytext', '0', '0', '$topic', '$aid', '$notes', '$ihome', '$alanguage', '$acomm', '$haspoll', '$id', '0', '0', '$associated')", $dbi);
	$result = sql_query("select sid from ".$prefix."_stories WHERE title='$subject' order by time DESC limit 0,1", $dbi);
	list($artid) = sql_fetch_row($result, $dbi);
	sql_query("UPDATE ".$prefix."_poll_desc SET artid='$artid' WHERE pollID='$id'", $dbi);
	if (!$result) {
	    exit();
	}
	$result = sql_query("update ".$prefix."_authors set counter=counter+1 where aid='$aid'", $dbi);
	if ($ultramode) {
	    ultramode();
	}
	Header("Location: admin.php?op=adminMain");
    }
}

function submissions() {
    global $admin, $bgcolor1, $bgcolor2, $prefix, $dbi, $radminsuper;
    $dummy = 0;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._SUBMISSIONSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
	$result = sql_query("SELECT qid, subject, timestamp, alanguage FROM ".$prefix."_queue order by timestamp DESC", $dbi);
	if(sql_num_rows($result, $dbi) == 0) {
	    echo "<table width=\"100%\"><tr><td bgcolor=\"$bgcolor1\" align=\"center\"><b>"._NOSUBMISSIONS."</b></td></tr></table>\n";
	} else {
	    echo "<center><font class=\"content\"><b>"._NEWSUBMISSIONS."</b></font><form action=\"admin.php\" method=\"post\"><table width=\"100%\" border=\"1\" bgcolor=\"$bgcolor2\">\n";
	    while (list($qid, $subject, $timestamp, $alanguage) = sql_fetch_row($result, $dbi)) {
		$hour = "AM";
		ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $timestamp, $datetime);
		if ($datetime[4] > 12) { $datetime[4] = $datetime[4]-12; $hour = "PM"; }
		$datetime = date(""._DATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
		echo "<tr>\n"
		    ."<td align=\"center\"><font class=\"content\">&nbsp;(<a href=\"admin.php?op=DeleteStory&amp;qid=$qid\">"._DELETE."</a>)&nbsp;</td>\n"
	    	    ."<td width=\"100%\"><font class=\"content\">\n";
		if ($subject == "") {
		    echo "&nbsp;<a href=\"admin.php?op=DisplayStory&amp;qid=$qid\">"._NOSUBJECT."</a></font>\n";
		} else {
		    echo "&nbsp;<a href=\"admin.php?op=DisplayStory&amp;qid=$qid\">$subject</a></font>\n";
		}
		if ($alanguage == "") {
		    $alanguage = _ALL;
		}
		echo "</td><td align=\"center\"><font size=\"2\">$alanguage</font>\n";
		$timestamp = ereg_replace(" ", "@", $timestamp);
		echo "</td><td align=\"right\"><font class=\"content\">&nbsp;$timestamp&nbsp;</font></td></tr>\n";
		$dummy++;
	    }
	    if ($dummy < 1) {
		echo "<tr><td bgcolor=\"$bgcolor1\" align=\"center\"><b>"._NOSUBMISSIONS."</b></form></td></tr></table>\n";
	    } else {
		echo "</table></form>\n";
	    }
	}
    if ($radminsuper==1) {
	echo "<br><center>"
	    ."[ <a href=\"admin.php?op=subdelete\">"._DELETE."</a> ]"
	    ."</center><br>";
    }
    CloseTable();
    include ("footer.php");
}

function subdelete() {
    global $prefix, $dbi;
    sql_query("delete from ".$prefix."_queue", $dbi);
    Header("Location: admin.php?op=adminMain");
}

switch($op) {

    case "EditCategory":
    EditCategory($catid);
    break;

    case "subdelete":
    subdelete();
    break;

    case "DelCategory":
    DelCategory($cat);
    break;

    case "YesDelCategory":
    YesDelCategory($catid);
    break;

    case "NoMoveCategory":
    NoMoveCategory($catid, $newcat);
    break;

    case "SaveEditCategory":
    SaveEditCategory($catid, $title);
    break;

    case "SelectCategory":
    SelectCategory($cat);
    break;

    case "AddCategory":
    AddCategory();
    break;

    case "SaveCategory":
    SaveCategory($title);
    break;

    case "DisplayStory":
    displayStory($qid);
    break;

    case "PreviewAgain":
    previewStory($automated, $year, $day, $month, $hour, $min, $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $alanguage, $acomm, $pollTitle, $optionText, $assotop);
    break;

    case "PostStory":
    postStory($automated, $year, $day, $month, $hour, $min, $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $alanguage, $acomm, $pollTitle, $optionText, $assotop);
    break;

    case "EditStory":
    editStory($sid);
    break;

    case "RemoveStory":
    removeStory($sid, $ok);
    break;

    case "ChangeStory":
    changeStory($sid, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $alanguage, $acomm, $assotop);
    break;

    case "DeleteStory":
    deleteStory($qid);
    break;

    case "adminStory":
    adminStory($sid);
    break;

    case "PreviewAdminStory":
    previewAdminStory($automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $topic, $catid, $ihome, $alanguage, $acomm, $pollTitle, $optionText, $assotop);
    break;

    case "PostAdminStory":
    postAdminStory($automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $topic, $catid, $ihome, $alanguage, $acomm, $pollTitle, $optionText, $assotop);
    break;

    case "autoDelete":
    autodelete($anid);
    break;

    case "autoEdit":
    autoEdit($anid);
    break;

    case "autoSaveEdit":
    autoSaveEdit($anid, $year, $day, $month, $hour, $min, $title, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $alanguage, $acomm);
    break;

    case "submissions":
    submissions();
    break;

}

} else {
    echo "Access Denied";
}

?>

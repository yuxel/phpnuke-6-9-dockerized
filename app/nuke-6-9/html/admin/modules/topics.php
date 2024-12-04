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

$result = sql_query("select radmintopic, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radmintopic, $radminsuper) = sql_fetch_row($result, $dbi);
if (($radmintopic==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Topics Manager Functions                              */
/*********************************************************/

function topicsmanager() {
    global $prefix, $dbi;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._TOPICSMANAGER."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._CURRENTTOPICS."</b></font><br>"._CLICK2EDIT."</font></center><br>"
	."<table border=\"0\" width=\"100%\" align=\"center\" cellpadding=\"2\">";
    $count = 0;
    $result = sql_query("select topicid, topicname, topicimage, topictext from ".$prefix."_topics order by topicname", $dbi);
    while(list($topicid, $topicname, $topicimage, $topictext) = sql_fetch_array($result, $dbi)) {
	echo "<td align=\"center\">"
	    ."<a href=\"admin.php?op=topicedit&amp;topicid=$topicid\"><img src=\"images/topics/$topicimage\" border=\"0\" alt=\"\"></a><br>"
	    ."<font class=\"content\"><b>$topictext</td>";
	$count++;
	if ($count == 5) {
	    echo "</tr><tr>";
	    $count = 0;
	}
    }
    echo "</table>";
    CloseTable();
    echo "<br><a name=\"Add\">";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._ADDATOPIC."</b></font></center><br>"
    	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._TOPICNAME.":</b><br><font class=\"tiny\">"._TOPICNAME1."<br>"
	.""._TOPICNAME2."</font><br>"
	."<input type=\"text\" name=\"topicname\" size=\"20\" maxlength=\"20\" value=\"$topicname\"><br><br>"
	."<b>"._TOPICTEXT.":</b><br><font class=\"tiny\">"._TOPICTEXT1."<br>"
	.""._TOPICTEXT2."</font><br>"
	."<input type=\"text\" name=\"topictext\" size=\"40\" maxlength=\"40\" value=\"$topictext\"><br><br>"
	."<b>"._TOPICIMAGE.":</b><br>"
	."<select name=\"topicimage\">";
    $path1 = explode ("/", "images/topics/");
    $path = "$path1[0]/$path1[1]";
    $handle=opendir($path);
    while ($file = readdir($handle)) {
	if ( (ereg("^([_0-9a-zA-Z]+)([.]{1})([_0-9a-zA-Z]{3})$",$file)) AND $file != "AllTopics.gif") {
	    $tlist .= "$file ";
	}
    }
    closedir($handle);
    $tlist = explode(" ", $tlist);
    sort($tlist);
    for ($i=0; $i < sizeof($tlist); $i++) {
	if($tlist[$i]!="") {
	    echo "<option name=\"topicimage\" value=\"$tlist[$i]\">$tlist[$i]\n";
	}
    }
    echo "</select><br><br>"
	."<input type=\"hidden\" name=\"op\" value=\"topicmake\">"
	."<input type=\"submit\" value=\""._ADDTOPIC."\">"
	."</form>";
    CloseTable();
    include("footer.php");
}

function topicedit($topicid) {
    global $prefix, $dbi;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._TOPICSMANAGER."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $result = sql_query("select topicid, topicname, topicimage, topictext from ".$prefix."_topics where topicid=$topicid", $dbi);
    list($topicid, $topicname, $topicimage, $topictext) = sql_fetch_array($result, $dbi);
    echo "<img src=\"images/topics/$topicimage\" border=\"0\" align=\"right\" alt=\"$topictext\">"
	."<font class=\"option\"><b>"._EDITTOPIC.": $topictext</b></font>"
	."<br><br>"
	."<form action=\"admin.php\" method=\"post\"><br>"
	."<b>"._TOPICNAME.":</b><br><font class=\"tiny\">"._TOPICNAME1."<br>"
	.""._TOPICNAME2."</font><br>"
	."<input type=\"text\" name=\"topicname\" size=\"20\" maxlength=\"20\" value=\"$topicname\"><br><br>"
	."<b>"._TOPICTEXT.":</b><br><font class=\"tiny\">"._TOPICTEXT1."<br>"
	.""._TOPICTEXT2."</font><br>"
	."<input type=\"text\" name=\"topictext\" size=\"40\" maxlength=\"40\" value=\"$topictext\"><br><br>"
	."<b>"._TOPICIMAGE.":</b><br>"
	."<select name=\"topicimage\">";
    $path1 = explode ("/", "images/topics/");
    $path = "$path1[0]/$path1[1]";
    $handle=opendir($path);
    while ($file = readdir($handle)) {
	if ( (ereg("^([_0-9a-zA-Z]+)([.]{1})([_0-9a-zA-Z]{3})$",$file)) AND $file != "AllTopics.gif") {
	    $tlist .= "$file ";
	}
    }
    closedir($handle);
    $tlist = explode(" ", $tlist);
    sort($tlist);
    for ($i=0; $i < sizeof($tlist); $i++) {
	if($tlist[$i]!="") {
	    if ($topicimage == $tlist[$i]) {
		$sel = "selected";
	    } else {
		$sel = "";
	    }
	    echo "<option name=\"topicimage\" value=\"$tlist[$i]\" $sel>$tlist[$i]\n";
	}
    }
    echo "</select><br><br>"
	."<b>"._ADDRELATED.":</b><br>"
	.""._SITENAME.": <input type=\"text\" name=\"name\" size=\"30\" maxlength=\"30\"><br>"
	.""._URL.": <input type=\"text\" name=\"url\" value=\"http://\" size=\"50\" maxlength=\"200\"><br><br>"
	."<b>"._ACTIVERELATEDLINKS.":</b><br>"
	."<table width=\"100%\" border=\"0\">";
    $res=sql_query("select rid, name, url from ".$prefix."_related where tid=$topicid", $dbi);
    $num = sql_num_rows($res, $dbi);
    if ($num == 0) {
	echo "<tr><td><font class=\"tiny\">"._NORELATED."</font></td></tr>";
    }
    while(list($rid, $name, $url) = sql_fetch_row($res, $dbi)) {
        echo "<tr><td align=\"left\"><font class=\"content\"><strong><big>&middot;</big></strong>&nbsp;&nbsp;<a href=\"$url\">$name</a></td>"
    	    ."<td align=\"center\"><font class=\"content\"><a href=\"$url\">$url</a></td><td align=\"right\"><font class=\"content\">[ <a href=\"admin.php?op=relatededit&amp;tid=$topicid&amp;rid=$rid\">"._EDIT."</a> | <a href=\"admin.php?op=relateddelete&amp;tid=$topicid&amp;rid=$rid\">"._DELETE."</a> ]</td></tr>";
    }
    echo "</table><br><br>"
	."<input type=\"hidden\" name=\"topicid\" value=\"$topicid\">"
	."<input type=\"hidden\" name=\"op\" value=\"topicchange\">"
        ."<INPUT type=\"submit\" value=\""._SAVECHANGES."\"> <font class=\"content\">[ <a href=\"admin.php?op=topicdelete&amp;topicid=$topicid\">"._DELETE."</a> ]</font>"
	."</form>";
    CloseTable();
    include("footer.php");
}

function relatededit($tid, $rid) {
    global $prefix, $dbi;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._TOPICSMANAGER."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result=sql_query("select name, url from ".$prefix."_related where rid=$rid", $dbi);
    list($name, $url) = sql_fetch_row($result, $dbi);
    $result2=sql_query("select topictext, topicimage from ".$prefix."_topics where topicid=$tid", $dbi);
    list($topictext, $topicimage) = sql_fetch_row($result2, $dbi);
    OpenTable();    
    echo "<center>"
	."<img src=\"images/topics/$topicimage\" border=\"0\" alt=\"$topictext\" align=\"right\">"
	."<font class=\"option\"><b>"._EDITRELATED."</b></font><br>"
	."<b>"._TOPIC.":</b> $topictext</center>"
	."<form action=\"admin.php\" method=\"post\">"
	.""._SITENAME.": <input type=\"text\" name=\"name\" value=\"$name\" size=\"30\" maxlength=\"30\"><br><br>"
	.""._URL.": <input type=\"text\" name=\"url\" value=\"$url\" size=\"60\" maxlength=\"200\"><br><br>"
	."<input type=\"hidden\" name=\"op\" value=\"relatedsave\">"
	."<input type=\"hidden\" name=\"tid\" value=\"$tid\">"
        ."<input type=\"hidden\" name=\"rid\" value=\"$rid\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\"> "._GOBACK.""
	."</form>";
    CloseTable();
    include("footer.php");
}

function relatedsave($tid, $rid, $name, $url) {
    global $prefix, $dbi;
    sql_query("update ".$prefix."_related set name='$name', url='$url' where rid=$rid", $dbi);
    Header("Location: admin.php?op=topicedit&topicid=$tid");
}

function relateddelete($tid, $rid) {
    global $prefix, $dbi;
    sql_query("delete from ".$prefix."_related where rid='$rid'", $dbi);
    Header("Location: admin.php?op=topicedit&topicid=$tid");
}

function topicmake($topicname, $topicimage, $topictext) {
    global $prefix, $dbi;
    $topicname = stripslashes(FixQuotes($topicname));
    $topicimage = stripslashes(FixQuotes($topicimage));
    $topictext = stripslashes(FixQuotes($topictext));
    sql_query("INSERT INTO ".$prefix."_topics VALUES (NULL,'$topicname','$topicimage','$topictext','0')", $dbi);
    Header("Location: admin.php?op=topicsmanager#Add");
}

function topicchange($topicid, $topicname, $topicimage, $topictext, $name, $url) {
    global $prefix, $dbi;
    $topicname = stripslashes(FixQuotes($topicname));
    $topicimage = stripslashes(FixQuotes($topicimage));
    $topictext = stripslashes(FixQuotes($topictext));
    $name = stripslashes(FixQuotes($name));
    $url = stripslashes(FixQuotes($url));
    sql_query("update ".$prefix."_topics set topicname='$topicname', topicimage='$topicimage', topictext='$topictext' where topicid=$topicid", $dbi);
    if (!$name) {
    } else {
        sql_query("insert into ".$prefix."_related VALUES (NULL, '$topicid','$name','$url')", $dbi);
    }
    Header("Location: admin.php?op=topicedit&topicid=$topicid");
}

function topicdelete($topicid, $ok=0) {
    global $prefix, $dbi;
    if ($ok==1) {
	$result=sql_query("select sid from ".$prefix."_stories where topic='$topicid'", $dbi);
	list($sid) = sql_fetch_row($result, $dbi);
	sql_query("delete from ".$prefix."_stories where topic='$topicid'", $dbi);
	sql_query("delete from ".$prefix."_topics where topicid='$topicid'", $dbi);
	sql_query("delete from ".$prefix."_related where tid='$topicid'", $dbi);
	$result = sql_query("select sid from ".$prefix."_comments where sid='$sid'", $dbi);
	list($sid) = sql_fetch_row($result, $dbi);
	sql_query("delete from ".$prefix."_comments where sid='$sid'", $dbi);
	Header("Location: admin.php?op=topicsmanager");
    } else {
	global $topicimage;
	include("header.php");
	GraphicAdmin();
        OpenTable();
	echo "<center><font class=\"title\"><b>"._TOPICSMANAGER."</b></font></center>";
	CloseTable();
	echo "<br>";
	$result2=sql_query("select topicimage, topictext from ".$prefix."_topics where topicid='$topicid'", $dbi);
	list($topicimage, $topictext) = sql_fetch_row($result2, $dbi);
	OpenTable();
	echo "<center><img src=\"images/topics/$topicimage\" border=\"0\" alt=\"$topictext\"><br><br>"
	    ."<b>"._DELETETOPIC." $topictext</b><br><br>"
	    .""._TOPICDELSURE." <i>$topictext</i>?<br>"
	    .""._TOPICDELSURE1."<br><br>"
	    ."[ <a href=\"admin.php?op=topicsmanager\">"._NO."</a> | <a href=\"admin.php?op=topicdelete&amp;topicid=$topicid&amp;ok=1\">"._YES."</a> ]</center><br><br>";
	CloseTable();
	include("footer.php");
    }
}

switch ($op) {

    case "topicsmanager":
    topicsmanager();
    break;

    case "topicedit":
    topicedit($topicid);
    break;

    case "topicmake":
    topicmake($topicname, $topicimage, $topictext);
    break;

    case "topicdelete":
    topicdelete($topicid, $ok);
    break;

    case "topicchange":
    topicchange($topicid, $topicname, $topicimage, $topictext, $name, $url);
    break;

    case "relatedsave":
    relatedsave($tid, $rid, $name, $url);
    break;
	
    case "relatededit":
    relatededit($tid, $rid);
    break;
			
    case "relateddelete":
    relateddelete($tid, $rid);
    break;

}

} else {
    echo "Access Denied";
}

?>
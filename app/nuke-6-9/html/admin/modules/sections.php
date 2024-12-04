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

$result = sql_query("select radminsection, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radminsection, $radminsuper) = sql_fetch_row($result, $dbi);
if (($radminsection==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Sections Manager Functions                            */
/*********************************************************/

function sections() {
    global $prefix, $dbi, $language, $multilingual;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._SECTIONSADMIN."</b></font></center>";
    CloseTable();
    $result = sql_query("select secid, secname from ".$prefix."_sections order by secid", $dbi);
    if (sql_num_rows($result, $dbi) > 0) {
	echo "<br>";
	OpenTable();
	echo "<center><b>"._ACTIVESECTIONS."</b><br><font class=\"content\">"._CLICK2EDITSEC."</font></center><br>"
	    ."<table border=0 width=100% align=center cellpadding=1 align=\"center\"><tr><td align=center>";
	while(list($secid, $secname) = sql_fetch_array($result, $dbi)) {
	    echo "<strong><big>&middot;</big></strong>&nbsp;&nbsp;<a href=\"admin.php?op=sectionedit&amp;secid=$secid\">$secname</a>";
	}
	echo "</td></tr></table>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><font class=\"option\"><b>"._ADDSECARTICLE."</b></font></center><br>"
	    ."<form action=\"admin.php\" method=\"post\">"
	    ."<b>"._TITLE."</b><br>"
	    ."<input type=\"text\" name=\"title\" size=\"60\"><br><br>"
	    ."<b>"._SELSECTION.":</b><br>";
	$result = sql_query("select secid, secname from ".$prefix."_sections order by secid", $dbi);
	while(list($secid, $secname) = sql_fetch_array($result, $dbi)) {
	    echo "<input type=\"radio\" name=\"secid\" value=\"$secid\"> $secname<br>";
	}
	echo "<font class=\"content\">"._DONTSELECT."</font><br>";
	if ($multilingual == 1) {
	    echo "<br><b>"._LANGUAGE.": </b>"
		."<select name=\"slanguage\">";
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
	    echo "</select>";
	} else {
	    echo "<input type=\"hidden\" name=\"slanguage\" value=\"$language\">";
	}
	echo "<br><br><b>"._CONTENT."</b><br>"
	    ."<textarea name=\"content\" cols=\"60\" rows=\"10\"></textarea><br>"
	    ."<font class=\"content\">"._PAGEBREAK."</font><br><br>"
	    ."<input type=\"hidden\" name=\"op\" value=\"secarticleadd\">"
	    ."<input type=\"submit\" value=\""._ADDARTICLE."\">"
	    ."</form>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><font class=\"option\"><b>"._LAST." 20 "._ARTICLES."</b></font></center><br>"
	    ."<ul>";
	$result = sql_query("select artid, secid, title, content, slanguage from ".$prefix."_seccont order by artid desc limit 0,20", $dbi);
	while(list($artid, $secid, $title, $content, $slanguage) = sql_fetch_array($result, $dbi)) {
	    $result2 = sql_query("select secid, secname from ".$prefix."_sections where secid='$secid'", $dbi);
	    list($secid, $secname) = sql_fetch_row($result2, $dbi);
	    echo "<li>$title - ($slanguage) - ($secname) [ <a href=\"admin.php?op=secartedit&amp;artid=$artid\">"._EDIT."</a> | <a href=\"admin.php?op=secartdelete&amp;artid=$artid&amp;ok=0\">"._DELETE."</a> ]";
	}
	echo "</ul>"
	    ."<form action=\"admin.php\" method=\"post\">"
	    .""._EDITARTID.": <input type=\"text\" name=\"artid\" size=\"10\">&nbsp;&nbsp;"
	    ."<input type=\"hidden\" name=\"op\" value=\"secartedit\">"
	    ."<input type=\"submit\" value=\""._OK."\">"
	    ."</form>";
	CloseTable();
    }
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._ADDSECTION."</b></font></center><br>"
	."<form action=\"admin.php\" method=\"post\"><br>"
	."<b>"._SECTIONNAME.":</b><br>"
	."<input type=\"text\" name=\"secname\" size=\"40\" maxlength=\"40\"><br><br>"
	."<b>"._SECTIONIMG."</b><br><font class=\"tiny\">"._SECIMGEXAMPLE."</font><br>"
	."<input type=\"text\" name=\"image\" size=\"40\" maxlength=\"50\"><br><br>"
	."<input type=\"hidden\" name=\"op\" value=\"sectionmake\">"
	."<INPUT type=\"submit\" value=\""._ADDSECTIONBUT."\">"
	."</form>";
    CloseTable();
    include("footer.php");
}

function secarticleadd($secid, $title, $content, $slanguage) {
    global $prefix, $dbi;
    $title = stripslashes(FixQuotes($title));
    $content = stripslashes(FixQuotes($content));
    sql_query("INSERT INTO ".$prefix."_seccont VALUES (NULL,'$secid','$title','$content','0','$slanguage')", $dbi);
    Header("Location: admin.php?op=sections");
}

function secartedit($artid) {
    global $prefix, $dbi, $multilingual;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._SECTIONSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = sql_query("select artid, secid, title, content, slanguage from ".$prefix."_seccont where artid='$artid'", $dbi);
    list($artid, $secid, $title, $content, $slanguage) = sql_fetch_array($result, $dbi);
    OpenTable();
    echo "<center><font class=\"option\"><b>"._EDITARTICLE."</b></font></center><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._TITLE."</b><br>"
	."<input type=\"text\" name=\"title\" size=\"60\" value=\"$title\"><br><br>"
	."<b>"._SELSECTION.":</b><br>";
    $result2 = sql_query("select secid, secname from ".$prefix."_sections order by secname", $dbi);
    while(list($secid2, $secname) = sql_fetch_array($result2, $dbi)) {
	    if ($secid2==$secid) {
		$che = "checked";
	    }
	    echo "<input type=\"radio\" name=\"secid\" value=\"$secid2\" $che>$secname<br>";
	    $che = "";
    }
    if ($multilingual == 1) {
	echo "<br><b>"._LANGUAGE.": </b>"
	    ."<select name=\"slanguage\">";
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
		if($languageslist[$i]==$slanguage) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	echo "</select><br>";
    } else {
	echo "<input type=\"hidden\" name=\"slanguage\" value=\"$language\">";
    }
    echo "<br><b>"._CONTENT."</b><br>"
	."<textarea name=\"content\" cols=\"60\" rows=\"10\">$content</textarea><br><br>"
	."<input type=\"hidden\" name=\"artid\" value=\"$artid\">"
	."<input type=\"hidden\" name=\"op\" value=\"secartchange\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\"> [ <a href=\"admin.php?op=secartdelete&amp;artid=$artid&amp;ok=0\">"._DELETE."</a> ]"
	."</form>";
    CloseTable();
    include("footer.php");
}

function sectionmake($secname, $image) {
    global $prefix, $dbi;
    $secname = stripslashes(FixQuotes($secname));
    $image = stripslashes(FixQuotes($image));
    sql_query("INSERT INTO ".$prefix."_sections VALUES (NULL,'$secname', '$image')", $dbi);
    Header("Location: admin.php?op=sections");
}

function sectionedit($secid) {
    global $prefix, $dbi;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._SECTIONSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = sql_query("select secid, secname, image from ".$prefix."_sections where secid=$secid", $dbi);
    list($secid, $secname, $image) = sql_fetch_array($result, $dbi);
    $result2 = sql_query("select artid from ".$prefix."_seccont where secid=$secid", $dbi);
    $number = sql_num_rows($result2, $dbi);
    OpenTable();
    echo "<img src=\"images/sections/$image\" border=\"0\" alt=\"\"><br><br>"
	."<font class=\"option\"><b>"._EDITSECTION.": $secname</b></font>"
	."<br>("._SECTIONHAS." $number "._ARTICLESATTACH.")"
	."<br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._SECTIONNAME."</b><br><font class=\"tiny\">"._40CHARSMAX."</font><br>"
	."<input type=\"text\" name=\"secname\" size=\"40\" maxlength=\"40\" value=\"$secname\"><br><br>"
	."<b>"._SECTIONIMG."</b><br><font class=\"tiny\">"._SECIMGEXAMPLE."</font><br>"
	."<input type=\"text\" name=\"image\" size=\"40\" maxlength=\"50\" value=\"$image\"><br><br>"
	."<input type=\"hidden\" name=\"secid\" value=\"$secid\">"
	."<input type=\"hidden\" name=\"op\" value=\"sectionchange\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\"> [ <a href=\"admin.php?op=sectiondelete&amp;secid=$secid&amp;ok=0\">"._DELETE."</a> ]"
	."</form>";
    CloseTable();
    include("footer.php");
}

function sectionchange($secid, $secname, $image) {
    global $prefix, $dbi;
    $secname = stripslashes(FixQuotes($secname));
    $image = stripslashes(FixQuotes($image));
    sql_query("update ".$prefix."_sections set secname='$secname', image='$image' where secid=$secid", $dbi);
    Header("Location: admin.php?op=sections");
}

function secartchange($artid, $secid, $title, $content, $slanguage) {
    global $prefix, $dbi;
    $title = stripslashes(FixQuotes($title));
    $content = stripslashes(FixQuotes($content));
    sql_query("update ".$prefix."_seccont set secid='$secid', title='$title', content='$content', slanguage='$slanguage' where artid=$artid", $dbi);
    Header("Location: admin.php?op=sections");
}

function sectiondelete($secid, $ok=0) {
    global $prefix, $dbi;
    if ($ok==1) {
        sql_query("delete from ".$prefix."_seccont where secid='$secid'", $dbi);
        sql_query("delete from ".$prefix."_sections where secid='$secid'", $dbi);
        Header("Location: admin.php?op=sections");
    } else {
        include("header.php");
        GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._SECTIONSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	$result=sql_query("select secname from ".$prefix."_sections where secid=$secid", $dbi);
	list($secname) = sql_fetch_row($result, $dbi);
	OpenTable();
	echo "<center><b>"._DELSECTION.": $secname</b><br><br>"
	    .""._DELSECWARNING." $secname?<br>"
	    .""._DELSECWARNING1."<br><br>"
	    ."[ <a href=\"admin.php?op=sections\">"._NO."</a> | <a href=\"admin.php?op=sectiondelete&amp;secid=$secid&amp;ok=1\">"._YES."</a> ]</center>";
	CloseTable();
        include("footer.php");
    }
}

function secartdelete($artid, $ok=0) {
    global $prefix, $dbi;
    if ($ok==1) {
        sql_query("delete from ".$prefix."_seccont where artid='$artid'", $dbi);
        Header("Location: admin.php?op=sections");
    } else {
        include("header.php");
        GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._SECTIONSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	$result = sql_query("select title from ".$prefix."_seccont where artid=$artid", $dbi);
	list($title) = sql_fetch_row($result, $dbi);
	OpenTable();
	echo "<center><b>"._DELARTICLE.": $title</b><br><br>"
	    .""._DELARTWARNING."<br><br>"
	    ."[ <a href=\"admin.php?op=sections\">"._NO."</a> | <a href=\"admin.php?op=secartdelete&amp;artid=$artid&amp;ok=1\">"._YES."</a> ]</center>";
	CloseTable();
        include("footer.php");
    }
}

switch ($op) {

    case "sections":
    sections();
    break;

    case "sectionedit":
    sectionedit($secid);
    break;

    case "sectionmake":
    sectionmake($secname, $image);
    break;

    case "sectiondelete":
    sectiondelete($secid, $ok);
    break;

    case "sectionchange":
    sectionchange($secid, $secname, $image);
    break;

    case "secarticleadd":
    secarticleadd($secid, $title, $content, $slanguage);
    break;

    case "secartedit":
    secartedit($artid);
    break;

    case "secartchange":
    secartchange($artid, $secid, $title, $content, $slanguage);
    break;

    case "secartdelete":
    secartdelete($artid, $ok);
    break;

}

} else {
    echo "Access Denied";
}

?>
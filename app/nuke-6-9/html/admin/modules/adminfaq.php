<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* ========================                                             */
/* Based on PHP-Nuke Add-On                                             */
/* Copyright (c) 2001 by Richard Tirtadji AKA King Richard              */
/*                       (rtirtadji@hotmail.com)                        */
/*                       Hutdik Hermawan AKA hotFix                     */
/*                       (hutdik76@hotmail.com)                         */
/* http://www.nukeaddon.com                                             */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!eregi("admin.php", $_SERVER['PHP_SELF'])) { die ("Access Denied"); }
$result = sql_query("select radminfaq, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radminfaq, $radminsuper) = sql_fetch_row($result, $dbi);
if (($radminfaq==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Faq Admin Function                                    */
/*********************************************************/

function FaqAdmin() {
    global $admin, $bgcolor2, $prefix, $dbi, $currentlang, $multilingual;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._FAQADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._ACTIVEFAQS."</b></font></center><br>"
	."<table border=\"1\" width=\"100%\" align=\"center\"><tr>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._ID."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._CATEGORIES."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._LANGUAGE."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._FUNCTIONS."</b></td></tr><tr>";
    $result = sql_query("select id_cat, categories, flanguage from ".$prefix."_faqCategories order by id_cat", $dbi);
    while(list($id_cat, $categories, $flanguage) = sql_fetch_row($result, $dbi)) {
	if ($flanguage == "") {
	    $flanguage = _ALL;
	}
	echo "<td align=\"center\">$id_cat</td>"
	    ."<td align=\"center\">$categories</td>"
	    ."<td align=\"center\">$flanguage</td>"
	    ."<td align=\"center\">[ <a href=\"admin.php?op=FaqCatGo&amp;id_cat=$id_cat\">"._CONTENT."</a> | <a href=\"admin.php?op=FaqCatEdit&amp;id_cat=$id_cat\">"._EDIT."</a> | <a href=\"admin.php?op=FaqCatDel&amp;id_cat=$id_cat&amp;ok=0\">"._DELETE."</a> ]</td><tr>";
    }
    echo "</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._ADDCATEGORY."</b></font></center><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<table border=\"0\" width=\"100%\"><tr><td>"
	.""._CATEGORIES.":</td><td><input type=\"text\" name=\"categories\" size=\"30\"></td>";
    if ($multilingual == 1) {
	echo "<tr><td>"._LANGUAGE.":</td><td>"
	    ."<select name=\"flanguage\">";
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
		if($languageslist[$i]==$currentlang) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	echo "</select></td>";
    } else {
	echo "<input type=\"hidden\" name=\"flanguage\" value=\"$language\">";
    }
	echo "</tr></table>"
	."<input type=\"hidden\" name=\"op\" value=\"FaqCatAdd\">"
	."<input type=\"submit\" value="._SAVE.">"
	."</form>";
    CloseTable();
    include("footer.php");
}

function FaqCatGo($id_cat) {
    global $admin, $bgcolor2, $prefix, $dbi;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._FAQADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._QUESTIONS."</b></font></center><br>"
	."<table border=1 width=100% align=\"center\"><tr>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\">"._CONTENT."</td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\">"._FUNCTIONS."</td></tr>";
    $result = sql_query("select id, question, answer from ".$prefix."_faqAnswer where id_cat='$id_cat' order by id", $dbi);
    while(list($id, $question, $answer) = sql_fetch_row($result, $dbi)) {
	echo "<tr><td><i>$question</i><br><br>$answer"
	    ."</td><td align=\"center\">[ <a href=\"admin.php?op=FaqCatGoEdit&amp;id=$id\">"._EDIT."</a> | <a href=\"admin.php?op=FaqCatGoDel&amp;id=$id&amp;ok=0\">"._DELETE."</a> ]</td></tr>"
	    ."</td></tr>";
    }
    echo "</table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._ADDQUESTION."</b></center><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<table border=\"0\" width=\"100%\"><tr><td>"
	.""._QUESTION.":</td><td><input type=\"text\" name=\"question\" size=\"40\"></td></tr><tr><td>"
	.""._ANSWER." </td><td><textarea name=\"answer\" cols=\"60\" rows=\"10\"></textarea>"
	."</td></tr></table>"
	."<input type=\"hidden\" name=\"id_cat\" value=\"$id_cat\">"
	."<input type=\"hidden\" name=\"op\" value=\"FaqCatGoAdd\">"
	."<input type=\"submit\" value="._SAVE."> "._GOBACK.""
	."</form>";
    CloseTable();
    include("footer.php");
}

function FaqCatEdit($id_cat) {
    global $admin, $dbi, $multilingual;
    include ("config.php");
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._FAQADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = sql_query("select categories, flanguage from ".$prefix."_faqCategories where id_cat='$id_cat'", $dbi);
    list($categories,$flanguage) = sql_fetch_row($result, $dbi);
    OpenTable();
    echo "<center><font class=\"option\"><b>"._EDITCATEGORY."</b></font></center>"
	."<form action=\"admin.php\" method=\"post\">"
	."<input type=\"hidden\" name=\"id_cat\" value=\"$id_cat\">"
	."<table border=\"0\" width=\"100%\"><tr><td>"
	.""._CATEGORIES.":</td><td><input type=\"text\" name=\"categories\" size=\"31\" value='$categories'></td>";
    if ($multilingual == 1) {
	echo "<tr><td>"._LANGUAGE.":</td><td>"
	    ."<select name=\"flanguage\">";
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
		echo "<option name=\"flanguage\" value=\"$languageslist[$i]\" ";
		if($languageslist[$i]==$flanguage) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	echo "</select></td>";
    } else {
	echo "<input type=\"hidden\" name=\"flanguage\" value=\"$language\">";
    }
	echo "</tr></table>"
	."<input type=\"hidden\" name=\"op\" value=\"FaqCatSave\">"
	."<input type=\"submit\" value=\""._SAVE."\"> "._GOBACK.""
	."</form>";
    CloseTable();
    include("footer.php");
}

function FaqCatGoEdit($id) {
    global $admin, $bgcolor2, $prefix, $dbi;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._FAQADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = sql_query("select question, answer from ".$prefix."_faqAnswer where id='$id'", $dbi);
    list($question, $answer) = sql_fetch_row($result, $dbi);
    OpenTable();
    echo "<center><font class=\"option\"><b>"._EDITQUESTIONS."</b></font></center>"
	."<form action=\"admin.php\" method=\"post\">"
	."<input type=\"hidden\" name=\"id\" value=\"$id\">"
	."<table border=\"0\" width=\"100%\"><tr><td>"
	.""._QUESTION.":</td><td><input type=\"text\" name=\"question\" size=\"31\" value=\"$question\"></td></tr><tr><td>"
	.""._ANSWER.":</td><td><textarea name=\"answer\" cols=60 rows=5>$answer</textarea>"
	."</td></tr></table>"
	."<input type=\"hidden\" name=\"op\" value=\"FaqCatGoSave\">"
	."<input type=\"submit\" value="._SAVE."> "._GOBACK.""
	."</form>";
    CloseTable();
    include("footer.php");
}


function FaqCatSave($id_cat, $categories, $flanguage) {
    global $prefix, $dbi;
    $categories = stripslashes(FixQuotes($categories));
    sql_query("update ".$prefix."_faqCategories set categories='$categories', flanguage='$flanguage' where id_cat='$id_cat'", $dbi);
    Header("Location: admin.php?op=FaqAdmin");
}

function FaqCatGoSave($id, $question, $answer) {
    global $prefix, $dbi;
    $question = stripslashes(FixQuotes($question));
    $answer = stripslashes(FixQuotes($answer));
    sql_query("update ".$prefix."_faqAnswer set question='$question', answer='$answer' where id='$id'", $dbi);
    Header("Location: admin.php?op=FaqAdmin");
}

function FaqCatAdd($categories, $flanguage) {
    global $prefix, $dbi;
    $categories = stripslashes(FixQuotes($categories));
    sql_query("insert into ".$prefix."_faqCategories values (NULL, '$categories', '$flanguage')", $dbi);
    Header("Location: admin.php?op=FaqAdmin");
}

function FaqCatGoAdd($id_cat, $question, $answer) {
    global $prefix, $dbi;
    $question = stripslashes(FixQuotes($question));
    $answer = stripslashes(FixQuotes($answer));
    sql_query("insert into ".$prefix."_faqAnswer values (NULL, '$id_cat', '$question', '$answer')", $dbi);
    Header("Location: admin.php?op=FaqCatGo&id_cat=$id_cat");
}

function FaqCatDel($id_cat, $ok=0) {
    global $prefix, $dbi;
    if($ok==1) {
	sql_query("delete from ".$prefix."_faqCategories where id_cat='$id_cat'", $dbi);
	sql_query("delete from ".$prefix."_faqAnswer where id_cat='$id_cat'", $dbi);
	Header("Location: admin.php?op=FaqAdmin");
    } else {
	include("header.php");
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._FAQADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<br><center><b>"._FAQDELWARNING."</b><br><br>";
    }
	echo "[ <a href=\"admin.php?op=FaqCatDel&amp;id_cat=$id_cat&amp;ok=1\">"._YES."</a> | <a href=\"admin.php?op=FaqAdmin\">"._NO."</a> ]</center><br><br>";
	CloseTable();
	include("footer.php");
}

function FaqCatGoDel($id, $ok=0) {
    global $prefix, $dbi;
    if($ok==1) {
	sql_query("delete from ".$prefix."_faqAnswer where id='$id'", $dbi);
	Header("Location: admin.php?op=FaqAdmin");
    } else {
	include("header.php");
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._FAQADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<br><center><b>"._QUESTIONDEL."</b><br><br>";
    }
	echo "[ <a href=\"admin.php?op=FaqCatGoDel&amp;id=$id&amp;ok=1\">"._YES."</a> | <a href=\"admin.php?op=FaqAdmin\">"._NO."</a> ]</center><br><br>";
	CloseTable();
	include("footer.php");
}

switch($op) {

    case "FaqCatSave":
    FaqCatSave($id_cat, $categories, $flanguage); /* Multilingual Code : added variable */
    break;

    case "FaqCatGoSave":
    FaqCatGoSave($id, $question, $answer);
    break;

    case "FaqCatAdd":
    FaqCatAdd($categories, $flanguage); /* Multilingual Code : added variable */
    break;

    case "FaqCatGoAdd":
    FaqCatGoAdd($id_cat, $question, $answer);
    break;

    case "FaqCatEdit":
    FaqCatEdit($id_cat);
    break;

    case "FaqCatGoEdit":
    FaqCatGoEdit($id);
    break;

    case "FaqCatDel":
    FaqCatDel($id_cat, $ok);
    break;

    case "FaqCatGoDel":
    FaqCatGoDel($id, $ok);
    break;

    case "FaqAdmin":
    FaqAdmin();
    break;

    case "FaqCatGo":
    FaqCatGo($id_cat);
    break;
}

} else {
    echo "Access Denied";
}

?>
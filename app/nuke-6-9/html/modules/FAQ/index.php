<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* ======================                                               */
/* Based on Automated FAQ                                               */
/* Copyright (c) 2001 by                                                */
/*    Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)         */
/*    Hutdik Hermawan AKA hotFix (hutdik76@hotmail.com)                 */
/* http://www.phpnuke.web.id                                            */
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

$pagetitle = "- $module_name";

function ShowFaq($id_cat, $categories) {
    global $bgcolor2, $sitename, $prefix, $db, $module_name;
    OpenTable();
    echo"<center><font class=\"content\"><b>$sitename "._FAQ2."</b></font></center><br><br>"
	."<a name=\"top\"></a><br>" /* Bug fix : added missing closing hyperlink tag messes up display */
	.""._CATEGORY.": <a href=\"modules.php?name=$module_name\">"._MAIN."</a> -> $categories"
	."<br><br>"
	."<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\">"
	."<tr bgcolor=\"$bgcolor2\"><td colspan=\"2\"><font class=\"option\"><b>"._QUESTION."</b></font></td></tr><tr><td colspan=\"2\">";
    $sql = "SELECT id, id_cat, question, answer FROM ".$prefix."_faqAnswer WHERE id_cat='$id_cat'";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	$id = $row[id];
	$id_cat = $row[id_cat];
	$question = $row[question];
	$answer = $row[answer];
	echo"<strong><big>&middot;</big></strong>&nbsp;&nbsp;<a href=\"#$id\">$question</a><br>";
    }
    echo "</td></tr></table>
    <br>";
}

function ShowFaqAll($id_cat) {
    global $bgcolor2, $prefix, $db, $module_name;
    echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\">"
	."<tr bgcolor=\"$bgcolor2\"><td colspan=\"2\"><font class=\"option\"><b>"._ANSWER."</b></font></td></tr>";
    $sql = "SELECT id, id_cat, question, answer FROM ".$prefix."_faqAnswer WHERE id_cat='$id_cat'";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	$id = $row[id];
	$id_cat = $row[id_cat];
	$question = $row[question];
	$answer = $row[answer];
	echo"<tr><td><a name=\"$id\"></a>"
	    ."<strong><big>&middot;</big></strong>&nbsp;&nbsp;<b>$question</b>"
	    ."<p align=\"justify\">$answer</p>"
	    ."[ <a href=\"#top\">"._BACKTOTOP."</a> ]"
	    ."<br><br>"
	    ."</td></tr>";
    }
    echo "</table><br><br>"
	."<div align=\"center\"><b>[ <a href=\"modules.php?name=$module_name\">"._BACKTOFAQINDEX."</a> ]</b></div>";
}

if (!$myfaq) {
    global $currentlang, $multilingual;
    if ($multilingual == 1) {
    	$querylang = "WHERE flanguage='$currentlang'";
    } else {
    	$querylang = "";
    }
    include("header.php");
    OpenTable();
    echo "<center><font class=\"option\">"._FAQ2."</font></center><br><br>"
	."<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\">"
	."<tr><td bgcolor=\"$bgcolor2\"><font class=\"option\"><b>"._CATEGORIES."</b></font></td></tr><tr><td>";
    $sql = "SELECT id_cat, categories FROM ".$prefix."_faqCategories $querylang";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	$id_cat = $row[id_cat];
	$categories = $row[categories];
	$catname = urlencode($categories);
	echo"<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?name=$module_name&amp;myfaq=yes&amp;id_cat=$id_cat&amp;categories=$catname\">$categories</a><br>";
    }
    echo "</td></tr></table>";
    CloseTable();
    include("footer.php");
} else {
    include("header.php");
    ShowFaq($id_cat, $categories);
    ShowFaqAll($id_cat);
    CloseTable();
    include("footer.php");
}

?>
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
$_SERVER['PHP_SELF'] = "modules.php";
$sql = "SELECT main_module from ".$prefix."_main";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$name = $row[main_module];
$home = 1;

if ($httpref==1) {
    $referer = $_SERVER["HTTP_REFERER"];
    $referer = check_html($referer, nohtml);
    if ($referer=="" OR eregi("^unknown", $referer) OR substr("$referer",0,strlen($nukeurl))==$nukeurl OR eregi("^bookmark",$referer)) {
    } else {
	$sql = "INSERT INTO ".$prefix."_referer VALUES (NULL, '$referer')";
	$result = $db->sql_query($sql);
    }
    $sql = "SELECT * FROM ".$prefix."_referer";
    $result = $db->sql_query($sql);
    $numrows = $db->sql_numrows($result);
    if($numrows>=$httprefmax) {
	$sql = "DELETE FROM ".$prefix."_referer";
	$result = $db->sql_query($sql);
    }
}
if (!isset($mop)) { $mop="modload"; }
if (!isset($mod_file)) { $mod_file="index"; }
if (ereg("\.\.",$name) || ereg("\.\.",$file) || ereg("\.\.",$mod_file) || ereg("\.\.",$mop)) {
    echo "You are so cool...";
} else {
    $ThemeSel = get_theme();
    if (file_exists("themes/$ThemeSel/module.php")) {
	include("themes/$ThemeSel/module.php");
	if (is_active("$default_module") AND file_exists("modules/$default_module/$mod_file.php")) {
	    $name = $default_module;
	}
    }
    if (file_exists("themes/$ThemeSel/modules/$name/$mod_file.php")) {
	$modpath = "themes/$ThemeSel/";
    }
    $modpath .= "modules/$name/$mod_file.php";
    if (file_exists($modpath)) {
	include($modpath);
    } else {
	$index = 1;
	include("header.php");
	OpenTable();
	if (is_admin($admin)) {
	    echo "<center><font class=\"\"><b>"._HOMEPROBLEM."</b></font><br><br>[ <a href=\"admin.php?op=modules\">"._ADDAHOME."</a> ]</center>";
	} else {
	    echo "<center>"._HOMEPROBLEMUSER."</center>";
	}
	CloseTable();
	include("footer.php");
    }
}

?>
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

if (eregi("block-Login.php", $_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}

global $admin, $user, $sitekey, $gfx_chk;

mt_srand ((double)microtime()*1000000);
$maxran = 1000000;
$random_num = mt_rand(0, $maxran);

$content = "<form action=\"modules.php?name=Your_Account\" method=\"post\">";
$content .= "<center><font class=\"content\">"._NICKNAME."<br>";
$content .= "<input type=\"text\" name=\"username\" size=\"10\" maxlength=\"25\"><br>";
$content .= ""._PASSWORD."<br>";
$content .= "<input type=\"password\" name=\"user_password\" size=\"10\" maxlength=\"20\"><br>";
if (extension_loaded("gd") AND ($gfx_chk == 2 OR $gfx_chk == 4 OR $gfx_chk == 5 OR $gfx_chk == 7)) {
    $content .= ""._SECURITYCODE.": <img src='modules.php?name=Your_Account&op=gfx&random_num=$random_num' border='1' alt='"._SECURITYCODE."' title='"._SECURITYCODE."'><br>\n";
    $content .= ""._TYPESECCODE."<br><input type=\"text\" NAME=\"gfx_check\" SIZE=\"7\" MAXLENGTH=\"6\">\n";
    $content .= "<input type=\"hidden\" name=\"random_num\" value=\"$random_num\"><br>\n";
} else {
    $content .= "<input type=\"hidden\" name=\"random_num\" value=\"$random_num\">";
    $content .= "<input type=\"hidden\" name=\"gfx_check\" value=\"$code\">";
}
$content .= "<input type=\"hidden\" name=\"op\" value=\"login\">";
$content .= "<input type=\"submit\" value=\""._LOGIN."\"></font></center></form>";
$content .= "<center><font class=\"content\">"._ASREGISTERED."</font></center>";

if (is_admin($admin) AND is_user($user)) {
    $content = "<center>"._ADMIN."<br>[ <a href=\"admin.php?op=logout\">"._LOGOUT."</a> ]</center>";
}

?>
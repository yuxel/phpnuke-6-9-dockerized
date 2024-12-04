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

if (eregi("auth.php",$_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}

if ((isset($aid)) && (isset($pwd)) && ($op == "login")) {
    $datekey = date("F j");
    $rcode = hexdec(md5($_SERVER[HTTP_USER_AGENT] . $sitekey . $_POST[random_num] . $datekey));
    $code = substr($rcode, 2, 6);
    if (extension_loaded("gd") AND $code != $_POST[gfx_check] AND ($gfx_chk == 1 OR $gfx_chk == 5 OR $gfx_chk == 6 OR $gfx_chk == 7)) {
	Header("Location: admin.php");
	die();
    }
    if($aid!="" AND $pwd!="") {
	$pwd = md5($pwd);
	$sql = "SELECT pwd, admlanguage FROM ".$prefix."_authors WHERE aid='$aid'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	if($row[pwd] == $pwd) {
	    $admin = base64_encode("$aid:$pwd:$row[admlanguage]");
	    setcookie("admin","$admin",time()+2592000);
	    unset($op);
	}
    }
}

$admintest = 0;

if(isset($admin) && $admin != "") {
  $admin = base64_decode($admin);
  $admin = explode(":", $admin);
  $aid = "$admin[0]";
  $pwd = "$admin[1]";
  $admlanguage = "$admin[2]";
  if ($aid=="" || $pwd=="") {
    $admintest=0;
    echo "<html>\n";
    echo "<title>INTRUDER ALERT!!!</title>\n";
    echo "<body bgcolor=\"#FFFFFF\" text=\"#000000\">\n\n<br><br><br>\n\n";
    echo "<center><img src=\"images/eyes.gif\" border=\"0\"><br><br>\n";
    echo "<font face=\"Verdana\" size=\"+4\"><b>Get Out!</b></font></center>\n";
    echo "</body>\n";
    echo "</html>\n";
    exit;
  }
  $sql = "SELECT pwd FROM ".$prefix."_authors WHERE aid='$aid'";
  if (!($result = $db->sql_query($sql))) {
        echo "Selection from database failed!";
        exit;
  } else {
    $row = $db->sql_fetchrow($result);
    if($row[pwd] == $pwd && $row[pwd] != "") {
        $admintest = 1;
    }
  }
}

?>
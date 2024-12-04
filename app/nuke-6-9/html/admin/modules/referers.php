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

$result = sql_query("select radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radminsuper) = sql_fetch_row($result, $dbi);
if ($radminsuper==1) {

/*********************************************************/
/* Referer Functions to know who links us                */
/*********************************************************/

function hreferer() {
    global $bgcolor2, $prefix, $dbi;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._HTTPREFERERS."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>"._WHOLINKS."</b></center><br><br>"
	."<table border=\"0\" width=\"100%\">";
    $hresult = sql_query("select rid, url from ".$prefix."_referer", $dbi);
    while(list($rid, $url) = sql_fetch_row($hresult, $dbi)) {
	echo "<tr><td bgcolor=\"$bgcolor2\"><font class=\"content\">$rid</td>"
	    ."<td bgcolor=\"$bgcolor2\"><font class=\"content\"><a target=\"_blank\" href=\"$url\">$url</a></td></tr>";
    }
    echo "</table>"
	."<form action=\"admin.php\" method=\"post\">"
	."<input type=\"hidden\" name=\"op\" value=\"delreferer\">"
	."<center><input type=\"submit\" value=\""._DELETEREFERERS."\"></center>";
    CloseTable();
    include ("footer.php");
}

function delreferer() {
    global $prefix, $dbi;
    sql_query("delete from ".$prefix."_referer", $dbi);
    Header("Location: admin.php?op=adminMain");
}

switch($op) {

    case "hreferer":
    hreferer();
    break;

    case "delreferer":
    delreferer();
    break;

}

} else {
    echo "Access Denied";
}
?>
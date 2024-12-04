<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* Last referers block for phpNuke portal                               */
/* Copyright (c) 2001 by Jack Kozbial (jack@internetintl.com            */
/* http://www.InternetIntl.com                                          */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (eregi("block-Last_Referers.php",$_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}

global $prefix, $db, $admin;

$ref = 10; // how many referers in block
$a = 1;
$sql = "SELECT rid, url FROM ".$prefix."_referer ORDER BY rid DESC LIMIT 0,$ref";
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)) {
    $rid = $row[rid];
    $url = $row[url];
    $url2 = ereg_replace("_", " ", $url);
    if(strlen($url2) > 18) {
	$url2 = substr($url,0,20);
        $url2 .= "..";
    }
    $content .= "$a:&nbsp;<a href=\"$url\" target=\"new\">$url2</a><br>";
    $a++;
}
if (is_admin($admin)) {
    $total = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_referer"));
    $content .= "<br><center>$total "._HTTPREFERERS."<br>[ <a href=\"admin.php?op=delreferer\">"._DELETE."</a> ]</center>";
    
}

?>
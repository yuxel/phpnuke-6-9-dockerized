<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* Note: If you need more than one banner block, just copy this file    */
/*       with another name                                              */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (eregi("block-Advertising.php",$_SERVER[PHP_SELF])) {
    Header("Location: ../index.php");
    die();
}

global $prefix, $db;

$numrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_banner WHERE type='1' AND active='1'"));

if ($numrows>1) {
    $numrows = $numrows-1;
    mt_srand((double)microtime()*1000000);
    $bannum = mt_rand(0, $numrows);
} else {
    $bannum = 0;
}

$sql = "SELECT bid, imageurl, alttext FROM ".$prefix."_banner WHERE type='1' AND active='1' LIMIT $bannum,1";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
    
if (!is_admin($admin)) {
    $db->sql_query("UPDATE ".$prefix."_banner SET impmade=impmade+1 WHERE bid='$row[bid]'");
}
if($numrows>0) {
    $sql = "SELECT cid, imptotal, impmade, clicks, date FROM ".$prefix."_banner WHERE bid=$row[bid]";
    $result = $db->sql_query($sql);
    $row2 = $db->sql_fetchrow($result);
    $cid = $row2[cid];
    $imptotal = $row2[imptotal];
    $impmade = $row2[impmade];
    $clicks = $row2[clicks];
    $date = $row2[date];

/* Check if this impression is the last one and print the banner */

    if (($imptotal <= $impmade) AND ($imptotal != 0)) {
	$db->sql_query("UPDATE ".$prefix."_banner SET active='0' WHERE bid='$row[bid]'");
    }
    $content = "<center><br><a href=\"banners.php?op=click&amp;bid=$row[bid]\" target=\"_blank\"><img src=\"$row[imageurl]\" border=\"1\" alt=\"$row[alttext]\" title='$row[alttext]'></a><br><br></center>";
}

?>
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

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
	die ("You can't access this file directly...");
}
require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

if(!isset($sid)) {
    exit();
}

function PrintPage($sid) {
    global $site_logo, $nukeurl, $sitename, $datetime, $prefix, $db, $module_name;

    $sql = "SELECT title, time, hometext, bodytext, topic, notes FROM ".$prefix."_stories WHERE sid='$sid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $title = $row[title];
    $time = $row[time];
    $hometext = $row[hometext];
    $bodytext = $row[bodytext];
    $topic = $row[topic];
    $notes = $row[notes];

    $sql = "SELECT topictext FROM ".$prefix."_topics WHERE topicid='$topic'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $topictext = $row[topictext];

    formatTimestamp($time);
    echo "
    <html>
    <head><title>$sitename - $title</title></head>
    <body bgcolor=\"#ffffff\" text=\"#000000\">
    <table border=\"0\" align=\"center\"><tr><td>

    <table border=\"0\" width=\"640\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#000000\"><tr><td>
    <table border=\"0\" width=\"640\" cellpadding=\"20\" cellspacing=\"1\" bgcolor=\"#ffffff\"><tr><td>
    <center>
    <img src=\"images/$site_logo\" border=\"0\" alt=\"\"><br><br>
    <font class=\"content\">
    <b>$title</b></font><br>
    <font class=tiny><b>"._PDATE."</b> $datetime<br><b>"._PTOPIC."</b> $topictext</font><br><br>
    </center>
    <font class=\"content\">
    $hometext<br><br>
    $bodytext<br><br>
    $notes<br><br>
    </font>
    </td></tr></table></td></tr></table>
    <br><br><center>
    <font class=\"content\">
    "._COMESFROM." $sitename<br>
    <a href=\"$nukeurl\">$nukeurl</a><br><br>
    "._THEURL."<br>
    <a href=\"$nukeurl/modules.php?name=$module_name&file=article&sid=$sid\">$nukeurl/modules.php?name=$module_name&file=article&sid=$sid</a>
    </font>
    </td></tr></table>
    </body>
    </html>
    ";
}

PrintPage($sid);

?>
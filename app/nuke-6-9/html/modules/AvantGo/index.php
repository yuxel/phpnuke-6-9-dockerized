<?php

######################################################################
# PHP-NUKE: Web Portal System: AvantGo Add-on
# ===========================================
#
# This module is to view your last news items via Palm or Windows CE
# devices, using AvantGo software or compatible palm device browsers
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
#
# Copyright (c) 2000 by Tim Litwiller - http://linux.made-to-order.net
######################################################################

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
        die ("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

header("Content-Type: text/html");
echo "<html>\n"
    ."<head>\n"
    ."<title>$sitename - AvantGo</title>\n"
    ."<meta name=\"HandheldFriendly\" content=\"True\">\n"
    ."</head>\n"
    ."<body>\n\n\n"
    ."<div align=\"center\">\n";
$sql = "SELECT sid, title, time FROM ".$prefix."_stories ORDER BY sid DESC LIMIT 10";
$result = $db->sql_query($sql);
if (!result) {
    echo "An error occured";
} else {
    echo "\t<a href=\"index.php\"><img src=\"images/logo.gif\" alt=\"$slogan\" title=\"$slogan\" border=\"0\"></a><br>\r\n"
        ."\t<h1>$sitename</h1>\r\n"
        ."\t<table border=\"0\" align=\"center\">\r\n"
        ."\t\t<tr>\r\n"
        ."\t\t\t<td bgcolor=\"#efefef\">"._TITLE."</td>\r\n"
        ."\t\t\t<td bgcolor=\"#efefef\">"._DATE."</td>\r\n"
        ."\t\t</tr>\r\n";
    for ($m=0; $m < $db->sql_numrows($result); $m++) {
        $row = $db->sql_fetchrow($result);
        $sid = $row['sid'];
        $title = $row[title];
        $time = $row[time];
        echo "\t\t<tr>\r\n"
            ."\t\t\t<td><a href=\"modules.php?name=$module_name&file=print&sid=$sid\">$title</a></td>\r\n"
            ."\t\t\t<td>$time</td>\r\n"
            ."\t\t</tr>\r\n";
    }
    echo"\t</table>\r\n";
}
echo "</body>\n"
    ."</html>";
include ("includes/counter.php");

?>